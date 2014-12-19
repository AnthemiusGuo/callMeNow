<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/building_model.php");
class Building_list extends List_model {
    public function __construct() {
        parent::__construct('cBuilding');
        parent::init("Building_list","Building_model");
        
    }
    
    public function init($crmId=0){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Building_model();
            $this->record_list[$i]->init($i);
        }
        
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','mianji','jianzaoshijian','louxing','weifang');
    }
}
?>