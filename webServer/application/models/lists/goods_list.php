<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/goods_model.php");
class Goods_list extends List_model {
    public function __construct() {
        parent::__construct("gGoods");
        parent::init("Goods_list","Goods_model");

        $this->is_lightbox = false;
        $this->quickSearchWhere = array("name");
        $this->orderKey = array("updateTS"=>"desc");
    }


    public function build_search_infos(){
        return array('name','category','status','updateTS');
    }
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','category','status','updateTS');
    }
}
?>
