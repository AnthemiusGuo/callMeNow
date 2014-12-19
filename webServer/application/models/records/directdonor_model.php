<?php
include_once(APPPATH."models/record_model.php");
class Directdonor_model extends Record_model {
    public function __construct() {
        parent::__construct("cDirectDonor");
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteDirectDonor';
        $this->edit_link = 'crm/editDirectDonor';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['donorPeriod'] = $this->load->field('Field_string',"资助周期","donorPeriod");
        $this->field_list['donorComments'] = $this->load->field('Field_text',"资助说明","donorComments");

        $this->field_list['beDonoredCrmId'] = $this->load->field('Field_relate_simple_id',"被赞助方",'beDonoredCrmId');
        $this->field_list['beDonoredCrmId']->set_relate_db('cCrm','id','name');

        $this->field_list['donorCrmId'] = $this->load->field('Field_relate_simple_id',"赞助方",'donorCrmId');
        $this->field_list['donorCrmId']->set_relate_db('cCrm','id','name');
        
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