<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-warning" role="alert">只有老板身份才可以编辑本页面内容。
            <? if ($this->isVip==false){
                echo '非 VIP 客户每个商户只能拥有一个用户。<a class="btn btn-success" href="'.site_url('index/goVip').'" target="_blank"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> 成为VIP</a>';
            }
            ?>
        </div>
        <?
        if ($this->canEdit){
        ?>
        <div class="alert alert-info" role="alert">
            本商户店员自助加入密码为<?=$this->myOrgInfo->field_list['enterCode']->gen_show_html();?>
        </div>
        <?
        }
        ?>
    </div>
</div>
