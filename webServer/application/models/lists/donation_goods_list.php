<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/donation_goods_model.php");
class Donation_goods_list extends List_model {
    public function __construct() {
        parent::__construct('dDonGoods');
        parent::init("Donation_goods_list","Donation_goods_model");
    }
    
    public function build_list_titles(){
        return array('showId','donor','goods','donorCount','donorTS','method','isAnou');
    }
}
?>