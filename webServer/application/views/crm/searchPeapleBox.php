<ul class="list-search search-ul-paged">
	<?
    $i = 1;
    $targat_js_data = array();
    foreach($this->listInfo->record_list as  $this_record): 
        $targat_js_data[$this_record->field_list['id']->value] = array(
            'id' => $this_record->field_list['id']->value, 
            'name' => $this_record->field_list["name"]->value
        );
    ?>
        <li class="list-search-item" id="list_relate_li_<?=$this_record->id?>" onclick="miniInputBoxAdd(<?=$this_record->id?>)">
            <?php echo $this_record->field_list["name"]->gen_list_html();?>  <a><span class="pull-right glyphicon glyphicon-plus"><span></a>
        </li>
    <?
    endforeach;
    ?>
	
</ul>
<script>
	$(".search-ul-paged").quickPager({pageSize:7});
    relate_datas = <?=json_encode($targat_js_data)?>;
    
</script>