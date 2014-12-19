<?php
include_once(APPPATH."models/record_model.php");
class Contactor_model extends Record_model {
    public function __construct() {
        parent::__construct('cContactor');

        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteContactor';
        $this->edit_link = 'crm/editContactor';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");

        $this->field_list['name'] = $this->load->field('Field_string',"姓名","name",true);
        $this->field_list['isFirst'] = $this->load->field('Field_bool',"首选联系人","isFirst",true);
        $this->field_list['typ'] = $this->load->field('Field_enum',"身份","typ",true);
        $this->field_list['typ']->setEnum(array('其他','公益负责人','企业/单位负责人'));
        $this->field_list['crmId'] = $this->load->field('Field_int',"crmId","crmId");

        
        $this->field_list['dianhua'] = $this->load->field('Field_string',"联系电话","dianhua");
        $this->field_list['youxiang'] = $this->load->field('Field_email',"电子邮箱","youxiang");
        $this->field_list['qitafangshi'] = $this->load->field('Field_string',"其他联系方式","qitafangshi");
        
        
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        $this->field_list['dianhua']->init(rand(13910001000,13999999999));
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
        return $this->field_list['name']->gen_show_html().' <small> '.$this->field_list['typ']->gen_show_html().' </small>';
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