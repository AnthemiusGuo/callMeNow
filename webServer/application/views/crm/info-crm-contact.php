<?
if ($this->need_plus!=""){
    include_once(APPPATH."views/".$this->need_plus.".php");
}
?>
<div class="info-crm row hidden" id="info-crm-contact">
    <?php
    if ($this->canEdit):
    ?>
    <div class="col-lg-12 list-title-op">
        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createContract/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a>
        
    </div>
    <?
    endif;
    ?>
    <div class="col-lg-12">
        <table class="table table-striped simplePagerContainer">
            <thead>
                <tr>
                    
                    <?
                    foreach ($this->contactList->build_list_titles() as $key_names):
                    ?>
                        <th>
                            <?php
                            echo $this->contactList->dataModel[$key_names]->gen_show_name();;
                            ?>
                        </th>
                    <?
                    endforeach;
                    ?>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody class="table-paged">
                <?php 
                $i = 1;
                foreach($this->contactList->record_list as  $this_record): ?>
                    <tr>
                        
                        <?
                        foreach ($this->contactList->build_list_titles() as $key_names):
                        ?>
                            <td>
                                <?php
                                if ($this_record->field_list[$key_names]->typ=="Field_title" || $this_record->field_list[$key_names]->is_title):
                                
                                    echo $this_record->gen_url($key_names,$this->force_lightbox,$this->info_link);
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

        <div id="main_pager">

        </div>
        <?php
        if (count($this->contactList->record_list)==0):
        ?>
            <div class="no-data-large">
                没有相关记录
            </div>
        <?
        endif;
        ?>
    </div>
</div>
<script>
    
    
</script>