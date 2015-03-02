<ul class="list-group search-ul-paged">
	<?
    $i = 1;
    foreach($this->listInfo->record_list as  $this_record): 
    ?>
        <li class="list-group-item">
            <?php echo $this_record->field_list["name"]->gen_list_html();?>  <a><span class="pull-right glyphicon glyphicon-circle-arrow-right"><span></a>
        </li>
    <?
    endforeach;
    ?>
	
</ul>
<script>
	$(".search-ul-paged").quickPager({pageSize:7});
</script>