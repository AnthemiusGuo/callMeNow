<table class="table">
    <tbody>
        <tr>
            <th class="td_title">
                <?php echo $this->dataInfo->field_list['name']->gen_show_name(); ?>
            </th>
            <th class="td_data">
                <?php echo $this->dataInfo->field_list['name']->gen_show_html() ?>          
            </th>
            <th class="td_title">
                <?php echo $this->dataInfo->field_list['province']->gen_show_name(); ?>
            </th>
            <th class="td_data">
                <?php echo $this->dataInfo->field_list['province']->gen_show_html() ?>         
            </th>
            
        </tr>
        <tr>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['typ']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['typ']->gen_show_html() ?>          
            </td>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['status']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['status']->gen_show_html() ?>          
            </td>
        </tr>
        <tr>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['needPayIn']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['needPayIn']->gen_show_html() ?>          
            </td>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['needPayOut']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['needPayOut']->gen_show_html() ?>          
            </td>
        </tr>
        <tr>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['mainContactorName']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['mainContactorName']->gen_show_html() ?>          
            </td>
            <td class="td_title">
            </td>
            <td class="td_data">
            </td>
        </tr>
        <tr>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['mainContactorType']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['mainContactorType']->gen_show_html() ?>          
            </td>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['mainContactorNum']->gen_show_name(); ?>
            </td>
            <td class="td_data">
                <?php echo $this->dataInfo->field_list['mainContactorNum']->gen_show_html() ?>          
            </td>
        </tr>
        <tr>
            <td class="td_title">
                联系人
            </td>
            <td colspan="3">
                <ul class="list-group">
                <?php 
                $i = 1;
                foreach($this->contactorList->record_list as  $this_record): 
                    echo '<li class="list-group-item">'.$this_record->gen_brief_html().'</li>';       
                    $i++;
                endforeach; ?>  
                </ul>
                <a href="javascript:void" onclick="info_load('crm','contactors')">编辑</a>     
            </td>
        </tr>
        <tr>
            <td class="td_title">
                <?php echo $this->dataInfo->field_list['comments']->gen_show_name(); ?>
            </td>
            <td colspan="3">
                <?php echo $this->dataInfo->field_list['comments']->gen_show_html() ?>          
            </td>
        </tr>
    </tbody>
</table>