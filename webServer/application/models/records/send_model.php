<?php
include_once(APPPATH."models/record_model.php");
class Send_model extends Record_model {
    public function __construct() {
        parent::__construct('bSend');

        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteSend';
        $this->edit_link = 'crm/edit_send';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['crmId'] = $this->load->field('Field_string',"客户","crmId");

        $this->field_list['items'] = $this->load->field('Field_array_items',"发货单","items");
        

        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");
        
        $this->field_list['orderId'] = $this->load->field('Field_int',"发货单号","orderId");

        $this->field_list['status'] = $this->load->field('Field_enum',"发货状态","status");
        $this->field_list['status']->setEnum(array('打包','发货','已收货'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"发货日期","beginTS");
        $this->field_list['packP'] = $this->load->field('Field_string',"打包人","packP");
        $this->field_list['sendP'] = $this->load->field('Field_string',"发货人","sendP");


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
    public function delete_db($ids){
        $effect = 0;
        $idArray = explode('-',$ids);
        foreach ($idArray as $id) {
            $this->db->where('id', $id)->delete($this->tableName);
            $effect += $this->db->affected_rows();
            $this->db->where('aboveDepartmentId',$id)->update('pDepartment',array(
               'aboveDepartmentId' => 0
            ));
            $this->db->where('departmentId',$id)->update('pTitle',array(
               'departmentId' => 0
            ));
            $this->db->where('departmentId',$id)->update('pPeaple',array(
               'departmentId' => 0
            ));
        }
        return $effect;
    }
    

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '部门:'.$this->field_list['name']->gen_show_html();
    }

    
    
}
?>