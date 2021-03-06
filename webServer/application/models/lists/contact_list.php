<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/contact_model.php");
class Contact_list extends List_model {
    public function __construct() {
        parent::__construct("cContactHis");
        parent::init("Contact_list","Contact_model");
        $this->is_lightbox = true;
    }

    public function build_search_infos(){
        return array('contactTS','typ');
    }

    public function build_inline_list_titles(){
        return array('contactTS','typ','contactMethod','desc');
    }
    public function build_short_list_titles(){
        return array('crmId','contactTS','desc');
    }

    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('crmId','contactTS','typ','contactMethod','desc');
    }
}
?>
