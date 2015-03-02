<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/contact_model.php");
class Contact_list extends List_model {
    public function __construct() {
        parent::__construct("cContactHis");
        parent::init("Contact_list","Contact_model");
    }
    
    public function build_search_infos(){
        return array('contactTS','typ');
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('contactTS','typ','contactMethod','contactUid');
    }
}
?>