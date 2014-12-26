<div class="row">
    <div>
        <ol class="breadcrumb">
            <li><a href="#"><span class='glyphicon glyphicon-home'></span> Home</a></li>
            <li><a href="#"><span class='glyphicon <?=$this->menus[$this->controller_name]['icon']?>'></span> <?=$this->menus[$this->controller_name]['name']?></a></li>
            <li class="active"><span class='glyphicon glyphicon-file'></span> <?php echo $this->dataInfo->field_list['name']->gen_show_html() ?></li>
        </ol>
    </div>
    <div class="col-lg-9">
        <h3><?php echo $this->dataInfo->field_list['name']->gen_show_html() ?></h3>
    </div>
    <div class="col-lg-3">
        <a href="<?=$this->refer?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> 返回</a>
        <?php
        if ($this->canEdit):
        ?>
        <a class="dropdown-toggle btn btn-primary" data-toggle="dropdown" href="#">
                 <span class="glyphicon glyphicon-wrench"></span> 操作 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                        
                        <li>
                            <a href="#" onclick="lightbox({size:'m',url:'<?=site_url($this->dataInfo->edit_link).'/'.$this->dataInfo->id?>'})" title="编辑">
                            <span class="glyphicon glyphicon-edit"></span> 编辑</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" onclick='reqDelete("<?=$this->dataInfo->deleteCtrl?>","<?=$this->dataInfo->deleteMethod?>",<?=$this->dataInfo->id?>)' title="删除"><span class="glyphicon glyphicon-trash"></span> 删除</a></li>
                </ul>
        <?
        endif;
        ?>
    </div>
    <div class="col-lg-12">
        <ul id="nav-crm" class="nav nav-tabs">
             <li id="nav-crm-mini_info" class="active"><a href="#" onclick="info_load('crm','mini_info')">信息</a></li>
                <?php
                foreach ($this->sub_menus as $key => $value) :
                ?>
                    <li id="nav-crm-<?php echo $key ?>"><a href="#" onclick="info_load('crm','<?php echo $key ?>')"><?php echo $value['name'] ?></a></li>
                <?php    
                endforeach;
                ?>
        </ul>
    </div>
    <div class="clearfix"></div>
    <div id="project_info" class="col-lg-12">
        <?php 
        include_once("info-crm-info.php");
        ?>

        <?php
        foreach ($this->sub_menus as $key => $value) :
            include_once("info-crm-".$key.".php");
        endforeach;
        ?>
    </div>
    <div class="clearfix"><hr/></div>
    <div class="note note-success text-left">
        <h5>条目编辑历史</h5>
        <dl class="editor_info">
            <dt><?php echo $this->dataInfo->field_list['createUid']->gen_show_name(); ?></dt>
            <dd><?php echo $this->dataInfo->field_list['createUid']->gen_show_html() ?></dd>
            <dt><?php echo $this->dataInfo->field_list['createTS']->gen_show_name(); ?></dt>
            <dd><?php echo $this->dataInfo->field_list['createTS']->gen_show_html() ?></dd>
            <dt><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_name(); ?></dt>
            <dd><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_html() ?></dd>
            <dt><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_name(); ?></dt>
            <dd><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_html() ?></dd>
        </dl>
        <div class="clearfix"></div>
    </div>
</div>

<script type="text/javascript">
    var now_page = 'crm_info';
    $(function(){
        info_load('crm','<?=$this->begin_sub_menu?>');
    });
</script>