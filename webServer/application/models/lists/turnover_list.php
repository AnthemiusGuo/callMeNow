<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/turnover_model.php");
include_once(APPPATH."models/records/financeany_model.php");


class Turnover_list extends List_model {
    public function __construct() {
        parent::__construct("fTurnover");
        parent::init("Turnover_list","Turnover_model");
    }
    public function init_by_project($projectId=0){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Turnover_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function init($begin_ts,$end_ts){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Turnover_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function analyse_by_typ_and_date($orgId,$typ,$begin_ts,$end_ts){

        parent::init("Turnover_list","Financeany_model");
        
        $realBeginTs = mktime(0,0,0,$begin_ts[1],1,$begin_ts[0]);
        $realEndTs = mktime(23,59,59,$end_ts[1]+1,0,$end_ts[0]);

        if ($typ==1) {
            $this->db->select("SUM(incoming) as s_incoming,SUM(outgoing) as s_outgoing,TSByMonth as title")
                ->from("fTurnover")
                ->where("orgId",$orgId)
                ->where("status","1")
                ->where("beginTS >=",$realBeginTs)
                ->where("beginTS <=",$realEndTs)
                ->group_by("TSByMonth");
        } elseif ($typ==2) {
            $this->db->select("SUM(fTurnover.incoming) as s_incoming,SUM(fTurnover.outgoing) as s_outgoing,fTurnover.typ,fTurnoverType.name as title")
                ->from("fTurnover")
                ->join('fTurnoverType', 'fTurnoverType.id = fTurnover.typ', 'left')
                ->where("fTurnover.orgId",$orgId)
                ->where("fTurnover.status","1")
                ->where("beginTS >=",$realBeginTs)
                ->where("beginTS <=",$realEndTs)
                ->group_by("fTurnover.typ");
        } elseif ($typ==3) {
            $this->db->select("SUM(fTurnover.incoming) as s_incoming,SUM(fTurnover.outgoing) as s_outgoing,fTurnover.projectId,pProject.name as title")
                ->from("fTurnover")
                ->join('pProject', 'pProject.id = fTurnover.projectId', 'left')
                ->where("fTurnover.orgId",$orgId)
                ->where("fTurnover.status","1")
                ->where("fTurnover.beginTS >=",$realBeginTs)
                ->where("fTurnover.beginTS <=",$realEndTs)
                ->group_by("fTurnover.projectId");
        }
        
        // print $this->db->_compile_select();
        // exit;
        $query = $this->db->get();
        if ($typ==1) {
            $name = "时间";
        } elseif ($typ==2) {
            $name = "类别";
        } elseif ($typ==3) {
            $name = "项目";
        } 
        $this->dataModel["title"]->show_name = $name;
        if ($query->num_rows() > 0)
        {
            $i=0;
            foreach ($query->result_array() as $row)
            {
                $i++;
                if ($row['title']==""){
                    $row['title'] = "未设置";
                }
                    $this_record = new Financeany_model();
                    $this_record->init_with_data($i,$row);
                    $this->record_list[] = $this_record;
            }
            return $query->num_rows();
        } else {
            return 0;
        }
    }
    public function build_inline_list_titles(){
        return array('showId','name','desc','beginTS','typ','outgoing','balance','projectId','bookkeeper','auditor');
    }
    public function build_list_titles(){
        return array('beginTS','showId','desc','typ','status','incoming','outgoing','balance','projectId','bookkeeper','auditor');
    }
    public function build_search_infos(){
        return array('beginTS1','beginTS2','showId','typ','incoming','outgoing','projectId','bookkeeper','auditor');
    }
    public function load_data_with_search($searchInfo){
        if ($searchInfo['t']=="no") {
            $this->load_data();
        } elseif ($searchInfo['t']=="quick"){
            
            $this->add_where(WHERE_TXT,'quick',str_replace(array('{ifrom}','{ito}'), array($searchInfo['ifrom'],$searchInfo['ito']), $this->quickSearchWhere));
            
            $this->load_data_with_where();
        } elseif ($searchInfo['t']=="full"){
            foreach ($searchInfo['i'] as $key => $value) {
                if ($key=="beginTS1") {
                    $this->build_where($value['e'],"beginTS",$value['v']);
                } elseif ($key=="beginTS2") {
                    $this->build_where($value['e'],"beginTS",$value['v']);
                } else {
                    $this->build_where($value['e'],$key,$value['v']);
                }
            };
            $this->load_data_with_where();
        }
    }
}
?>