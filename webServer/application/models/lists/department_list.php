<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/department_model.php");
class Department_list extends List_model {
    public function __construct() {
        parent::__construct('pDepartment');
        parent::init("Department_list","Department_model");
    }
    
    public function init(){
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Department_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function build_search_infos(){
        return array('name','aboveDepartmentId');
    }
    public function build_list_titles(){
        return array('name','aboveDepartmentId');
    }
}
?>