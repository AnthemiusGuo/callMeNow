<?php include_once('common/header.php');
?>
<body class="page-header">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?=site_url()?>">
                    <img src="<?=static_url('images/logo.png')?>" alt="logo" class="logo-default" width="150px">
                </a>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

                        <span class="username username-hide-on-mobile">
                            <?
                            if (isset($this->myOrgInfo)){
                                echo $this->myOrgInfo->field_list['name']->gen_show_value().' - ';
                            }?>
                            <?=$this->userInfo->field_list['name']->gen_show_value()?> </span>
                        <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default" style="z-index:2000;">
                            <li>
                                <a href="extra_lock.html">
                                <span class="glyphicon glyphicon-lock"></span>锁屏
                                </a>
                            </li>
                            <li>
                                <a href="<?=site_url('index/doLogout')?>">
                                <span class="glyphicon glyphicon-log-out"></span>退出
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-quick-sidebar-toggler">
                        <a href="javascript:;" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                        </a>
                    </li>
                    <!-- END QUICK SIDEBAR TOGGLER -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul id="nav-sidebar" class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <?php
                foreach ($this->menus as $menu_name=>$menu_info):
                ?>
                    <li class="main-nav <?php echo ($this->controller_name==$menu_name)?"active open":"" ?>" id="nav-side-title-<?php echo $menu_name;?>">
                        <a href="#" onclick="nav_sidebar_collapse('<?php echo $menu_name;?>')">
                        <span class="glyphicon <?php echo $menu_info["icon"]?>"></span>
                        <span class="title"><?php echo $menu_info['name'];?></span>
                        <span class="showing_icon glyphicon glyphicon-chevron-down pull-right <?php echo ($this->controller_name==$menu_name)?"show":"hidden" ?>"></span>
                        <?php echo ($this->controller_name==$menu_name)?'<span class="selected"></span>':"" ?>
                        </a>

                        <ul class="nav sub-nav <?php echo ($this->controller_name==$menu_name)?"show":"hidden" ?>" id="nav-side-list-<?php echo $menu_name;?>">
                            <?php
                            foreach ($menu_info["menu_array"] as $sub_menu_name=>$sub_menu_info):
                            ?>
                            <li class="<?php echo ($this->controller_name==$menu_name && $sub_menu_name==$this->method_name)?'active':'' ?>">
                                <a href="<?php echo ("href"==$sub_menu_info['method'])?$sub_menu_info['href']:'javascript:void(0);' ?>" <?php echo ("onclick"==$sub_menu_info['method'])?'onclick="'.$sub_menu_info['onclick'].'"':'' ?> >
                                <span class="glyphicon <?php echo ($this->controller_name==$menu_name && $sub_menu_name==$this->method_name)?'glyphicon-chevron-right':'glyphicon-minus' ?>"></span>
                                <?php echo $sub_menu_info['name'] ?></a>
                                </li>
                            <?
                            endforeach;
                            ?>
                        </ul>
                    </li>
                <?
                endforeach;
                ?>

            </ul>
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content" style="min-height:1227px">
        <?php echo $contents; ?>
        </div>
    </div>
    <!-- END CONTENT -->
<script>
jQuery(document).ready(function() {
    $(".table-paged").quickPager({pageSize:10,holder:'#main_pager',struct:'tbody'});
    $(".tablesorter").tablesorter();
    $('.tooltips').powerTip({offset:20});
});
</script>
<!-- END JAVASCRIPTS -->
<?php include_once('common/footer.php')?>
