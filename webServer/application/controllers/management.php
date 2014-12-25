<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Management extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function index() {
        $this->load_menus();
		$this->load_org_info();

		$this->template->load('default_page', 'management/index');
	}

    function hr(){
        $this->load_menus();
        $this->load_org_info();

        $this->template->load('default_page', 'management/hr');
    }

	function createOrg(){
        if ($typ=='null') {
            return;
        }

        $this->setViewType(VIEW_TYPE_HTML);
        $modelName = 'records/Org_model';

        $this->load->model($modelName,"dataInfo");
        $this->title_create = $this->dataInfo->title_create;

        $this->createUrlC = 'org';
        $this->createUrlF = 'doCreateOrg';

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 0;
        $this->template->load('default_lightbox_new', 'common/new_common');
	}

	
	function info($id) {
        $this->id = $id;
        $this->load->model('records/Org_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
		$this->template->load('default_lightbox_info', 'org/info');
	}

	function doCreateOrg(){
		$modelName = 'records/Org_model';
        $jsonRst = 1;
        $zeit = time();

        if ($this->userInfo->field_list['orgId']->value!==0){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='您已经创建了商户，不可重复创建!';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $this->load->model($modelName,"dataInfo");
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $data = array();
        foreach ($this->createPostFields as $value) {
            $data[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
        }

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

        $userData = array('orgId'=>$newId);

        $this->userInfo->update_db($userData,$this->userInfo->uid);

        $jsonData = array();

        $jsonData['goto_url'] = site_url('index/index');

        $jsonData['newId'] = (string)$newId;
        echo $this->exportData($jsonData,$jsonRst);
	}


}