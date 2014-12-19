<?php
include_once(APPPATH."models/record_model.php");
class Donor_model extends Record_model {
    public function __construct() {
        parent::__construct("cDonor");
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name");
        $this->field_list['xingming'] = $this->load->field('Field_title',"姓名","xingming");
        $this->field_list['nicheng'] = $this->load->field('Field_title',"昵称","nicheng");
        $this->field_list['shiyongnicheng'] = $this->load->field('Field_bool',"使用妮称","shiyongnicheng");  

        $this->field_list['xingbie'] = $this->load->field('Field_sex',"性别","xingbie");
        $this->field_list['chusheng'] = $this->load->field('Field_date',"出生日期","chusheng");
        $this->field_list['qingkuang'] = $this->load->field('Field_text',"情况说明","qingkuang");
        
        $this->field_list['province'] = $this->load->field('Field_provinceid',"省份","xuexiao");
        $this->field_list['tongxundizhi'] = $this->load->field('Field_string',"通讯地址","tongxundizhi");
        $this->field_list['youbian'] = $this->load->field('Field_string',"邮编","youbian");

        $this->field_list['zuzhixingshi'] = $this->load->field('Field_enum',"组织形式","zuzhixingshi");
        $this->field_list['zuzhixingshi']->setEnum(array("未设置","国有企业","私营企业","外企"));
        
        $this->field_list['zhuguandanwei'] = $this->load->field('Field_string',"主管单位","zhuguandanwei");
        $this->field_list['guimo'] = $this->load->field('Field_string',"规模","guimo");
        $this->field_list['danwei'] = $this->load->field('Field_string',"单位","danwei");
        $this->field_list['dianhua'] = $this->load->field('Field_string',"联系电话","dianhua");
        $this->field_list['youxiang'] = $this->load->field('Field_email',"电子邮箱","youxiang");
        $this->field_list['qitalianxi'] = $this->load->field('Field_string',"其他联系方式","qitalianxi");
        $this->field_list['zhucezijin'] = $this->load->field('Field_string',"注册资金","youbian");
        $this->field_list['yingyee'] = $this->load->field('Field_string',"年营业额","youbian");
        $this->field_list['nianduyusuan'] = $this->load->field('Field_string',"年度公益预算","nianduyusuan");
        $this->field_list['gongyirenshu'] = $this->load->field('Field_string',"公益团队人数","gongyirenshu");
        $this->field_list['gongyidiqu'] = $this->load->field('Field_string',"公益目标地区","gongyidiqu");
        $this->field_list['gongyirenqun'] = $this->load->field('Field_string',"公益目标人群","gongyirenqun");
        $this->field_list['techang'] = $this->load->field('Field_string',"特长","techang");

        $this->field_list['beDonoredIds'] = $this->load->field('Field_text',"直接资助人","bedonoredIds");
        $this->field_list['contactorIds'] = $this->load->field('Field_text',"联系人","contactorIds");

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最后更新","lastModifyTS");
    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        
        $this->field_list['beDonoredIds']->init("[1,2,3]");
        $this->field_list['contactorIds']->init("[1,2,3]");

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
        return '社会关系:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
    public function init_with_id($id){
        $this->db->select('*')
                    ->from($this->tableName)
                    ->where('id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $this->init_with_data($result['id'],$result);
            return 1;
        }
        else
        {
            $this->db->select('*')
                    ->from("cCrm")
                    ->where('id', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->row_array();
                $data = array('name'=>$result['name'],'xingming'=>$result['name'],'province'=>$result['province'],'id'=>$result['id']);
                $this->insert_db($data);
                return $this->init_with_id($id);
            } else {
                return -1;
            }
        }
    }
}
?>