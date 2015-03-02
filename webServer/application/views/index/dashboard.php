<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<?php
include_once("dashboardHelper.php");
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-phone-alt"></span>
                    来电快捷输入</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <form role="form" action="<?=site_url("index/doLogin")?>" method="post">

                    <h3 class="form-title">请输入来电号码</h3>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">来电号码</label>
                        <div class="input-icon">
                            <span class="glyphicon glyphicon-ok-circle"></span>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="来电号码" id="enterCode" name="enterCode">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        
                            <button type="button" class="btn green-meadow pull-right" onclick="req_login()">
                            查询 <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-globe"></span>
                    您的商户信息</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <?=$this->myOrgInfo->buildShowCardAdmin()?>                
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    
</div>