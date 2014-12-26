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

    function info($id,$sub_menu="mini_info"){
        $this->load_menus();
        $this->load_org_info();

        $this->method_name = 'index';
        $this->id = $id;
        $this->load->library('user_agent');
        $this->refer = $this->agent->referrer();

        $this->load->model('records/Crm_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $this->showDetailNeedFields = $this->dataInfo->buildDetailShowFields();


        $this->begin_sub_menu = $sub_menu;


        $this->sub_menus["contactor"] = array("name"=>"联系人");
        $this->sub_menus["contact"] = array("name"=>"联系记录");
        $this->sub_menus["orders"] = array("name"=>"订单记录");
        
        $this->load->model('lists/Contactor_list',"contactorList");

        $this->contactorList->load_data_with_data($this->dataInfo->field_list['allContactors']->value,$this->dataInfo->field_list['allContactors']->arrayModel);

        $this->load->model('lists/Contact_list',"contactList");
        $this->contactList->load_data_with_foreign_key("crmId",$id);
    
        // $this->sub_menus["relateDocument"] = array("name"=>"相关文档");
        // $this->load->model('lists/Document_list',"documentList");
        // $this->documentList->add_where(WHERE_TYPE_WHERE,"relateID",$id);
        // $this->documentList->add_where(WHERE_TYPE_WHERE,"relateTyp",2);

        // $this->documentList->load_data_with_where();

        // $this->load->model('lists/Project_list',"projectList");
        // $this->projectList->init_byCrmID($id);

        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->template->load('default_page', 'crm/info');
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
        $this->template->load('default_lightbox_new', 'crm/create');
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
        $this->template->load('default_lightbox_new', 'crm/create');
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

}