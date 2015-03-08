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
        $this->listInfo->load_data_with_fullSearch('name',$searchArray,$searchPlus);
        
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
        exit;

        $this->dataModel->delete_db($id); 
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
        $this->template->load('default_lightbox_edit', 'crm/create');
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

        //创建联系人相关记录
        $contactorData = array();
        $this->load->model('records/Contactor_model',"contactorInfo");
        $contactorData['orgId']= $this->myOrgId;
        $contactorData['crmId']= $newId;

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

        $jsonData = array();

        $jsonData['goto_url'] = site_url('crm/index');

        $jsonData['newId'] = (string)$newId;
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
        if ($this->input->post('dianhua')=="" && $this->input->post('qq')=="" && $this->input->post('weixin')=="" && $this->input->post('qitafangshi')==""){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_dianhua';
            $jsonData['err']['msg'] ='至少填写一种联系方式';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $dataInfo = $this->onCreateSubTable($modelName);
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

            $this->db->where(array('_id'=>new MongoId($dataInfo['crmId'])))->update('cCrm',$contactorData);
            //将其他记录全部设为非主要
            
            // $this->db->where(array('crmId'=>$dataInfo['crmId'],'_id'=>array('$ne'=>new MongoId($dataInfo['newId']))))->update('cContactor',array('isMain'=>0));
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

    private function onCreateSubTable($modelName){
        
        $this->load->model($modelName,"dataModel");
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
            return false;
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

        $dataInfo['newId'] = $this->dataModel->insert_db($dataInfo);
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

        $rst = $this->dataModel->init_with_id($id);
        if ($rst==false){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该记录不存在';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }
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