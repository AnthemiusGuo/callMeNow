<?php
include_once(APPPATH."models/record_model.php");
class Goodscate_model extends Record_model {
    public function __construct() {
        parent::__construct("gGoodsCategory");
        $this->default_is_lightbox_or_page = false;
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDeleteGoodsCate';
        $this->edit_link = 'store/editGoodsCate/';
        $this->info_link = 'store/infoGoodsCate/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['comments'] = $this->load->field('Field_text',"记事本","comments");
        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");
    }

    public function buildInfoTitle(){
        return $this->field_list['name']->gen_show_html();
    }

    public function buildChangeShowFields(){
            return array(
                    array('name'),
                    array('comments'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name'),
                    array('comments'),
                );
    }

    public function checkHasRelateData($id){
        $where_clause = array('category' => $id );
        $this->db->where($where_clause, TRUE);
        $query = $this->db->get('gGoods');
        $num = $query->num_rows();
        if ($num>0) {
            return "goods";
        }


        return "null";
    }
}
?>
