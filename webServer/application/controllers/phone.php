<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phone extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function call($phone =""){
		$this->controller_name = "index";
		$this->phone = trim($phone);

		$this->load_menus();
        $this->load_org_info();

		if ($phone==""){
			$this->template->load('default_page', 'phone/no_phone');
			return;
		}
		//添加 title
		array_unshift($this->title,$this->phone);

		//搜索数据
		$regex = new MongoRegex("/$info/iu");

		$this->load->model('lists/Contactor_list',"contactorList");
		$where_clause = array(
			'orgId'=>$this->myOrgId,
			'$or'=>array(array("dianhua"=>$regex),
					array("qq"=>$regex))
			);


		$num = $this->contactorList->load_data_with_orignal_where($where_clause);


        if ($num > 1)
        {
			foreach ($this->contactorList->record_list as $key => $this_record){
				//遍历数据，看看模糊搜索是否有精确匹配
				if ($this_record->field_list['dianhua']->value == $this->phone ||
				$this_record->field_list['qq']->value== $this->phone){
					//精确匹配走一个联系人的
					$this->contactorInfo = $this_record;
					$this->__one_contactor();
					return;
				}
			}
			$this->__more_contactor();
			return;
        } else if ($num==1){
			//有查到一个联系人
			$this->contactorInfo = $this->contactorList->recordList[0];
			$this->__one_contactor();
			return;
		} else {

			$this->__no_contactor();
        }
	}

	private function __one_contactor(){
		$this->load->model('records/Crm_model',"crmInfo");
		$this->crmId = $this->contactorInfo->field_list['crmId']->value;
		$this->id = $this->crmId;
        $this->crmInfo->init_with_id($this->crmId);

		$typ = $this->crmInfo->field_list['typ']->value;
		//上游或其他
        if ($typ==0 || $typ==1 || $typ==2) {
            $this->load->model('lists/Bookin_list',"bookInList");
            $this->bookInList->load_data_with_foreign_key("crmId",$this->crmId,5);
        }
        //下游或其他
        if ($typ==0 || $typ==2 || $typ==3) {
            $this->load->model('lists/Book_list',"bookList");
            $this->bookList->load_data_with_foreign_key("crmId",$this->crmId,5);
        }
        //打包发货物流
        if ($typ==4) {
            $this->load->model('lists/Send_list',"sendList");

            $where_array = array('orgId'=>$this->myOrgId,'$or'=>array(array('packP'=>$this->crmId),array('sendP'=>$this->crmId)));
            $this->sendList->load_data_with_orignal_where($where_array,5);
        } else {
            $this->load->model('lists/Send_list',"sendList");
            $this->sendList->load_data_with_foreign_key("crmId",$this->crmId,5);
        }

		$this->load->model('lists/Contact_list',"contactList");
        $this->contactList->load_data_with_foreign_key("crmId",$this->crmId,5);

        $this->load->model('lists/Contact_list',"contactList");
        $this->contactList->load_data_with_foreign_key("crmId",$this->crmId,5);

        $this->load->model('lists/Pay_list',"payList");
        $this->payList->load_data_with_foreign_key("crmId",$this->crmId,5);

		$this->template->load('default_page', 'phone/one_contactor');
	}

	private function __no_contactor(){
		$this->template->load('default_page', 'phone/no_contactor');
	}

	private function __more_contactor(){
		$this->template->load('default_page', 'phone/more_contactor');
	}
}
