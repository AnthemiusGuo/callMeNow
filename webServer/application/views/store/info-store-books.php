<?php
if ($this->canEdit):
?>
<div class="list-title-op">
    <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createBook/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a>
    <a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="reqDelete('crm','doDeleteBook',0)"><span class="glyphicon glyphicon-trash"></span> 批量删除</a>
</div>
<?
endif;
?>
<div>
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <th><input type="checkbox" value="" class="selectAll" data-select="crm-book-table"> 全选</th>
                <?
                foreach ($this->bookList->build_list_titles() as $key_names):
                ?>
                    <th>
                        <?php
                        echo $this->bookList->dataModel[$key_names]->gen_show_name();;
                        ?>
                    </th>
                <?
                endforeach;
                ?>
                <th>操作</th>
            </tr>
        </thead>
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->bookList->record_list as  $this_record): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="check_target[]" data-select="crm-book-table" value="<?=$this_record->field_list['_id']->gen_list_html()?>">
                    </td>
                    <?
                    foreach ($this->bookList->build_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                            elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                echo $this_record->field_list[$key_names]->gen_list_html(8);
                            else :
                                echo $this_record->field_list[$key_names]->gen_list_html();

                            endif;
                            ?>
                        </td>
                    <?
                    endforeach;
                    ?>
                    <td>
                        <?php echo $this_record->gen_list_op()?>
                    </td>
                </tr>
            <?php $i++;
            endforeach; ?>

        </tbody>
    </table>
    <div id="crm-book-list_pager">

    </div>
</div>
