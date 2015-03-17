<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/send_model.php");
class Send_list extends List_model {
    public function __construct() {
        parent::__construct('bSend');
        parent::init("Send_list","Send_model");
    }

    public function build_search_infos(){
        return array('status','beginTS','packP','sendP');
    }
    public function build_inline_list_titles(){
        return array('items','status','beginTS','packP','sendP','desc');
    }
    public function build_short_list_titles(){
        return array('crmId','status','beginTS','packP','sendP','desc');
    }
    public function build_list_titles(){
        return array('crmId','items','status','beginTS','packP','sendP','desc');
    }

    public function load_anylatics_with_begin_end($beginTs,$endTS){
        $array = array('totalGetting'=>0);
        if ($this->whereOrgId!==null && isset($this->dataModel['orgId'])){
            $where_clause['orgId'] = $this->whereOrgId;
        }
        $where_clause['beginTS'] = array('$gte'=>$beginTs,'$lte'=>$endTS);
        $this->db->where($where_clause, TRUE)->select(array("totalGetting"));
        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $array['totalGetting'] += $row['totalGetting'];
            }
        }
        return $array;
    }
}
?>
