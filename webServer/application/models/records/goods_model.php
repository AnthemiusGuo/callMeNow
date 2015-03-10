<?php
include_once(APPPATH."models/record_model.php");
class Goods_model extends Record_model {
    public function __construct() {
        parent::__construct("gGoods");
        $this->default_is_lightbox_or_page = false;
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDeleteGoods';
        $this->edit_link = 'store/editGoods/';
        $this->info_link = 'store/infoGoods/';

        $this->field_list['_id'] = $this->load->field('Field_mongoid',"id","_id");
        $this->field_list['name'] = $this->load->field('Field_title',"名称","name",true);
        $this->field_list['category'] = $this->load->field('Field_related_id',"分类","category");
        $this->field_list['category']->set_relate_db('gGoodsCategory','_id','name');
        $this->field_list['category']->setEditor('store','searchGoodsCate');


        $this->field_list['orgId'] = $this->load->field('Field_mongoid',"组织","orgId");

        $this->field_list['status'] = $this->load->field('Field_enum',"状态","status",true);
        $this->field_list['status']->setEnum(array("发售中","无备货","已下架"));

        $this->field_list['colors'] = $this->load->field('Field_array_colors',"颜色/子类","colors");
        $this->field_list['price'] = $this->load->field('Field_money',"参考售价（￥/米）","price");

        $this->field_list['comments'] = $this->load->field('Field_text',"记事本","comments");

        $this->field_list['updateTS'] = $this->load->field('Field_ts',"更新时间","updateTS");

        $this->field_list['createUid'] = $this->load->field('Field_userid',"创建人","createUid");
        $this->field_list['createTS'] = $this->load->field('Field_ts',"创建时间","createTS");
        $this->field_list['lastModifyUid'] = $this->load->field('Field_userid',"最终编辑人","lastModifyUid");
        $this->field_list['lastModifyTS'] = $this->load->field('Field_ts',"最后更新","lastModifyTS");

        $this->relateTableName = array('cContactHis','cContactor');
    }

    public function gen_list_html($templates){
        $msg = $this->load->view($templates, '', true);
    }
    public function gen_editor(){

    }
    public function gen_new_contactor($name,$typ,$num){
        return array('_id'=>new MongoId(),'name'=>$name,'typ'=>$typ,'num'=>$num);
    }
    public function buildInfoTitle(){
        return $this->field_list['name']->gen_show_html().' <small> '.$this->field_list['category']->gen_show_html().' </small>';
    }

    public function buildChangeShowFields(){
            return array(
                    array('name','price'),
                    array('category','status'),
                    array('colors'),
                    array('comments'),

                );
    }

    public function buildDetailShowFields(){
        return array(
                    array('name','price'),
                    array('category','status'),
                    array('colors'),
                    array('comments'),
                );
    }

    public function checkHasRelateData($id){
        $where_clause = array('items.itemName' => $id );
        $this->db->where($where_clause, TRUE);
        $query = $this->db->get('bBook');
        $num = $query->num_rows();
        if ($num>0) {
            return "book";
        }
        $this->db->where($where_clause, TRUE);
        $query = $this->db->get('bSend');
        $num = $query->num_rows();
        if ($num>0) {
            return "send";
        }
        $where_clause= array('goodsId' => $id );
        $this->db->where($where_clause, TRUE);
        $query = $this->db->get('gInventory');
        $num = $query->num_rows();
        if ($num>0) {
            return "inventory";
        }


        return "null";
    }
}
?>
