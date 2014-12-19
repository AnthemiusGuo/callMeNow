<?php

class List_model extends CI_Model {
    public $name;
    public $record_list;
    public $quickSearchWhere;
    public $orderKey = "id";

    public function __construct($tableName = '') {
        parent::__construct();
        $this->tableName = $tableName;
        $this->record_list = array();
        $this->whereData = array();
        $this->whereOrgId = 0;
        $this->quickSearchWhere = "(`name` LIKE '%{search}%')";
    }

    public function setOrgId($orgId) {
        $this->whereOrgId = $orgId;
        foreach ($this->dataModel as $key => $value) {
            $value->setOrgId($orgId);
        }
    }
    public function init($name,$dataModelName){
        $this->name = $name;
        $this->dataModelName = $dataModelName;
        $dataModel = new $dataModelName();
        $this->dataModel = $dataModel->field_list;
        
    }
    public function init_with_relate_id($relateField,$relateId){
        
    }

    public function purge_where(){
        $this->whereData = array();
    }
    public function add_where($typ,$name,$data){
        $this->whereData[$name] = array('typ'=>$typ,'data'=>$data);
    }
    public function build_where($typ,$name,$data){
        
        switch ($typ) {
            case '=':
                $this->add_where(WHERE_TYPE_WHERE,$name,$data);
                break;
            case 'like':
                $this->add_where(WHERE_TYPE_LIKE,$name,$data);
                break;
            case '>':
                $this->add_where(WHERE_TYPE_WHERE,$name." > ",$data);
                break;
            case '<':
                $this->add_where(WHERE_TYPE_WHERE,$name." < ",$data);
                break;
            case '>=':
                $this->add_where(WHERE_TYPE_WHERE,$name." >= ",$data);
                break;
            case '<=':
                $this->add_where(WHERE_TYPE_WHERE,$name." <= ",$data);
                break;
            case '!=':
                $this->add_where(WHERE_TYPE_WHERE,$name." != ",$data);
                break;
            default:
                print("-----------------<br/>");
                var_dump($typ,$name,$data);
                print("-----------------<br/>");
                # code...
                break;
        }
    }

    public function load_data_with_search($searchInfo){
        if ($searchInfo['t']=="no") {
            $this->load_data_with_where();
        } elseif ($searchInfo['t']=="quick"){
            
            $this->add_where(WHERE_TXT,'quick',str_replace('{search}', $searchInfo['i'], $this->quickSearchWhere));
            
            $this->load_data_with_where();
        } elseif ($searchInfo['t']=="full"){
            foreach ($searchInfo['i'] as $key => $value) {
                $this->build_where($value['e'],$key,$this->dataModel[$key]->gen_search_result_id($value['v']));
                
            };
            $this->load_data_with_where();
        }
    }

    public function load_data_with_where($where_array=0){
        if ($where_array==0){
            $where_array = $this->whereData;
        }
        foreach ($where_array as $key => $value) {
            if ($value['typ'] == WHERE_TYPE_WHERE){
                $this->db->where($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_IN) {
                $this->db->where_in($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TYPE_LIKE)) {
                $this->db->like($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_OR_WHERE){
                $this->db->or_where($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_OR_IN) {
                $this->db->or_where_in($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TYPE_OR_LIKE)) {
                $this->db->or_like($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TXT)) {
                $this->db->where($value['data']);
            }
        }
        if ($this->whereOrgId>0 && isset($this->dataModel['orgId'])){
            $this->db->where('orgId', $this->whereOrgId);
        }

        $this->db->select('*')
                    ->from($this->tableName)
                    ->order_by($this->orderKey,'desc');
                    
        $query = $this->db->get();
        // print $this->db->last_query();
         //exit;
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {

                if (!isset($row['id']) && isset($row['uid'])) {
                    $row['id'] = $row['uid'];
                }

                $this->record_list[$row['id']] = new $this->dataModelName();
                $this->record_list[$row['id']]->orgId = $this->whereOrgId;
                $this->record_list[$row['id']]->init_with_data($row['id'],$row);
            }
            return $query->num_rows();
        } else {
            return 0;
        }

    }

    public function get_pids_by_uid($uid){
        $pids = array();
        $this->db->select('id')
                    ->from("pPeaple")
                    ->where("uid",$uid);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $pids[] = $row['id'];
            }
        } 
        return $pids;
    }
    public function load_data(){
        $this->purge_where();
        $this->load_data_with_where();
    }

    public function load_data_with_foreign_key($keyName,$keyValue){
        $this->purge_where();
        $this->add_where(WHERE_TYPE_WHERE,$keyName,$keyValue);
        $this->load_data_with_where();
    }
}
?>