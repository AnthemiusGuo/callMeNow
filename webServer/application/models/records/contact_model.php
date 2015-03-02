<?php
include_once(APPPATH."models/record_model.php");
class Contact_model extends Record_model {
    public function __construct() {
        parent::__construct("cContactHis");
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteContactHis';
        $this->edit_link = 'crm/editContactHis';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");
        $this->field_list['crmId']= $this->load->field('Field_relate_crm',"客户","crmId");
        $this->field_list['typ'] = $this->load->field('Field_enum',"类型","typ",true);
        $this->field_list['typ']->setEnum(array("其他","订货","咨询"));

        $this->field_list['contactMethod'] = $this->load->field('Field_enum',"联系方式","contactMethod",true);
        $this->field_list['contactMethod']->setEnum(array("电话","QQ","微信","门店现场","其他"));
        
        $this->field_list['contactUid'] = $this->load->field('Field_relate_contactor',"对方联系人","contactUid");
        

        $this->field_list['contactTS'] = $this->load->field('Field_date',"联系日期","contactTS");
        $this->field_list['desc'] = $this->load->field('Field_text',"联系详情","desc",true);

        
    }
    public function buildChangeNeedFields(){
        return array('name','crmId','typ','contactMethod','desc','contactTS');
    }

    public function buildChangeShowFields(){
            return array(
                    array('typ','contactMethod'),
                    array('contactTS'),
                    array('desc'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('crmId'),
                    array('typ','contactMethod'),
                    array('contactTS'),
                    array('desc'),
                );
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return $this->field_list['typ']->gen_show_html().' <small> '.$this->field_list['contactTS']->gen_show_html().' </small>';
    }
    
}
?>