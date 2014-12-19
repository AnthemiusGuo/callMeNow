<?php
include_once(APPPATH."models/record_model.php");
class Peaple_model extends Record_model {
    public function __construct() {
        parent::__construct('pPeaple');
        $this->deleteCtrl = 'hr';
        $this->deleteMethod = 'doDeletePeaple';
        $this->edit_link = 'hr/edit_peaple';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"姓名","name",true);
        $this->field_list['sex'] = $this->load->field('Field_sex',"性别","sex",true);
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        $this->field_list['typInAll'] = $this->load->field('field_peaple_typ_in_all',"身份","typInAll",true);
        $this->field_list['typInAll']->setEnum(array('未设置','员工','志愿者'));


        $this->field_list['uid'] = $this->load->field('Field_int',"用户id","uid");
        $this->field_list['nickname'] = $this->load->field('Field_string',"昵称","nickname");
        
        // $this->field_list['usenick'] = $this->load->field('Field_bool',"使用昵称","usenick");
        $this->field_list['email'] = $this->load->field('Field_email',"电子邮箱","email",true);
        $this->field_list['accountName'] = $this->load->field('Field_string',"账号","accountName");
        $this->field_list['roleId'] = $this->load->field('Field_relate_simple_id',"角色","roleId",true);
        // $this->field_list['roleId']->setEnum(array(0=>'未设置',1=>'志愿者',2=>'员工',99=>'超级管理员'));
        $this->field_list['roleId']->set_relate_db('oRole','id','name');
        $this->field_list['roleId']->needOrgId=2;
        
        $this->field_list['departmentId'] = $this->load->field('Field_relate_simple_id',"部门","departmentId",true);
        $this->field_list['departmentId']->set_relate_db('pDepartment','id','name');

        $this->field_list['titleId'] = $this->load->field('Field_relate_simple_id',"职位","titleId",true);
        $this->field_list['titleId']->set_relate_db('pTitle','id','name');
        $this->field_list['reportToUserId'] = $this->load->field('Field_relate_peaple',"汇报给","reportToUserId");
        // $this->field_list['reportToUserId']->set_relate_db('pPeaple','id','name');

        $this->field_list['attendTS'] = $this->load->field('Field_date',"加入时间","attendTS",true);
        // $this->field_list['beginNGOTS'] = $this->load->field('Field_date',"开始公益时间","beginNGOTS");
        $this->field_list['birthTS'] = $this->load->field('Field_date',"出生年月","birthTS");
        $this->field_list['idType'] = $this->load->field('field_enum',"证件类型","idType");
        $this->field_list['idType']->setEnum(array(0=>'身份证',1=>'护照',2=>'其他'));
        $this->field_list['idNumber'] = $this->load->field('Field_string',"证件号码","idNumber");
        // $this->field_list['nationId'] = $this->load->field('Field_string',"国籍","nationId");
        $this->field_list['provinceId'] = $this->load->field('Field_provinceid',"省份","provinceId");
        $this->field_list['addresses'] = $this->load->field('Field_string',"通讯地址","addresses");
        $this->field_list['zipCode'] = $this->load->field('Field_string',"邮编","zipCode");
        if ($this->config->item("app_type")=="npone"){
            $this->field_list['phoneNumber'] = $this->load->field('Field_string',"手机号码","phoneNumber");
        } else {
            $this->field_list['phoneNumber'] = $this->load->field('Field_string',"手机号码","phoneNumber",true);
        }
        
        $this->field_list['qqNumber'] = $this->load->field('Field_string',"QQ","qqNumber");
        $this->field_list['wechatNumber'] = $this->load->field('Field_string',"微信","wechatNumber");
        $this->field_list['weiboNumber'] = $this->load->field('Field_string',"微博","weiboNumber");
        $this->field_list['otherContact'] = $this->load->field('Field_string',"其他联系方式","otherContact");
        if ($this->config->item("app_type")=="npone"){
            $this->field_list['education'] = $this->load->field('Field_string',"学历","education");
            $this->field_list['school'] = $this->load->field('Field_string',"毕业学校","school");
        } else {
            $this->field_list['education'] = $this->load->field('Field_string',"班级","education");
            $this->field_list['school'] = $this->load->field('Field_string',"学校","school");
        }
        
        // $this->field_list['major'] = $this->load->field('Field_string',"专业","major");
        $this->field_list['outcomming'] = $this->load->field('Field_text',"特长","outcomming");
        $this->field_list['comments'] = $this->load->field('Field_text',"人事说明","comments");
        $this->field_list['attendProjects'] = $this->load->field('Field_string',"参加的项目","attendProjects");

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }

    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['name']->init("张三");
        
        $this->field_list['orgId']->init("1");
        $this->field_list['sex']->init("1");
        $this->field_list['nickname']->init("三哥");
        
        
        
        $this->field_list['createUid']->init("1");
        $this->field_list['createTS']->init("1");
        $this->field_list['lastModifyUid']->init("1");
        $this->field_list['lastModifyTS']->init("1");
    }

    public function checkEmailExist($email,$orgId){
        $sql = "SELECT * FROM pPeaple where email='{$email}' AND orgId=$orgId";
        $query  = $this->db->query($sql, array($email)); 
        
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            return $result['id'];
        } else {
            return 0;
        }
    }

    public function checkIsSupper($ids){
        $whereStr = str_replace('-', ',', $ids);
        $sql = "SELECT * FROM pPeaple where roleId=99 AND id IN ({$whereStr})";
        $query  = $this->db->query($sql, array($email)); 
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }


    public function setRelatedOrgId($orgId){
        parent::setRelatedOrgId($orgId);
        $this->field_list['departmentId']->setOrgId($orgId);
        $this->field_list['titleId']->setOrgId($orgId);
        $this->field_list['reportToUserId']->setOrgId($orgId);
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '人事关系:'.$this->field_list['name']->gen_show_html().'<small>'.$this->field_list['nickname']->gen_show_html().'</small>';
    }

    public function getUids($ids){
        $this->db->select('uid')
        ->from('pPeaple')
        ->where_in('id',$ids);
        $query = $this->db->get();
        // print $this->db->last_query();
        $return_ids = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $return_ids[] = $row['uid'];
            }
        }
        return $return_ids;
    }

    public function checkImportData($data){
        $cfg_field_lists = array(
            "name",'sex','email','nickname',
            'attendTS','addresses','zipCode','phoneNumber','qqNumber','weiboNumber','wechatNumber',
            'otherContact','birthTS','idType','idNumber','provinceId',
            'education','school','outcomming','comments'
        );
        return $this->checkImportDataBase($data,$cfg_field_lists);
    }

    public function importData($line){
        $cfg_field_lists = array(
            "name",'sex','email','nickname',
            'attendTS','addresses','zipCode','phoneNumber','qqNumber','weiboNumber','wechatNumber',
            'otherContact','birthTS','idType','idNumber','provinceId',
            'education','school','outcomming','comments'
        );
        $data = array();
        foreach ($line as $key => $value) {
            # code...
            $field_name = $cfg_field_lists[$key];
            if ($field_name=="idNumber") {
                //1.11111198001012E+17
                $value = sprintf("%.0f",(float)$value);

                // $split = explode("E",$value);
                // if (count($split)>0) {
                //     $value = (float)$split[0] * pow(10,(int)$split[1]);
                // }
                
            }
            $data[$field_name] = $this->field_list[$field_name]->importData($value);
        }
        return $data;
    }
}
?>