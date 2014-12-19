<?php
include_once(APPPATH."models/list_model.php");
include_once(APPPATH."models/records/directdonor_model.php");
class Directdonor_list extends List_model {
    public function __construct() {
        parent::__construct('cDirectDonor');
        parent::init("Directdonor_list","Directdonor_model");
    }

    public function load_data_with_crm_id($typ,$crm_id){
        if ($typ==1) {
            $this->db->where('beDonoredCrmId',$crm_id);
        } else {
            $this->db->where('donorCrmId',$crm_id);
        }
        $this->db->select('*')
                    ->from('cDirectDonor')
                    ->order_by('id','desc');

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $this->record_list[$row['id']] = new $this->dataModelName();
                $this->record_list[$row['id']]->orgId = $this->whereOrgId;
                $this->record_list[$row['id']]->init_with_data($row['id'],$row);
                $this->record_list[$row['id']]->set_page_typ($typ);
            }
            return $query->num_rows();
        } else {
            return 0;
        }

    }

    public function build_search_infos(){
        return array('donorCrmId','aboveDepartmentId');
    }
    public function build_list_titles(){
        return array('donorCrmId','beDonoredCrmId','donorPeriod','donorComments');
    }
}
?>