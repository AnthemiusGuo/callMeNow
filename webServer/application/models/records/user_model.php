<?php
include_once(APPPATH."models/record_model.php");
class User_model extends Record_model {
    public function __construct() {
        parent::__construct('uUser');
        $this->uname = '';
        $this->uid = 0;

        $this->deleteCtrl = 'management';
        $this->deleteMethod = 'doDelUser';
        $this->edit_link = 'management/editUser/';
        $this->info_link = 'management/infoUser/';


        $this->field_list['_id'] = $this->load->field('Field_mongoid',"uid","_id");
        $this->field_list['uid'] = $this->load->field('Field_string',"uid","uid");

        $this->field_list['email'] = $this->load->field('Field_email',"电子邮箱","email");
        $this->field_list['phone'] = $this->load->field('Field_string',"电话","phone");

        $this->field_list['regTS'] = $this->load->field('Field_date',"注册时间","regTS");
        $this->field_list['typ'] = $this->load->field('Field_enum',"身份","typ");
        $this->field_list['typ']->setEnum(array("员工","老板"));

        $this->field_list['isAdmin'] = $this->load->field('Field_bool',"超级管理员","isAdmin");
        $this->field_list['pwd'] = $this->load->field('Field_pwd',"密码","pwd");
        $this->field_list['orgId'] = $this->load->field('Field_relate_org',"商户","orgId");

        $this->field_list['name'] = $this->load->field('Field_title',"姓名","name");
        $this->field_list['sign'] = $this->load->field('Field_string',"签名","sign");
        $this->field_list['intro'] = $this->load->field('Field_text',"个人介绍","intro");

        $this->field_list['everEdit'] = $this->load->field('Field_int',"曾修改","everEdit");

    }

    public function buildShowCard(){
        $_html = '<div class="shopInfoCard">';
        $url = '#';//$this->gen_front_url();
        $_html .= '<h4><a href="'.$url.'" target="_blank">'.$this->field_list['name']->gen_show_html().'</a></h4>';
        if (!$this->field_list['orgId']->isEmpty()){
            $_html .= '<span class="shopBegin"> '.$this->field_list['orgId']->gen_show_html().' </span>';
        }

        $_html .= '<span class="shopBegin"> '.$this->field_list['sign']->gen_show_html().' </span>';
        $_html .= '<p class="shopDesc">'.$this->field_list['intro']->gen_show_html().'</p>';

        $_html .= '<dt>电话</dt>';
        $_html .= '<dd>'.$this->field_list['phone']->gen_show_html().'</dd>';
        $_html .= '<dt>电邮</dt>';
        $_html .= '<dd>'.$this->field_list['email']->gen_show_html().'</dd>';
        // $_html .= '<dt>微信</dt>';
        // $_html .= '<dd>'.$this->field_list['orgId']->gen_show_html().'</dd>';
        // $_html .= '<dt>旺旺</dt>';
        // $_html .= '<dd>'.$this->field_list['wangwang']->gen_show_html().'</dd>';
        $_html .= '<div class="clearfix"></div></div>';


        return $_html;
    }

