<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/project_model.php");
class Project_list extends List_model {
    public function __construct() {
        parent::__construct("pProject");
        parent::init("Project_list","Project_model");
    }
    
    public function load_now_with_uid($uid){

        $pids = $this->get_pids_by_uid($uid);
        if (count($pids)==0){
            return;
        }

        $this->db->select('pProject.*')
                    ->from('pProjectPeapleRel')
                    ->join('pProject', 'pProject.id = pProjectPeapleRel.projectId', 'left')
                    ->where_in("pProjectPeapleRel.peapleId",$pids)
                    ->where_in("pProject.status ",array(0,1,2))
                    ->order_by('id','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this_task = new Project_model;
                $this_task->orgId = $row['orgId'];
                $this_task->init_with_data($row['id'],$row);
                if (!isset($this->record_list[$row['orgId']])){
                    $this->record_list[$row['orgId']] = array();
                }
                $this->record_list[$row['orgId']][$row['id']] = $this_task;
            }
            return $query->num_rows();

        } else {
            return 0;
        }
            
    }
    public function init_byCrmID($crmId){
        $this->db->select('pProject.*')
                        ->from("pProjectCrmRel")
                        ->join('pProject', 'pProject.id = pProjectCrmRel.projectId','left')
                        ->where('crmId',$crmId)
                        ->order_by('id','desc');

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this->record_list[$row['id']] = new Project_model();
                $this->record_list[$row['id']]->init_with_data($row['id'],$row);
            }
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
    if ($typ==1 || $typ==2) {
        $this->db->select("COUNT(pProjectTypRel.id) as count_id,typId")
                ->from("pProject")
                ->join('pProjectTypRel', 'pProjectTypRel.projectId = pProject.id', 'RIGHT')
                ->where("orgId",$this->whereOrgId)
                ->group_by("pProjectTypRel.typId");
    } else {
        $this->db->select("COUNT(id) as count_id,year( FROM_UNIXTIME( `beginTS` )) as typId")
                ->from("pProject")
                ->where("orgId",$this->whereOrgId)
                ->where("beginTS >",0)
                ->group_by("year( FROM_UNIXTIME( `beginTS` ))");
    }
        $real_data = array();

        if ($typ==1){
            $this->db->where_in("status",array(0,1,2));
        } elseif ($typ==2){
            // $this->db->where_in("status",array(3));
        } 

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $real_data[$row['typId']] = intval($row['count_id']);
            }
        } 

        if ($typ==1 || $typ==2){
            $exportData = array();
            foreach ($this->dataModel['typ']->enum as $key => $value) {
                $exportData[] = array("label"=>$value,
                        "data"=>(isset($real_data[$key]))?$real_data[$key]:0
                            );
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
    
    public function load_data_with_his_uid($uid){

        $pids = $this->get_pids_by_uid($uid);
        if (count($pids)==0){
            return;
        }

        $this->db->select('pProject.*')
                    ->from('pProjectPeapleRel')
                    ->join('pProject', 'pProject.id = pProjectPeapleRel.projectId', 'left')
                    ->where_in("pProjectPeapleRel.peapleId",$pids)
                    ->where_in("pProject.status ",array(3,4))
                    ->order_by('id','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this_task = new Project_model;
                $this_task->orgId = $row['orgId'];
                $this_task->init_with_data($row['id'],$row);

                $this->record_list[$row['id']] = $this_task;
            }
            return $query->num_rows();

        } else {
            return 0;
        }
            
    }

    public function build_search_infos(){
        if ($this->config->item("app_type")=="npone"){
            return array('name','beginTS','endTS','typ','status','userInCharge');
        } else {
            return array('name','beginTS','endTS','status','userInCharge');
        }
        
    }
    public function build_list_titles(){
        if ($this->config->item("app_type")=="npone"){
            return array('showId','name','typ','beginTS','endTS','status','orgRequirement','userInCharge');
        } else {
            return array('showId','name','beginTS','endTS','status','userInCharge');
        }
        
    }
}
?>