<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends P_Controller {
	function __construct() {
		parent::__construct(true);
		if ($this->userInfo->field_list['isAdmin']->toBool()==false){
			header("Location:".site_url('index/noAuth'));
            exit;
        }
	}

	function orgs() {
		$this->load_menus();
        $this->load_org_info();
        $this->quickSearchName = "名称";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/Org_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);



        $this->info_link = $this->controller_name . "/info/";
        $this->create_link =  $this->controller_name . "/createOrg/";
        $this->deleteCtrl = 'crm';
        $this->deleteMethod = 'doDeleteCrm';

        $this->need_plus = 'crm/index_plus';
		$this->template->load('default_page', 'common/list_view');
	}

	function infoOrg($id){

		$this->load->model('records/Org_model',"dataInfo");
		$this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;
        $this->edit_link = $this->dataInfo->edit_link;

        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->showNeedFields = $this->dataInfo->buildAdminDetailShowFields();

        $this->template->load('default_lightbox_info', 'common/info');
	}

	function createOrg($id=""){
		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'admin';
        $this->createUrlF = 'doCreateOrg';

        $this->load->model('records/Org_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->editor_typ = 0;
        $this->title_create = "新建商户";
		$this->template->load('default_lightbox_new', 'common/create');
    }

    function editOrg($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'admin';
        $this->createUrlF = 'doUpdateOrg';

        $this->load->model('records/Org_model',"dataInfo");
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildAdminChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑商户信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

	function doGetVip($id){
		$modelName = 'records/Org_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($id);

		if ($this->dataModel->field_list['isVip']->toBool()==true){
			$jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该商户已经是 VIP 了！';
            echo $this->exportData($jsonData,$jsonRst);
		}
        $data = array();
		$data['isVip'] = 1;
        $data['lastModifyUid'] = $this->userInfo->uid;
        $data['lastModifyTS'] = $zeit;


        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('admin/orgs');
        echo $this->exportData($jsonData,$jsonRst);
	}

	function doDisVip($id){
		$modelName = 'records/Org_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($id);

		if ($this->dataModel->field_list['isVip']->toBool()==false){
			$jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该商户 不是 VIP ！';
            echo $this->exportData($jsonData,$jsonRst);
		}
        $data = array();
		$data['isVip'] = 0;
        $data['lastModifyUid'] = $this->userInfo->uid;
        $data['lastModifyTS'] = $zeit;


        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('admin/orgs');
        echo $this->exportData($jsonData,$jsonRst);
	}

    function doCreateOrg(){

        $modelName = 'records/Org_model';
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
		$jsonData['goto_url'] = site_url('admin/orgs');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateOrg($id){
        $modelName = 'records/Org_model';
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

		$jsonData['goto_url'] = site_url('admin/orgs');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doDeleteOrg($id){
		return;
		$modelName = 'records/Org_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($id);

        $data = array("orgId"=>"");

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('admin/orgs');
        echo $this->exportData($jsonData,$jsonRst);
	}



	function admins() {
		$this->load_menus();
        $this->load_org_info();
        $this->quickSearchName = "名称";
        $this->buildSearch($searchInfo);


        $this->load->model('lists/User_list',"listInfo");

		$this->listInfo->add_where(WHERE_TYPE_WHERE,"isAdmin",1);
        $this->listInfo->load_data_with_where();



        $this->info_link = $this->controller_name . "/info/";
        $this->create_link =  $this->controller_name . "/createAdmin/";
        $this->deleteCtrl = 'admin';
        $this->deleteMethod = 'doDeleteAdmin';

		$this->template->load('default_page', 'common/list_view');
	}

	function createAdmin(){

		$this->title_create = "新建管理员";
        $this->createUrlC = 'admin';
        $this->createUrlF = 'doCreateAdmin';
        $this->createPostFields = array(
        	'email'
        	);
        $this->load->model('records/User_model',"dataInfo");
        $this->template->load('default_lightbox_new', 'admin/new_admin');
	}

	function doCreateAdmin(){
		$this->load->model('records/User_model',"dataInfo");
		$email = $this->dataInfo->field_list['phone']->gen_value($this->input->post('phone'));
		$hasUser = $this->dataInfo->init_with_phone($phone);


		if ($hasUser==-1){
			$jsonRst = -1;
			$jsonData = array();
			$jsonData['err']['msg'] ='该用户不存在';
			echo $this->exportData($jsonData,$jsonRst);
			return;
		}

		$data['isAdmin'] = 1;
        $this->dataInfo->update_db($data,$this->dataInfo->field_list['uid']->value);
        $jsonRst = 1;
        $jsonData = array();
		$jsonData['goto_url'] = site_url('admin/admins');
		echo $this->exportData($jsonData,$jsonRst);
	}

}
