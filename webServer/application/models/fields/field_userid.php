<?php
include_once(APPPATH."models/fields/field_int.php");

class Field_userid extends Field_int {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_string";
    }
    public function init($value){
        parent::init($value);
        if ($value<=0){
            $this->userName = '[系统]';
        } else {
            $this->CI->db->select('username,name,nickname,usenick')
                    ->from('uUser')
                    ->where('uid', $value);

            $query = $this->CI->db->get();
            if ($query->num_rows() > 0)
            {
                $result = $query->row_array(); 
                if ($result['usenick']==1){
                    $this->userName = $result['nickname'];
                } else {
                    $this->userName = $result['name'];
                } 
                if ($this->userName==''){
                    $this->userName = $result['username'];
                }
                
            } else {
                $this->userName = '[未知(id:'.$value.')]';
            }
        }
    }
    public function gen_list_html(){
        return $this->userName;
    }
    public function gen_show_html(){
        return $this->userName;
    }
    public function gen_search_element($default="="){
        $editor = "<input type=\"hidden\" id=\"searchEle_{$this->name}\" name=\"search_{$this->name}\" class=\"form-control input-sm\" value=\"=\">";
        $editor .= "=";
        return $editor;
    }
    
}
?>