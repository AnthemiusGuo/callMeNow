<?php
include_once(APPPATH."models/record_model.php");
class Document_model extends Record_model {
    public function __construct() {
        parent::__construct('docDocument');
        $this->deleteCtrl = 'document';
        $this->deleteMethod = 'doDeleteDocument';
        $this->edit_link = 'document/edit';
        
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['showId'] = $this->load->field('Field_string',"编号","showId");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['desc'] = $this->load->field('Field_text',"文档说明","desc");
        $this->field_list['orgId'] = $this->load->field('Field_int',"组织","orgId");
        

        $this->field_list['fileLink'] = $this->load->field('Field_upload',"文件","fileLink",true);
        $this->field_list['uploadTS'] = $this->load->field('Field_ts',"上传日期","uploadTS");
        $this->field_list['uploadUid'] = $this->load->field('Field_relate_peaple',"上传人","uploadUid");
        $this->field_list['relateTyp'] = $this->load->field('Field_enum',"关联类型","relateTyp");
        $this->field_list['relateTyp']->setEnum(array("无","项目","社会关系"));
        $this->field_list['relateID'] = $this->load->field('Field_relate_simple_id',"关联","relateID");
        $this->field_list['relateID']->set_relate_db('pProject','id','name');
        
        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }
    public function init_with_id($id){
        parent::init_with_id($id);
        //取数据库，先跳过
        $relateTyp = $this->field_list['relateTyp']->value;
        
        if ($relateTyp==1){
            $this->field_list['relateID']->set_relate_db('pProject','id','name');
        } elseif($relateTyp==2) {
            $this->field_list['relateID']->set_relate_db('cCrm','id','name');
        }
        $this->field_list['relateID']->init($this->field_list['relateID']->value);
    }

    public function init_with_data($id,$data){
        parent::init_with_data($id,$data);
        //取数据库，先跳过
        $relateTyp = $this->field_list['relateTyp']->value;
        
        if ($relateTyp==1){
            $this->field_list['relateID']->set_relate_db('pProject','id','name');
        } elseif($relateTyp==2) {
            $this->field_list['relateID']->set_relate_db('cCrm','id','name');
        }
        $this->field_list['relateID']->init($this->field_list['relateID']->value);
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