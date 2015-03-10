<div class="row">
    <div class="col-lg-12">
        <table class="table">
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['name']->gen_show_name(); ?></td>
                <td colspan="3">
                    <?php echo $this->dataInfo->field_list['name']->gen_show_html() ?>    
                </td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['desc']->gen_show_name(); ?></td>
                <td colspan="3">
                    <div class="panel panel-data">
                        <div class="panel-body"> 
                        <p>
                        <?php echo $this->dataInfo->field_list['desc']->gen_show_html() ?>    
                        </p>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['isWholeDay']->gen_show_name(); ?></td>
                <td colspan="3">
                    <?php echo $this->dataInfo->field_list['isWholeDay']->gen_show_html() ?>    
                </td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['beginTS']->gen_show_name(); ?></td>
                <td class="td_data"><?php echo $this->dataInfo->field_list['beginTS']->gen_show_html() ?></td>
                <td class="td_title"><?php echo $this->dataInfo->field_list['endTS']->gen_show_name(); ?></td>
                <td class="td_data"><?php echo $this->dataInfo->field_list['endTS']->gen_show_html() ?></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['projectId']->gen_show_name(); ?></td>
                <td colspan="3"><?php echo $this->dataInfo->field_list['projectId']->gen_show_html() ?></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['place']->gen_show_name(); ?></td>
                <td colspan="3"><?php echo $this->dataInfo->field_list['place']->gen_show_html() ?></td>
            </tr>
            <tr>
                <td class="td_title"><?php echo $this->dataInfo->field_list['userInCharge']->gen_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['userInCharge']->gen_show_html() ?></td>
                <td class="td_title"><?php echo $this->dataInfo->field_list['userInvolved']->gen_show_name(); ?></td>
                <td><?php echo $this->dataInfo->field_list['userInvolved']->gen_show_html() ?></td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        
    </div>
</div>