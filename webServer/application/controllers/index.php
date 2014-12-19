<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends P_Controller {
	function __construct() {
		parent::__construct(false);

	}

	function index() {
		$this->login_verify();
		
		if ($this->userInfo->field_list['everEdit']->value==0) {
			$this->needInputUserInfo = true;
		} else {
			$this->needInputUserInfo = false;
		}
		
		$this->needInputOrgInfo = false;
		
		
		
        $this->load->model('lists/Project_list',"project_list");
        $this->project_list->load_now_with_uid($this->uid);
        $this->project_list->list_titles = array('id','name');

        $this->load->model('lists/Schedule_list',"schedule_list");
        $this->schedule_list->load_dated_data_with_uid($this->uid);

        $this->load->model('lists/Task_list',"task_list");
        $this->task_list->load_dated_data_with_uid($this->uid);

        
		$this->template->load('default_npo', 'index/dashboard');
	}
	function doTest(){
			$content = "亲爱的{username}，您好！<br/>
<br/>
您在{datetime}提交了账号密码找回请求，请点击下面的链接修改密码。<br/>";
			

			$this->sendMail("1964398291@qq.com",$content,"感谢您注册npone.cn");
	}
	function doTest2(){
		$headers = 'From: zhujun@the9.com' . "\r\n" .
    'Reply-To: zhujun@the9.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    	$ret = mail("1964398291@qq.com", "邮箱认证" ,  "sssssssss",$headers);
    	var_dump($ret);
    }

	function license(){
		$this->infoTitle = "用户协议";
		$this->load->library('markdown');
		$markdown_file_path = APPPATH.'views/index/license.md';
		$this->license_html = $this->markdown->parse_file($markdown_file_path);
		$this->template->load('default_lightbox_info', 'index/license');
	}

	function forgetMe($email,$zeit,$verify_code) {
		$email = base64_decode(urldecode($email));
		$real_verify_code = substr(md5($email.'xUUJKK'.$zeit),5,10);

		while (true) {
			if ($zeit<time()-86400){
				$this->result = false;
				$this->msg = "对不起，您的重置密码请求时间太久了，请重新请求重置密码！";
				break;
			}
			if ($verify_code!=$real_verify_code){
				$this->result = false;
				$this->msg = "您的重置密码请求不正确";
				break;
			}
			$this->result = true;
			$new_password = substr(md5($email.'eeEDD'.time()),7,8);
			$this->load->model('records/user_model',"userInfo");

			$login_rst = $this->userInfo->forceChangePwd($email,$new_password);

			$this->msg = "重置密码成功，您的密码目前是 $new_password ，请复制并且登录后立刻修改密码！";
			break;
		}
		$this->template->load('default_before_login', 'index/forgetMe');
		
	}
	function forgot() {
		$this->title_create = "忘记密码";
        $this->createUrlC = 'index';
        $this->createUrlF = 'doForgot';
        $this->createPostFields = array(
        	'email'
        );

        $this->template->load('default_lightbox_new', 'index/forgot');
	}

	function doForgot() {
		$email = $this->input->post('email');
		if(!preg_match("/^[0-9a-zA-Z.]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i",$email )){
            $this->display_error("json","邮箱格式不正确");
        }
        $this->db->select('*')
                    ->from("uUser")
                    ->where('email', $email);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
        	$result = $query->row_array(); 
            
            $uName = $result['username'];
        	$zeit = time();
	        $verify_code = substr(md5($email.'xUUJKK'.$zeit),5,10);
	        $url = site_url('index/forgetMe').'/'.urlencode(base64_encode($email)).'/'.$zeit.'/'.$verify_code;
	        $content = "亲爱的{username}，您好！<br/>
<br/>
您在{datetime}提交了账号密码找回请求，请点击下面的链接修改密码。<br/>
<a href=\"{url}\" target=\"_blank\">{url}</a> <br/>
(如果您无法点击此链接，请将它复制到浏览器地址栏后访问)<br/>
为了保证您帐号的安全，该链接有效期为24小时，并且点击一次后失效！<br/>
<br/>
敬上，<br/>
NPONE团队<br/>
<br/>
http://www.npone.cn<br/>
客服邮箱：xxxx@npone.cn<br/>
";

			$content = str_replace(array('{username}',"{datetime}","{url}"),
			array($uName,date('y年m月d日 H:i:s',$zeit),$url),$content);
        	$this->sendMail($email,$content,"npone.cn账号密码找回");
        }
        
        $jsonRst = 1;
        $jsonData = array('succ'=>array());
        $jsonData['succ']['msg'] ='您的重置密码邮件已发送，请遵循邮件步骤重置密码';
        // $jsonData['err']['msg'] =$url;

        echo $this->exportData($jsonData,$jsonRst);
	}

	function mailbox(){
		$this->getPage();

		$this->login_verify();
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
	
	function userInfo($uid)
	{
		$this->load->model('records/user_model',"dataInfo");
        $this->dataInfo->init($uid);
		$this->infoTitle = "个人信息：".$this->dataInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_info', 'index/userInfo');
	}

	function perInfo() {
		$this->login_verify();
		$this->infoTitle = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_perInfo', 'index/perInfo');
	}
	function perInfoEdit() {
		$this->login_verify();
		$id = $this->userInfo->uid;
		$this->id = $id;
        $this->load->model('records/user_model',"dataInfo");
        $this->dataInfo->init($id);
        $this->title_create = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
        $this->createUrlC = 'index';
        $this->createUrlF = 'doUpdatePerInfo';
        $this->createPostFields = array(
            'name','sex','nickname','usenick','birthTS','idType','idNumber','provinceId',
            'addresses','zipCode','phoneNumber','qqNumber','wechatNumber','weiboNumber','otherContact','education',
            'school','outcomming',
        );
        $this->editor_typ = 1;

		
		$this->template->load('default_lightbox_edit', 'index/perInfoEdit');
	}

	function real(){
		$this->login_verify();
		$this->infoTitle = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_perInfo', 'index/real');
	}
	function realEdit(){
		$this->login_verify();
		$this->infoTitle = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_edit', 'index/realEdit');
	}

	function changePwd(){
		$this->login_verify();
		$this->infoTitle = "个人信息：".$this->userInfo->field_list['username']->gen_show_html();
		$this->template->load('default_lightbox_perInfo', 'index/changePwd');
	}

	function login() {
		$this->is_login = false;
		$this->pageClass = 'login';
		if ($this->login->is_login()){
			$this->login->logout();
		}
		$this->loginname = get_cookie('loginname');
		if (!$this->loginname){
			$this->loginname = '';
		}
		$this->template->load('default_before_login', 'index/login');
	}
	function reg() {
		$this->is_login = false;
		$this->pageClass = 'login';
		if ($this->login->is_login()){
			$this->login->logout();
		}
		$this->template->load('default_before_login', 'index/reg');
	}

	function doUpdatePerInfo(){
		$this->login_verify();
		$jsonRst = 1;
        $zeit = time();
        $id = $this->userInfo->uid;
        $this->createPostFields = array(
            'name','sex','nickname',
          'beginNGOTS','birthTS','idType','idNumber','provinceId',
            'addresses','zipCode','phoneNumber','qqNumber','wechatNumber','weiboNumber','otherContact','education',
            'school','outcomming',
        );
        $this->load->model('records/User_model',"dataInfo");
        $this->dataInfo->init($id);
        $data = array();
        foreach ($this->createPostFields as $value) {
            $newValue = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
            if ($newValue!="".$this->dataInfo->field_list[$value]->value){
                $data[$value] = $newValue;
            }   
        }
        
        if (empty($data)){
            $jsonRst = -2;
            $jsonData = array();
            $jsonData['err']['msg'] ='无变化';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $data['everEdit'] = 1;
        
        $checkRst = $this->dataInfo->check_data($data,false);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $this->dataInfo->update_db($data,$id);
        // print $this->db->last_query();
        // exit;
        $jsonData = array();
        echo $this->exportData($jsonData,$jsonRst);
	}

	function doReg(){
		$email = $this->input->post('uEmail');
		$pwd = $this->input->post('uPassword');
		$inviteCode = $this->input->post('uInvite');
		$uName = $this->input->post('uName');

		$this->load->model('records/user_model',"userModel");

		$uid = $this->userModel->reg_user($email,$pwd,$uName,$inviteCode);
		if ($uid>0){
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
			$this->login->process_login($email,$uid,$rememberMe);
			$data = array();
			$data['goto_url'] = site_url('index/index');
			echo $this->exportData($data,$uid);
		} else {
			$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户已存在'),
								-2=>array('id'=>'uInvite','msg'=>'已有组织录入您的数据，请咨询组织获得邀请码'),
								-3=>array('id'=>'uInvite','msg'=>'您的邀请码输入有误!请咨询组织获得正确的邀请码'));
			$err_code = isset($err_codes[$uid])? $err_codes[$uid]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$uid);
		}
	}

	function doLogin(){
		$email = $this->input->post('uEmail');
		$pwd = $this->input->post('uPassword');
		$rememberMe = $this->input->post('uRememberMe');
		$this->load->model('records/user_model',"userModel");
        $login_rst = $this->userModel->verify_login($email,$pwd);
		if ($login_rst > 0) {
			$this->login->process_login($email,$this->userModel->uid,$rememberMe);
			$data = array();
			$data['goto_url'] = site_url('index/index');
			echo $this->exportData($data,$login_rst);
		} else {
			$err_codes = array(-1=>array('id'=>'uEmail','msg'=>'用户不存在'),
								-2=>array('id'=>'uPassword','msg'=>'密码不正确'));
			$err_code = isset($err_codes[$login_rst])? $err_codes[$login_rst]:array('id'=>'uEmail','msg'=>'未知错误');
			;

			echo $this->exportData(array('err'=>$err_code),$login_rst);
		}
	}

	function doChangePwd(){
		$this->login_verify();
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
	function doLogout(){
		$this->login->logout();
		$this->session->sess_destroy();
		header("Location:".site_url('index/login'));
	}
	function doBind($orgId){
		$this->login_verify();


		$this->db->where("orgId",$orgId)
		->where("email",$this->userInfo->field_list['email']->value)
		->update('pPeaple',array('uid'=>$this->uid));
		
		$jsonRst = 1;
		$jsonData = array();
		$jsonData['goto_url'] = site_url('index/index');
		echo $this->exportData($jsonData,$jsonRst);
	}
}