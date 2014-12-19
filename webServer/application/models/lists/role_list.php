<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/role_model.php");
class Role_list extends List_model {
    public function __construct() {
        parent::__construct('oRole');
        parent::init("Role_list","Role_model");

    }

    public function load_role_list($orgId){
        $this->add_where(WHERE_TXT,'quick',"(orgId=0 OR orgId=$orgId)");
            
        $this->load_data_with_where();
    }

    public function load_system_prepare(){
        $this->add_where(WHERE_TXT,'quick',"(orgId=0)");
            
        $this->load_data_with_where();
    }

    public function create_role(){
        
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','typ');
    }
}
?>