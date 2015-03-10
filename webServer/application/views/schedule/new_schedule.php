<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['name']->gen_editor_show_name(); ?></td>
                <td colspan="3">
                    <?php echo $this->dataInfo->field_list['name']->gen_editor($this->editor_typ) ?>    
                </td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['desc']->gen_editor_show_name(); ?></td>
                <td colspan="3">
                        <?php echo $this->dataInfo->field_list['desc']->gen_editor($this->editor_typ) ?>    
                </td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['isWholeDay']->gen_editor_show_name(); ?></td>
                <td>
                    <?php echo $this->dataInfo->field_list['isWholeDay']->gen_editor($this->editor_typ) ?>    
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['beginTS']->gen_editor_show_name(); ?></td>
                <td class="td_data"><?php echo $this->dataInfo->field_list['beginTS']->gen_editor($this->editor_typ) ?></td>
                <td class="td_title"><?php echo $this->dataInfo->field_list['endTS']->gen_editor_show_name(); ?></td>
                <td class="td_data"><?php echo $this->dataInfo->field_list['endTS']->gen_editor($this->editor_typ) ?></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['projectId']->gen_editor_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['projectId']->gen_editor($this->editor_typ) ?></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['place']->gen_editor_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['place']->gen_editor($this->editor_typ) ?></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['userInCharge']->gen_editor_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['userInCharge']->gen_editor($this->editor_typ) ?></td>
                <td class="td_title"><?php echo $this->dataInfo->field_list['userInvolved']->gen_editor_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['userInvolved']->gen_editor($this->editor_typ) ?></td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        
    </div>
</div>