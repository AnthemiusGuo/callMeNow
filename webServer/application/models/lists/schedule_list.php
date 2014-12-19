<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/schedule_model.php");
class Schedule_list extends List_model {
    public function __construct() {
        parent::__construct('pSchedule');
        parent::init("Schedule_list","Schedule_model");
        
    }
    
    public function init($projectId = 0){
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Schedule_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function load_dated_data_with_uid($uid){
        $zeit = time();
        $sunrise = $zeit-($zeit+3600*8)%86400;
        
        $this->record_list = array();
        $pids = $this->get_pids_by_uid($uid);
        if (count($pids)==0){
            return;
        }
        $this->db->select('pSchedule.*')
                    ->from('pSchedulePeapleRel')
                    ->join('pSchedule', 'pSchedule.id = pSchedulePeapleRel.scheduleId', 'left')
                    ->where_in("pSchedulePeapleRel.peapleId",$pids)
                    ->where("pSchedule.endTS >=",$sunrise)
                    ->order_by('id','desc');


        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this_task = new Schedule_model;
                $this_task->orgId = $this->whereOrgId;
                $this_task->init_with_data($row['id'],$row);

                $beginTS = $row['beginTS'];
                $endTS = $row['endTS'];
                $this->record_list[date("Y-m-d",$beginTS)][$row['id']] = $this_task;
            }
        } 
    }
    public function build_search_infos(){
        return array('name','beginTS','endTS','place','userInCharge');
    }
    public function build_inline_list_titles(){
        return array('name','beginTS','endTS','isWholeDay','place','userInCharge');
    }
    public function build_short_list_titles(){
        return array('name','beginTS','endTS','place');
    }
    public function build_list_titles(){
        return array('name','beginTS','endTS','projectId','isWholeDay','place','userInCharge');
    }
}
?>