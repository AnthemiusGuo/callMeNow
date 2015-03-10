<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends P_Controller {
	function __construct() {
		parent::__construct(true);
	}

    function index($typ=0){
        $this->load_menus();
        $this->load_org_info();
        if (!is_numeric($typ)){
            $searchInfo = $typ;
            $typ = 1;

        }
        if ($typ==0){
            $this->load->model('lists/Schedule_list',"listInfo");
           $this->listInfo->load_data();
            $this->info_link = $this->controller_name . "/info/";
            $this->create_link = $this->controller_name . "/create/";
            $this->deleteCtrl = 'schedule';
            $this->deleteMethod = 'doDeleteSchedule';
            $this->load->library('calendar');
            $this->template->load('default_page', 'schedule/calender');
        } else {
            $this->title = "日程快捷查询";
            $this->quickSearchName = "名称/地点";
            $this->buildSearch($searchInfo);

            $this->load->model('lists/Schedule_list',"listInfo");
            $this->listInfo->setOrgId($this->orgId);

            $this->listInfo->quickSearchWhere = "(`name` LIKE '%{search}%' OR `place` LIKE '%{search}%')";
            $this->listInfo->load_data_with_search($this->searchInfo);

            $this->info_link = $this->controller_name . "/info/";
            $this->create_link = $this->controller_name . "/create/";
            $this->deleteCtrl = 'schedule';
            $this->deleteMethod = 'doDeleteSchedule';

            $this->template->load('default_page', 'schedule/list');
        }

    }

    function calendar(){
        $random_colors = array('#4b8df8','#e02222','#35aa47','#852b99','#555555', '#fafafa', '#ffb848');
        $this->start = (int)$this->input->get('start');
        $this->end = (int)$this->input->get('end');
        $this->load->model('lists/Schedule_list',"listInfo");
        $this->listInfo->setOrgId($this->orgId);
        $this->listInfo->add_where(WHERE_TXT,'time',"((beginTS >= {$this->start}) OR (endTS <= {$this->end}) OR (beginTS <= {$this->start} AND endTS >= {$this->end}))");

        $this->listInfo->load_data_with_where();

        $events = array();
        $i = 0;
        foreach($this->listInfo->record_list as  $this_record) {
            $events[] = array(
                        "id"=>$this_record->field_list['id']->value,
                        "title"=>$this_record->field_list['name']->value." @ ".$this_record->field_list['place']->value,
                        "start"=>$this_record->field_list['beginTS']->value,
                        "end"=>$this_record->field_list['endTS']->value,
                        "allDay"=>($this_record->field_list['isWholeDay']->value==1)?true:false,
                        "backgroundColor"=>$random_colors[$i % count($random_colors)]
                //         start: new Date(y, m, d - 5),
                //         end: new Date(y, m, d - 2),
                //         backgroundColor: layoutColorCodes['green']
                //     }
                );
            $i++;
        }
        echo json_encode($events);
    }

    function info($id) {
        $this->setViewType(VIEW_TYPE_HTML);

        $this->checkRule("Project","BaseView");
        $this->id = $id;
        $this->load->model('records/Schedule_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $this->infoTitle = $this->dataInfo->buildInfoTitle();
		$this->template->load('default_lightbox_info', 'schedule/info');
	}

    function create($targetPorjectId=0) {
        $this->setViewType(VIEW_TYPE_HTML);

        $this->checkRule("Project","Edit");
		$this->title_create = "新建日程";
        $this->createUrlC = 'schedule';
        $this->createUrlF = 'doCreateSchedule';
        $this->createPostFields = array(
        	'name','desc','isWholeDay','beginTS','endTS','projectId','place','userInCharge','userInvolved',
        );

        $this->editor_typ = 0;
        $this->load->model('records/Schedule_model',"dataInfo");
        $this->dataInfo->field_list['projectId']->init($targetPorjectId);

        $this->template->load('default_lightbox_new', 'schedule/new_schedule');
	}

    function edit($id) {
        $this->setViewType(VIEW_TYPE_HTML);

        $this->checkRule("Project","Edit");
        $this->id = $id;
        $this->load->model('records/Schedule_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
        $this->title_create = "编辑: ".$this->dataInfo->buildInfoTitle();
        $this->createUrlC = 'schedule';
        $this->createUrlF = 'doUpdateSchedule';
        $this->createPostFields = array(
            'name','desc','isWholeDay','beginTS','endTS','projectId','place','userInCharge','userInvolved',
        );
        $this->editor_typ = 1;
		$this->template->load('default_lightbox_edit', 'schedule/new_schedule');
    }

    function doCreateSchedule(){
        $this->checkRule("Project","Edit");
        $jsonRst = 1;
        $zeit = time();
        $this->createPostFields = array(
            'name','desc','isWholeDay','beginTS','endTS','projectId','place','userInCharge','userInvolved',
            );
        $this->load->model('records/Schedule_model',"dataInfo");
        $data = array();
        foreach ($this->createPostFields as $value) {
            $data[$value] = $this->dataInfo->field_list[$value]->gen_value($this->input->post($value));
        }

        $data['orgId'] = $this->orgId;
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
        $this->dataInfo->build_peaple_relation($newId,$data);

        $jsonData = array();
        $now_page = $this->input->post('now_page');
        switch ($now_page) {
            case 'project_info':
                $jsonData['goto_url'] = site_url('project/info/'.$data['projectId'].'/schedule');
                break;
            case false:
                $jsonData['goto_url'] = site_url('schedule/index');
                break;
            default:
                $jsonData['goto_url'] = site_url('schedule/index');

                break;
        }

        $jsonData['newId'] = $newId;
        echo $this->exportData($jsonData,$jsonRst);
    }

    function doUpdateSchedule($id){
        $this->checkRule("Project","Edit");
        $jsonRst = 1;
        $zeit = time();
        $this->createPostFields = array(
           'name','desc','isWholeDay','beginTS','endTS','projectId','place','userInCharge','userInvolved',
        );
        $this->load->model('records/Schedule_model',"dataInfo");
        $this->dataInfo->init_with_id($id);
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

        $data['lastModifyUid'] = $this->userInfo->uid;
        $data['lastModifyTS'] = $zeit;

        $checkRst = $this->dataInfo->check_data($data,false);
        if (!$checkRst){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='请填写所有星号字段！';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $this->dataInfo->update_db($data,$id);
        if (!isset($data['userInCharge'])){
            $data['userInCharge'] = $this->dataInfo->field_list['userInCharge']->value;
        }
        if (!isset($data['userInvolved'])){
            $data['userInvolved'] = $this->dataInfo->field_list['userInvolved']->value;
        }
        $this->dataInfo->build_peaple_relation($id,$data);

        $jsonData = array();

        if (isset($data['projectId'])){
            $relateProjectId = $data['projectId'];
        } else {
            $relateProjectId = $this->dataInfo->field_list['projectId']->value;
        }

        $now_page = $this->input->post('now_page');
        switch ($now_page) {
            case 'project_info':
                $jsonData['goto_url'] = site_url('project/info/'.$relateProjectId.'/schedule');
                break;
            case false:
                $jsonData['goto_url'] = site_url('schedule/index');
                break;
            default:
                $jsonData['goto_url'] = site_url('schedule/index');

                break;
        }
        echo $this->exportData($jsonData,$jsonRst);
    }

    function doDeleteSchedule($id){
        $this->checkRule("Project","Edit");
        $this->load->model('records/Schedule_model',"dataInfo");
        $delRst = $this->dataInfo->delete_db($id);
        if  ($delRst <=0){
            $jsonRst = -1;
            $jsonData = array();
            $jsonData['err']['msg'] ='该记录不存在';
            echo $this->exportData($jsonData,$jsonRst);
            return;
        }
        $this->dataInfo->build_peaple_relation($id,array());
        $jsonRst = 1;
        $jsonData = array();

        echo $this->exportData($jsonData,$jsonRst);

    }
}
