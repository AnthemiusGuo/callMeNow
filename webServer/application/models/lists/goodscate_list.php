<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/goodscate_model.php");
class Goodscate_list extends List_model {
    public function __construct() {
        parent::__construct("gGoodsCategory");
        parent::init("Goodscate_list","Goodscate_model");

        $this->is_lightbox = true;
        $this->quickSearchWhere = array("name");
    }
    public function build_search_infos(){
        return array('name');
    }
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','comments');
    }
}
?>
