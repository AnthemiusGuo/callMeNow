<?php
include_once(APPPATH."models/record_model.php");
class Title_model extends Record_model {
    public function __construct() {
        parent::__construct('pTitle');
        $this->deleteCtrl = 'hr';
        $this->deleteMethod = 'doDeleteTitle';
        $this->edit_link = 'hr/edit_title';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"职位名称","name",true);
        $this->field_list['desc'] = $this->load->field('Field_text',"职位说明","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");

        $this->field_list['departmentId'] = $this->load->field('Field_relate_simple_id',"所属部门","departmentId",true);
        $this->field_list['departmentId']->set_relate_db('pDepartment','id','name');

        $this->field_list['isAdmin'] = $this->load->field('Field_bool',"部门负责职位","isAdmin");

        
        $this->field_list['aboveTitleId'] = $this->load->field('Field_relate_simple_id',"上级职位","aboveTitleId");
        $this->field_list['aboveTitleId']->set_relate_db('pTitle','id','name');

        $this->field_list['peapleCount'] = $this->load->field('Field_int',"职位人数","peapleCount");
        
        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function setRelatedOrgId($orgId){
        parent::setRelatedOrgId($orgId);
        $this->field_list['departmentId']->setOrgId($orgId);
        $this->field_list['aboveTitleId']->setOrgId($orgId);
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['name']->init("文员");
        
        
        $this->field_list['departmentId']->init("1");
        $this->field_list['isAdmin']->init(1);
        $this->field_list['aboveTitleId']->init("1");
        $this->field_list['peapleCount']->init("10");
        
        $this->field_list['createUid']->init("1");
        $this->field_list['createTS']->init("1");
        $this->field_list['lastModifyUid']->init("1");
        $this->field_list['lastModifyTS']->init("1");
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '职位:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
}
?>