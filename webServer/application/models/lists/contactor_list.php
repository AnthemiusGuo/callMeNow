<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/contactor_model.php");
class Contactor_list extends List_model {
    public function __construct() {
        parent::__construct('cContactor');
        parent::init("Contactor_list","Contactor_model");
    }

    public function init($ids){
        $json_ids = json_decode($ids);
        
        foreach ($json_ids as $id) {
            $this->record_list[$id] = new Contactor_model();
            $this->record_list[$id]->init($id);
        }
       
    }
    
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','dianhua','qq','weixin','qitafangshi','isMain');
    }
}
?>