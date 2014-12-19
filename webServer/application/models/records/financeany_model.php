<?php
include_once(APPPATH."models/record_model.php");
class Financeany_model extends Record_model {
    public function __construct() {
        parent::__construct();
        
        $this->field_list['title'] = $this->load->field('Field_string',"","title");
        $this->field_list['s_incoming'] = $this->load->field('Field_int',"收入","s_incoming");
        $this->field_list['s_outgoing'] = $this->load->field('Field_int',"支出","s_outgoing");

       

    }
    public function setName($typ){
        
        $this->field_list['title']->show_name = $name;
    }
    
}
?>