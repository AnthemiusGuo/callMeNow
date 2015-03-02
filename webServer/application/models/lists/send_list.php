<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/send_model.php");
class Send_list extends List_model {
    public function __construct() {
        parent::__construct('bSend');
        parent::init("Send_list","Send_model");
    }

    public function build_search_infos(){
        return array('status','beginTS','packP','sendP');
    }
    public function build_inline_list_titles(){
        return array('items','status','beginTS','packP','sendP','desc');
    }
    public function build_short_list_titles(){
        return array('crmId','status','beginTS','packP','sendP','desc');
    }
    public function build_list_titles(){
        return array('crmId','items','status','beginTS','packP','sendP','desc');
    }
}
?>