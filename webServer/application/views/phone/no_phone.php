<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-star"></span>
                    来电快捷输入
                </div>
            </div>
            <div class="portlet-body">
                <form role="form" action="<?=site_url("index/doLogin")?>" method="post">

                    <h3 class="form-title">请输入来电号码</h3>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">来电号码</label>
                        <div class="input-icon">
                            <span class="glyphicon glyphicon-ok-circle"></span>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="来电号码" id="dashPhoneSearch" name="dashPhoneSearch">
                        </div>
                    </div>

                    <div class="form-group">

                            <button type="button" class="btn green-meadow pull-right" onclick="phoneSearch('dashPhoneSearch')">
                            查询 <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </div>
                    <div class="clear"></div>
                    <br/>
                    <br/>
                    <br/>
                </form>
            </div>
        </div>
    </div>
</div>
