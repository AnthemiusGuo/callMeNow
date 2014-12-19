<?php
include_once(APPPATH."models/record_model.php");
class Role_model extends Record_model {
    public function __construct() {
        parent::__construct('oRole');
        $this->deleteCtrl = 'management';
        $this->deleteMethod = 'doDeleteRole';
        $this->edit_link = 'management/editRole';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['typ'] = $this->load->field('Field_enum',"分类","typ");
        $this->field_list['typ']->setEnum(array(0=>'系统预置',1=>'用户创建'));
        $this->field_list['accessRule'] = $this->load->field('Field_access',"权限","accessRule");

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");

    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['name']->init("H7N9：跨种感染机制突破");
        
        
        $this->field_list['createUid']->init("1");
        $this->field_list['createTS']->init("1");
        $this->field_list['lastModifyUid']->init("1");
        $this->field_list['lastModifyTS']->init("1");
    }

    public function create_role($data){
        $this->db->insert('oRole', $data); 
        return $this->db->insert_id();
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '任务:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
    
}
?>