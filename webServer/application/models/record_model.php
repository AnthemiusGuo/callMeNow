 <?php
 class Record_model extends CI_Model {
    public $id;
    public $field_list;
    public $tableName;
    public $orgId;
    public $deleteCtrl = '';
    public $deleteMethod = '';
    public $edit_link = '';

    public function __construct($tableName='') {
        parent::__construct();
        $this->tableName = $tableName;
        $this->field_list = array();
        $this->orgId = 0;
        $this->errData = '';
    }
    public function init($id){
        $this->id = $id;
        
    }

    public function fetchArray(){
        $arrayRst = array();
        foreach ($this->field_list as $key => $value) {
            $arrayRst[$key] = $value->value;
        }
    }
    public function setRelatedOrgId($orgId){
        $this->orgId = $orgId;
        foreach ($this->field_list as $key => $value) {
            $value->setOrgId($orgId);
        }
    }
    public function gen_list_html($templates){
        
    }
    public function gen_editor(){
        
    }
    
    public function buildInfoTitle(){
        
    }

    public function check_data($data,$strict=true){
        $effect = 0;
        $this->error_field = "";
        foreach ($this->field_list as $key => $value) {
            if ($value->is_must_input){
                if (!isset($data[$key])){
                    if ($strict){
                        $this->error_field = $key;
                        return false;
                    }
                    
                }  elseif ($value->check_data_input($data[$key])==false) {
                    $this->error_field = $key;
                    return false;
                }
            }
        }
        return true;
    }

    public function get_error_field(){
        if (isset($this->error_field)){
            return $this->error_field;
        } else {
            return "";
        }
    }

    public function checkNameExist($name){
        $this->db->select('*')
                    ->from($this->tableName)
                    ->where('name', $name);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function init_with_id($id){
        $this->db->select('*')
                    ->from($this->tableName)
                    ->where('id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $this->init_with_data($result['id'],$result);
            return 1;
        }
        else
        {
            return -1;
        }
    }

    public function init_with_data($id,$data){
        $this->id = $id->{'$id'};
        foreach ($data as $key => $value) {
            if (isset($this->field_list[$key])){
                $this->field_list[$key]->init($value);
            }
        }
    }

    public function gen_op_edit(){
        return '<a class="list_op tooltips" onclick="lightbox({size:\'m\',url:\''.site_url($this->edit_link).'/'.$this->id.'\'})" title="编辑"><span class="glyphicon glyphicon-edit"></span></a>';

    }
    public function gen_op_delete(){
        return '<a class="list_op tooltips" onclick=\'reqDelete("'.$this->deleteCtrl.'","'.$this->deleteMethod.'",'.$this->id.')\' title="删除"><span class="glyphicon glyphicon-trash"></span></a>';

    }

    public function get_list_ops(){
         $CI =& get_instance();
        $allow_ops = array();
        $class_name = get_class($this);
        switch ($class_name) {
            case 'Project_model':
            case 'Task_model':
            case 'Schedule_model':
            case 'Budget_model':
            case 'Crm_model':
            case 'Donation_model':
            case 'Project_model':
            case 'Project_model':
            case 'Project_model':
                if ($CI->checkActionRule("Project","Edit")) {
                    $allow_ops[] = 'edit';
                    $allow_ops[] = 'delete';
                }
                break;
            case 'Peaple_model':
            case 'Department_model':
            case 'Title_model':
                if ($CI->checkActionRule("Hr","Edit")) {
                    $allow_ops[] = 'edit';
                    $allow_ops[] = 'delete';
                }
                break;
            case 'Comment_model':
                if ($CI->checkActionRule("Project","Edit")) {
                    $allow_ops[] = 'delete';
                }
                break;
            default:
                # code...
                $allow_ops[] = 'edit';
                $allow_ops[] = 'delete';
                break;
        }
        return $allow_ops;
    }

    public function get_info_ops(){
        return array('edit','delete');
    }

    public function gen_list_op(){
        $opList = $this->get_list_ops();
        $strs = array();
        foreach ($opList as $op) {
            $func = "gen_op_".$op;
            $strs[] = $this->$func();
        }
        return implode(" | ", $strs);
    }
    
    public function insert_db($data){
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }
    
    public function delete_db($ids){
        $effect = 0;
        $idArray = explode('-',$ids);
        foreach ($idArray as $id) {
            $this->db->where('id', $id)->delete($this->tableName);
            $effect += $this->db->affected_rows();
        }
        return $effect;
    }

    public function update_db($data,$id){
        $this->db->where('id', $id)->update($this->tableName,$data);
        // echo $this->db->last_query();
        // exit;
        return $this->db->affected_rows();
    }

    public function genShowId($orgId,$typ){
         $this->db->select('*')
                    ->from('oMaxIds')
                    ->where('orgId', $orgId);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
        }
        else
        {
            $this->db->insert('oMaxIds', array("orgId"=>$orgId));
            $result = array("orgId"=>$orgId);
        }
        //处理年份
        if (!isset($result['lastModifyTs'])){
            $result['lastModifyTs'] = 0;
        }

        $zeit  = time();
        $now_year = date('Y',$zeit);
        $last_modify_year = date('Y',$result['lastModifyTs']);

        if ($now_year > $last_modify_year) {
            $result[$typ] = 0;
        }

        if (!isset($result[$typ])){
            $update[$typ] = 1;
        } else {

            $update[$typ] = $result[$typ]+1;
        }
        $update["lastModifyTs"] = $zeit;
        $this->db->where('orgId', $orgId)->update('oMaxIds',$update);

        return $now_year . sprintf("%06d",$update[$typ]);

    }

    function checkImportDataBase($data,$cfg_field_lists){
        $errorData = array();
        foreach ($data as $key => $value) {
            # code...
            if (!isset($cfg_field_lists[$key])) {
                continue;
            }
            $rst = $this->field_list[$cfg_field_lists[$key]]->checkImportData($value);
            if ($rst<=0) {
                $errorData[$this->field_list[$cfg_field_lists[$key]]->gen_show_name()] = $value;
            }
        }
        return $errorData;
    }

    function checkIdBy($param){
        $this->db->select("id")
            ->from($this->tableName)
            ->where($param);
        // $this->checkWhere();
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            return $result["id"];
        } else {
            return -1;
        }
    }

    function checkWhere(){

    }
    
     
}
?>