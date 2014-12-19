<?php
include_once(APPPATH."models/record_model.php");
class Apply_model extends Record_model {
    public function __construct() {
        parent::__construct('oOrgApply');

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['uid'] = $this->load->field('Field_userid',"申请人","uid");
        $this->field_list['uid']->is_title = true;
        $this->field_list['applyTS'] = $this->load->field('Field_date',"申请时间","applyTS",true);
        $this->field_list['applyComments'] = $this->load->field('Field_text',"申请详情","applyComments");
        $this->field_list['orgId'] = $this->load->field('Field_relate_simple_id',"组织","orgId");
        $this->field_list['orgId']->set_relate_db('oOrg','id','name');

        $this->field_list['roleId'] = $this->load->field('Field_enum',"申请身份","roleId");

        $this->field_list['applyResult'] = $this->load->field('Field_enum',"状态","applyResult");

        $this->field_list['roleId']->setEnum(array('未设置','员工','志愿者'));
        $this->field_list['applyResult']->setEnum(array('未审核','拒绝','通过'));

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
        return '<a class="list_op tooltips" onclick=\'reqOperator("management","doApproveApply/1",'.$this->id.')\' title="审批"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_deny(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("management","doApproveApply/0",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    public function get_list_ops(){
        return array('approve','deny');
    }
    public function buildInfoTitle(){
        return '申请:'.$this->field_list['uid']->gen_show_html();
    }
    
}
?>