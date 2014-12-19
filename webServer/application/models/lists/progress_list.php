<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/progress_model.php");
class Progress_list extends List_model {
    public function __construct() {
        parent::__construct();
    }
    
    public function init($projectId = 0){
        parent::init("Progress_list","Progress_model");
        
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Progress_model();
            $this->record_list[$i]->init($i);

        }
       
    }

    public function build_inline_list_titles(){
        return array('showId','name','preProgressId','beginTS','endTS','overdue','progress');
    }
    public function build_short_list_titles(){
        return array('name','beginTS','endTS','progress');
    }
    public function build_list_titles(){
        return array('name','beginTS','endTS','progress','projectId','userInCharge');
    }
}
?>