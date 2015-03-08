<?
$inputName = $this->editorData->build_input_name($this->editorData->editor_typ);
$validates = $this->editorData->build_validator();
?>
<div class="relate_box_holder">
    <input id="<?=$inputName?>" name="<?=$inputName?>" class="form-control" placeholder="" type="text" value="<?=$this->editorData->default?>" >
    <div id="<?=$inputName?>_list_holder" class="relate_box_list hidden">
        <ul id="<?=$inputName?>_list" class="list-group relate_box_ul">
          
        </ul>
        <ul id="<?=$inputName?>_list_close" class="list-group relate_box_list_close">
            <li class="list-group-item">
            <span><img src="<?=static_url('images/loading.gif')?>" alt="loading" id="search_loading" class="search_loading hidden"></span>
            <button type="button" class="btn btn-default btn-sm pull-right" onclick="$('#<?=$inputName?>_list_holder').addClass('hidden');">
              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> 关闭
            </button>
            </li>
        </ul>

    </div>
</div>
<script>
    $("#<?=$inputName?>").focus(function(){
        searchbox_on_change('<?=$inputName?>','<?=$this->editorData->editorController?>','<?=$this->editorData->editorMethod . $this->editorData->searchPlus?>')}
        ).bind('input propertychange',function(){
            searchbox_on_change('<?=$inputName?>','<?=$this->editorData->editorController?>','<?=$this->editorData->editorMethod . $this->editorData->searchPlus?>')
        });
    // }).blur(function(){
    //     $("#<?=$inputName?>_list_holder").addClass('hidden');
    // })
</script>