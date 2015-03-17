<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/pay_model.php");
class Pay_list extends List_model {
    public function __construct() {
        parent::__construct('bPay');
        parent::init("Pay_list","Pay_model");
    }

    public function build_search_infos(){
        return array('money','method','status','payTS','typ');
    }

    public function build_inline_list_titles(){
    	return array('money','method','status','payTS');
    }
    public function build_list_titles(){
        return array('crmId','typ','money','method','status','payTS');
    }

    public function load_anylatics_with_begin_end($beginTs,$endTS){
        $array = array('PayIn'=>0,'PayOut'=>0);
        if ($this->whereOrgId!==null && isset($this->dataModel['orgId'])){
            $where_clause['orgId'] = $this->whereOrgId;
        }
        $where_clause['payTS'] = array('$gte'=>$beginTs,'$lte'=>$endTS);
        $this->db->where($where_clause, TRUE)->select(array("money","typ"));
        $query = $this->db->get($this->tableName);

        $num = $query->num_rows();
        if ($num > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if ($row['typ']==0){
                    $array['PayIn'] += $row['money'];
                } else {
                    $array['PayOut'] += $row['money'];
                }
            }
        }
        return $array;
    }
}
?>
