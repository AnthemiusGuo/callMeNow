<?php
include_once(APPPATH."models/record_model.php");
class Comment_model extends Record_model {
    public function __construct() {
        parent::__construct('cComments');
        $this->deleteCtrl = 'comments';
        $this->deleteMethod = 'doDeleteComments';
        $this->edit_link = 'comments/edit';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['cdesc'] = $this->load->field('Field_text',"内容","cdesc",true);
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        
        $this->field_list['uploadPeapleId'] = $this->load->field('Field_relate_peaple',"上传人","uploadPeapleId");
        $this->field_list['uploadZeit'] = $this->load->field('Field_ts',"上传时间","uploadZeit");
        $this->field_list['crelateTyp'] = $this->load->field('Field_string',"关联类型","crelateTyp");
        $this->field_list['crelateID'] = $this->load->field('Field_int',"关联","crelateID");
        $this->field_list['isUserDefineField']= $this->load->field('Field_bool',"自定义字段","isUserDefineField",true);
        $this->field_list['fieldName']= $this->load->field('Field_string',"自定义字段名","fieldName");
    }
    
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '文档:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
}
?>