<?php
include_once(APPPATH."models/record_model.php");
class Mail_model extends Record_model {
    public function __construct() {
        parent::__construct('uSysMail');

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['orgId'] = $this->load->field('Field_relate_simple_id',"组织","orgId");
        $this->field_list['orgId']->set_relate_db('oOrg','id','name');
        $this->field_list['fromUid'] = $this->load->field('Field_userid',"寄件人","fromUid");
        $this->field_list['toUid'] = $this->load->field('Field_userid',"收件人","toUid");

        $this->field_list['sendTS'] = $this->load->field('Field_ts',"最新时间","name",true);
        $this->field_list['mailComments'] = $this->load->field('Field_text',"详情","mailComments");
        $this->field_list['readed'] = $this->load->field('Field_bool',"已读","readed");

    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);

    }
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }

    public function gen_op_approve(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("Management","doApproveApply/1",'.$this->id.')\' title="审批"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_deny(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("Management","doApproveApply/0",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    public function get_list_ops(){
        return array('approve','deny');
    }
    public function buildInfoTitle(){
        return '申请:'.$this->field_list['uid']->gen_show_html();
    }
    
}
?>