<table class="table table-striped">
    <?php
    foreach ($this->showNeedFields as $key => $value) {
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
            <td class="td_title"><?php echo $this->dataInfo->field_list[$v]->gen_show_name(); ?></td>
            <td <?=($colspan==0)?'class="td_data"':'colspan="'.$colspan.'"'?> >
                    <?php echo $this->dataInfo->field_list[$v]->gen_show_html() ?>
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
