<?php
include_once(APPPATH."models/record_model.php");
class Department_model extends Record_model {
    public function __construct() {
        parent::__construct('pDepartment');

        $this->deleteCtrl = 'hr';
        $this->deleteMethod = 'doDeleteDepartment';
        $this->edit_link = 'hr/edit_department';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"部门名称","name",true);
        $this->field_list['desc'] = $this->load->field('Field_text',"部门描述","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        
        $this->field_list['aboveDepartmentId'] = $this->load->field('Field_relate_simple_id',"上级部门","aboveDepartmentId");
        $this->field_list['aboveDepartmentId']->set_relate_db('pDepartment','id','name');

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function delete_db($ids){
        $effect = 0;
        $idArray = explode('-',$ids);
        foreach ($idArray as $id) {
            $this->db->where('id', $id)->delete($this->tableName);
            $effect += $this->db->affected_rows();
            $this->db->where('aboveDepartmentId',$id)->update('pDepartment',array(
               'aboveDepartmentId' => 0
            ));
            $this->db->where('departmentId',$id)->update('pTitle',array(
               'departmentId' => 0
            ));
            $this->db->where('departmentId',$id)->update('pPeaple',array(
               'departmentId' => 0
            ));
        }
        return $effect;
    }
    public function setRelatedOrgId($orgId){
        parent::setRelatedOrgId($orgId);
        $this->field_list['aboveDepartmentId']->setOrgId($orgId);
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        
        
        
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
        return '部门:'.$this->field_list['name']->gen_show_html();
    }

    
    
}
?>