<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/title_model.php");
class Title_list extends List_model {
    public function __construct() {
        parent::__construct('pTitle');
        parent::init("Title_list","Title_model");
    }
    
    public function init(){
        
        for ($i=0;$i<35;$i++){
            $this->record_list[$i] = new Title_model();
            $this->record_list[$i]->init($i);
        }
       
    }
    public function build_search_infos(){
        return array('name','departmentId','peapleCount');
    }
    public function build_list_titles(){
        return array('name','departmentId','isAdmin','aboveTitleId','peapleCount');
    }
    public function loadDepartTitleRelate($orgId){
        $this->db->select('id,name,departmentId')
                    ->from($this->tableName)
                    ->where('orgId',$orgId);
                    
        $query = $this->db->get();
        $result = array();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                if (!isset($result[$row['departmentId']])){
                    $result[$row['departmentId']] = array();
                }
                $result[$row['departmentId']][$row['id']] = $row['name'];
            }
        }

        return $result;
    }
}
?>