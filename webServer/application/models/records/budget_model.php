<?php
include_once(APPPATH."models/record_model.php");
class Budget_model extends Record_model {
    public function __construct() {
        parent::__construct("fBudget");
        $this->deleteCtrl = 'project';
        $this->deleteMethod = 'doDeleteBudget';
        $this->edit_link = 'project/editBudget';
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['showId'] = $this->load->field('Field_string',"编号","showId");
        $this->field_list['showId']->is_title = true;
        
        $this->field_list['name'] = $this->load->field('Field_string',"事项","name");
        $this->field_list['desc'] = $this->load->field('Field_text',"明细","desc",true);
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");

        $this->field_list['typ'] = $this->load->field('Field_turnover_typ',"类型","typ");
        $this->field_list['typ']->setTyp(1);

        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('未审批','已审批','已拒绝','已转支'));
         
        $this->field_list['unitPrice'] = $this->load->field('Field_money',"单价","unitPrice");
        $this->field_list['unit'] = $this->load->field('Field_string',"单位","unit");
        $this->field_list['unitCount'] = $this->load->field('Field_string',"数量","unitCount");

        $this->field_list['totalPrice'] = $this->load->field('Field_money',"总价","totalPrice",true);

        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");

        $this->field_list['bookkeeper'] = $this->load->field('Field_relate_peaple',"录入人","bookkeeper");
        $this->field_list['approveUid'] = $this->load->field('Field_relate_peaple',"审批人","approveUid");
        $this->field_list['approveTS'] = $this->load->field('Field_ts',"审批时间","approveTS");

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    
    public function gen_op_audit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("project","doAuditBudget/1/'.$this->field_list['projectId']->value.'/",'.$this->id.')\' title="审批"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_deaudit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("project","doAuditBudget/0/'.$this->field_list['projectId']->value.'/",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    public function gen_op_toEx(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("project","doTransBudget/'.$this->field_list['projectId']->value.'/",'.$this->id.')\' title="转支出"><span class="glyphicon glyphicon-share"></span></a>';
    }
    
    
    public function get_list_ops(){
        $CI =& get_instance();
        //array('未审核','已审核','审批被拒','已转支'));
        switch ($this->field_list['status']->value) {  
            case 1:
            //已审核
            
                $array = array('toEx') ;
                break;

            case 0:
            //未审核
                $array = array('edit','delete','audit','deaudit') ;
                break;
            case 2:
            //审批被拒
                $array = array('edit','delete') ;
                break;   
            default:
                $array = array() ;
                break;
        }
        if (!$CI->checkActionRule("Project","Edit")) {
            if (($key = array_search('edit', $array)) !== false) {
                unset($array[$key]);
            }
            if (($key = array_search('delete', $array)) !== false) {
                unset($array[$key]);
            }
            if (($key = array_search('toEx', $array)) !== false) {
                unset($array[$key]);
            }
            $array = array_values($array);
        }
        if (!$CI->checkActionRule("Project","BugetApprove")) {
            if (($key = array_search('audit', $array)) !== false) {
                unset($array[$key]);
            }
            if (($key = array_search('deaudit', $array)) !== false) {
                unset($array[$key]);
            }
            $array = array_values($array);
        }
        
        
        return $array;;
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '预算编号:'.$this->field_list['showId']->gen_show_html();
    }
    
}
?>