<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/bookin_model.php");
class Bookin_list extends List_model {
    public function __construct() {
        parent::__construct('bBookin');
        parent::init("Bookin_list","Bookin_model");
    }

    public function genAnylytics($typ){
        $this->db->select("COUNT(id) as count_id,typId")
                ->from("dDonation")
                ->where("orgId",$this->whereOrgId)
                ->group_by("pProjectTypRel.typId");
        $real_data = array();

        if ($typ==1){
            $this->db->where_in("status",array(1,2));
        } elseif ($typ==2){
            // $this->db->where_in("status",array(3));
        } 

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $real_data[intval($row['typId'])] = intval($row['count_id']);
            }
        } 

        $exportData = array();
        foreach ($this->dataModel['typ']->enum as $key => $value) {
            $exportData[] = array("label"=>$value,
                    "data"=>(isset($real_data[$key]))?$real_data[$key]:0
                        );
        }
        return $exportData;
    }

    public function build_search_infos(){
        return array('name','status','payStatus','beginTS');
    }
    public function build_inline_list_titles(){
        return array('name','status','payStatus','totalGetting','beginTS','endTS');
    }
    public function build_short_list_titles(){
        return array('crmId','name','status','payStatus','totalGetting','beginTS','endTS');
    }
    public function build_list_titles(){
        return array('crmId','name','status','payStatus','totalGetting','beginTS','endTS');
    }
}
?>