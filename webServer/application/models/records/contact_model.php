<?php
include_once(APPPATH."models/record_model.php");
class Contact_model extends Record_model {
    public function __construct() {
        parent::__construct("cContactHis");
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteContactHis';
        $this->edit_link = 'crm/editContactHis';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        $this->field_list['crmId']= $this->load->field('Field_int',"被访人","crmId");
        $this->field_list['typ'] = $this->load->field('Field_enum',"类型","typ",true);
        $this->field_list['typ']->setEnum(array("未设置","背景调查","日常联系","项目推进","回访"));

        $this->field_list['contactMethod'] = $this->load->field('Field_enum',"联系方式","contactMethod",true);
        $this->field_list['contactMethod']->setEnum(array("电话","邮件","现场","其他"));
        
        $this->field_list['contactUid'] = $this->load->field('Field_relate_contactor',"对方联系人","contactUid");
        

        $this->field_list['followUid'] = $this->load->field('Field_related_multi_ids',"本方联系人","followUid");
        $this->field_list['followUid']->set_relate_db('pPeaple','id','name');
        $this->field_list['followUid']->setEditor('hr/searchPeaple/');
        $this->field_list['followUid']->setPlusCreateData(array('name'=>'','roleId'=>0));
        $this->field_list['contactTS'] = $this->load->field('Field_date',"联系日期","contactTS");
        $this->field_list['desc'] = $this->load->field('Field_text',"联系详情","desc",true);
        
        $this->field_list['nextContactTS'] = $this->load->field('Field_date',"下次联系时间","nextContactTS");

        
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['orgId']->init("1");
        $this->field_list['crmId']->init("1");

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