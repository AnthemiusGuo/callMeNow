<?php
include_once(APPPATH."models/record_model.php");
class Donation_model extends Record_model {
    public function __construct() {
        parent::__construct('dDonation');
        $this->deleteCtrl = 'donation';
        $this->deleteMethod = 'doDeleteDonation';
        $this->edit_link = 'donation/edit';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['desc'] = $this->load->field('Field_text',"描述","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        $this->field_list['showId']= $this->load->field('Field_string',"募捐编号","showId");

        $this->field_list['targetCrmId'] = $this->load->field('Field_relate_crm',"募捐对象","targetCrmId",true);
        $this->field_list['targetCrmId']->setTyp('Bedonored');

        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('未设置','准备','进行中','完成','中止'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"开始日期","beginTS");
        $this->field_list['endTS'] = $this->load->field('Field_date',"截止日期","endTS");
        $this->field_list['userInCharge'] = $this->load->field('Field_relate_multi_peaple',"负责人","userInCharge");
        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");
        
        $this->field_list['totalGetting'] = $this->load->field('Field_money',"募集资金(￥)","totalGetting");

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
        return '募捐:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['showId']->gen_show_html().'</small>';
    }

    public function updTotalGetting($id){
        $this->db->select('SUM(money) as sum_money')
                    ->from('dDonMoney')
                    ->where('donationId', $id);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $totalGetting = $result['sum_money'];

        }
        else
        {
            return -1;
        }

        $this->db->where("id",$id)
                ->update("dDonation",array("totalGetting"=>$totalGetting));
        return $totalGetting;
    }
    
}
?>