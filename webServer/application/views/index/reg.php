<!-- BEGIN REGISTRATION FORM -->
 <form class="login-form" id="regForm" action="<?=site_url("index/doReg")?>" method="post">
    <h3>注册</h3>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">登录邮箱</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="text" placeholder="登录邮箱" name="uEmail">
        </div>
        <span class="help-block">可以使用qq号@qq.com</span>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">登录手机号</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="text" placeholder="登录手机号" name="uEmail">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">姓名</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="姓名" name="username">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">密码</label>
        <div class="input-icon">
            <span class="glyphicon glyphicon-edit"></span>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="密码" name="password">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">重输密码</label>
        <div class="controls">
            <div class="input-icon">
                <span class="glyphicon glyphicon-edit"></span>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="重输密码" name="rpassword">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="uAgree" name="uAgree" required checked="checked">同意<a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/license') ?>',size:'m'})">网站注册协议</a>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" id="register-submit-btn" class="btn green-haze pull-right" onclick="req_reg()">
        注册<span class="glyphicon glyphicon-edit"></span>
        </button>
    </div>
    <div class="clearfix"></div>
    <div class="create-account">
        <h4>已经有帐号？</h4>
        <p>
            <a href="<?php echo site_url('index/login') ?>">点击登录</a>&nbsp;
            <a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/forgot') ?>',size:'m'})">忘记密码</a>
        </p>
        
    </div>
</form>
<!-- END REGISTRATION FORM -->

<script>
var validator = $("#regForm").validate();
function req_reg(){

    var uAgree = $("#uAgree").prop('checked');
    if (uAgree==false){
        alert("请阅读并同意网站协议");
        return;
    }
    $("#regForm .form-group").removeClass('has-error');
    if (validator.form()==false) {
        return;
    };
    var uEmail = $("#uEmail").val();
    var uPassword = $("#uPassword").val();
    var uInvite = $("#uInvite").val();
    var uName = $("#uName").val();

    ajax_post({m:'index',a:'doReg',data:{uEmail:uEmail,uPassword:uPassword,uInvite:uInvite,uName:uName},callback:function(json){
            if (json.rstno>0){
                window.location.href=json.data.goto_url; 
            } else {
                var showErr = {};
                showErr[json.data.err.id] = json.data.err.msg ;
                validator.showErrors(showErr);
            }
        }
  });
}
</script>