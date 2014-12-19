<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/peaple_model.php");
class Peaple_list extends List_model {
    public function __construct() {
        parent::__construct('pPeaple');
        parent::init("Peaple_list","Peaple_model");
    }
    public function genAnylyticsAllUser(){
        $exportData = array('all'=>0,'data'=>array());

        $this->db->select("COUNT(id) as count_id")
                    ->from("pPeaple")
                    ->where("orgId",$this->whereOrgId)
                    ->where("uid >",0);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $exportData['data'][] = array("label"=>"已注册",
                    "data"=>intval($row['count_id']));
                $exportData['all'] += intval($row['count_id']);
            }
        } else {
            $exportData['data'][] = array("label"=>"已注册",
                    "data"=>0);
        }
       $this->db->select("COUNT(id) as count_id")
                    ->from("pPeaple")
                    ->where("orgId",$this->whereOrgId)
                    ->where("uid",0);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $exportData['data'][] = array("label"=>"未注册",
                    "data"=>intval($row['count_id']));
                $exportData['all'] += intval($row['count_id']);
            }
        } else {
            $exportData['data'][] = array("label"=>"未注册",
                    "data"=>0);
        }

        
        return $exportData;
    }

    public function genAnylyticsYear($typ){
        $exportData = array('all'=>0,'data'=>array());

        if ($typ==1) {
            $this->db->select("COUNT(id) as count_id,year( FROM_UNIXTIME( `updateTS` )) as typId")
                    ->from("cCrm")
                    ->where("orgId",$this->whereOrgId)
                    ->where("updateTS >",0)
                    ->group_by("year( FROM_UNIXTIME( `updateTS` ))");
        } else {
            $this->db->select("COUNT(id) as count_id,year( FROM_UNIXTIME( `updateTS` )) as typId")
                    ->from("cCrm")
                    ->where("orgId",$this->whereOrgId)
                    ->where("updateTS >",0)
                    ->group_by("year( FROM_UNIXTIME( `updateTS` ))");
        }
        $real_data = array();

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $real_data[intval($row['typId'])] = intval($row['count_id']);
            }
        } 

        foreach ($this->dataModel['typInAll']->enum as $key => $value) {
            $exportData['data'][] = array("label"=>$value,
                    "data"=>(isset($real_data[$key]))?$real_data[$key]:0);
        }
        return $exportData;
    }

    public function genAnylytics(){
        $exportData = array('all'=>0,'data'=>array());

        $this->db->select("COUNT(id) as count_id,typInAll as typId")
                ->from("pPeaple")
                ->where("orgId",$this->whereOrgId)
                ->group_by("typId");

        $real_data = array();

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $real_data[intval($row['typId'])] = intval($row['count_id']);
            }
        } 

        foreach ($this->dataModel['typInAll']->enum as $key => $value) {
            $count = (isset($real_data[$key]))?$real_data[$key]:0;
            $exportData['data'][] = array("label"=>$value,
                    "data"=>$count);
            $exportData['all'] += $count;
        }
        
        return $exportData;
    }

    public function build_search_infos(){
        return array('name','nickname','roleId','departmentId','titleId');
    }
    public function build_list_titles(){
        return array('name','nickname','phoneNumber','email','sex','roleId','departmentId','titleId','attendTS');
    }

    public function preloadImportData($data){
        $cfg_field_lists = array(
            "name",'sex','email','nickname',
            'attendTS','addresses','zipCode','phoneNumber','qqNumber','weiboNumber','wechatNumber',
            'otherContact','birthTS','idType','idNumber','provinceId',
            'education','school','outcomming','comments'
        );
        //姓名*必填 性别*必填   电子邮箱*必填，不可重复    昵称  使用昵称    
        //加入时间*必填 开始公益时间  通讯地址    邮编  联系电话    QQ  微博  微信  
        //其他联系方式  出生年月    证件类型    证件号码    国籍  省份    
        //学历 毕业学校    专业  特长  人事说明
        
        foreach ($data as $lineId => $line) {
            if ($lineId==0) {
                continue;
            }
            

            foreach ($line as $cellId => $cell) {
                
            }
        }
    }
}
?>