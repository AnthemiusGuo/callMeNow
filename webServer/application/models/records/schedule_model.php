<?php
include_once(APPPATH."models/record_model.php");
class Schedule_model extends Record_model {
    public function __construct() {
        parent::__construct('pSchedule');
        $this->deleteCtrl = 'schedule';
        $this->deleteMethod = 'doDeleteSchedule';
        $this->edit_link = 'schedule/edit';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        

        $this->field_list['desc'] = $this->load->field('Field_text',"描述","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        
        $this->field_list['isWholeDay'] = $this->load->field('Field_bool',"全天事件","isWholeDay");

        $this->field_list['beginTS'] = $this->load->field('Field_ts',"开始时间","beginTS",true);
        $this->field_list['endTS'] = $this->load->field('Field_ts',"结束时间","endTS",true);
        
        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");
        $this->field_list['place'] = $this->load->field('Field_string',"地点","place",true);



        $this->field_list['userInCharge'] = $this->load->field('Field_relate_multi_peaple',"负责人","userInCharge");

        $this->field_list['userInvolved'] = $this->load->field('Field_relate_multi_peaple',"参与者","userInvolved");
        

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['name']->init("H7N9：跨种感染机制突破");
        
        $this->field_list['orgId']->init("1");
        $this->field_list['desc']->init("中科院微生物所已破译了目前最让人担忧的两种禽流感病毒H5N1和H7N9跨种传播机制，并发现H7N9病毒已经出现突变，开始具备有限的人际传播的能力。

无论是政府官员、科学家还是普罗大众，都迫不及待地想弄清楚两个问题，禽流感会不会人传人？禽流感什么时候会人传人？

H5N1和H7N9是近年来对人类威胁最大的两种禽流感病毒，H5N1病毒自1997年在首次感染人类后，在全球60多个国家肆虐，死亡率高达60%。H7N9在2013年2月底在中国长三角地区首次出现后，也是来势汹汹，在10个月内，中国12个省市发现了148人感染，其中46人死亡。");

        $this->field_list['isWholeDay']->init(0);
        $this->field_list['beginTS']->init("1389582799");
        $this->field_list['endTS']->init("1389582799");
        $this->field_list['place']->init("新天地");
        $this->field_list['projectId']->init("1");
        $this->field_list['userInCharge']->init("1");
        $this->field_list['userInvolved']->init("1");
        
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
        return '日程:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }

    public function build_peaple_relation($id,$data){


        $this->db->where("scheduleId",$id)->delete("pSchedulePeapleRel");
        if (count($data)==0) {
            return;
        }   
        $insert_data = array();
        if (isset($data['userInCharge'])){
            $related_crm = explode(',', $data['userInCharge']);
            foreach ($related_crm as $key) {
                if ($key!="") {
                    $insert_data[$key] = array("peapleId"=>$key,"scheduleId"=>$id);
                }
            }
        }
        if (isset($data['userInvolved'])){
            $related_crm = explode(',', $data['userInvolved']);
            foreach ($related_crm as $key) {
                if ($key!="") {
                    $insert_data[$key] = array("peapleId"=>$key,"scheduleId"=>$id);
                }
                
            }
        }
        $insert_data = array_values($insert_data);
        if (count($insert_data)==0) {
            return;
        }

        $this->db->insert_batch("pSchedulePeapleRel",$insert_data);

    }

    
}
?>