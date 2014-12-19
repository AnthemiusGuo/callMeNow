<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/reimbursement_model.php");
class Reimbursement_list extends List_model {
    public function __construct() {
        parent::__construct("fReimbursement");
        parent::init("Reimbursement_list","Reimbursement_model");
    }
    
    public function init(){
        
        
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Reimbursement_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function build_my_list_titles(){
        return array('showId','applyTS','outgoing','status','approveUid','auditor');
    }
    public function build_list_titles($typ=0){
        switch ($typ) {
            case 0:
                return array('showId','applyTS','outgoing','status','approveUid','approveTS','auditor');
                break;
            case 1:
                return array('showId','applyUid','applyTS','outgoing');
                break;
            case 2:
                return array('showId','applyUid','applyTS','outgoing','approveUid','approveTS');
                break;
            case 3:
                return array('showId','applyUid','applyTS','outgoing','approveUid','approveTS','auditor','auditTS','status');
                break;

            case 4:
                return array('showId','applyUid','applyTS','outgoing','approveUid','approveTS','auditor','auditTS','status');
                break;
            default:
                # code...
                break;
        }
        
    }
}
?>