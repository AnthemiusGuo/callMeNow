<?php
include_once(APPPATH."models/fields/field_int.php");
class Field_relate_simple_id extends Field_int {
    public $where = array();
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_related_id";
        $this->tableName = '';
        $this->valueField = '';
        $this->showField = '';
        $this->whereData = array();
        $this->valueSetted = false;
        $this->showValue = ' - ';
        $this->enum = array();
        $this->needOrgId = 1;
    }


    public function init($value){
        parent::init($value);
        if ($value<=0){
            $this->showValue = ' - ';
            return;
        }
        $this->valueSetted = true;
        $this->CI->db->select("{$this->valueField},{$this->showField}")
            ->from($this->tableName)
            ->where($this->valueField, $value);
        $this->checkWhere();
        
        $query = $this->CI->db->get();
        if ($query->num_rows() > 0)
        {
            $result = $query->row_array(); 
            $this->showValue = $result[$this->showField];
        } else {
            $this->showValue = '[未知(id:'.$value.')]';
        }
    }
    public function gen_list_html(){
        return $this->showValue;
    }
    public function set_relate_db($tableName,$valueField,$showField){
        $this->tableName = $tableName;
        $this->valueField = $valueField;
        $this->showField = $showField;
    }

    public function add_where($typ,$name,$data){
        $this->whereData[$name] = array('typ'=>$typ,'data'=>$data);
    }
    public function setOrgId($orgId){
        parent::setOrgId($orgId);
        $this->whereOrgId = $orgId;
    }
    public function checkWhere(){
        $where_array = $this->whereData;
        foreach ($where_array as $key => $value) {
            if ($value['typ'] == WHERE_TYPE_WHERE){
                $this->CI->db->where($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_IN) {
                $this->CI->db->where_in($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TYPE_LIKE)) {
                $this->CI->db->like($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_OR_WHERE){
                $this->CI->db->or_where($key, $value['data']);
            } elseif ($value['typ'] == WHERE_TYPE_OR_IN) {
                $this->CI->db->or_where_in($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TYPE_OR_LIKE)) {
                $this->CI->db->or_like($key, $value['data']);
            } elseif (($value['typ'] == WHERE_TXT)) {
                $this->CI->db->where($value['data']);
            }
        }
        
        if ($this->whereOrgId>0 && $this->needOrgId==1){
            $this->CI->db->where('orgId', $this->whereOrgId);
        } elseif ($this->whereOrgId>0 && $this->needOrgId==2){
            $this->CI->db->where_in('orgId',array($this->whereOrgId,0));
        } 
    }
    private function setEnum(){
        $this->CI->db->select("{$this->valueField},{$this->showField}")
            ->from($this->tableName);
        $this->checkWhere();

        $this->CI->db->order_by('id','asc');
        $query = $this->CI->db->get();

        $this->enum[0] = ' - ';
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this->enum[$row[$this->valueField]] = $row[$this->showField];
            }
            
        } 
        
    }
    public function gen_search_element($default="="){
        return "<input type='hidden' name='searchEle_{$this->name}' id='searchEle_{$this->name}' value='='>=";
    }
    public function gen_show_html(){
        return $this->showValue;
    }
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();
        $this->setEnum();
        if ($typ==1){
            $this->default = $this->value;
        } 
        
        $editor = "<select id=\"{$inputName}\" name=\"{$inputName}\" class=\"{$this->input_class}\" value=\"{$this->default}\" $validates>";

        foreach ($this->enum as $key => $value) {
            $editor.= "<option ". (($key==$this->default)?'selected="selected"':'') ." value=\"$key\">$value</option>";
        }
        $editor .= "</select>";
        return $editor;
    }
    public function gen_search_result_show($value){
        return $this->enum[$value];
    }
    public function check_data_input($input)
    {
        if ($input==0){
            return false;
        }
        return parent::check_data_input($input);
    }
    public function build_validator(){
        if ($this->is_must_input){
            $validater .= ' notnull="notnull" ';
        }
        $validater .= Fields::build_validator();
        return $validater;
    }
}
?>