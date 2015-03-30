<?php
include_once(APPPATH."views/phone/mini_search.php");
?>
<div class="row">
    <div class="col-md-12">
        <h3>来电号码：<?php echo $this->phone ?> ;<small>多个匹配联系人</small></h3>
        <table class="table table-striped">
            <tr>
                <td><?=$this->contactorList->dataModel['crmId']->gen_show_name()?></td>
                <td><?=$this->contactorList->dataModel['name']->gen_show_name()?></td>
                <td><?=$this->contactorList->dataModel['dianhua']->gen_show_name()?></td>
                <td><?=$this->contactorList->dataModel['qq']->gen_show_name()?></td>
                <td><?=$this->contactorList->dataModel['weixin']->gen_show_name()?></td>
                <td><?=$this->contactorList->dataModel['qitafangshi']->gen_show_name()?></td>
            </tr>
            <?php
            foreach ($this->contactorList->record_list as $key => $this_record){
            ?>
            <tr>
                <td><a href="<?=site_url('phone/call/'.$this->phone.'/'.$this_record->field_list['_id']->toString());?>"><?=$this_record->field_list['crmId']->gen_show_html()?></a></td>
                <td><?=$this_record->field_list['name']->gen_show_html()?></td>
                <td><?=$this_record->field_list['dianhua']->gen_show_html()?></td>
                <td><?=$this_record->field_list['qq']->gen_show_html()?></td>
                <td><?=$this_record->field_list['weixin']->gen_show_html()?></td>
                <td><?=$this_record->field_list['qitafangshi']->gen_show_html()?></td>
            </tr>
            <?
            }
            ?>

        </table>
    </div>
</div>
