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


    function searchCrm($searchInfo=""){
        $this->title = "社会关系快捷查询";
        $this->quickSearchName = "姓名/名称";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Crm_list',"listInfo");
        $this->listInfo->setOrgId($this->orgId);
        $this->listInfo->load_data_with_search($this->searchInfo);
        $this->template->load('default_search_box', 'crm/searchPeapleBox');
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


        $typ = $this->dataInfo->getTyp();
        //上游或其他
        if ($typ==1 || $typ==3){
            $this->load->model('lists/Bookin_list',"bookInList");
            $this->bookInList->load_data_with_foreign_key("crmId",$id);
        }
        //下游或其他
        if ($typ==2 || $typ==3){
            $this->load->model('lists/Book_list',"bookList");
            $this->bookList->load_data_with_foreign_key("crmId",$id);
        }

        $this->load->model('lists/Contactor_list',"contactorList");
        $this->contactorList->load_data_with_foreign_key("crmId",$id);

        $this->load->model('lists/Contact_list',"contactList");
        $this->contactList->load_data_with_foreign_key("crmId",$id);

        $this->load->model('lists/Pay_list',"payList");
        $this->payList->load_data_with_foreign_key("crmId",$id);

        $this->load->model('lists/Send_list',"sendList");
        $this->sendList->load_data_with_foreign_key("crmId",$id);


        $this->detailShowFields = $this->dataInfo->buildDetailShowFields();

        $this->infoTitle = $this->dataInfo->buildInfoTitle();

        $this->sub_menus = array(
            "mini_info"=>array("name"=>"信息"),
            "contactors"=>array("name"=>"联系人"),
            "contacts"=>array("name"=>"通话记录"),
            "books"=>array("name"=>"订货记录"),
            "bookins"=>array("name"=>"订货记录(上游)"),
            "send"=>array("name"=>"发货记录"),
            "pays"=>array("name"=>"付款记录"),
        );
        //上游
        if ($typ==1){
            $this->sub_menus["pays"]["name"] = "付款记录";
            unset($this->sub_menus["books"]);
        }
        //下游
        else if ($typ==2){
            $this->sub_menus["pays"]["name"] = "收款记录";
            unset($this->sub_menus["bookins"]);
        }
        //其他
        else {
            $this->sub_menus["pays"]["name"] = "收付款记录";
        }


        if (isset($this->sub_menus[$sub_menu])){
            $this->now_sub_menu = $sub_menu;
        } else {
            $this->now_sub_menu = "mini_info";
        }
        
        $this->template->load('default_page', 'crm/info');
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
        $this->template->load('default_lightbox_new', 'crm/create');
    }

    function createContractor(){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateContractor';

        $this->load->model('records/Contactor_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 0;
        $this->title_create = "新建客户信息";
        $this->template->load('default_lightbox_new', 'crm/create');
    }

    function editContractor($id){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doUpdateContractor';

        $this->load->model('records/Contactor_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑客户信息";
        $this->template->load('default_lightbox_new', 'crm/create');
    }
    function createContract(){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateContract';

        $this->load->model('records/Contact_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 0;
        $this->title_create = "新建客户信息";
        $this->template->load('default_lightbox_new', 'crm/create');
    }

    function editContract($id){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doUpdateContract';

        $this->load->model('records/Contact_model',"dataInfo");
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
        $data['allContactors'] = array($this->dataInfo->gen_new_contactor($this->input->post('mainContactorName'),$this->input->post('mainContactorType'),$this->input->post('mainContactorNum')));
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


        $jsonData = array();

        $jsonData['goto_url'] = site_url('crm/index');

        $jsonData['newId'] = (string)$newId;
        exit;
        echo $this->exportData($jsonData,$jsonRst);
    }

    function createContacter($id){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateContacter';

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

    function doCreateContacter(){
        
        $modelName = 'records/Contactor_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'contactors';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }

        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }

    function createContactHis($id = ''){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateContacterHis';

        $this->load->model('records/Contact_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['contactTS']->default = time();

        $this->createSubTable($id);
    }

    function doCreateContacterHis(){
        
        $modelName = 'records/Contact_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'contacts';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }

        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }
    function createBook($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateBook';

        $this->load->model('records/Book_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['beginTS']->default = time();
        $this->title_create = "新建订货记录";

        $this->createSubTable($id);
    }

    function doCreateBook(){
        
        $modelName = 'records/Book_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'books';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }
        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }
    function createBookIn($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateBookIn';

        $this->load->model('records/Bookin_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->field_list['beginTS']->default = time();
        $this->title_create = "新建订货记录（上游）";

        $this->createSubTable($id);
    }

    function doCreateBookIn(){
        
        $modelName = 'records/Bookin_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'bookins';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }
        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }

    function createPay($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreatePay';

        $this->load->model('records/Pay_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->title_create = "新建付款记录";
        $this->dataInfo->field_list['payTS']->default = time();

        $this->title_create = "新建订货记录";

        $this->createSubTable($id);
    }

    function doCreatePay(){
        
        $modelName = 'records/Pay_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'pays';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }
        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }

    function createSend($id = ""){
        $this->setViewType(VIEW_TYPE_HTML);
        
        $this->createUrlC = 'crm';
        $this->createUrlF = 'doCreateSend';

        $this->load->model('records/Send_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->dataInfo->field_list['beginTS']->default = time();

        $this->title_create = "新建发货记录";
        $this->createSubTable($id);
    }

    function doCreateSend(){
        
        $modelName = 'records/Send_model';
        $jsonRst = 1;
        $zeit = time();
        $targetSubMenu = 'send';
        $dataInfo = $this->onCreateSubTable($modelName);
        if ($dataInfo==false){
            return;
        }
        $this->onCreateSubTableReturn($dataInfo,$targetSubMenu);
    }

    private function updateCrmUpdateTS($crmId){
        $zeit = time();
        $this->db->where(array('id'=>$crmId))->update('cCrm',array('updateTS'=>$zeit));
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

    private function onCreateSubTable($modelName){
        
        $this->load->model($modelName,"dataInfo");
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('crmId'));

        $dataInfo = array();
        foreach ($this->createPostFields as $value) {
            if (isset($this->dataInfo->field_list[$value])){
                $dataInfo[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
            }
            
        }
        $checkRst = $this->dataInfo->check_data($dataInfo);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataInfo->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
        $dataInfo['orgId'] = $this->myOrgId;

        $dataInfo['newId'] = $this->dataInfo->insert_db($dataInfo);
        $this->updateCrmUpdateTS($dataInfo['crmId']);

        return $dataInfo;
    }

    private function onCreateSubTableReturn($dataInfo,$targetSubMenu=""){
        $jsonRst = 1;
        $jsonData = array();
        $jsonData['goto_url'] = site_url('crm/info/'.$dataInfo['crmId'].'/'.$targetSubMenu);
        $jsonData['newId'] = $dataInfo['newId'];
        echo $this->exportData($jsonData,$jsonRst);
    }
    

}