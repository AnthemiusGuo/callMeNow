<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login {
	private $CI;
	public $uid;

	public function __construct() {
		$this->CI =& get_instance();
		//$this->_user = $this->CI->session->userdata('user');
	}
	
	public function is_login() {
		$logininfo = get_cookie('uinfo');
		if ($logininfo==false){
			$this->CI->session->unset_userdata('user');
			return false;
		} 

		$loginUser = $this->decode_cookie_data($logininfo);
		if (substr(md5($loginUser['uuid'].$loginUser['login_ts'].'Sa34KJ9'), 10,8)!=$loginUser['auth']){
			$this->CI->session->unset_userdata('user');
			return false;
		}
		$rememberme = false;
		if (substr(md5($loginUser['uuid'].$loginUser['login_ts'].'qwerrrr'), 10,8)==$loginUser['rememberme']){
			$rememberme = true;
		}

		$user = $this->CI->session->userdata('user');
		

		if(!empty($user['uuid']) && $loginUser['uuid'] == $user['uuid']) {
			//判断用户是否登录超时
			$current_ts = time();
			if(empty($user['login_ts']) 
			|| $current_ts - $user['login_ts'] > $this->CI->config->item('login_expire')) {
				$this->CI->session->unset_userdata('user');

				return false;
			}
			$user['login_ts'] = $current_ts;
			$this->CI->session->set_userdata('user', $user);
			$this->uid = $user['uuid'];
			return true;
		} elseif (!empty($user['uuid']) && $rememberme) {
			//记住我
			$this->process_login('',$user['uuid'],$rememberme);
		}
		//$this->CI->session->sess_destroy();
		$this->CI->session->unset_userdata('user');
		return false;
	}

	public function encode_cookie_data($user){
		$cookie_data = base64_encode($user['uuid']
									.'|'.$user['login_ts']
									.'|'.$user['auth']
									.'|'.$user['rememberme']);
		return $cookie_data;
	}
	public function decode_cookie_data($data){

		$cookie_data = explode('|',base64_decode($data));
		if (count($cookie_data)!=4){
			return array(
				'uuid'      => -1,
				'login_ts'  =>0,
				'auth'=> '',
				'rememberme'=>''
			);
		}
		$user['uuid'] = $cookie_data[0];
		$user['login_ts'] = $cookie_data[1];
		$user['auth'] = $cookie_data[2];
		$user['rememberme'] = $cookie_data[3];
		return $user;
	}

	
	public function process_login($loginname, $uid, $save_cookie = true) {
		$zeit =  time();
		
		$user = array(
			'loginname' => $loginname,
			'uuid'      => $uid,
			'login_ts'  =>$zeit,
			'auth'=> substr(md5($uid.$zeit.'Sa34KJ9'), 10,8),
			'rememberme'=>($save_cookie)?substr(md5($uid.$zeit.'qwerrrr'), 10,8):''
		);
		
		$this->CI->session->set_userdata('user', $user);

		if($save_cookie){
			$cookie_timeout = $zeit+86400*15;
			
		} else {
			$cookie_timeout = '0';
		}

		$cookie = array(
			'name'   => 'uinfo',
			'value'  => $this->encode_cookie_data($user),
			'expire' => $cookie_timeout,
		);
		set_cookie($cookie);
		if ($loginname!=''){
			$cookie = array(
				'name'   => 'loginname',
				'value'  => $loginname,
				'expire' => $zeit+86400*15
			);
			set_cookie($cookie);
		}
		
		return array('user' => $user);
	}
	
	
	public function logout() {
		delete_cookie('uinfo');
		$this->CI->session->sess_destroy();
	}
}