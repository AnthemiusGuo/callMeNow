<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phone extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function call($phone ="",$callId=""){
		$this->controller_name = "index";
		$this->phone = trim($phone);

		$this->load_menus();
        $this->load_org_info();

		if ($this->phone==""){
			$this->template->load('default_page', 'phone/no_phone');
			return;
		}
		//添加 title
		array_unshift($this->title,$this->phone);

		if ($callId!="" && MongoId::isValid($callId)){
			$this->load->model('lists/Contactor_list',"contactorList");
			$where_clause = array(
				'_id'=>new MongoId($callId)
				);

			$num = $this->contactorList->load_data_with_orignal_where($where_clause);
		} else {
			//搜索数据
			$regex = new MongoRegex("/{$this->phone}/iu");

			$this->load->model('lists/Contactor_list',"contactorList");
			$where_clause = array(
				'orgId'=>$this->myOrgId,
				'$or'=>array(array("dianhua"=>$regex),
						array("qq"=>$regex))
				);

			$num = $this->contactorList->load_data_with_orignal_where($where_clause);
		}

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
			foreach ($this->contactorList->record_list as $key=>$this_record){
				$this->contactorInfo = $this_record;
			}

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

		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'batchCreate';

        $this->load->model('records/Pay_model',"payModel");
        $this->payModel->setRelatedOrgId($this->myOrgId);
        $this->payModel->field_list['payTS']->default = time();

		$this->load->model('records/Contact_model',"contactModel");
        $this->contactModel->setRelatedOrgId($this->myOrgId);
        $this->contactModel->field_list['contactTS']->default = time();

		$this->load->model('records/Send_model',"sendModel");
        $this->sendModel->setRelatedOrgId($this->myOrgId);
        $this->sendModel->field_list['beginTS']->default = time();

		$this->load->model('records/Book_model',"bookModel");
        $this->bookModel->setRelatedOrgId($this->myOrgId);
        $this->bookModel->field_list['beginTS']->default = time();

		$this->load->model('records/Bookin_model',"bookinModel");
        $this->bookinModel->setRelatedOrgId($this->myOrgId);
        $this->bookinModel->field_list['beginTS']->default = time();

		// $this->load->model('records/Pay_model',"payModel");
        // $this->payModel->setRelatedOrgId($this->myOrgId);
        // $this->payModel->field_list['payTS']->default = time();


		$this->template->load('default_page', 'phone/one_contactor');
	}

	private function __no_contactor(){
		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doQuickCreateContactor';

        $this->load->model('records/Contactor_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
		$this->dataInfo->field_list['dianhua']->default = $this->phone;
		$this->dataInfo->field_list['crmId']->is_must_input = true;

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('crmId'));
        $this->modifyNeedFields = $this->dataInfo->buildQuickChangeShowFields();

        $this->editor_typ = 0;
        $this->title_create = "新建联系人";

		$this->template->load('default_page', 'phone/no_contactor');
	}

	private function __more_contactor(){
		$this->template->load('default_page', 'phone/more_contactor');
	}
}
