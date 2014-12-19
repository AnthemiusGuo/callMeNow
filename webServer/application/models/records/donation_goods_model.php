<?php
include_once(APPPATH."models/record_model.php");
class Donation_goods_model extends Record_model {
    public function __construct() {
        parent::__construct('dDonGoods');

        $this->deleteCtrl = 'donation';
        $this->deleteMethod = 'doDeleteDonationGoods';
        $this->edit_link = 'donation/editDetailGoods';

        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        $this->field_list['showId']= $this->load->field('Field_string',"募捐编号","showId");
        $this->field_list['donationId']= $this->load->field('Field_int',"donationId","donationId");
        $this->field_list['donor']= $this->load->field('Field_string',"募捐人","donor",true);
        $this->field_list['goods']= $this->load->field('Field_string',"物资","goods",true);
        $this->field_list['donorCount']= $this->load->field('Field_string',"数量","donorCount",true);
        $this->field_list['isAnou']= $this->load->field('Field_bool',"匿名","isAnou");

        $this->field_list['method'] = $this->load->field('Field_enum',"分类","method");
        $this->field_list['method']->setEnum(array("其他","直接赠送","拍卖"));
        $this->field_list['donorTS'] = $this->load->field('Field_date',"日期","donorTS");
        $this->field_list['projectId'] = $this->load->field('Field_projectid',"关联项目","projectId");
        

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        
        $this->field_list['orgId']->init("1");
        $this->field_list['desc']->init("中科院微生物所已破译");

        $this->field_list['showId']->init("123");
        
        $this->field_list['typ']->init("1");
        $this->field_list['projectId']->init("1");
        $this->field_list['donationId']->init("1");
        
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
        return '募捐:'.$this->field_list['donor']->gen_show_html().'<small> ID:'.$this->field_list['goods']->gen_show_html() .' X '.$this->field_list['donorCount']->gen_show_html() .'</small>';
    }
    
}
?>