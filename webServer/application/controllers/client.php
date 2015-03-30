<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends P_Controller {
	function __construct() {
		parent::__construct(false);

	}

	function login($uPhone="",$pwd="") {
		$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户不存在'),
							-2=>array('id'=>'uPassword','msg'=>'密码不正确'),
							-3=>array('id'=>'no','msg'=>'您尚未创建商户，请先到网页端注册账户'),
							-4=>array('id'=>'no','msg'=>'您的商户尚未开通 VIP，请开通后重试登录'),
							-5=>array('id'=>'no','msg'=>'商户信息有误，请到网页端检查'),
							-6=>array('id'=>'no','msg'=>'用户名、密码必须输入！'),);
		if ($uPhone==""){
			$uPhone = $this->input->post('uName');
		}
		if ($pwd==""){
			$pwd = $this->input->post('uPwd');
		}
		if ($uPhone=="" || $pwd==""){
			$err_code = isset($err_codes[-6])? $err_codes[-6]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),-6);
			return;
		}

		$this->load->model('records/user_model',"userModel");
		$login_rst = $this->userModel->verify_login($uPhone,$pwd);

		if ($login_rst > 0) {
			if ($this->userModel->field_list['orgId']->value==0){
				$err_code = isset($err_codes[-3])? $err_codes[-3]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-3);
				return;
			}
			$this->load->model('records/org_model',"orgModel");
			$org_rst = $this->orgModel->init_with_id($this->userModel->field_list['orgId']->value);
			if (!$org_rst){
				$err_code = isset($err_codes[-5])? $err_codes[-5]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-5);
				return;
			}
			if (!$this->orgModel->isVip()){
				$err_code = isset($err_codes[-4])? $err_codes[-4]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-4);
				return;
			}

			$authCode = $this->userModel->gen_auth_code();
			$data = array('uid'=>$this->userModel->uid,
						'name'=>$this->userModel->field_list['name']->value,
						'authCode'=>$authCode);


			echo $this->exportData($data,$login_rst);
		} else {

			$err_code = isset($err_codes[$login_rst])? $err_codes[$login_rst]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$login_rst);
		}

	}

	function checkAuth($auth=""){
		$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户不存在'),
							-2=>array('id'=>'uPassword','msg'=>'密码不正确'),
							-3=>array('id'=>'no','msg'=>'您尚未创建商户，请先到网页端注册账户'),
							-4=>array('id'=>'no','msg'=>'您的商户尚未开通 VIP，请开通后重试登录'),
							-5=>array('id'=>'no','msg'=>'商户信息有误，请到网页端检查'),
							-6=>array('id'=>'no','msg'=>'数据错误，请联系客服'));
		if ($auth==""){
			$err_code = isset($err_codes[-6])? $err_codes[-6]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),-6);
			return;
		}
		$this->load->model('records/user_model',"userModel");
		$login_rst = $this->userModel->check_auth_code($auth);


		if ($login_rst > 0) {
			if ($this->userModel->field_list['orgId']->value==0){
				$err_code = isset($err_codes[-3])? $err_codes[-3]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-3);
				return;
			}
			$this->load->model('records/org_model',"orgModel");
			$org_rst = $this->orgModel->init_with_id($this->userModel->field_list['orgId']->value);
			if (!$org_rst){
				$err_code = isset($err_codes[-5])? $err_codes[-5]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-5);
				return;
			}
			if (!$this->orgModel->isVip()){
				$err_code = isset($err_codes[-4])? $err_codes[-4]:array('id'=>'uEmail','msg'=>'未知错误');
				;

				echo $this->exportData(array('err'=>$err_code),-4);
				return;
			}


			$data = array('uid'=>$this->userModel->uid,
						'name'=>$this->userModel->field_list['name']->value,
						'authCode'=>$auth);


			echo $this->exportData($data,$login_rst);
		} else {
			$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户不存在'),
								-2=>array('id'=>'uPassword','msg'=>'密码不正确'));
			$err_code = isset($err_codes[$login_rst])? $err_codes[$login_rst]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$login_rst);
		}
	}

	function call($auth="",$phone=""){
		$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户不存在'),
							-2=>array('id'=>'uPassword','msg'=>'密码不正确'),
							-3=>array('id'=>'no','msg'=>'您尚未创建商户，请先到网页端注册账户'),
							-4=>array('id'=>'no','msg'=>'您的商户尚未开通 VIP，请开通后重试登录'),
							-5=>array('id'=>'no','msg'=>'商户信息有误，请到网页端检查'),
							-6=>array('id'=>'no','msg'=>'数据错误，请联系客服'));
		if ($auth==""){
			$err_code = isset($err_codes[-6])? $err_codes[-6]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),-6);
			return;
		}
		$this->load->model('records/user_model',"userModel");
		$login_rst = $this->userModel->check_auth_code($auth);

		if ($login_rst > 0) {
			$this->login->process_login($this->userModel->field_list['email']->value,$this->userModel->uid,true,true);
			header("Location:".site_url('phone/call/'.$phone));
		} else {
			$err_code = isset($err_codes[$login_rst])? $err_codes[$login_rst]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$login_rst);
		}
	}


}
