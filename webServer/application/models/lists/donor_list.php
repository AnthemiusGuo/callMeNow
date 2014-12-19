<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/donor_model.php");
class Donor_list extends List_model {
    public function __construct() {
        parent::__construct();
    }
    
    public function init($donor_ids){
        $json_ids = json_decode($donor_ids);
        parent::init("Donor_list","Donor_model");
        foreach ($json_ids as $id) {
            $this->record_list[$id] = new Donor_model();
            $this->record_list[$id]->init($id);
        }
       
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('xingming','dianhua');
    }
}
?>