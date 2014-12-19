<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/donation_money_model.php");
class Donation_money_list extends List_model {
    public function __construct() {
        parent::__construct('dDonMoney');
        parent::init("Donation_money_list","Donation_money_model");
    }
    
    
    public function build_list_titles(){
        return array('showId','donor','money','donorTS','method','isAnou');
    }
}
?>