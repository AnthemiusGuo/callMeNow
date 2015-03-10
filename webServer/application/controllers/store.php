<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function index($searchInfo="") {
        $this->load_menus();
        $this->load_org_info();
        $this->quickSearchName = "货号";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Goods_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/infoGoods/";
        $this->create_link =  $this->controller_name . "/createGoods/";
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDeleteGoods';

		$this->template->load('default_page', 'common/list_view');
	}

	function category($searchInfo="") {
		$this->load_menus();
        $this->load_org_info();
        $this->quickSearchName = "分类名";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Goodscate_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/infoGoodsCate/";
        $this->create_link =  $this->controller_name . "/createGoodsCate/";
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDeleteGoodsCate';

		$this->template->load('default_page', 'common/list_view');
	}


    function searchGoods($typ=""){
        $searchInfo = $this->input->post('data');
        if ($searchInfo===false){
            $searchArray = array();
        } else {
            $this->load->library("utility");
            $searchArray = $this->utility->mbstring_2_array($searchInfo);
        }
        $searchPlus = array();

        $this->load->model('lists/Goods_list',"listInfo");

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

	function searchGoodsCate(){
        $searchInfo = $this->input->post('data');
        if ($searchInfo===false){
            $searchArray = array();
        } else {
            $this->load->library("utility");
            $searchArray = $this->utility->mbstring_2_array($searchInfo);
        }
        $searchPlus = array();

        $this->load->model('lists/Goodscate_list',"listInfo");

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

    function infoGoods($id,$sub_menu="mini_info"){
        if ($id==0){
            return;
        }
        $this->load_menus();
        $this->load_org_info();


        $this->id = $id;
        $this->load->library('user_agent');
        $this->refer = $this->agent->referrer();

        $this->load->model('records/Goods_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
		$this->showNeedFields = $this->dataInfo->buildDetailShowFields();

        $this->load->model('lists/Book_list',"bookList");
        $this->bookList->load_data_with_foreign_key("items.itemName",$id);

        $this->load->model('lists/Send_list',"sendList");
        $this->sendList->load_data_with_foreign_key("items.itemName",$id);

		$this->load->model('lists/Inventory_list',"inventoryList");
		$this->inventoryList->load_data_with_foreign_key("goodsId",$id);

        $this->detailShowFields = $this->dataInfo->buildDetailShowFields();

        $this->infoTitle = $this->dataInfo->buildInfoTitle();

        $this->sub_menus = array(
            "mini_info"=>array("name"=>"信息"),
            "books"=>array("name"=>"订货记录"),
            "send"=>array("name"=>"发货记录"),
			"inventorys"=>array("name"=>"库存"),
        );

        if (isset($this->sub_menus[$sub_menu])){
            $this->now_sub_menu = $sub_menu;
        } else {
            $this->now_sub_menu = "mini_info";
        }

        $this->template->load('default_page', 'store/info');
    }


    function doDeleteGoods($id){
        $this->load->model('records/Goods_model',"dataModel");
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
                                  "book"=>"该商品有订货记录，请先确认删除订货记录",
                                  "send"=>"该商品有发货记录，请先确认删除发货记录",
								  "inventory"=>"该商品有库存记录，请先确认删除"
							);

            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] = $cfgRstString[$rst];
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $this->dataModel->delete_db($id);

        $jsonRst = 1;
        $jsonData['goto_url'] = site_url('crm/index');

        echo $this->exportData($jsonData,$jsonRst);
    }

    function inventory($searchInfo = ""){

        $this->load_menus();
        $this->load_org_info();

        $this->quickSearchName = "名称/姓名/电话";
        $this->buildSearch($searchInfo);

        $this->load->model('lists/Inventory_list',"listInfo");

        $this->listInfo->setOrgId($this->myOrgId);
        $this->listInfo->load_data_with_search($this->searchInfo);

        $this->info_link = $this->controller_name . "/infoInventory";
        $this->create_link =  $this->controller_name . "/createInventory";
        $this->deleteCtrl = 'store';
        $this->deleteMethod = 'doDeleteInventory';

        $this->template->load('default_page', 'common/list_view');
    }

	function infoInventory($id){

		$this->load->model('records/Inventory_model',"dataInfo");
		$this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;
        $this->edit_link = $this->dataInfo->edit_link;

        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->showNeedFields = $this->dataInfo->buildDetailShowFields();

        $this->template->load('default_lightbox_info', 'common/info');
	}

	function createInventory($id=""){
		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doCreateInventory';

        $this->load->model('records/Inventory_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields(array('goodsId'));
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		$this->dataInfo->field_list['inTS']->default = time();


        if ($id==''){
            array_unshift($this->modifyNeedFields,array('goodsId'));
			$this->dataInfo->field_list['goodsId']->is_must_input = true;
        } else {
            $this->dataInfo->field_list['goodsId']->init($id);
            $this->related_field = 'goodsId';
            $this->related_id = $id;
        }


		$this->editor_typ = 0;
        $this->title_create = "新建库存信息";
		$this->template->load('default_lightbox_new', 'common/create_related');
    }

    function editInventory($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doUpdateInventory';

        $this->load->model('records/Inventory_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑库存信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

    function doCreateInventory(){

        $modelName = 'records/Inventory_model';
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

        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('store/inventory');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateInventory($id){
        $modelName = 'records/Inventory_model';
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

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('store/inventory/');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doDelInventory($id){
		$this->load->model('records/Inventory_model',"dataModel");

        $this->dataModel->delete_db($id);

        $jsonRst = 1;
		$jsonData['goto_url'] = site_url('store/category');

        echo $this->exportData($jsonData,$jsonRst);
	}

    function createGoods(){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doCreateGoods';

        $this->load->model('records/Goods_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		// $this->dataInfo->field_list['typ']->setDefault(3);
		// $this->dataInfo->field_list['status']->setDefault(1);

        $this->editor_typ = 0;
        $this->title_create = "新建商品信息";
        $this->template->load('default_lightbox_new', 'common/create');
    }

    function editGoods($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doUpdateGoods';

        $this->load->model('records/Goods_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑商品信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

    function doCreateGoods(){

        $modelName = 'records/Goods_model';
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

        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('store/infoGoods/'.(string)$newId);
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateGoods($id){
        $modelName = 'records/Goods_model';
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

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('store/infoGoods/'.$id);
        echo $this->exportData($jsonData,$jsonRst);
    }

	function createGoodsCate(){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doCreateGoodsCate';

        $this->load->model('records/Goodscate_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

		// $this->dataInfo->field_list['typ']->setDefault(3);
		// $this->dataInfo->field_list['status']->setDefault(1);

        $this->editor_typ = 0;
        $this->title_create = "新建商品信息";
        $this->template->load('default_lightbox_new', 'common/create');
    }

    function editGoodsCate($id){
        $this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'store';
        $this->createUrlF = 'doUpdateGoodsCate';

        $this->load->model('records/Goodscate_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($id);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑客户信息";
        $this->template->load('default_lightbox_edit', 'common/create');
    }

	function infoGoodsCate($id){

		$this->load->model('records/Goodscate_model',"dataInfo");
		$this->setViewType(VIEW_TYPE_HTML);

        // $this->checkRule("Project","BaseView");

        $this->id = $id;
        $this->edit_link = $this->dataInfo->edit_link;

        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
        $this->showNeedFields = $this->dataInfo->buildDetailShowFields();

        $this->template->load('default_lightbox_info', 'common/info');
	}

    function doCreateGoodsCate(){

        $modelName = 'records/Goodscate_model';
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
        $newId = $this->dataInfo->insert_db($data);

        $jsonData = array();

        $jsonData['newId'] = (string)$newId;
		$jsonData['goto_url'] = site_url('store/category');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doUpdateGoodsCate($id){
        $modelName = 'records/Goodscate_model';
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

        $this->dataModel->update_db($data,$id);

		$jsonData['goto_url'] = site_url('store/category');
        echo $this->exportData($jsonData,$jsonRst);
    }

	function doDeleteGoodsCate($id){
		$this->load->model('records/Goodscate_model',"dataModel");
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
                                  "goods"=>"该分类有商品记录，请先确定删除",
                                  );

            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] = $cfgRstString[$rst];
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

        $this->dataModel->delete_db($id);

        $jsonRst = 1;
		$jsonData['goto_url'] = site_url('store/category');

        echo $this->exportData($jsonData,$jsonRst);
	}


    private function updateGoodsUpdateTS($goodId){
        $zeit = time();
		if (isObject($goodId)){

		} else {
			$goodId = new MongoId($goodId);
		}
        $this->db->where(array('_id'=>$goodId))->update('cCrm',array('updateTS'=>$zeit));
    }

}
