<?php
include_once(APPPATH."models/record_model.php");
class Reimbursement_model extends Record_model {
    public function __construct() {
        parent::__construct("fReimbursement");
        $this->deleteCtrl = 'finance';
        $this->deleteMethod = 'doDeleteReimbursement';
        $this->edit_link = 'finance/edit_reimbursement';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['showId'] = $this->load->field('Field_string',"报销单号","showId");
        $this->field_list['detail'] = $this->load->field('Field_text',"明细","detail");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");


        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('未提交','已提交','审批通过','审批被拒','审核通过','审核被拒','报销完成'));

        $this->field_list['applyTS'] = $this->load->field('Field_ts',"申请日期","applyTS");
        $this->field_list['approveTS'] = $this->load->field('Field_ts',"审批日期","auditTS");
        
        $this->field_list['auditTS'] = $this->load->field('Field_ts',"审核日期","auditTS");
        $this->field_list['outgoing'] = $this->load->field('Field_int',"金额(元)","outgoing");


        $this->field_list['applyUid'] = $this->load->field('Field_relate_peaple',"申请人","applyUid");

        $this->field_list['approveUid'] = $this->load->field('Field_relate_peaple',"审批人","approveUid");
        ;

        $this->field_list['auditor'] = $this->load->field('Field_relate_peaple',"审核人","auditor");
        

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
    public function gen_op_edit(){
        return '<a class="list_op tooltips" onclick="lightbox({size:\'l\',url:\''.site_url($this->edit_link).'/'.$this->id.'\'})" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>';

    }

    public function gen_op_post(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doPostReimbursement",'.$this->id.')\' title="提交"><span class="glyphicon glyphicon-share-alt"></span></a>';

    }

    public function gen_op_approve(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doApproveReimbursement/1",'.$this->id.')\' title="审批"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_audit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doAuditReimbursement/1",'.$this->id.')\' title="审核"><span class="glyphicon glyphicon-ok"></span></a>';
    }
    public function gen_op_deapprove(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doApproveReimbursement/0",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    public function gen_op_deaudit(){
        return '<a class="list_op tooltips" onclick=\'reqOperator("finance","doAuditReimbursement/0",'.$this->id.')\' title="拒绝"><span class="glyphicon glyphicon-remove"></span></a>';
    }
    

    public function gen_check_list_op(){
        $opList = $this->get_check_list_ops();
        $strs = array();
        foreach ($opList as $op) {
            $func = "gen_op_".$op;
            $strs[] = $this->$func();
        }
        return implode(" | ", $strs);
    }
    public function get_check_list_ops(){
        //array('未提交','已提交','审批通过','审批被拒','审核通过','审核被拒','报销完成'));
        switch ($this->field_list['status']->value) {  
            case 1:
            //已提交
            
                $array = array('edit','delete') ;
                break;

            case 0:
            //未提交
            case 3:
            //审批被拒
            case 5:
            //审核被拒

                $array = array('edit','delete','post') ;
                break;
            case 2:
            //审批通过
            case 4:
            //审核通过
                
            case 6:
            //报销完成
                $array = array() ;
                break;    
            default:
                $array = array() ;
                break;
        }
        return $array;;
    }

    public function gen_approve_list_op(){
        $opList = $this->get_approve_list_ops();
        $strs = array();
        foreach ($opList as $op) {
            $func = "gen_op_".$op;
            $strs[] = $this->$func();
        }
        return implode(" | ", $strs);
    }
    public function get_approve_list_ops(){
        //array('未提交','已提交','审批通过','审批被拒','审核通过','审核被拒','报销完成'));
        switch ($this->field_list['status']->value) {  
            case 1:
            //已提交
                $array = array('approve','deapprove') ;
                break;
            case 2:
            //审批通过
                $array = array('audit','deaudit') ;
                break;
            default:
                $array = array() ;
                break;
        }
        return $array;;
    }
    public function buildInfoTitle(){
        return '申请人:'.$this->field_list['applyUid']->gen_show_html().'<small> 报销单号:'.$this->field_list['showId']->gen_show_html().'</small>';
    }
    
}
?>