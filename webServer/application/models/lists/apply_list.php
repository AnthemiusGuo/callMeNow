<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/apply_model.php");
class Apply_list extends List_model {
    public function __construct() {
        parent::__construct('oOrgApply');
        parent::init("Apply_list","Apply_model");
    }

    public function build_search_infos(){
        return array('applyTS','roleId');
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('uid','applyTS','roleId','applyComments');
    }
}
?>