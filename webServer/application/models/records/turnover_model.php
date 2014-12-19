<?php
include_once(APPPATH."models/record_model.php");
class Turnover_model extends Record_model {
    public function __construct() {
        parent::__construct("fTurnover");

        $this->deleteCtrl = 'finance';
        $this->deleteMethod = 'doDeleteTurnover';
        $this->edit_link = 'finance/editTurnover';


        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['showId'] = $this->load->field('Field_string',"序号","id");
        $this->field_list['name'] = $this->load->field('Field_title',"事项","name",true);
        $this->field_list['desc'] = $this->load->field('Field_text',"明细","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");

        $this->field_list['typ'] = $this->load->field('Field_turnover_typ',"类型","typ");
        
        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('未审核','已审核','被拒绝'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"日期","beginTS",true);
        $this->field_list['TSByMonth'] = $this->load->field('Field_int',"日期","TSByMonth");
        
        $this->field_list['beginTS1'] = $this->load->field('Field_date',"日期起","beginTS1");
        $this->field_list['beginTS1']->special_search_element = "<input type='hidden' name='searchEle_beginTS1' id='searchEle_beginTS1' value='>='> &gt;=";

        $this->field_list['beginTS2'] = $this->load->field('Field_date',"日期止","beginTS2");
        $this->field_list['beginTS2']->special_search_element = "<input type='hidden' name='searchEle_beginTS2' id='searchEle_beginTS2' value='>='> &lt;=";

        
        

         $this->field_list['auditTS'] = $this->load->field('Field_ts',"审核时间","auditTS");
        $this->field_list['incoming'] = $this->load->field('Field_int',"收入","incoming");
        $this->field_list['outgoing'] = $this->load->field('Field_int',"支出","outgoing");

        $this->field_list['balance'] = $this->load->field('Field_int',"余额","balance");
        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");

        $this->field_list['bookkeeper'] = $this->load->field('Field_related_id',"录入人","bookkeeper");
        $this->field_list['bookkeeper']->set_relate_db('pPeaple','id','name');
        $this->field_list['bookkeeper']->setEditor('hr/searchPeaple/');
        $this->field_list['bookkeeper']->setPlusCreateData(array('name'=>'','roleId'=>0));

        $this->field_list['auditor'] = $this->load->field('Field_related_id',"审核人","auditor");
        $this->field_list['auditor']->set_relate_db('pPeaple','id','name');
        $this->field_list['auditor']->setEditor('hr/searchPeaple/');
        $this->field_list['auditor']->setPlusCreateData(array('name'=>'','roleId'=>0));


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
        return '流水编号:'.$this->field_list['showId']->gen_show_html();
    }

    public function gen_op_audit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doAuditTurnover/1",'.$this->id.')\' title="审核"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_deaudit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doAuditTurnover/0",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    
    public function get_list_ops(){
        //array('未提交','已提交','审批通过','审批被拒','审核通过','审核被拒','报销完成'));
        switch ($this->field_list['status']->value) {  
            case 1:
            //已审核
            
                $array = array('deaudit') ;
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
        return $array;;
    }

    
    
}
?>