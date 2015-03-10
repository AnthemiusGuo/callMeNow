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
        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/User_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);



        $this->info_link = $this->controller_name . "/infoUser/";
        $this->create_link =  $this->controller_name . "/createUser/";
        $this->deleteCtrl = 'management';
        $this->deleteMethod = 'doDeleteUser';

		$this->need_plus = 'management/hr_plus';
		if ($this->userInfo->field_list['typ']->value==0){
			$this->canEdit = false;
		}
		$this->template->load('default_page', 'common/list_view');
    }

	function infoUser($id){

		$this->load->model('records/User_model',"dataInfo");
		$this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;
        $this->edit_link = $this->dataInfo->edit_link;

        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->showNeedFields = $this->dataInfo->buildDetailShowFields();

        $this->template->load('default_lightbox_info', 'common/info');
	}

	function createUser($id=""){
		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'management';
        $this->createUrlF = 'doCreateUser';

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('goodsId'));
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->dataInfo->field_list['pwd']->is_must_input = true;


		$this->editor_typ = 0;
        $this->title_create = "新建员工信息";
		$this->template->load('default_lightbox_new', 'common/create');
    }

    function editUser($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'management';
        $this->createUrlF = 'doUpdateUser';

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑员工信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

    function doCreateUser(){

        $modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataInfo");
        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $data = array();
        foreach ($this->createPostFields as $value) {
            $data[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
        }

        $data['orgId'] = $this->myOrgId;

        $checkRst = $this->dataInfo->check_data($data);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['id'] = 'creator_'.$this->dataInfo->get_error_field();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
		$data['pwd'] = md5($data['pwd']);
        $newId = $this->dataInfo->insert_db($data);

        $jsonData = array();

        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('management/hr');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateUser($id){
        $modelName = 'records/User_model';
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


        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('management/hr');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doDelUser($id){
		//删除用户很特殊，不是真删除，只是标记为 orgID 为空
		//商户超级用户不可删除
		$this->load_org_info();

		// if ($this->myOrgInfo->field_list['supperUid']->value==$id){
		// 	$jsonRst = -1;
        //     $jsonData = array();
        //     $jsonData['err']['msg'] ='商户超级账户不可删除';
        //     echo $this->exportData($jsonData,$jsonRst);
        //     return false;
		// }
		$modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($id);

        $data = array("orgId"=>"");

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('management/hr');
        echo $this->exportData($jsonData,$jsonRst);
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
