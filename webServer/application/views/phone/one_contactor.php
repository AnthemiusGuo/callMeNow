<?php
include_once(APPPATH."views/phone/mini_search.php");
?>
<div class="row">
    <div class="col-md-12">
        <h3><?php echo $this->phone ?> : <?=$this->contactorInfo->field_list['name']->gen_show_value()?>
            <small><?=$this->contactorInfo->field_list['crmId']->gen_show_value()?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">商户信息
                <a href="#" class="btn btn-success btn-sm" onclick="lightbox({size:'m',url:'<?=site_url($this->crmInfo->edit_link).'/'.$this->id?>'})" title="编辑">
                        <span class="glyphicon glyphicon-edit"></span> 编辑</a>
            </div>
            <div class="panel-body">

                <table class="table table-striped">
                    <?php
                    $showNeedFields = $this->crmInfo->buildBriefShowFields();
                    foreach ($showNeedFields as $key => $value) {

                    ?>
                    <tr>
                        <?php
                        $colspan = 0;
                        if (count($value)==1){
                            $colspan = 3;
                        }
                        foreach ($value as $k => $v) {
                            if ($v=="null") {
                        ?>
                            <td class="td_title"></td><td class="td_data"></td>
                        <?
                            } else {
                        ?>
                            <td class="td_title"><?php echo $this->crmInfo->field_list[$v]->gen_show_name(); ?></td>
                            <td <?=($colspan==0)?'class="td_data"':'colspan="'.$colspan.'"'?> >
                                    <?php echo $this->crmInfo->field_list[$v]->gen_show_html() ?>
                            </td>
                        <?
                            }
                        ?>

                        <?
                        }
                        ?>
                    </tr>
                    <?
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">联系人信息
                <a href="#" class="btn btn-success btn-sm" onclick="lightbox({size:'m',url:'<?=site_url($this->contactorInfo->edit_link).'/'.$this->contactorInfo->id?>'})" title="编辑">
                        <span class="glyphicon glyphicon-edit"></span> 编辑</a>
            </div>
            <div class="panel-body">
        <table class="table table-striped">
            <?php
            $showNeedFields = $this->contactorInfo->buildDetailShowFields();
            foreach ($showNeedFields as $key => $value) {
            ?>
            <tr>
                <?php
                $colspan = 0;
                if (count($value)==1){
                    $colspan = 3;
                }
                foreach ($value as $k => $v) {
                    if ($v=="null") {
                ?>
                    <td class="td_title"></td><td class="td_data"></td>
                <?
                    } else {
                ?>
                    <td class="td_title"><?php echo $this->contactorInfo->field_list[$v]->gen_show_name(); ?></td>
                    <td <?=($colspan==0)?'class="td_data"':'colspan="'.$colspan.'"'?> >
                            <?php echo $this->contactorInfo->field_list[$v]->gen_show_html() ?>
                    </td>
                <?
                    }
                ?>

                <?
                }
                ?>
            </tr>
            <?
            }
            ?>

                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <ul id="nav-phone" class="nav nav-tabs">
            <li id="nav-phone-info" class="active"><a href="#" onclick="info_load('phone','info')">基本信息</a></li>
            <!-- <li id="nav-phone-input" ><a href="#" onclick="info_load('phone','input')">来电快捷录入</a></li> -->
        </ul>
    </div>
</div>
<div id="phone_info">
    <div class="info-phone row " id="info-phone-info">
        <?php
        include_once(APPPATH."views/phone/info.php");
        ?>
    </div>
    <div class="row info-phone hidden" id="info-phone-input">

        <?php
        // include_once(APPPATH."views/phone/create.php");
        ?>
    </div>
</div>
