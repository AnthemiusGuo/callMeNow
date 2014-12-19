<?php
include_once(APPPATH."models/record_model.php");
class User_model extends Record_model {
    public function __construct() {
        parent::__construct('uUser');
        $this->uname = '';
        $this->uid = 0;
        $this->field_list['id'] = $this->load->field('Field_int',"uid","uid");
        $this->field_list['uid'] = $this->load->field('Field_int',"uid","uid");
        $this->field_list['username'] = $this->load->field('Field_string',"昵称","username");
        $this->field_list['everEdit'] = $this->load->field('Field_bool',"已输入","everEdit");
        
        $this->field_list['email'] = $this->load->field('Field_string',"电子邮箱","email");
        $this->field_list['isAdmin'] = $this->load->field('Field_bool',"管理员","isAdmin");
        
        $this->field_list['regTS'] = $this->load->field('Field_date',"注册时间","regTS");
        $this->field_list['pwd'] = $this->load->field('Field_string',"密码","pwd");
        $this->field_list['orgIds'] = $this->load->field('Field_string',"组织","orgIds");

        $this->field_list['name'] = $this->load->field('Field_title',"姓名","name");
        $this->field_list['sex'] = $this->load->field('Field_sex',"性别","sex");
        $this->field_list['nickname'] = $this->load->field('Field_string',"昵称","nickname");
        
        // $this->field_list['usenick'] = $this->load->field('Field_bool',"使用昵称","usenick");
        $this->field_list['email'] = $this->load->field('Field_string',"电子邮箱","email");
        // $this->field_list['beginNGOTS'] = $this->load->field('Field_date',"开始公益时间","beginNGOTS");
        $this->field_list['birthTS'] = $this->load->field('Field_date',"出生年月","birthTS");
        $this->field_list['idType'] = $this->load->field('Field_enum',"证件类型","idType");
        $this->field_list['idType']->setEnum(array('身份证','护照','其他'));
        $this->field_list['idNumber'] = $this->load->field('Field_string',"证件号码","idNumber");
        $this->field_list['nationId'] = $this->load->field('Field_string',"国籍","nationId");
        $this->field_list['provinceId'] = $this->load->field('Field_provinceid',"省份","provinceId");
        $this->field_list['addresses'] = $this->load->field('Field_string',"通讯地址","addresses");
        $this->field_list['zipCode'] = $this->load->field('Field_string',"邮编","zipCode");
        $this->field_list['phoneNumber'] = $this->load->field('Field_string',"联系电话","phoneNumber");
        $this->field_list['qqNumber'] = $this->load->field('Field_string',"QQ","qqNumber");
        $this->field_list['wechatNumber'] = $this->load->field('Field_string',"微信","wechatNumber");
        $this->field_list['weiboNumber'] = $this->load->field('Field_string',"微博","weiboNumber");
        $this->field_list['otherContact'] = $this->load->field('Field_string',"其他联系方式","otherContact");
        $this->field_list['education'] = $this->load->field('Field_string',"学历","education");
        $this->field_list['school'] = $this->load->field('Field_string',"毕业学校","school");
        // $this->field_list['major'] = $this->load->field('Field_string',"专业","major");
        $this->field_list['outcomming'] = $this->load->field('Field_text',"特长","outcomming");
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

    public function init_with_email($email){
        parent::init($id);
        $this->db->select('*')
                    ->from('uUser')
                    ->where('email', $email);

        $query = $this->db->get();
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

    public function gen_sync_data(){
        $field_list = array('name', 'sex','nickname','birthTS','idType','idNumber','provinceId','addresses','zipCode','phoneNumber','qqNumber','wechatNumber','weiboNumber','otherContact','education','school','outcomming');
        $rst = array();
        foreach ($field_list as $value) {
            $rst[$value] = $this->field_list[$value]->gen_js_value();
        }
        return $rst;
    }

    public function init_with_data($id,$data){
        parent::init_with_data($id,$data);
        $this->uid = $id;
        $this->uname = $data['username'];
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

    public function check_user_exist($email){
        $this->cimongo->where(array('email'=>$email));
        $query = $this->cimongo->get($this->tableName);
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function reg_user($email,$pwd,$uName,$inviteCode){
        if ($this->check_user_exist($email)){
            return -1;
        }
        $zeit = time();
        $updateSupperUser = 0;
        $bindOrgId = 0;
        $applyOrgId = 0;
        if ($inviteCode!=''){
            $temp = explode('-',$inviteCode);
            if (count($temp)!=2){
                return -3;
            }
            if ($temp[0]=='A'){
                //机构普通邀请码，如果尚未录入数据，则创建一条组织申请
                //如果已经录入数据，则自动绑定该帐号
                $this->db->select('id,name')
                    ->from('oOrg')
                    ->where('commonInviteCode', $inviteCode);

                $query = $this->db->get();
                if ($query->num_rows() > 0)
                {
                    $result = $query->row_array(); 
                    $input_users = $this->check_user_inputed($email,$result['id']);
                    if (count($input_users)>0){
                        $bindOrgId = $result['id'];
                    } else {
                        $applyOrgId = $result['id'];
                    }
                } else {
                    return -3;
                } 

            } elseif ($temp[0]=='B'){
                //机构超级邀请码，如果机构的超级用户字段尚未设置，则直接成为超级用户，
                //并自动创建人事字段，自动绑定
                //如果已经设置，禁止注册
                $this->db->select('id,name,supperUid')
                    ->from('oOrg')
                    ->where('supperInviteCode', $inviteCode);

                $query = $this->db->get();
                if ($query->num_rows() > 0)
                {
                    $result = $query->row_array(); 
                    if ($result['supperUid']>0){
                        return -3;
                    }
                    $input_users = $this->check_user_inputed($email,$result['id']);
                    if (count($input_users)>0){
                        $bindOrgId = $result['id'];
                    } else {
                        $data = array(
                           'email' => $email ,
                           'orgId' => $result['id'],
                           'name' => $uName,
                           'roleId' =>99,
                           'createUid'=>0,
                           'createTS'=>$zeit,
                           'lastModifyUid'=>0,
                           'lastModifyTS'=>$zeit,
                        );

                        $this->db->insert('pPeaple', $data); 
                        $bindOrgId = $result['id'];
                    }
                    $updateSupperUser = $result['id'];
                } else {
                    return -3;
                } 

            } elseif ($temp[0]=='C'){
                //个人邀请码，如果填错不允许注册
                //如果已经录入数据，则自动绑定该帐号
                $input_users = $this->check_user_inputed($email);
                
                if (count($input_users)>0){
                    if (!isset($input_users[$inviteCode])){
                        return -2;
                    } 
                    $bindOrgId = $input_users[$inviteCode];
                } 
            } else {
                return -3;
            }
        }
        
        $data = array(
           'email' => $email ,
           'pwd' => strtolower(md5($pwd)) ,
           'username' => $uName,
           'name' => $uName,
           'regTS' =>time(),
        );

        $this->cimongo->insert('uUser', $data); 
        $insert_ret = $this->cimongo->insert_id();
        $uid = $insert_ret->id;

        $data['uid'] = $uid;
        $this->init_with_data($uid,$data);


        if ( $bindOrgId>0 ){
            $this->update_pPeaple_binding($bindOrgId,$email);
        }
        if ( $applyOrgId>0 ){
            $data = array(
               'orgId' => $applyOrgId,
               'uid' =>$uid,
               'applyTS'=>$zeit,
               'roleId'=>0,
               'applyComments'=>'使用机构普通激活码自动申请',
               'applyResult'=>0
            );

            $this->db->insert('oOrgApply', $data); 
        }
        if ($updateSupperUser>0){
            $data = array(
               'supperUid' => $uid,
               'supperInviteCode' => '-'
            );

            $this->db->where('id', $updateSupperUser)
                ->update('oOrg', $data); 
        }
        return $uid;
    }

    public function verify_login($email,$pwd){

        $this->cimongo->where(array('email'=>$email));
        $query = $this->cimongo->get($this->tableName);

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $real_pwd = $result['pwd'];
            if (strtolower(md5($pwd))==strtolower($real_pwd)){
                $this->init_with_data($result['uid'],$result);
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
    public function forceChangePwd($email,$new_password){
        $data = array(
           'pwd' => strtolower(md5($new_password))
        );
        $this->db->where('email', $email);
        $this->db->update('uUser', $data); 
    }
    public function changePwd($pwd,$pwdNew){

        if (strtolower(md5($pwd))!=strtolower($this->field_list['pwd']->value)){
            
            return -1;
        } 
        $data = array(
           'pwd' => strtolower(md5($pwdNew))
        );

        $this->db->where('uid', $this->uid);
        $this->db->update('uUser', $data); 
        return 1;
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '人事关系:'.$this->field_list['name']->gen_show_html().'<small>'.$this->field_list['nickname']->gen_show_html().'</small>';
    }
    public function update_db($data,$id){
        $this->db->where('uid', $id)->update($this->tableName,$data);
        // echo $this->db->last_query();
        // exit;
        return $this->db->affected_rows();
    }
    
}
?>