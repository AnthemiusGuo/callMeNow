<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/mail_model.php");
class Mail_list extends List_model {
    public function __construct() {
        parent::__construct('uSysMail');
        parent::init("Mail_list","Mail_model");
    }

    public function get_unread_count_with_uid($uid) {
        $this->db->select('COUNT(id) as count_id')
                    ->from('uSysMail')
                    ->where("toUid",$uid)
                    ->where("readed",0);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                return $row['count_id'];
            }
        } 
        return 0;
    }

    public function get_all_count_with_uid($uid) {
        $this->db->select('COUNT(id) as count_id')
                    ->from('uSysMail')
                    ->where("toUid",$uid);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                return $row['count_id'];
            }
        } 
        return 0;
    }

    public function load_data_with_uid($uid,$page,$per_page) {
        $this->record_list = array();
        $this->db->select('*')
                    ->from('uSysMail')
                    ->where("toUid",$uid)
                    ->order_by('sendTS','desc')
                    ->limit($per_page,$page);

        $query = $this->db->get();

        print $this->db->last_query();

        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this->record_list[$row['id']] = new $this->dataModelName();
                $this->record_list[$row['id']]->init_with_data($row['id'],$row);
            }
        } 
        if (count($this->record_list)>0){
            $this->db->where_in("id",array_keys($this->record_list))
        ->update("uSysMail",array("readed"=>1));
        }
        
    }
    public function build_list_titles(){
        //姓名,类型,省份,状态,最后更新
        return array('orgId','fromUid','toUid','sendTS');
    }
}
?>