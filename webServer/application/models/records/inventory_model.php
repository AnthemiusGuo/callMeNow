<?php
include_once(APPPATH."models/record_model.php");
class Inventory_model extends Record_model {
    public function __construct() {
        parent::__construct('gInventory');
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDelInventory';
        $this->edit_link = 'store/editInventory/';
        $this->info_link = 'store/infoInventory/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['desc'] = $this->load->field('Field_text',"备注","desc");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['goodsId'] = $this->load->field('Field_relate_goods',"货号","goodsId");
        $this->field_list['goodsId']->is_title = true;
        $this->field_list['color'] = $this->load->field('Field_string',"颜色/子型号","color");
        $this->field_list['meter'] = $this->load->field('Field_float',"米数/个数","meter",true);
        $this->field_list['meter']->tips = "如果是布料，可以每匹创建一条，也可以所有的同类创建一条";

        $this->field_list['inTS'] = $this->load->field('Field_date',"进货时间","inTS",true);

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最终编辑时间","lastModifyTS");
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function buildInfoTitle(){
        return '库存记录:'.$this->field_list['goodsId']->gen_show_html().'<small> 颜色/子型号:'.$this->field_list['color']->gen_show_html().'</small>';
    }




    public function buildChangeShowFields(){
            return array(
                    array('color','null'),
                    array('meter','inTS'),
                    array('desc'),
                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('goodsId','color'),
                    array('meter','inTS'),
                    array('desc'),
                );
    }

}
?>
