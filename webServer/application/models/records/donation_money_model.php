<?php
include_once(APPPATH."models/record_model.php");
class Donation_money_model extends Record_model {
    public function __construct() {
        parent::__construct('dDonMoney');
        $this->deleteCtrl = 'donation';
        $this->deleteMethod = 'doDeleteDonationMoney';
        $this->edit_link = 'donation/editDetailMoney';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        $this->field_list['showId']= $this->load->field('Field_string',"募捐编号","showId",true);
        $this->field_list['donationId']= $this->load->field('Field_int',"donationId","donationId");

        $this->field_list['donor']= $this->load->field('Field_string',"捐赠人","donor",true);
        $this->field_list['money']= $this->load->field('Field_money',"金额(￥)","money",true);
        

        $this->field_list['isAnou']= $this->load->field('Field_bool',"匿名","isAnou");


        $this->field_list['method'] = $this->load->field('Field_enum',"方式","method");
        $this->field_list['method']->setEnum(array("其他","现金","银行转帐","支付宝等","支票"));
        $this->field_list['donorTS'] = $this->load->field('Field_date',"日期","donorTS");
        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");
        

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        
        $this->field_list['orgId']->init("1");
        $this->field_list['desc']->init("中科院微生物所已破译");

        $this->field_list['showId']->init("123");
        
        $this->field_list['method']->init("1");
        $this->field_list['projectId']->init("1");
        $this->field_list['donationId']->init("1");
        
        $this->field_list['createUid']->init("1");
        $this->field_list['createTS']->init("1");
        $this->field_list['lastModifyUid']->init("1");
        $this->field_list['lastModifyTS']->init("1");
    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '募捐:'.$this->field_list['donor']->gen_show_html().'<small> ID:'.$this->field_list['money']->gen_show_html() .'</small>';
    }
    
}
?>