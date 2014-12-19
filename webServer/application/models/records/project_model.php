<?php
include_once(APPPATH."models/record_model.php");
class Project_model extends Record_model {
    public function __construct() {
        parent::__construct("pProject");
        
        $this->deleteCtrl = 'project';
        $this->deleteMethod = 'doDeleteProject';
        $this->edit_link = 'project/editProject';

        if ($this->config->item("app_type")=="npone"){
            $this->field_list['typ'] = $this->load->field('Field_tag',"项目分类","typ");
        $this->field_list['typ']->setEnum(array("A"=>"其他","B"=>"教育","C"=>"医疗","D"=>"保障","E"=>"环保","F"=>"文艺","G"=>"节能","H"=>"城市保护","I"=>"助老","J"=>"助残","K"=>"培训","L"=>"社交"));
            $this->field_list['desc'] = $this->load->field('Field_text',"描述","desc",true);
            $this->field_list['orgRequirement'] = $this->load->field('Field_relate_multicrm',"需求方","orgRequirement",true);
        $this->field_list['orgRequirement']->setTyp('Bedonored');
        
        $this->field_list['orgBuild'] = $this->load->field('Field_relate_multicrm',"主办方","orgBuild",true);
        $this->field_list['orgBuild']->setTyp('Donor');
        $this->field_list['orgBuild']->setMyOrgDft(true);
        $this->field_list['orgBuild']->needOrgId=2;
        
        $this->field_list['orgRes'] = $this->load->field('Field_relate_multicrm',"资源方","orgRes");
        $this->field_list['orgRes']->setTyp('Donor');

        $this->field_list['orgCoop'] = $this->load->field('Field_relate_multicrm',"合作方","orgCoop");
        $this->field_list['orgCoop']->setTyp('Donor');
        $this->field_list['userInvolved'] = $this->load->field('Field_relate_multi_peaple',"参与人","userInvolved");
        $this->field_list['descInvestigation'] = $this->load->field('Field_text',"背景调查","descInvestigation");
        $this->field_list['descReqAnalysis'] = $this->load->field('Field_text',"需求分析","descReqAnalysis");
        $this->field_list['financingGap'] = $this->load->field('Field_int',"资金缺口","financingGap");
        $this->field_list['suppliesRequired'] = $this->load->field('Field_text',"物资需求","suppliesRequired");
        } else {
            
            $this->field_list['orgRequirement'] = $this->load->field('Field_text',"学校名称","orgRequirement",true);
            $this->field_list['descReqAnalysis'] = $this->load->field('Field_text',"项目背景与目的","descReqAnalysis",true);
            $this->field_list['target'] = $this->load->field('Field_text',"项目目标","target",true);
            $this->field_list['plan'] = $this->load->field('Field_text',"项目里程碑计划","plan");
            $this->field_list['standard'] = $this->load->field('Field_text',"评价标准","standard");
            $this->field_list['suppliesRequired'] = $this->load->field('Field_text',"项目假定与约束条件","suppliesRequired");
            $this->field_list['userInvolved'] = $this->load->field('Field_text',"主要干系人","userInvolved");

        }
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['showId'] = $this->load->field('Field_int',"编号","showId");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        
        $this->field_list['beginTS'] = $this->load->field('Field_date',"开始时间","beginTS");
        $this->field_list['endTS'] = $this->load->field('Field_date',"结束时间","endTS");
        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        if ($this->config->item("app_type")=="npone"){
            $this->field_list['status']->setEnum(array("未设置","准备","进行中","完成","中止"));
        } else {
            $this->field_list['status']->setEnum(array("未设置","准备","进行","完成","取消"));
        }
        $this->field_list['userInCharge'] = $this->load->field('Field_relate_multi_peaple',"负责人","userInCharge");

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
    public function build_typ_relation($id,$typs=""){
        $this->db->where("projectId",$id)->delete("pProjectTypRel");
        //["1","2","3"]
        if ($typs==""){
            return;
        }
        $real_typ = json_decode($typs,true);
        if (!is_array($real_typ)){
            return;
        }
        foreach ($real_typ as $key) {
            $data = array("typId"=>$key,"projectId"=>$id);
            $this->db->insert("pProjectTypRel",$data);
        }
    }
    public function build_peaple_relation($id,$data){


        $this->db->where("projectId",$id)->delete("pProjectPeapleRel");
        if (count($data)==0) {
            return;
        }   
        $insert_data = array();
        if (isset($data['userInCharge'])){
            $related_crm = explode(',', $data['userInCharge']);
            foreach ($related_crm as $key) {
                if ($key!="") {
                    $insert_data[$key] = array("peapleId"=>$key,"projectId"=>$id);
                }
            }
        }
        if (isset($data['userInvolved'])){
            $related_crm = explode(',', $data['userInvolved']);
            foreach ($related_crm as $key) {
                if ($key!="") {
                    $insert_data[$key] = array("peapleId"=>$key,"projectId"=>$id);
                }
                
            }
        }
        $insert_data = array_values($insert_data);
        if (count($insert_data)==0) {
            return;
        }

        $this->db->insert_batch("pProjectPeapleRel",$insert_data);
    }
    public function build_crm_relation($id,$data){
        $this->db->where("projectId",$id)->delete("pProjectCrmRel");
        //["1","2","3"]
        if (count($data)==0) {
            return;
        }
        $insert_data = array();
        if (isset($data['orgRequirement'])){
            $related_crm = explode(',', $data['orgRequirement']);
            foreach ($related_crm as $key) {
                $insert_data[$key] = array("crmId"=>$key,"projectId"=>$id);
            }
        }
        if (isset($data['orgBuild'])){
            $related_crm =explode(',', $data['orgBuild']);
            foreach ($related_crm as $key) {
                $insert_data[$key] = array("crmId"=>$key,"projectId"=>$id);
            }
        }
        if (isset($data['orgRes'])){
            $related_crm = explode(',', $data['orgRes']);
            foreach ($related_crm as $key) {
                $insert_data[$key] = array("crmId"=>$key,"projectId"=>$id);
            }
        }
        if (isset($data['orgCoop'])){
            $related_crm = explode(',', $data['orgCoop']);
            foreach ($related_crm as $key) {
                $insert_data[$key] = array("crmId"=>$key,"projectId"=>$id);
            }
        }
        $insert_data = array_values($insert_data);
        if (count($insert_data)==0) {
            return;
        }

        $this->db->insert_batch("pProjectCrmRel",$insert_data);
    }
    public function checkImportData($data){
        $cfg_field_lists = array(
            "name",'desc','typ','beginTS','endTS',
            'status','orgRequirement','orgBuild','orgRes','orgCoop','userInCharge','userInvolved','descInvestigation','descReqAnalysis',
            'financingGap','suppliesRequired');
        //名称*必填 描述*必填   项目分类    开始日期    结束日期    状态  需求方*必填  主办方*必填  资源方 合作方 负责人 参与者 背景调查    需求分析    资金缺口    物资需求

        return $this->checkImportDataBase($data,$cfg_field_lists);
    }

    public function importData($line){
        $cfg_field_lists = array(
            "name",'desc','typ','beginTS','endTS',
            'status','orgRequirement','orgBuild','orgRes','orgCoop','userInCharge','userInvolved','descInvestigation','descReqAnalysis',
            'financingGap','suppliesRequired');
        $data = array();
        foreach ($line as $key => $value) {
            # code...
            $field_name = $cfg_field_lists[$key];
            if ($field_name=="idNumber") {
                //1.11111198001012E+17
                $value = sprintf("%.0f",(float)$value);

                // $split = explode("E",$value);
                // if (count($split)>0) {
                //     $value = (float)$split[0] * pow(10,(int)$split[1]);
                // }
                
            }
            $data[$field_name] = $this->field_list[$field_name]->importData($value);
        }
        return $data;
    }
    
}
?>