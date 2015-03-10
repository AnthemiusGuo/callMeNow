<?php
include_once(APPPATH."models/record_model.php");
class Colors_model extends Record_model {
    public function __construct() {
        parent::__construct("iItems");
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteDirectDonor';
        $this->edit_link = 'crm/editDirectDonor';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['colorName'] = $this->load->field('Field_string',"颜色/子型号","colorName",true);
        $this->field_list['subprice'] = $this->load->field('Field_money',"参考售价(￥/米|件)","subprice",true);

    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }


}
?>
