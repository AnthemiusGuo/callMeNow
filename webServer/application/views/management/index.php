<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <span class="glyphicon glyphicon-shopping-cart"></span>
            </div>
            <div class="details">
                <div class="number">
                    1349/1700
                </div>
                <div class="desc">
                    本月已发货金额/订单金额
                </div>
            </div>
            <a class="more" href="#">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <span class="glyphicon glyphicon-shopping-cart"></span>
            </div>
            <div class="details">
                <div class="number">
                    1349/1700
                </div>
                <div class="desc">
                    本月已发货金额/订单金额
                </div>
            </div>
            <a class="more" href="#">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <span class="glyphicon glyphicon-usd"></span>
            </div>
            <div class="details">
                <div class="number">
                    10万/12万
                </div>
                <div class="desc">
                    本月进账/出帐
                </div>
            </div>
            <a class="more" href="#">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <span class="glyphicon glyphicon-credit-card"></span>
            </div>
            <div class="details">
                <div class="number">
                    549/400
                </div>
                <div class="desc">
                    迄今待收/待付
                </div>
            </div>
            <a class="more" href="#">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-globe"></span>
                    您的商户信息
                </div>
                <div class="tools">
                    <a href="#" onclick=""><span class="glyphicon glyphicon-edit"></span>编辑</a>

                </div>
            </div>
            <div class="portlet-body">
                <?=$this->myOrgInfo->buildShowCardAdmin()?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-globe"></span>
                    最新电话记录
                </div>
            </div>
            <div class="portlet-body">

            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-globe"></span>订单管理
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="reload" data-original-title="" title="">
                    </a>
                    <a href="javascript:;" class="remove" data-original-title="" title="">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#overview_1" data-toggle="tab">
                            Top Selling </a>
                        </li>
                        <li>
                            <a href="#overview_2" data-toggle="tab">
                            Most Viewed </a>
                        </li>
                        <li>
                            <a href="#overview_3" data-toggle="tab">
                            New Customers </a>
                        </li>
                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview_1">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_2">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_3">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_4">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box red-sunglo">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-globe"></span>
                    商户留言板
                </div>
            </div>
            <div class="portlet-body">
            </div>
        </div>
    </div>
</div>