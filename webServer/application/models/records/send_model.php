<?php
include_once(APPPATH."models/record_model.php");
class Send_model extends Record_model {
    public function __construct() {
        parent::__construct('bSend');

        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doSubDel/send';
        $this->edit_link = 'crm/subEdit/send/';
        $this->info_link = 'crm/subinfo/send/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['crmId'] = $this->load->field('Field_relate_crm',"客户","crmId",true);
        $this->field_list['crmId']->setTyp('noSend');

        $this->field_list['items'] = $this->load->field('Field_array_items',"发货单","items",true);
        $this->field_list['items']->is_title = true;

        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");
        
        $this->field_list['orderId'] = $this->load->field('Field_int',"发货单号","orderId");

        $this->field_list['status'] = $this->load->field('Field_enum',"发货状态","status");
        $this->field_list['status']->setEnum(array('打包','发货','已收货'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"发货日期","beginTS");

        $this->field_list['packP'] = $this->load->field('Field_relate_crm',"打包人","packP");
        $this->field_list['sendP'] = $this->load->field('Field_relate_crm',"发货人","sendP");
        $this->field_list['packP']->setPlusCreateData(array('typ'=>4));
        $this->field_list['sendP']->setPlusCreateData(array('typ'=>4));
        $this->field_list['packP']->setTyp('send');
        $this->field_list['sendP']->setTyp('send');

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function buildChangeShowFields(){
            return array(
                    array('items'),
                    array('status','beginTS'),
                    array('packP','sendP'),
                    array('desc'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('items'),
                    array('status','beginTS'),
                    array('packP','sendP'),
                    array('desc'),
                );
    }
    

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '发货记录:'.$this->field_list['crmId']->gen_show_html().'<small> ID:'.$this->field_list['beginTS']->gen_show_html().'</small>';
    }

    
    
}
?>