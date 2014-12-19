<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends P_Controller {
	function __construct() {
		parent::__construct(false);

	}

	function index() {
		$page = 1;
		if (isset($_GET['page'])) {
			$page = (int)$_GET['page'];
		}
		$keywords = "";
		if (isset($_GET['keywords'])) {
			$keywords = $_GET['keywords'];
		}


		//通过sql获得原始数据，注意如果数据太多，还是需要LIMIT一下，例如200或者500条之类的
		//
		$org_array = array(
    				array(
      					"id"=>1,
      					"logo"=>"",
    					"name"=>"游戏1",
    					"intro"=>"游戏1的介绍....",
    					"guide"=>"游戏1的操作说明.....",
    					"category"=>
    						array(""),
    					"tag"=>"RPG,修仙",
    					"type"=>"0",
    					"price"=>"0",
            			"price_current"=>"0",
            			"share_num"=>"12",
            			"play_num"=>"27",
            			"rating"=>"3.33",
            			"screenshots"=>array(""),
            			"size"=>"10.00",
            			"buy_status"=>"0",
            			"create_time"=>"1417762595"
    				),
    				array(
      					"id"=>2,
      					"logo"=>"图标1",
    					"name"=>"游戏2",
    					"intro"=>"游戏2的介绍....",
    					"guide"=>"游戏2的操作说明.....",
    					"category"=>
    						array(""),
						"tag"=>"模拟经营,大富翁",
    					"type"=>"1",
    					"price"=>"10",
            			"price_current"=>"5",
            			"share_num"=>"4",
            			"play_num"=>"5",
            			"rating"=>"5.00",
            			"screenshots"=>array(""),
            			"size"=>"10.00",
            			"buy_status"=>"2",
            			"create_time"=>"1417762603"
    				),
    				array(
      					"id"=>3,
      					"logo"=>"图标3",
    					"name"=>"游戏3",
    					"intro"=>"游戏3的介绍....",
    					"guide"=>"游戏3的操作说明.....",
    					"category"=>
    						array(""),
    					"tag"=>"动作,酷跑",
    					"type"=>"1",
    					"price"=>"10",
            			"price_current"=>"5",
            			"share_num"=>"0",
            			"play_num"=>"0",
            			"rating"=>"0.00",
            			"screenshots"=>array(""),
            			"size"=>"20.00",
            			"buy_status"=>"2",
            			"create_time"=>"1418196717"
    				)
    			);
		
		//配置优先级，使用数字越大的越优先
		//数字相等表示优先级相同，混在一起进行play_num排序
		define('MATCH_NONE',1);
		define('MATCH_PART',2);
		define('MATCH_ALL',3);
		//下标第一级是name的匹配，二级是tag的匹配

		global $config_priority;
		$config_priority = array();
		$config_priority[MATCH_NONE][MATCH_NONE] = 0;
		$config_priority[MATCH_NONE][MATCH_PART] = 10;
		$config_priority[MATCH_NONE][MATCH_ALL] = 20;

		$config_priority[MATCH_PART][MATCH_NONE] = 30;
		$config_priority[MATCH_PART][MATCH_PART] = 40;
		$config_priority[MATCH_PART][MATCH_ALL] = 50;

		//精确命中名字，未命中tag
		$config_priority[MATCH_ALL][MATCH_NONE] = 60;
		//精确命中名字，部分命中tag
		$config_priority[MATCH_ALL][MATCH_PART] = 70;
		//精确命中名字，精确命中tag
		$config_priority[MATCH_ALL][MATCH_ALL] = 80;


		//先将游戏分为命中名字组和命中标签组,命中又分为部分命中和全部命中

		$target_all = array();

		foreach ($org_array as $item) {
			$item['match_name'] = MATCH_NONE;
			$item['match_tag'] = MATCH_NONE;

			if ($item['name']==$keywords) {
				$item['match_name'] = MATCH_ALL;
				
			} elseif ($keywords!="" && strpos($item['name'],$keywords)!==false) {
				$item['match_name'] = MATCH_PART;
			} 


			$tag = explode(",",$item['tag']);
			$flag = false;
			foreach ($tag as $this_tag) {
				if ($this_tag==$keywords) {
					$flag = true;
					$item['match_tag'] = MATCH_ALL;
					//只有完全命中才break，完全命中无需检查后面部分命中了
					break;
				}
				//例如tag模拟经营，搜索经营
				if ($keywords!="" && strpos($this_tag,$keywords)!==false) {
					$flag = true;
					$item['match_tag'] = MATCH_PART;
				}
			}
			if ($flag==false){
				//兜底，按照逻辑不应该发生，除非$keywords为空,或者输入数组是自己手写
				$item['match_tag'] = MATCH_NONE;
			}
			$target_all[] = $item;
		}

		//对四个数据分别进行排序
		//
		// var_dump('$target_name_all',$target_name_all,'$target_name_match',$target_name_match);
		// var_dump('$target_tag_all',$target_tag_all,'$target_tag_match',$target_tag_match);
		// exit;

		function mysort($a,$b){
			global $config_priority;
			$priority_a = $config_priority[$a['match_name']][$a['match_tag']];
			$priority_b = $config_priority[$b['match_name']][$b['match_tag']];

			if ($priority_a!=$priority_b){

				return ($priority_a < $priority_b )? 1 : -1;
			}
			if ($a['play_num']==$b['play_num']) {
				//相等时候，如果需要更多判断，写在这里，例如
				//最终实在无法判断时候返回0即可
				if($a['create_time']==$b['create_time']) {
					return 0;
				} else {
					//大于 的话是越小的越靠前，也就是说越老创建的越考前，这个策划没有，只是例子
					//自己自由调整，需要多条规则的，就不停的往里写就好了
					return ($a['create_time']>$b['create_time'])? 1 : -1;
				}
			} else {
				//小于 的话是越大的越靠前
				return ($a['play_num']<$b['play_num'])? 1 : -1;
			}
		}

		usort($target_all,"mysort");
		foreach ($target_all as $key=>$item) {
			unset($target_all[$key]['match_name']);
			unset($target_all[$key]['match_tag']);
		}

		//处理分页

		$countPerPage = 10;

		$totalPage = ceil(count($target_all)/$countPerPage);
		if ($page > $totalPage) {
			$page = $totalPage;
		}

		if ($totalPage>1) {
			$target_array = array_slice($target_all,$countPerPage*($page-1),$countPerPage);
		} else {
			//就1页
			$target_array = $target_all;
		}

		$rst_array = array(
  			"list"=>$target_array,
  				
        	"pagecount"=>$totalPage,
        	"currentPage"=>$page
        );
        //currentPage原来没传，这个可有可无，请求时候客户端是知道的
        //传的话主要是容错用的
		var_dump($rst_array);
	}
}