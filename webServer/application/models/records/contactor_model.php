<?php
include_once(APPPATH."models/record_model.php");
class Contactor_model extends Record_model {
    public function __construct() {
        parent::__construct('cContactor');

        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doSubDel/contactor';
        $this->edit_link = 'crm/subEdit/contactor/';
        $this->info_link = 'crm/subinfo/contactor/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"商户","orgId");
        $this->field_list['name'] = $this->load->field('Field_string',"姓名","name",true);
        $this->field_list['name']->is_title = true;
        $this->field_list['crmId'] = $this->load->field('Field_relate_crm',"客户","crmId");
        $this->field_list['dianhua'] = $this->load->field('Field_string',"电话","dianhua");
        $this->field_list['qq'] = $this->load->field('Field_string',"QQ","qq");
        $this->field_list['weixin'] = $this->load->field('Field_string',"微信","weixin");


        $this->field_list['qitafangshi'] = $this->load->field('Field_string',"其他联系方式","qitafangshi");
        $this->field_list['isMain'] = $this->load->field('Field_bool',"是主要联系人","isMain");

    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return $this->field_list['name']->gen_show_html().' <small> '.$this->field_list['dianhua']->gen_show_html().' </small>';
    }

    public function buildQuickChangeShowFields(){
            return array(
                    array('name','crmId'),
                    array('dianhua','qq'),
                    array('weixin','qitafangshi'),

                );
    }

    public function buildChangeShowFields(){
            return array(
                    array('name','null'),
                    array('dianhua','qq'),
                    array('weixin','qitafangshi'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name','isMain'),
                    array('dianhua','qq'),
                    array('weixin','qitafangshi'),
                );
    }
    public function gen_brief_html(){
        $_html = '<strong>'.$this->field_list['name']->gen_show_html().'</strong>';
        if ($this->field_list['dianhua']->value!=''){
            $_html .= '  <span>电话:'.$this->field_list['dianhua']->gen_show_html().';</span>';
        }
        if ($this->field_list['qq']->value!=''){
            $_html .= '  <span>QQ:'.$this->field_list['qq']->gen_show_html().'</span>';
        }
        if ($this->field_list['weixin']->value!=''){
            $_html .= '  <span>微信:'.$this->field_list['weixin']->gen_show_html().'</span>';
        }
        if ($this->field_list['qitafangshi']->value!=''){
            $_html .= '  <span>其他:'.$this->field_list['qitafangshi']->gen_show_html().'</span>';
        }
        return $_html;
    }
    //0姓名*必填   1性别*必填   2出生日期*必填 3省份*必填   4状态*必填   5情况说明    6学校  7学校地址    8通讯地址    9邮编  10需赞助金额   11汇款账号    12联系人姓名   13联系电话    14电子邮箱    15其他联系方式
    public function checkImportDataP($data){
        $cfg_field_lists = array(
            12=>"name",13=>"dianhua",14=>"youxiang",15=>"qitafangshi"
        );
        return $this->checkImportDataBase($data,$cfg_field_lists);
    }
    //0名称*必填 1省份*必填   2状态*必填   3情况说明    4通讯地址    5邮编  6需赞助金额   7汇款账号    8建筑设施    9其他  10联系人姓名   11联系电话    12电子邮箱    13其他联系方式
    public function checkImportDataO($data){
        $cfg_field_lists = array(
            10=>"name",11=>"dianhua",12=>"youxiang",13=>"qitafangshi"
        );
        return $this->checkImportDataBase($data,$cfg_field_lists);
    }
    public function importData($line,$typ){
        if ($typ=="BedonaredP") {
            $cfg_field_lists = array(
                12=>"name",13=>"dianhua",14=>"youxiang",15=>"qitafangshi"
            );
        } else {
            $cfg_field_lists = array(
            10=>"name",11=>"dianhua",12=>"youxiang",13=>"qitafangshi"
        );
        }
        $data = array();
        foreach ($line as $key => $value) {
            # code...
            if (!isset($cfg_field_lists[$key])) {
                continue;
            }
            $field_name = $cfg_field_lists[$key];

            $data[$field_name] = $this->field_list[$field_name]->importData($value);
        }
        return $data;
    }
}
?>
