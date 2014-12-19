<?php include_once('common/header.php')?>
<?php echo link_tag(static_url('css/login2.css')); ?>
<body class="<?=$this->pageClass ?>">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<a href="<?=site_url()?>">
			<img src="<?=static_url('images/logo.png')?>" alt="">
		</a>
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<?php echo $contents; ?>
	</div>
	<!-- END LOGIN -->
<?php include_once('common/footer.php')?>