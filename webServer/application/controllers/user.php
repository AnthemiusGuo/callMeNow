<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends P_Controller {
	function __construct() {
		parent::__construct(true);

	}

	function mailbox(){
		$this->getPage();

		$this->infoTitle = "我的信箱";
        $this->load->model('lists/mail_list',"listInfo");
        $this->all_counts = $this->listInfo->get_all_count_with_uid($this->userInfo->uid);
        $this->listInfo->load_data_with_uid($this->userInfo->uid,$this->pageNow,5);

        $this->load->library('kuopage');

        $config = array();
		$config['base_url'] = site_url('index/mailbox/');
		$config['total_rows'] = $this->all_counts;
		$config['per_page'] = 5;
		$config['now_page'] = $this->pageNow;
		$config['last_link'] = false;
		$config['query_string_segment'] = 'page';
		$this->kuopage->initialize($config);

		$this->pages = $this->kuopage->create_links();


        $this->template->load('default_lightbox_list', 'index/mailbox');
	}

	function doBindOrg($enterCode = ""){

		if ($enterCode==""){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='未输入有效商户加入密码';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
        }

		$modelName = 'records/Org_model';
        $jsonRst = 1;

        $this->load->model($modelName,"dataModel");

		$rst = $this->dataModel->init_with_org_enterCode($enterCode);
		if ($rst==false){
			$jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] ='未输入有效商户加入密码';
            echo $this->exportData($jsonData,$jsonRst);
            return false;
		}

		$data['orgId'] = $this->dataModel->field_list['_id']->value;
        $this->userInfo->update_db($data,$this->uid);

		$jsonData['goto_url'] = site_url('index/index');
        echo $this->exportData($jsonData,$jsonRst);
	}

	function userInfo($uid)
	{
		$this->load->model('records/user_model',"dataInfo");
        $this->dataInfo->init($uid);
		$this->infoTitle = "个人信息：".$this->dataInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_info', 'index/userInfo');
	}


	function changePwd(){
		$this->infoTitle = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_perInfo', 'index/changePwd');
	}

	function editUserInfo(){
		$this->setViewType(VIEW_TYPE_HTML);

        $this->createUrlC = 'user';
        $this->createUrlF = 'doUpdateUser';

        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->setRelatedOrgId($this->myOrgId);
        $this->dataInfo->init_with_id($this->uid);

        $this->createPostFields = $this->dataInfo->buildChangeNeedFields();
        $this->modifyNeedFields = $this->dataInfo->buildChangeShowFields();

        $this->editor_typ = 1;
        $this->title_create = "编辑个人信息";
        $this->template->load('default_lightbox_edit', 'common/create');
	}

	function doUpdateUser($id){
		$modelName = 'records/User_model';
        $jsonRst = 1;
        $zeit = time();


        $this->load->model($modelName,"dataModel");

		$this->dataModel->init_with_id($this->uid);
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


        $this->dataModel->update_db($data,$this->uid);

		$jsonData['goto_url'] = site_url('index/index');
        echo $this->exportData($jsonData,$jsonRst);
	}


	function doReg(){
		$input_data = array();
		$input_data['email'] = $this->input->post('uEmail');
		$input_data['phone'] = $this->input->post('uPhone');
		$input_data['pwd'] = $this->input->post('uPassword');
		$input_data['inviteCode'] = $this->input->post('uInvite');
		$input_data['name'] = $this->input->post('uName');
		//这块需要做输入过滤，防XSS等，暂时省略

		$this->load->model('records/user_model',"userModel");

		$ret = $this->userModel->reg_user($input_data);
		if ($ret>0){
// 			$content = "{username}，您好，<br/>
// <br/>
// 感谢您注册npone.cn。<br/>
// 您的注册邮箱是：{useremail}。<br/>
// <br/>
// NPONE专注于公益行业信息化解决方案的研究和建设。<br/>
// 想了解我们的产品更新和新闻，请关注：<br/>
// 新浪微博：@xxxx（http://www.weibo.com/xxxxxxxx）<br/>
// 微博公众号：xxxxxxxx<br/>
// <br/>
// 敬上，<br/>
// NPONE团队<br/>
// <br/>
// http://www.npone.cn<br/>
// 客服邮箱：xxxx@npone.cn<br/>
// ";
// 			$content = str_replace(array('{username}',"{useremail}"),
// 			array($uName,$email),$content);

// 			$this->sendMail($email,$content,"感谢您注册npone.cn");
// 			$this->sendMsg($uid,0,0,$content);
			$uid = $this->userModel->uid;
			$this->login->process_login($input_data['email'],$uid,true);
			$data = array();
			$data['goto_url'] = site_url('index/index');
			$data['newId'] = $uid;
			echo $this->exportData($data,1);
		} else {
			$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户已存在'),
								-2=>array('id'=>'uPhone','msg'=>'用户已存在'),
								-3=>array('id'=>'uPhone','msg'=>'手机号或邮箱必填一个'),
								-999=>array('id'=>'uPhone','msg'=>'服务器故障，请稍后重试'),
								);
			$err_code = isset($err_codes[$ret])? $err_codes[$ret]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$ret);
		}
	}


	function doChangePwd(){
		$pwd = $this->input->post('uPassword');
		$pwdNew = $this->input->post('uPasswordNew');
		$login_rst = $this->userInfo->changePwd($pwd,$pwdNew);
		if ($login_rst > 0) {
			$data = array();
			$data['succMsg'] = '修改成功!';
			echo $this->exportData($data,$login_rst);
		} else {
			$err_codes = array(-1=>array('id'=>'uPassword','msg'=>'密码不正确'),
								-2=>array('id'=>'uPasswordNew','msg'=>'密码不正确'));
			$err_code = isset($err_codes[$login_rst])? $err_codes[$login_rst]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$login_rst);
		}
	}
}
