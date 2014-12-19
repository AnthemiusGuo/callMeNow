<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/task_model.php");
class Task_list extends List_model {
    public function __construct() {
        parent::__construct('pTask');
        parent::init("Task_list","Task_model");
    }
    
    public function init($projectId = 0){
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Task_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function load_dated_data_with_uid($uid){
        $zeit = time();
        $week_start = mktime(0,  0,  0,  date("m"), date("d") - date("w") + 1, date("Y"));
        $week_end   = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y"));
        $sunrise = $zeit-($zeit+3600*8)%86400;
        $sunset = $sunrise+86400;

        $month_start = mktime(0,  0,  0,  date("m"), 1, date("Y"));
        $month_end = mktime(23, 59, 59, date("m"),date("t"), date("Y"));
        $this->record_list = array(array(),array(),array(),array());
        $pids = $this->get_pids_by_uid($uid);
        if (count($pids)==0){
            return;
        }
        $this->db->select('pTask.*')
                    ->from('pTaskPeapleRel')
                    ->join('pTask', 'pTask.id = pTaskPeapleRel.taskId', 'left')
                    ->where_in("pTaskPeapleRel.peapleId",$pids)
                    ->where("pTask.endTS >=",$sunrise)
                    ->order_by('id','desc');

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this_task = new Task_model;
                $this_task->orgId = $this->whereOrgId;
                $this_task->init_with_data($row['id'],$row);

                $beginTS = $row['beginTS'];
                $endTS = $row['endTS'];
                // if ($beginTS<=$sunset || $endTS<=$sunset) {
                //     $this->record_list[0][$row['id']] = $this_task;
                // } elseif ($beginTS<=$week_end || $endTS<=$week_end) {
                //     $this->record_list[1][$row['id']] = $this_task;
                // } elseif ($beginTS<=$month_end || $endTS<=$month_end) {
                //     $this->record_list[2][$row['id']] = $this_task;
                // } else {
                //     $this->record_list[3][$row['id']] = $this_task;
                // }
                if ($endTS<=$sunset) {
                    $this->record_list[0][$row['id']] = $this_task;
                } elseif ($endTS<=$week_end) {
                    $this->record_list[1][$row['id']] = $this_task;
                } elseif ($endTS<=$month_end) {
                    $this->record_list[2][$row['id']] = $this_task;
                } else {
                    $this->record_list[3][$row['id']] = $this_task;
                }

            }
        } 
    }
    public function build_search_infos(){
        return array('name','beginTS','endTS','userInCharge','status');
    }
    public function build_inline_list_titles(){
        // return array('name','preTaskId','beginTS','endTS','progress','userInCharge');
        return array('name','beginTS','endTS','status','userInCharge','userInvolved','est_money','est_time');
    }
    public function build_short_list_titles(){
        return array('name','endTS','progress','userInCharge');
    }
    public function build_list_titles(){
        // return array('name','showId','preTaskId','beginTS','endTS','progress','projectId','userInCharge');
        return array('name','showId','beginTS','endTS','status','projectId','userInCharge','userInvolved','est_money','est_time');
    }
}
?>