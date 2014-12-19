<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/document_model.php");
class Document_list extends List_model {
    public function __construct() {
        parent::__construct('docDocument');
        parent::init("Document_list","Document_model");
    }
    
    public function init(){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Document_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function init_byCrmID($crmId){
        parent::init("Document_list","Document_model");
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Document_model();
            $this->record_list[$i]->init($i);
        }
    }
    public function build_search_infos(){
        return array('name','uploadTS','uploadUid');
    }
    public function build_inline_list_titles(){
       return array('showId','name','uploadTS','uploadUid','fileLink');
    }
    public function build_short_list_titles(){
        return array('name','uploadTS','uploadUid');
    }
    public function build_list_titles(){
        return array('showId','name','uploadTS','uploadUid','relateID','fileLink');
    }
}
?>