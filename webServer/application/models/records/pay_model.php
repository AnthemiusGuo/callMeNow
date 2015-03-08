<?php
include_once(APPPATH."models/record_model.php");
class Pay_model extends Record_model {
    public function __construct() {
        parent::__construct('bPay');
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doSubDel/pay';
        $this->edit_link = 'crm/subEdit/pay/';
        $this->info_link = 'crm/subinfo/pay/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['crmId'] = $this->load->field('Field_relate_crm',"客户","crmId",true);
        $this->field_list['money']= $this->load->field('Field_money',"金额(￥)","money",true);
        $this->field_list['money']->tips = '进账收款为正值，出账付款请输入负值';
        $this->field_list['money']->is_title = true;

        $this->field_list['method'] = $this->load->field('Field_enum',"方式","method");
        $this->field_list['method']->setEnum(array("其他","现金","银行转帐","支付宝等","支票"));
        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array("查账中","已到账"));
        $this->field_list['typ'] = $this->load->field('Field_enum',"类型","typ");
        $this->field_list['typ']->setEnum(array("进账","出账"));

        $this->field_list['payTS'] = $this->load->field('Field_date',"日期","payTS");
  

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '付款记录:'.$this->field_list['crmId']->gen_show_html().'<small> ID:'.$this->field_list['payTS']->gen_show_html().'</small>';
    }
    public function buildChangeNeedFields(){
        return array('payTS','crmId','money','desc','method','status');
    }

    public function buildChangeShowFields(){
            return array(
                    array('money','payTS'),
                    array('method','status'),
                    array('desc'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                     array('crmId'),
                    array('money','payTS'),
                    array('method','status'),
                    array('desc'),
                );
    }
    
}
?>