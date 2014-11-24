<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>Call Me Now</title>
	<?php echo link_tag(static_url('css/bootstrap.css')); ?>
    <?php echo link_tag(static_url('css/main.css')); ?>
    <?php echo link_tag(static_url('css/bootstrap-datetimepicker.min.css')); ?>

    
	<script type="text/javascript" src="<?php echo static_url('js/jquery-1.11.1.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/bootstrap-datetimepicker.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/bootstrap-datetimepicker.zh-CN.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo static_url('js/jquery.tablesorter.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/jquery.powertip.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/jquery.blockUI.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/jquery.validate.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/jquery.flot.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo static_url('js/jquery.flot.pie.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo static_url('js/main.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo static_url('js/ajaxfileupload.js'); ?>"></script>
    <script type="text/javascript">
    	var base_url = "<?php print base_url(); ?>";
    	var req_url_template = "<?php echo site_url('{ctrller}/{action}') ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<nav class="navbar" role="navigation">
    	<div class="container-fluid">
    		<div class="navbar-header">
		        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		          <span class="sr-only">Toggle navigation</span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		          <span class="icon-bar"></span>
		        </button>
		        <a class="navbar-brand" href="#">第九城市</a>
	      	</div>
	      	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

	        <ul class="nav navbar-nav navbar-right">

	          <li><a href="#">我的通行证</a></li>
	          <li class="dropdown">
	            <a href="#" class="dropdown-toggle" data-toggle="dropdown">九城游戏 <span class="caret"></span></a>
	            <ul class="dropdown-menu" role="menu">
	              <li><a href="#">正在运营</a></li>
	            <li class="divider"></li>
	              <li><a href="#">行星边际2&gt;</a></li>
	              <li><a href="#">神仙传&gt;</a></li>
	              
	              <li><a href="#">自由国度&gt;</a></li>
	              <li><a href="#">网页游戏&gt;</a></li>
	            </ul>
	          </li>
	          <li><a href="#">积分商城</a></li>
	          <li><a href="#">VIP会员</a></li>
	          <li><a href="#">手机游戏</a></li>
	          <li><a href="#">客服中心</a></li>
	        </ul>
	      </div><!-- /.navbar-collapse -->
	    </div><!-- /.container-fluid -->
	</nav>
