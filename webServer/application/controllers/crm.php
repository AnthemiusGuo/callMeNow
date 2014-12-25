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


        $jsonData = array();

        $jsonData['goto_url'] = site_url('crm/index');

        $jsonData['newId'] = (string)$newId;
        echo $this->exportData($jsonData,$jsonRst);
    }

}