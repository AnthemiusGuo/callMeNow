<?php
include_once(APPPATH."models/record_model.php");
class Turnovertype_model extends Record_model {
    public function __construct() {
        parent::__construct('fTurnoverType');

        $this->deleteCtrl = 'finance';
        $this->deleteMethod = 'doDeleteTuroverType';
        $this->edit_link = 'finance/editTuroverType';


        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['showId'] = $this->load->field('Field_string',"编号","showId");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");

        $this->field_list['typ'] = $this->load->field('Field_enum',"分类","typ");
        $this->field_list['typ']->setEnum(array(0=>'收入',1=>'支出'));

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
        return '任务:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
    
}
?>