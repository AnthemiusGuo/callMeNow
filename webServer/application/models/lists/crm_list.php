<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/crm_model.php");
class Crm_list extends List_model {
    public function __construct() {
        parent::__construct("cCrm");
        parent::init("Crm_list","Crm_model");
        $this->quickSearchWhere = array("name",'mainContactorName','mainContactorNum','allContactors.num','allContactors.name');
        $this->db->where($array,true);
    }
    
    public function init(){
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Crm_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function genAnylytics($typ){
        /*
        SELECT COUNT( pProjectTypRel.id ) , typId
FROM pProject
RIGHT JOIN pProjectTypRel ON pProjectTypRel.projectId = pProject.id
WHERE pProject.orgId >0
GROUP BY pProjectTypRel.typId
    */
    if ($typ==1) {
        $this->db->select("COUNT(id) as count_id,typ as typId")
                ->from("cCrm")
                ->where("orgId",$this->whereOrgId)
                ->group_by("typId");
    } elseif ($typ==2) {
        $this->db->select("COUNT(id) as count_id,province as typId")
                ->from("cCrm")
                ->where("orgId",$this->whereOrgId)
                ->group_by("typId");
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

        if ($typ==1){
            $exportData = array();
            for ($i=1;$i<=7;$i++){
                if (!isset($real_data[$i])){
                    $real_data[$i] = 0;
                }
            }

            $exportData[] = array("label"=>"资助方",
                        "data"=>($real_data[4]+$real_data[5]+$real_data[6]+$real_data[7]));
            $exportData[] = array("label"=>"被资助方",
                        "data"=>($real_data[1]+$real_data[2]+$real_data[3]));
            $exportData[] = array("label"=>"未设置",
                        "data"=>($real_data[0]));

            
            return $exportData;
        } else if ($typ==2){
            $exportData = array();
            foreach ($this->dataModel['province']->enum as $key => $value) {
                $exportData[] = array("label"=>$value,
                        "data"=>(isset($real_data[$key]))?$real_data[$key]:0);
            }
            return $exportData;

        } else if ($typ==3){
            //[ [2013, 3], [2014, 14.01], [2015, 3.14] ]
            $exportData = array();
            foreach ($real_data as $key => $value) {
                $exportData[] = array($key,$value);
            }
            return $exportData;
        }
    }
    
    
    public function build_search_infos(){
        return array('name','typ','status','province','updateTS');
    }
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('name','typ','province','status','updateTS');
    }
}
?>