<div class="login-bg">
<div class="row">
    <div class="col-md-8">
        
    </div>
    <div class="col-md-4">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">注册</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="regForm">
                        <div class="form-group" id="uEmailGroup">
                            <label for="uEmail" class="col-sm-3 control-label">登录邮箱</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="uEmail" name="uEmail" placeholder="邮箱（登录用）" required>
                            </div>
                        </div>
                        <div class="form-group" id="uPasswordGroup">
                            <label for="uPassword" class="col-sm-3 control-label">密码</label>
                            <div class="col-sm-9">
                                <input type="password" minlength="6" class="form-control" id="uPassword" placeholder="密码6位以上" name="uPassword" required>
                            </div>
                        </div>
                        <div class="form-group" id="uPasswordAgainGroup">
                            <label for="uPasswordAgain" class="col-sm-3 control-label">确认密码</label>
                            <div class="col-sm-9">
                                <input type="password" minlength="6" class="form-control" id="uPasswordAgain" placeholder="再次确认密码" name="uPasswordAgain" required equalTo="#uPassword">
                            </div>
                        </div>
                        <div class="form-group" id="uNameGroup">
                            <label for="uName" class="col-sm-3 control-label">用户名</label>
                            <div class="col-sm-9">
                                <input type="text" minlength="2" class="form-control" id="uName" required placeholder="用户名（显示用）" name="uName">
                            </div>
                        </div>
                        <div class="form-group" id="uPasswordGroup">
                            <label for="uInvite" class="col-sm-3 control-label">组织邀请码</label>
                            <div class="col-sm-9">
                                <input type="text" minlength="6" class="form-control" id="uInvite" placeholder="组织邀请码" name="uInvite">
                                <span class="help-block">如果您收到组织邀请码，可以直接绑定帐号到该组织。如果该组织已经录入您的资料，必须填写组织邀请码。</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="uAgree" name="uAgree" required>同意<a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/license') ?>',size:'m'})">网站注册协议</a>
                                    </label>
                              </div>
                            </div>
                        </div>
                        <div class="text-center">
                        <a href="javascript:void(0);" onclick="req_reg()" class="btn btn-primary">注   册</a>
                        </div>
                        <a href="<?php echo site_url('index/index') ?>">登   录</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onclick="lightbox({url:'<?php echo site_url('index/forgot') ?>',size:'m'})">忘记密码</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
$(".login-bg").height($(window).height()-50);
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