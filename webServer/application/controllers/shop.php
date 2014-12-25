<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shop extends P_Controller {
	function __construct() {
		parent::__construct(false);

	}
    function show($id) {
        $this->load->model('records/org_model',"shopInfo");
        $this->shopInfo->init_with_show_id($id);
        $this->urls = $this->shopInfo->gen_all_url();
        $this->template->load('default_front', 'shop/show');
    }
	function index() {
        
		$this->template->load('default_front', 'shop/index');
	}

    


}