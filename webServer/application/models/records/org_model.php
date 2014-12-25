<?php
include_once(APPPATH."models/record_model.php");
class Org_model extends Record_model {
    public function __construct() {
        parent::__construct('oOrg');
        $this->title_create = '创建商户';

        $this->field_list['id'] = $this->load->field('Field_int',"机构代码","id");
        $this->field_list['name'] = $this->load->field('Field_title',"商户名称","name",true);
        $this->field_list['provinceId'] = $this->load->field('Field_provinceid',"省份","provinceId");
        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status");
        $this->field_list['status']->setEnum(array('正常','冻结'));
        $this->field_list['beginTS'] = $this->load->field('Field_date',"成立时间","beginTS");
        $this->field_list['addresses'] = $this->load->field('Field_string',"商户地址","addresses");
        $this->field_list['phone'] = $this->load->field('Field_string',"电话","phone");
        $this->field_list['qq'] = $this->load->field('Field_string',"QQ","qq");
        $this->field_list['weixin'] = $this->load->field('Field_string',"微信","weixin");
        $this->field_list['wangwang'] = $this->load->field('Field_string',"旺旺","wangwang");

        $this->field_list['zipCode'] = $this->load->field('Field_string',"邮编","zipCode");
        $this->field_list['desc'] = $this->load->field('Field_text',"商户介绍","desc");
        $this->field_list['supperUid'] = $this->load->field('Field_userid',"店主","supperUid");
        $this->field_list['commonInviteCode'] = $this->load->field('Field_string',"通用邀请码","commonInviteCode");
        $this->field_list['supperInviteCode'] = $this->load->field('Field_string',"管理员邀请码","supperInviteCode");

        
        
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
        
        $this->field_list['desc']->init("中科院微生物所已破译了目前最让人担忧的两种禽流感病毒H5N1和H7N9跨种传播机制，并发现H7N9病毒已经出现突变，开始具备有限的人际传播的能力。

无论是政府官员、科学家还是普罗大众，都迫不及待地想弄清楚两个问题，禽流感会不会人传人？禽流感什么时候会人传人？

H5N1和H7N9是近年来对人类威胁最大的两种禽流感病毒，H5N1病毒自1997年在首次感染人类后，在全球60多个国家肆虐，死亡率高达60%。H7N9在2013年2月底在中国长三角地区首次出现后，也是来势汹汹，在10个月内，中国12个省市发现了148人感染，其中46人死亡。");
        $this->field_list['beginTS']->init("1389582799");
        
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
        return '组织 :'.$this->field_list['name']->gen_show_html().'&nbsp;&nbsp; <small> ID:'.$this->field_list['id']->gen_show_html().'</small>';
    }

    public function buildCardShowFields(){
        $_html = '<div class="shopInfoCard">';
        $_html .= '<h4>['.$this->field_list['provinceId']->gen_show_value().']'.$this->field_list['name']->gen_show_html().'</h4>';
        if ($this->field_list['beginTS']->value>86400){
            $_html .= '<span class="shopBegin">始于 '.date("Y",$this->field_list['beginTS']->value).' 年</span>';
        }
        
        $_html .= '<p class="shopDesc">'.$this->field_list['desc']->gen_show_html().'</p>';

        $_html .= '<dl><dt>商户地址</dt>';
        $_html .= '<dd class="dd_wide">'.$this->field_list['addresses']->gen_show_html().'</dd>';
        $_html .= '<dt>商户电话</dt>';
        $_html .= '<dd>'.$this->field_list['phone']->gen_show_html().'</dd>';
        $_html .= '<dt>QQ</dt>';
        $_html .= '<dd>'.$this->field_list['qq']->gen_show_html().'</dd>';
        $_html .= '<dt>微信</dt>';
        $_html .= '<dd>'.$this->field_list['weixin']->gen_show_html().'</dd>';
        $_html .= '<dt>旺旺</dt>';
        $_html .= '<dd>'.$this->field_list['wangwang']->gen_show_html().'</dd>';
        $_html .= '<div class="clearfix"></div></div>';
            
                    
        return $_html;
    }
    public function buildChangeNeedFields(){
        return array('name','provinceId','desc','addresses','phone','qq','weixin','wangwang');
    }

    public function buildChangeShowFields(){
            return array(
                    array('name'),
                    array('desc'),
                    array('provinceId','null'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name'),
                    array('desc'),
                    array('provinceId','null'),
                    array('addresses'),
                    array('phone','qq'),
                    array('weixin','wangwang'),
                    
                );
    }

    public function create_org($data){
        $newId = $this->insert_db($data);
        $mdata = array('orgId'=>$newId);
        $this->db->insert('oMaxIds',$mdata);
    }
    public function get_max_id_and_increase($idName){
        $this->db->select($idName)
                    ->from('oMaxIds')
                    ->where('orgId', $this->id);

        $query = $this->db->get();
        
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $maxId = $result[$idName];

            $data = array($idName=>$maxId+1);
            $this->db->where('orgId', $this->id);
            $this->db->update('oMaxIds', $data); 

            return $maxId;
        } else {
            return 0;
        }
    }
    
}
?>