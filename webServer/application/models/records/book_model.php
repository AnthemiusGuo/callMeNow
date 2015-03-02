<?php
include_once(APPPATH."models/record_model.php");
class Book_model extends Record_model {
    public function __construct() {
        parent::__construct('bBook');
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteCrm/book';
        $this->edit_link = 'crm/editBook';
        
        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['crmId'] = $this->load->field('Field_relate_crm',"订货方","crmId",true);

        $this->field_list['status'] = $this->load->field('Field_enum',"发货状态","status");
        $this->field_list['status']->setEnum(array('未确定','现货备货','订单生产','打包','发货','收货'));

        $this->field_list['payStatus'] = $this->load->field('Field_enum',"付款状态","payStatus");
        $this->field_list['payStatus']->setEnum(array('已付款','查账中','未付款'));

        $this->field_list['items'] = $this->load->field('Field_array_items',"订货单","items");

        $this->field_list['beginTS'] = $this->load->field('Field_date',"订货日期","beginTS");
        $this->field_list['endTS'] = $this->load->field('Field_date',"约定交货日期","endTS");
        
        $this->field_list['totalGetting'] = $this->load->field('Field_money',"金额(￥)","totalGetting",true);


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

    
    

    public function buildChangeShowFields(){
            return array(
                    array('items'),
                    array('totalGetting'),
                    array('beginTS','endTS'),
                    array('status','payStatus'),
                    array('desc'),
                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('crmId'),
                    array('items'),
                    array('totalGetting'),
                    array('beginTS','endTS'),
                    array('status','payStatus'),
                    array('desc'),
                );
    }
    
}
?>