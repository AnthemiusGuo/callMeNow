<div>
    <ol class="breadcrumb">
        <li><a href="#"><span class='glyphicon glyphicon-home'></span> Home</a></li>
        <li><a href="#"><span class='glyphicon <?=$this->menus[$this->controller_name]['icon']?>'></span> <?=$this->menus[$this->controller_name]['name']?></a></li>
        <li class="active"><span class='glyphicon glyphicon-file'></span> <?php echo $this->dataInfo->field_list['name']->gen_show_html() ?></li>
    </ol>
</div>
<div class="row">
    <div class="col-md-6">
        <h3><?php echo $this->dataInfo->field_list['name']->gen_show_html() ?></h3>
    </div>
    <div class="col-md-6">
        <a href="<?=$this->refer?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> 返回</a>
        <?php
        if ($this->canEdit):
        ?>
        <a href="#" class="btn btn-primary" onclick="lightbox({size:'m',url:'<?=site_url($this->dataInfo->edit_link).'/'.$this->dataInfo->id?>'})" title="编辑">
                    <span class="glyphicon glyphicon-edit"></span> 编辑</a>
                 
                    <a href="#" class="btn btn-primary" onclick='reqDelete("<?=$this->dataInfo->deleteCtrl?>","<?=$this->dataInfo->deleteMethod?>","<?=$this->dataInfo->id?>")' title="删除"><span class="glyphicon glyphicon-trash"></span> 删除</a>
        <?
        endif;
        ?>
    </div>
    <div class="col-lg-12">
        <ul id="nav-crm" class="nav nav-tabs">
            <?php
            foreach ($this->sub_menus as $key => $value) :
            ?>
                <li id="nav-crm-<?php echo $key ?>" class="<?=($this->now_sub_menu==$key)?'active':'';?>"><a href="#" onclick="info_load('crm','<?php echo $key ?>')"><?php echo $value['name'] ?></a></li>
            <?php    
            endforeach;
            ?>
        </ul>
    </div>
</div>
<div id="crm_info">
    <?php
    foreach ($this->sub_menus as $key => $value) :
        $is_hidden = ($this->now_sub_menu==$key)?'':'hidden';
        echo '<div class="info-crm row '.$is_hidden.'" id="info-crm-'.$key.'"><div class="col-md-12">';
        include_once("info-crm-".$key.".php");
        echo '</div></div>';
    endforeach;
    ?>
</div>
