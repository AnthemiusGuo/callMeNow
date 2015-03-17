<ul class='breadcrumb breadcrumb-with-search'>
    <li><a href='<?=site_url()?>'><span class='glyphicon glyphicon-home'></span> Home</a></li>
    <li><a href='<?=site_url($this->controller_name.'/index')?>'><span class='glyphicon <?=$this->menus[$this->controller_name]['icon']?>'></span> <?=$this->menus[$this->controller_name]['name']?></a></li>
    <li class='active'><span class='glyphicon glyphicon-circle-arrow-right'></span> <?=$this->menus[$this->controller_name]['menu_array'][$this->method_name]['name']?></a></li>

    <li class="pull-right search-aera-inline">
        <div class="">
            <form class="form-inline">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">快捷搜索</span>
                    <input type="text" id="callPhoneSearch" name="callPhoneSearch" class="form-control input-sm" placeholder="请输入电话\QQ 或名字" value="">
                    <div class="input-group-btn">
                        <a class="btn btn-primary btn-sm" id="btnQuickSearch" onclick="phoneSearch('callPhoneSearch')"><span class="glyphicon glyphicon-search"></span></a>
                    </div>

                </div>
            </form>
        </div>
    </li>
</ul>
