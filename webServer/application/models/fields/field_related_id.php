<?php
include_once(APPPATH."models/fields/field_relate_simple_id.php");
class Field_related_id extends Field_relate_simple_id {
    
    public function __construct($show_name,$name,$is_must_input=false) {
        parent::__construct($show_name,$name,$is_must_input);
        $this->typ = "Field_related_id";
        $this->model_name = '';
        $this->placeholder = '请点击<+>输入';
        $this->jsonValue = array();
        $this->plusCreateData = array();
    }
    public function setPlusCreateData($data){
        $this->plusCreateData = $data;
    }
    public function setEditor($editorUrl){
        $this->editorUrl = $editorUrl;
    }
    public function gen_search_editor($default=""){
        $this->input_class = "form-control input-sm";
        if ($default!="" && $default!=null) {
            $this->jsonValue = json_decode($default,true);
            var_dump( $this->jsonValue);
            $this->showValue = $this->jsonValue[0]['name'];
            $this->value = $this->jsonValue[0]['id'];
            $this->default = $this->jsonValue[0]['id'];
            if ($this->value>0){
                $this->placeholder = '<span class="label label-primary">'.$this->showValue.'</span>';
            } else {
                $this->placeholder = '请点击<+>输入';
            }

        } 
        return $this->gen_editor(2);
    }
    public function init($value){
        parent::init($value);

        $this->jsonValue = array(array('id'=>$value,'name'=>$this->showValue));
        if ($value>0){
            $this->placeholder = '<span class="label label-primary">'.$this->showValue.'</span>';
        } else {
            $this->placeholder = '请点击<+>输入';
        }
        
    }
    public function plusCreate($input){
        $this->plusCreateData[$this->showField] = $input[$this->showField];
        if (isset($this->whereOrgId)){
            $this->plusCreateData['orgId'] = $this->whereOrgId;
        } else {
            $this->plusCreateData['orgId'] = $this->CI->orgId;
        }
        
        $this->CI->db->insert($this->tableName,$this->plusCreateData);
        return $this->CI->db->insert_id();
    }
    public function gen_value($input){
        $value = json_decode($input,true);

        if (count($value)!=1){
            return 0;
        } else {
            if ($value[0]['id']==-1){
                return $this->plusCreate($value[0]);
            } else {
                return $value[0]['id'];
            }
            
        }
    }
    public function gen_search_result_id($value){
        $value = json_decode($value,true);
        if (count($value)!=1){
            return 0;
        } else {
            if ($value[0]['id']==-1){
                return -1;
            } else {
                return $value[0]['id'];
            }
            
        }
    }
    public function gen_search_result_show($value){
        $value = json_decode($value,true);
        if (count($value)!=1){
            return '-';
        } else {
            if ($value[0]['id']==-1){
                return '-';
            } else {
                return $value[0]['name'];
            }
            
        }
    }
    // public function gen_list_html(){
    //     return $this->value;
    // }
    // public function set_relate_model($modelName,$showField,$editorUrl){
    //     $modelName = strtolower($modelName);

    //     $this->modelName = $modelName;
    //     $this->showField = $showField;
    //     $this->editorUrl = $editorUrl;

    //     $modelFile = APPPATH."models/records/{$modelName}.php";
    //     if ( ! file_exists($modelFile))
    //     {
    //         return false;
    //     }
    //     require_once($modelFile);

    //     $class = ucfirst($modelName);

    //     return $this->dataModel = new $class();
    // }

    // public function gen_show_html(){
    //     return $this->dataModel->field_list[$this->showField]->gen_show_html();
    // }
    public function gen_editor($typ=0){
        $inputName = $this->build_input_name($typ);
        $validates = $this->build_validator();

        $default = json_encode($this->jsonValue);
        if ($typ==2) {
            $btn_class = "btn-sm";
        } else {
            $btn_class = "";
        }

        return "<div class=\"holder-editor-related-id\" >
                    <input type=\"hidden\" value='{$default}' id=\"$inputName\" name=\"$inputName\"/>
                    <div id=\"holder-editor-{$inputName}\">
                        <div id=\"holder_{$inputName}\" class=\"alert alert-danger editor-related-inputed pull-left\" style=\"width:75%;\">{$this->placeholder}</div>
                        <a class=\"btn btn-default {$btn_class} pull-right\" href=\"javascript:void(0);\" onclick=\"build_relate_box('{$this->name}','single',$typ,'".site_url($this->editorUrl)."')\">
                        <span class=\"glyphicon glyphicon-search\"></span>
                        </a>
                    </div>
                </div>";
    }
}
?>