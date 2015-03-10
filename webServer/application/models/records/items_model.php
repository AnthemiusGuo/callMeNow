<?php
include_once(APPPATH."models/record_model.php");
class Items_model extends Record_model {
    public function __construct() {
        parent::__construct("iItems");
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteDirectDonor';
        $this->edit_link = 'crm/editDirectDonor';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['meter'] = $this->load->field('Field_float',"米数","meter",true);
        $this->field_list['itemName'] = $this->load->field('Field_relate_goods',"货号","itemName",true);
        $this->field_list['color'] = $this->load->field('Field_string',"颜色","color");
        $this->field_list['price'] = $this->load->field('Field_money',"单价(￥/米)","price",true);
        $this->field_list['allPrice'] = $this->load->field('Field_money',"总价(￥)","allPrice",true);

    }

    public function set_page_typ($typ){
        if ($typ==1) {
            $this->edit_link = 'crm/editDirectDonor/1';
            $this->deleteMethod = 'doDeleteDirectDonor/1';
        } else {
            $this->edit_link = 'crm/editDirectDonor/2';
            $this->deleteMethod = 'doDeleteDirectDonor/2';

        }
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return $this->field_list['beDonoredCrmId']->gen_show_html();
    }

}
?>
