<?php
include_once(APPPATH."models/record_model.php");
class Bedonored_model extends Record_model {
    public function __construct() {
        parent::__construct("cBedonored");
        $this->field_list['id'] = $this->load->field('Field_int',"id","id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name");
        $this->field_list['xingming'] = $this->load->field('Field_title',"姓名","xingming");
        $this->field_list['xingbie'] = $this->load->field('Field_enum',"性别","xingbie");
        $this->field_list['xingbie']->setEnum(array("未设置","男","女"));
        $this->field_list['chusheng'] = $this->load->field('Field_date',"出生日期","chusheng");
        $this->field_list['qingkuang'] = $this->load->field('Field_text',"情况说明","qingkuang");
        
        $this->field_list['province'] = $this->load->field('Field_provinceid',"省份","xuexiao");

        $this->field_list['xuexiao'] = $this->load->field('Field_string',"学校","xuexiao");
        $this->field_list['xuexiaodizhi'] = $this->load->field('Field_string',"学校地址","xuexiaodizhi");
        $this->field_list['jianxiaoTS'] = $this->load->field('Field_string',"建校时间","jianxiaoTS");
        
        $this->field_list['tongxundizhi'] = $this->load->field('Field_string',"通讯地址","tongxundizhi");
        $this->field_list['youbian'] = $this->load->field('Field_string',"邮编","youbian");
        $this->field_list['zanzhujine'] = $this->load->field('Field_string',"需赞助金额","zanzhujine");
        $this->field_list['huikuanzhanghao'] = $this->load->field('Field_string',"汇款账号","huikuanzhanghao");


        $this->field_list['jianzhusheshi'] = $this->load->field('Field_text',"建筑设施","jianzhusheshi");        
        $this->field_list['jianzhusheshi']->setDefault("占地面积 : \n
总建筑面积 : \n
体育运动场地情况 : \n
其他说明: \n");

        $this->field_list['shishengguimo'] = $this->load->field('Field_text',"师生规模","shishengguimo");
        $this->field_list['shishengguimo']->setDefault(
"班级数 : \n
在校生人数 : \n
学校覆盖行政村个数: \n
受希望工程资助学生数 : \n
教职工人数 : \n
公办教师人数: \n
代课教师人数 : \n
住校学生人数 : \n
住校教师人数: \n");
        $this->field_list['jiaoxuejiben'] = $this->load->field('Field_text',"教育教学基本情况","jiaoxuejiben");
        $this->field_list['jiaoxuejiben']->setDefault("教育教学基本情况说明 : \n
图书拥有量 : \n
获奖情况说明 : \n");
        $this->field_list['qita'] = $this->load->field('Field_text',"其他","qita");
$this->field_list['qita']->setDefault("计划中项目说明 : \n
学校所在县/市年人均收入 : \n
学校所在乡/镇年人均收入 : \n");

        $this->field_list['buildingIds'] = $this->load->field('Field_text',"建筑细节","buildingIds");
        $this->field_list['donorIds'] = $this->load->field('Field_text',"直接资助人","donorIds");
        $this->field_list['contactorIds'] = $this->load->field('Field_text',"联系人","contactorIds");

    }
    public function init($id){
        parent::init($id);
        //取数据库，先跳过
        $this->field_list['id']->init($id);
        

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
    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){
        
    }
    public function buildInfoTitle(){
        return '社会关系:'.$this->field_list['name']->gen_show_html().'<small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }
    
    //0姓名*必填   1性别*必填   2出生日期*必填 3省份*必填   4状态*必填   5情况说明    6学校  7学校地址    8通讯地址    9邮编  10需赞助金额   11汇款账号    12联系人姓名   13联系电话    14电子邮箱    15其他联系方式
    public function checkImportDataP($data){
        $cfg_field_lists = array(
            0=>"xingming",1=>"xingbie",2=>"chusheng",3=>"province",5=>'qingkuang',6=>"xuexiao",7=>"xuexiaodizhi",8=>"tongxundizhi",
                9=>"youbian",10=>"zanzhujine",11=>"huikuanzhanghao"
        );
        return $this->checkImportDataBase($data,$cfg_field_lists);
    }

    //0名称*必填 1省份*必填   2状态*必填   3情况说明    4通讯地址    5邮编  6需赞助金额   7汇款账号    8建筑设施    9其他  10联系人姓名   11联系电话    12电子邮箱    13其他联系方式
    public function checkImportDataO($data){
        $cfg_field_lists = array(
            0=>"name",1=>"province",3=>"qingkuang",4=>"tongxundizhi",5=>'youbian',6=>"zanzhujine",7=>"huikuanzhanghao",8=>"jianzhusheshi",
                9=>"qita"
        );
        return $this->checkImportDataBase($data,$cfg_field_lists);
    }
    public function importData($line,$typ){
        if ($typ=="BedonaredP") {
            $cfg_field_lists = array(
            0=>"name",1=>"xingbie",2=>"chusheng",3=>"province",5=>'qingkuang',6=>"xuexiao",7=>"xuexiaodizhi",8=>"tongxundizhi",
                9=>"youbian",10=>"zanzhujine",11=>"huikuanzhanghao"
        );
        } else {
            $cfg_field_lists = array(
            0=>"name",1=>"province",3=>"qingkuang",4=>"tongxundizhi",5=>'youbian',6=>"zanzhujine",7=>"huikuanzhanghao",8=>"jianzhusheshi",
                9=>"qita"
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