<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crm extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function index($searchInfo="") {
        $this->load_menus();
        $this->load_org_info();
        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/Crm_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);



        $this->info_link = $this->controller_name . "/info/";
        $this->create_link =  $this->controller_name . "/create/";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteCrm';

        $this->need_plus = 'crm/index_plus';
		$this->template->load('default_page', 'common/list_view');
	}


    function searchCrm($typ=""){
        $searchInfo = $this->input->post('data');
        if ($searchInfo===false){
            $searchArray = array();
        } else {
            $this->load->library("utility");
            $searchArray = $this->utility->mbstring_2_array($searchInfo);
        }
        $searchPlus = array();
        //0"其他",1"上游厂商",2"同行",3"下游批发零售商",4"打包发货物流"
        if ($typ=="noSend") {
            $searchPlus['typ'] = array('$ne'=>4);
        } else if ($typ=="send") {
            $searchPlus['typ'] = 4;
        } else if ($typ=="book") {
            $searchPlus['typ'] = array('$in'=>array(0,2,3));
        } else if ($typ=="bookin") {
            $searchPlus['typ'] = array('$in'=>array(0,1,2));
        } else {

        }

        $this->load->model('lists/Crm_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
		$limit = 5;
        $this->listInfo->load_data_with_fullSearch('name',$searchArray,$searchPlus,$limit);

        $jsonRst = 1;
        $jsonData = array();
        foreach ($this->listInfo->record_list as  $this_record){
            $jsonData[] = array('id'=>$this_record->id,'name'=>$this_record->field_list['name']->value);
        }
        echo $this->exportData($jsonData,$jsonRst);
    }

    function info($id,$sub_menu="mini_info"){
        if ($id==0){
            return;
        }
        $this->load_menus();
        $this->load_org_info();


        $this->id = $id;
        $this->load->library('user_agent');
        $this->refer = $this->agent->referrer();

        $this->load->model('records/Crm_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $typ = $this->dataInfo->field_list['typ']->value;
        //(0"其他",1"上游厂商",2"同行",3"下游批发零售商",4"打包发货物流")

        //上游或其他
        if ($typ==0 || $typ==1 || $typ==2) {
            $this->load->model('lists/Bookin_list',"bookInList");
            $this->bookInList->load_data_with_foreign_key("crmId",$id);
        }
        //下游或其他
        if ($typ==0 || $typ==2 || $typ==3) {
            $this->load->model('lists/Book_list',"bookList");
            $this->bookList->load_data_with_foreign_key("crmId",$id);
        }
        //打包发货物流
        if ($typ==4) {
            $this->load->model('lists/Send_list',"sendList");

            $where_array = array('orgId'=>$this->myOrgId,'$or'=>array(array('packP'=>$id),array('sendP'=>$id)));
            $this->sendList->load_data_with_orignal_where($where_array);
        } else {
            $this->load->model('lists/Send_list',"sendList");
            $this->sendList->load_data_with_foreign_key("crmId",$id);
        }

        $this->load->model('lists/Contactor_list',"contactorList");
        $this->contactorList->load_data_with_foreign_key("crmId",$id);

        $this->load->model('lists/Contact_list',"contactList");
        $this->contactList->load_data_with_foreign_key("crmId",$id);

        $this->load->model('lists/Pay_list',"payList");
        $this->payList->load_data_with_foreign_key("crmId",$id);




        $this->detailShowFields = $this->dataInfo->buildDetailShowFields();

        $this->infoTitle = $this->dataInfo->buildInfoTitle();

        $this->sub_menus = array(
            "mini_info"=>array("name"=>"信息"),
            "contactors"=>array("name"=>"联系人"),
            "contacts"=>array("name"=>"通话记录"),
            "books"=>array("name"=>"订货记录"),
            "bookins"=>array("name"=>"订货记录(上游)"),
            "send"=>array("name"=>"发货记录"),
            "pays"=>array("name"=>"收付款记录"),
        );

        //上游
        if ($typ==1){
            unset($this->sub_menus["books"]);
            unset($this->sub_menus["send"]);
        }
        //下游
        else if ($typ==3){
            unset($this->sub_menus["bookins"]);
        }
        //其他
        elseif ($typ==4) {
            unset($this->sub_menus["bookins"]);
            unset($this->sub_menus["books"]);
        }


        if (isset($this->sub_menus[$sub_menu])){
            $this->now_sub_menu = $sub_menu;
        } else {
            $this->now_sub_menu = "mini_info";
        }
		array_unshift($this->title,$this->dataInfo->field_list['name']->gen_show_value());
        $this->template->load('default_page', 'crm/info');
    }

    function subinfo($typ,$id){

        switch ($typ){
            case "contactor":
                $this->load->model('records/Contactor_model',"dataInfo");
                break;
            case "contact":
                $this->load->model('records/Contact_model',"dataInfo");
                break;
            case "pay":
                $this->load->model('records/Pay_model',"dataInfo");
                break;
            case "book":
                $this->load->model('records/Book_model',"dataInfo");
                break;
            case "bookin":
                $this->load->model('records/Bookin_model',"dataInfo");
                break;
            case "send":
                $this->load->model('records/Send_model',"dataInfo");
                break;
            default:
                return;
                break;
        }

        $this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;
        $this->edit_link = $this->dataInfo->edit_link;

        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->showNeedFields = $this->dataInfo->buildDetailShowFields();

        $this->template->load('default_lightbox_info', 'common/info');
    }

    function subEdit($typ,$id){

        switch ($typ){
          	case "contactor":
                $this->load->model('records/Contactor_model',"dataInfo");
                break;
            case "contact":
                $this->load->model('records/Contact_model',"dataInfo");
                break;
            case "pay":
                $this->load->model('records/Pay_model',"dataInfo");
                break;
            case "book":
                $this->load->model('records/Book_model',"dataInfo");
                break;
            case "bookin":
                $this->load->model('records/Bookin_model',"dataInfo");
                break;
            case "send":
                $this->load->model('records/Send_model',"dataInfo");
                break;
            default:
                return;
                break;
        }

        $this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doUpdateSub/'.$typ;

        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑 - ".$this->dataInfo->buildInfoTitle();
        $this->template->load('default_lightbox_edit', 'common/create_related');
    }

	function doQuickCreateContactor(){
		$this->load->model('records/Contactor_model',"dataModel");
		if ($this->input->post('dianhua')=="" && $this->input->post('qq')=="" && $this->input->post('weixin')=="" && $this->input->post('qitafangshi')==""){
			$jsonRst = -1;
			$jsonData = array();
			$jsonData['err']['id'] = 'creator_dianhua';
			$jsonData['err']['msg'] ='至少填写一种联系方式';
			echo $this->exportData($jsonData,$jsonRst);
			return;
		}
		//逻辑区别，这里也要 crmId，但要在 gen_value之后


		//处理数据
		$this->createPostFields = $this->dataModel->buildChangeNeedFields(array('crmId'));

        $dataInfo = array();
        foreach ($this->createPostFields as $value) {
            if (isset($this->dataModel->field_list[$value])){
                $dataInfo[$value] = $this->dataModel->field_list[$value]->gen_value($this->input->post($value));
            }

        }
		$crmId = $dataInfo['crmId'];
		$this->load->model('records/Crm_model',"crmModel");
		$this->crmModel->init_with_id($crmId);
		if ($this->crmModel->field_list['mainContactorId']->value===0){
			//尚未创建mainContactorId
			$this->contactor_hack = true;
		}

        $checkRst = $this->dataModel->check_data($dataInfo);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataModel->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $dataInfo['orgId'] = $this->myOrgId;

        $zeit = time();
        if (isset($this->dataModel->field_list['createUid'])){
            $dataInfo['createUid'] = $this->userInfo->uid;
            $dataInfo['createTS'] = $zeit;
        }
        if (isset($this->dataModel->field_list['lastModifyUid'])){
            $dataInfo['lastModifyUid'] = $this->userInfo->uid;
            $dataInfo['lastModifyTS'] = $zeit;
        }
		if ($this->contactor_hack){
			$dataInfo['isMain'] = 1;
		}
		$dataInfo['newId'] = $this->dataModel->insert_db($dataInfo);



        $this->updateCrmUpdateTS($dataInfo['crmId']);

        if ($dataInfo==false){
            return;
        }

		if ($dataInfo['isMain']==1){
			//是主要联系人，更新主要联系人段落
			$contactorData = array();
			$contactorData['mainContactorName']= $dataInfo['name'];
			if ($dataInfo['dianhua']!=''){
				$contactorData['mainContactorType'] = 0;
				$contactorData['mainContactorNum'] = $dataInfo['dianhua'];
			} else if ($dataInfo['qq']!=''){
				$contactorData['mainContactorType'] = 1;
				$contactorData['mainContactorNum'] = $dataInfo['qq'];
			} else if ($dataInfo['weixin']!=''){
				$contactorData['mainContactorType'] = 2;
				$contactorData['mainContactorNum'] = $dataInfo['weixin'];
			} else {
				$contactorData['mainContactorType'] = 3;
				$contactorData['mainContactorNum'] = $dataInfo['qitafangshi'];
			}
			$contactorData['mainContactorId'] = $dataInfo['newId'];

			$this->db->where(array('_id'=>new MongoId($dataInfo['crmId'])))->update('cCrm',$contactorData);
		}
		$jsonRst = 1;
        $jsonData = array();
        $jsonData['goto_url'] = 'refer';
        $jsonData['newId'] = $dataInfo['newId'];
        echo $this->exportData($jsonData,$jsonRst);

	}

    function doCreateSub($typ){
        $zeit = time();
        $targetSubMenu = $typ;
        // "mini_info"=>array("name"=>"信息"),
        //     "contactors"=>array("name"=>"联系人"),
        //     "contacts"=>array("name"=>"通话记录"),
        //     "books"=>array("name"=>"订货记录"),
        //     "bookins"=>array("name"=>"订货记录(上游)"),
        //     "send"=>array("name"=>"发货记录"),
        //     "pays"=>array("name"=>"收付款记录"),
		$this->contactor_hack = false;
        switch ($typ){
            case "contactor":
                $this->load->model('records/Contactor_model',"dataModel");
                $targetSubMenu = "contactors";
                if ($this->input->post('dianhua')=="" && $this->input->post('qq')=="" && $this->input->post('weixin')=="" && $this->input->post('qitafangshi')==""){
                    $jsonRst = -1;
                    $jsonData = array();
                    $jsonData['err']['id'] = 'creator_dianhua';
                    $jsonData['err']['msg'] ='至少填写一种联系方式';
                    echo $this->exportData($jsonData,$jsonRst);
                    return;
                }
				//如果 crm 主表尚未创建过主联系人，这一条作为主联系人
				//联系人是无法直接创建的，必须通过 crm 列表，所以必须有 crmId
				$crmId = $this->input->post('crmId');
				if ($crmId===false || $crmId=="" || !MongoId::isValid($crmId)){
					$jsonRst = -2;
                    $jsonData = array();
                    $jsonData['err']['msg'] ='系统故障，请联系客服';
                    echo $this->exportData($jsonData,$jsonRst);
                    return;
				}
				$this->load->model('records/Crm_model',"crmModel");
				$this->crmModel->init_with_id($crmId);
				if ($this->crmModel->field_list['mainContactorId']->value===0){
					//尚未创建mainContactorId
					$this->contactor_hack = true;
				}
                break;
            case "contact":
                $this->load->model('records/Contact_model',"dataModel");
                $targetSubMenu = "contacts";

                break;
            case "pay":
                $this->load->model('records/Pay_model',"dataModel");
                $targetSubMenu = "pays";
                break;
            case "book":
                $this->load->model('records/Book_model',"dataModel");
                $targetSubMenu = "books";

                break;
            case "bookin":
                $this->load->model('records/Bookin_model',"dataModel");
                $targetSubMenu = "bookins";

                break;
            case "send":
                $this->load->model('records/Send_model',"dataModel");
                $targetSubMenu = "send";

                break;
            default:
                return;
                break;
        }

		//处理数据
		$this->createPostFields = $this->dataModel->buildChangeNeedFields(array('crmId'));

        $dataInfo = array();
        foreach ($this->createPostFields as $value) {
            if (isset($this->dataModel->field_list[$value])){
                $dataInfo[$value] = $this->dataModel->field_list[$value]->gen_value($this->input->post($value));
            }

        }

        $checkRst = $this->dataModel->check_data($dataInfo);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataModel->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $dataInfo['orgId'] = $this->myOrgId;

        $zeit = time();
        if (isset($this->dataModel->field_list['createUid'])){
            $dataInfo['createUid'] = $this->userInfo->uid;
            $dataInfo['createTS'] = $zeit;
        }
        if (isset($this->dataModel->field_list['lastModifyUid'])){
            $dataInfo['lastModifyUid'] = $this->userInfo->uid;
            $dataInfo['lastModifyTS'] = $zeit;
        }


		switch ($typ){
            case "contactor":
				if ($this->contactor_hack){
					$dataInfo['isMain'] = 1;
				}
				break;
			default:

				break;
		}

        $dataInfo['newId'] = $this->dataModel->insert_db($dataInfo);
        $this->updateCrmUpdateTS($dataInfo['crmId']);

        if ($dataInfo==false){
            return;
        }

        switch ($typ){
            case "contactor":

                if ($dataInfo['isMain']==1){
                    //是主要联系人，更新主要联系人段落
                    $contactorData = array();
                    $contactorData['mainContactorName']= $dataInfo['name'];
                    if ($dataInfo['dianhua']!=''){
                        $contactorData['mainContactorType'] = 0;
                        $contactorData['mainContactorNum'] = $dataInfo['dianhua'];
                    } else if ($dataInfo['qq']!=''){
                        $contactorData['mainContactorType'] = 1;
                        $contactorData['mainContactorNum'] = $dataInfo['qq'];
                    } else if ($dataInfo['weixin']!=''){
                        $contactorData['mainContactorType'] = 2;
                        $contactorData['mainContactorNum'] = $dataInfo['weixin'];
                    } else {
                        $contactorData['mainContactorType'] = 3;
                        $contactorData['mainContactorNum'] = $dataInfo['qitafangshi'];
                    }
					$contactorData['mainContactorId'] = $dataInfo['newId'];

                    $this->db->where(array('_id'=>new MongoId($dataInfo['crmId'])))->update('cCrm',$contactorData);
                }

                break;
            case "contact":

                break;
            case "pay":

                break;
            case "book":

                break;
            case "bookin":

                break;
            case "send":

                break;
            default:
                return;
                break;
        }
        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);

    }

    function doUpdateSub($typ,$id=""){
        $targetSubMenu = $typ;
        // "mini_info"=>array("name"=>"信息"),
        //     "contactors"=>array("name"=>"联系人"),
        //     "contacts"=>array("name"=>"通话记录"),
        //     "books"=>array("name"=>"订货记录"),
        //     "bookins"=>array("name"=>"订货记录(上游)"),
        //     "send"=>array("name"=>"发货记录"),
        //     "pays"=>array("name"=>"收付款记录"),
        switch ($typ){
            case "contactor":
                $this->load->model('records/Contactor_model',"dataModel");
                $targetSubMenu = "contactors";
                break;
            case "contact":
                $this->load->model('records/Contact_model',"dataModel");
                $targetSubMenu = "contacts";

                break;
            case "pay":
                $this->load->model('records/Pay_model',"dataModel");
                $targetSubMenu = "pays";

                break;
            case "book":
                $this->load->model('records/Book_model',"dataModel");
                $targetSubMenu = "books";

                break;
            case "bookin":
                $this->load->model('records/Bookin_model',"dataModel");
                $targetSubMenu = "bookins";

                break;
            case "send":
                $this->load->model('records/Send_model',"dataModel");
                $targetSubMenu = "send";

                break;
            default:
                return;
                break;
        }
        if ($id=="" || !MongoId::isValid($id)){
            return;
        }

        $dataInfo = $this->onEditSubTable($id);
        if ($dataInfo==false){
            return;
        }
		if ($typ=="contactor" && $this->dataModel->field_list['isMain']->value==1){
			//更新主联系人

			$contactorData = array();
			if (isset($dataInfo['name'])){
				$contactorData['mainContactorName']= $dataInfo['name'];
			}

			if (isset($dataInfo['dianhua']) && $dataInfo['dianhua']!=''){
				$contactorData['mainContactorType'] = 0;
				$contactorData['mainContactorNum'] = $dataInfo['dianhua'];
			} else if (isset($dataInfo['qq']) && $dataInfo['qq']!=''){
				$contactorData['mainContactorType'] = 1;
				$contactorData['mainContactorNum'] = $dataInfo['qq'];
			} else if (isset($dataInfo['weixin']) && $dataInfo['weixin']!=''){
				$contactorData['mainContactorType'] = 2;
				$contactorData['mainContactorNum'] = $dataInfo['weixin'];
			} else if (isset($dataInfo['qitafangshi']) && $dataInfo['qitafangshi']!=''){
				$contactorData['mainContactorType'] = 3;
				$contactorData['mainContactorNum'] = $dataInfo['qitafangshi'];
			}
			if (count($contactorData)>0){
				$this->db->where(array('_id'=>new MongoId($this->crmId)))->update('cCrm',$contactorData);
			}
		}

        $this->onEditSubTableReturn($dataInfo,$targetSubMenu);

    }

    function doSubDel($typ,$id=""){
        $targetSubMenu = $typ;
        // "mini_info"=>array("name"=>"信息"),
        //     "contactors"=>array("name"=>"联系人"),
        //     "contacts"=>array("name"=>"通话记录"),
        //     "books"=>array("name"=>"订货记录"),
        //     "bookins"=>array("name"=>"订货记录(上游)"),
        //     "send"=>array("name"=>"发货记录"),
        //     "pays"=>array("name"=>"收付款记录"),
        switch ($typ){
            case "contactor":
                $this->load->model('records/Contactor_model',"dataModel");
                $targetSubMenu = "contactors";

                break;
            case "contact":
                $this->load->model('records/Contact_model',"dataModel");
                $targetSubMenu = "contacts";

                break;
            case "pay":
                $this->load->model('records/Pay_model',"dataModel");
                $targetSubMenu = "pays";

                break;
            case "book":
                $this->load->model('records/Book_model',"dataModel");
                $targetSubMenu = "books";

                break;
            case "bookin":
                $this->load->model('records/Bookin_model',"dataModel");
                $targetSubMenu = "bookins";

                break;
            case "send":
                $this->load->model('records/Send_model',"dataModel");
                $targetSubMenu = "send";

                break;
            default:
                return;
                break;
        }
        if ($id=="" || !MongoId::isValid($id)){
            return;
        }
		$rst = $this->dataModel->init_with_id($id);
        if ($rst==false){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该记录不存在';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
		if ($typ=="contactor" && $this->dataModel->field_list['isMain']->value==1){
			//主要联系人
			$jsonRst = -10;
            $jsonData = array();
            $jsonData['err']['msg'] ='主要联系人不可删除';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
		}

        $dataInfo = $this->onDelSubTable($id);
        if ($dataInfo==false){
            return;
        }

        $this->onDelSubTableReturn($dataInfo,$targetSubMenu);

    }

    function doDeleteCrm($id){
        $this->load->model('records/Crm_model',"dataModel");
        $rst = $this->dataModel->init_with_id($id);
        if ($rst==false){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该记录不存在';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $rst = $this->dataModel->checkHasRelateData($id);
        if ($rst!="null"){
            $cfgRstString = array(
                                  "book"=>"该客户有订货记录，请先确认删除订货记录",
                                  "bookin"=>"该客户有订货记录（上游），请先确认删除订货记录",
                                  "send"=>"该客户有发货记录，请先确认删除发货记录",
                                  "pay"=>"该客户有付款记录，请先确认删除付款记录");


            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] = $cfgRstString[$rst];
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

		if ($this->dataModel->field_list['typ']->value==4){
			//打包物流商
			$this->load->model('lists/Send_list',"sendList");

            $where_array = array('orgId'=>$this->myOrgId,'$or'=>array(array('packP'=>$id),array('sendP'=>$id)));
            $this->sendList->load_data_with_orignal_where($where_array);
			if (count($this->sendList->record_list)>0){
				$jsonRst = -3;
	            $jsonData = array();
	            $jsonData['err']['msg'] = "该客户有发货的打包或者发货记录，请先确认删除发货记录";
	            echo $this->exportData($jsonData,$jsonRst);
	            return false;
			}
		}

        $this->dataModel->delete_db($id);

		//剩下的联系人和联系记录数据不检查删除时候是否有记录，直接一起删除掉
		$this->dataModel->delete_related($id);

        $jsonRst = 1;
        $jsonData['goto_url'] = site_url('crm/index');

        echo $this->exportData($jsonData,$jsonRst);
    }

    function contactList($searchInfo = ""){

        $this->load_menus();
        $this->load_org_info();

        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/Contact_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/subinfo/contact";
        $this->create_link =  $this->controller_name . "/createContactHis";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteContactHis';

        $this->template->load('default_page', 'common/list_view');
    }

    function order($searchInfo = ""){

        $this->load_menus();
        $this->load_org_info();

        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Book_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/subinfo/book";
        $this->create_link =  $this->controller_name . "/createBook";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteBook';

        $this->template->load('default_page', 'common/list_view');
    }

    function send($searchInfo = ""){

        $this->load_menus();
        $this->load_org_info();

        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Send_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/subinfo/send";
        $this->create_link =  $this->controller_name . "/createSend";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteSend';

        $this->template->load('default_page', 'common/list_view');
    }

    function pay($searchInfo = ""){

        $this->load_menus();
        $this->load_org_info();

        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Pay_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/subinfo/pay";
        $this->create_link =  $this->controller_name . "/createPay";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeletePay';

        $this->template->load('default_page', 'common/list_view');
    }

    function create(){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateCrm';

        $this->load->model('records/Crm_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->dataInfo->field_list['typ']->setDefault(3);
		$this->dataInfo->field_list['status']->setDefault(1);

        $this->editor_typ = 0;
        $this->title_create = "新建客户信息";
        $this->template->load('default_lightbox_new', 'common/create');
    }

    function editCrm($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doUpdateCrm';

        $this->load->model('records/Crm_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑客户信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

    function doCreateCrm(){

        $modelName = 'records/Crm_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataInfo");
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $data = array();
        foreach ($this->createPostFields as $value) {
            $data[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
        }

        $data['orgId'] = $this->myOrgId;

        $data['updateTS'] = $zeit;
        $data['createUid'] = $this->userInfo->uid;
        $data['createTS'] = $zeit;
        $data['lastModifyUid'] = $this->userInfo->uid;
        $data['lastModifyTS'] = $zeit;
        $checkRst = $this->dataInfo->check_data($data);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataInfo->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $newId = $this->dataInfo->insert_db($data);

        //创建联系人相关记录
        $contactorData = array();
        $this->load->model('records/Contactor_model',"contactorInfo");
        $contactorData['orgId']= $this->myOrgId;
        $contactorData['crmId']= $newId->{'$id'};

        $contactorData['name']= $data['mainContactorName'];
// array("电话","qq","微信","其他")
        switch ($data['mainContactorType']){
            case 0:
                $contactorData['dianhua'] = $data['mainContactorNum'];
                break;
            case 1:
                $contactorData['qq'] = $data['mainContactorNum'];
                break;
            case 2:
                $contactorData['weixin'] = $data['mainContactorNum'];
                break;
            case 3:
                $contactorData['qitafangshi'] = $data['mainContactorNum'];
                break;
            default:
                $contactorData['qitafangshi'] = $data['mainContactorNum'];
                break;
        }
        $contactorData['isMain'] = 1;

        $contactorData['newId'] = $this->contactorInfo->insert_db($contactorData);

		$updateData = array('mainContactorId'=> $contactorData['newId']);
		$this->dataInfo->update_db($updateData,$newId);

        $jsonData = array();



        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('crm/info/'.(string)$newId);
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateCrm($id){
        $modelName = 'records/Crm_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($id);
		$this->createPostFields = $this->dataModel->buildChangeNeedFields();

        $data = array();
        foreach ($this->createPostFields as $value) {
            $newValue = $this->dataModel->field_list[$value]->gen_value($this->input->post($value));
            if ($newValue!="".$this->dataModel->field_list[$value]->value){
                $data[$value] = $newValue;
            }
        }

        if (empty($data)){
            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] ='无变化';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $checkRst = $this->dataModel->check_data($data,false);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
        $zeit = time();

		$data['updateTS'] = $zeit;
        if (isset($this->dataModel->field_list['lastModifyUid'])){
            $data['lastModifyUid'] = $this->userInfo->uid;
            $data['lastModifyTS'] = $zeit;
        }

		//检查联系人数据是否更新过
		if (isset($data['mainContactorType']) ||
			isset($data['mainContactorNum']) ||
			isset($data['mainContactorName'])){
			//更新联系人相关记录
			$contactorId = $this->dataModel->field_list['mainContactorId']->value;
			$contactorData = array();
			$this->load->model('records/Contactor_model',"contactorInfo");
			$contactorData['name']= $data['mainContactorName'];
		// array("电话","qq","微信","其他")
			switch ($data['mainContactorType']){
				case 0:
					$contactorData['dianhua'] = $data['mainContactorNum'];
					break;
				case 1:
					$contactorData['qq'] = $data['mainContactorNum'];
					break;
				case 2:
					$contactorData['weixin'] = $data['mainContactorNum'];
					break;
				case 3:
					$contactorData['qitafangshi'] = $data['mainContactorNum'];
					break;
				default:
					$contactorData['qitafangshi'] = $data['mainContactorNum'];
					break;
			}

			if ($contactorId!==0){

				$this->contactorInfo->update_db($contactorData,$contactorId);
			} else {
				//自动创建的那种数据，需要创建主要联系人
				$this->load->model('records/Contactor_model',"contactorInfo");
				$contactorData['orgId']= $this->myOrgId;
				$contactorData['crmId']= $id;
				$contactorData['isMain'] = 1;
				$contactorData['newId'] = $this->contactorInfo->insert_db($contactorData);

				$data['mainContactorId'] = $contactorData['newId'];
			}
		}

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('crm/info/'.$id);
        echo $this->exportData($jsonData,$jsonRst);
    }

    function createContacter($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/contactor';

        $this->load->model('records/Contactor_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['crmId']->init($id);

        $this->related_field = 'crmId';
        $this->related_id = $id;

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('crmId'));
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 0;
        $this->title_create = "新建联系人";
        $this->template->load('default_lightbox_new', 'common/create_related');
    }

    function createContactHis($id = ''){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/contact';

        $this->load->model('records/Contact_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['contactTS']->default = time();

        $this->createSubTable($id);
    }

    function createBook($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/book';

        $this->load->model('records/Book_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['beginTS']->default = time();
        $this->title_create = "新建订货记录";

        $this->createSubTable($id);
    }

    function createBookIn($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/bookin';

        $this->load->model('records/Bookin_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['beginTS']->default = time();
        $this->title_create = "新建订货记录（上游）";

        $this->createSubTable($id);
    }

    function createPay($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/pay';

        $this->load->model('records/Pay_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->title_create = "新建付款记录";
        $this->dataInfo->field_list['payTS']->default = time();


        $this->createSubTable($id);
    }


    function createSend($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSub/send';

        $this->load->model('records/Send_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->dataInfo->field_list['beginTS']->default = time();

        $this->title_create = "新建发货记录";
        $this->createSubTable($id);
    }


    private function updateCrmUpdateTS($crmId){
        $zeit = time();
        $this->db->where(array('_id'=>new MongoId($crmId)))->update('cCrm',array('updateTS'=>$zeit));
    }

    private function createSubTable($id){
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('crmId'));
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        if ($id==''){
            array_unshift($this->modifyNeedFields,array('crmId'));
        } else {
            $this->dataInfo->field_list['crmId']->init($id);
            $this->related_field = 'crmId';
            $this->related_id = $id;
        }

        $this->editor_typ = 0;

        $this->template->load('default_lightbox_new', 'common/create_related');
    }

    private function onCreateSubTableReturn($dataInfo,$targetSubMenu=""){
        $jsonRst = 1;
        $jsonData = array();
        $jsonData['goto_url'] = site_url('crm/info/'.$dataInfo['crmId'].'/'.$targetSubMenu);
        $jsonData['newId'] = $dataInfo['newId'];
        echo $this->exportData($jsonData,$jsonRst);
    }

    private function onEditSubTable($id){

        $this->createPostFields = $this->dataModel->buildChangeNeedFields();
        $this->dataModel->init_with_id($id);

        $this->crmId = $this->dataModel->field_list['crmId']->value;

        $data = array();
        foreach ($this->createPostFields as $value) {
            $newValue = $this->dataModel->field_list[$value]->gen_value($this->input->post($value));
            if ($newValue!="".$this->dataModel->field_list[$value]->value){
                $data[$value] = $newValue;
            }
        }

        if (empty($data)){
            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] ='无变化';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $checkRst = $this->dataModel->check_data($data,false);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
        $zeit = time();

        if (isset($this->dataModel->field_list['lastModifyUid'])){
            $data['lastModifyUid'] = $this->userInfo->uid;
            $data['lastModifyTS'] = $zeit;
        }

        $this->dataModel->update_db($data,$id);
        $this->updateCrmUpdateTS($this->crmId );

        return $data;
    }

    private function onEditSubTableReturn($dataInfo,$targetSubMenu=""){
        $jsonRst = 1;
        $jsonData = array();
        $jsonData['goto_url'] = site_url('crm/info/'.$this->crmId.'/'.$targetSubMenu);

        echo $this->exportData($jsonData,$jsonRst);

    }

    private function onDelSubTable($id){
        $this->crmId = $this->dataModel->field_list['crmId']->value;

        $this->dataModel->delete_db($id);

        $this->updateCrmUpdateTS($this->crmId);
        return true;
    }

    private function onDelSubTableReturn($dataInfo,$targetSubMenu=""){
        $jsonRst = 1;
        $jsonData = array();
        $jsonData['goto_url'] = site_url('crm/info/'.$this->crmId.'/'.$targetSubMenu);

        echo $this->exportData($jsonData,$jsonRst);

    }


}