    public function buildChangeShowFields(){
            return array(
                    array('name','typ'),
                    array('email','phone'),
                    array('pwd'),
                    array('sign'),
                    array('intro'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name','typ'),
                    array('email','phone'),
                    array('regTS'),
                    array('sign'),
                    array('intro'),
                );
    }

    public function gen_auth_code($client_id=''){
        $auth_code = uniqid($this->uid);
        $this->update_db(array('client_auth_code'=>$auth_code,'client_id'=>$client_id),$this->uid);
        return $auth_code;
    }

    public function check_auth_code($auth_code){
        $this->cimongo->where(array('client_auth_code'=>$auth_code));

        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($result['_id'],$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }


    public function init($id){

        parent::init($id);
        $this->db->select('*')
                    ->from('uUser')
                    ->where('uid', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($id,$result);
        }
        else
        {
            return -1;
        }

    }

    public function init_by_uid($uid){
        parent::init($uid);
        $id = new MongoId($uid);
        $this->cimongo->where(array('_id'=>$id));

        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($id,$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }

    public function init_with_email($email){
        parent::init($id);
        $this->cimongo->where(array('email'=>$email));

        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($id,$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }


    public function init_with_data($id,$data){
        parent::init_with_data($id,$data);

        $this->uid = $id->{'$id'};
        $this->uname = $data['name'];
    }

    public function check_user_inputed($email,$orgId=0){
        $this->db->select('inviteCode,orgId')
                    ->from('pPeaple')
                    ->where('email', $email);
        if ($orgId>0){
            $this->db->where('orgId', $orgId);
        }
        $query = $this->db->get();
        $result = array();
        foreach ($query->result_array() as $row)
        {
           $result[$row['inviteCode']] = $row['orgId'];
        }
        return $result;
    }

    public function update_pPeaple_binding($orgId,$email){
        $data = array(
               'uid' => $this->uid
            );

        $this->db->where('orgId', $orgId)
                ->where('email', $email)
                ->update('pPeaple', $data);
    }

    public function check_email_exist($email){
        $this->cimongo->where(array('email'=>$email));
        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function check_phone_exist($phone){
        $this->cimongo->where(array('phone'=>$phone));
        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function reg_user($input){
        // if ($input['email']!='' && $this->check_email_exist($input['email'])){
        //     return -1;
        // }
        if ($input['phone']!='' && $this->check_phone_exist($input['phone'])){
            return -2;
        }
        // if ($input['email']=='' && $input['phone']=='') {
        //     return -3;
        // }

        $zeit = time();

        if ($input['inviteCode']!=''){
            $temp = explode('-',$input['inviteCode']);
            if (count($temp)!=2){
                return -4;
            }
            if ($temp[0]=='A'){


            } elseif ($temp[0]=='B'){


            } elseif ($temp[0]=='C'){

            } else {
                return -5;
            }
        }

        $data = array(
           'email' => $input['email'] ,
           'phone' => $input['phone'] ,
           'pwd' => $input['pwd'] ,
           'name' => $input['uName'],
           'orgId' => '',
           'superPwd' => 'null',
           'isAdmin' =>0,
           'sign'=>'新来的',
           'intro'=>'',
           'everEdit'=>0,
        );

        $modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $createPostFields = $this->buildChangeNeedFields();
        $data = array();
        foreach ($createPostFields as $value) {
            $data[$value] = $this->field_list[$value]->gen_value($input[$value]);
        }


        $checkRst = $this->check_data($data);
        if (!$checkRst){
            return -10;
        }
        if (isset($input['third_plat'])) {
    		$data['third_typ_'.$input['third_plat']] = $input['third_id'];
        }
        $data['regTS'] = time();
        $insert_ret = $this->insert_db($data);

        if ($insert_ret===false) {
            return -999;
        }
        $uid = $insert_ret->{'$id'};

        $data['uid'] = $uid;
        $data['_id'] = $insert_ret;
        $this->init_with_data($insert_ret,$data);

        $this->uid = $uid;
        return 1;
    }

    public function verify_login($email,$pwd){

        $this->cimongo->or_where(array('phone'=>$email,'email'=>$email));

        $query = $this->cimongo->get($this->tableName);

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $real_pwd = $result['pwd'];
            if (strtolower(md5($pwd))==strtolower($real_pwd)){


                $this->init_with_data($result['_id'],$result);
                return 1;
            } else {
                return -2;
            }
        }
        else
        {
            return -1;
        }
    }

    public function verify_third_login($third_typ,$third_id){
        $this->db->where(array('third_typ_'.$third_typ=>$third_id));
        $query = $this->db->get($this->tableName);

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array();
            $this->init_with_data($result['_id'],$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }

    public function bind_third($third_typ,$third_id){
        if (isset($this->none_field_data['third_typ_'.$third_typ])){
            if ($this->none_field_data['third_typ_'.$third_typ]==$third_id){
                return 2;
            } else {
                return -1;
            }
        } else {
            //执行绑定
            $this->update_db(array('third_typ_'.$third_typ=>$third_id),$this->uid);
            return 1;
        }

    }



    public function forceChangePwd($email,$new_password){
        $data = array(
           'pwd' => strtolower(md5($new_password))
        );
        $this->db->where(array('email'=>$email));
        $this->db->update('uUser', $data);
    }
    public function changePwd($pwd,$pwdNew){

        if (strtolower(md5($pwd))!=strtolower($this->field_list['pwd']->value)){

            return -1;
        }
        $data = array(
           'pwd' => strtolower(md5($pwdNew))
        );

        $this->db->where(array('uid'=>$this->uid));
        $this->db->update('uUser', $data);
        return 1;
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return '用户:'.$this->field_list['name']->gen_show_html().'<small>'.$this->field_list['typ']->gen_show_html().'</small>';
    }


}
?>
