<?php
include_once(APPPATH."models/record_model.php");
class Building_model extends Record_model {
    public function __construct() {
        parent::__construct('cBuilding');
    $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteBuilding';
        $this->edit_link = 'crm/editBuilding';
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['name'] = $this->load->field('Field_string',"名称","name",true);
        $this->field_list['mianji'] = $this->load->field('Field_string',"建筑面积","mianji");
        $this->field_list['jianzaoshijian'] = $this->load->field('Field_string',"建造时间","jianzaoshijian");
        
        $this->field_list['louxing'] = $this->load->field('Field_enum',"楼型","louxing");
        $this->field_list['louxing']->setEnum(array("其他","楼房","平房"));
        $this->field_list['weifang'] = $this->load->field('Field_enum',"危房级别","weifang");
        $this->field_list['weifang']->setEnum(array("其他","A","B","C","D"));
        $this->field_list['crmId']= $this->load->field('Field_int',"被访人","crmId");
        
        
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return $this->field_list['name']->gen_show_html().' <small> '.$this->field_list['typ']->gen_show_html().' </small>';
    }
    
}
?>