<?
$editorData = $this->editorData;
$inputName = $editorData->build_input_name($editorData->editor_typ);
$validates = $editorData->build_validator();
if ($typ==1){
    $editorData->default = $editorData->value;
}
?>
<ul class="list-group"  id="table_<?=$inputName?>">
<?
$now_data = array();
$now_data_counter = 0;
foreach ($editorData->datas as $item) {
    $now_data_counter ++;
    $now_data[$item->field_list['_id']->toString()] = array('_id'=>$item->field_list['_id']->toString(),
                                                            'itemName'=>$item->field_list['itemName']->gen_show_value(),
                                                            'color'=>$item->field_list['color']->gen_show_value(),
                                                            'meter'=>$item->field_list['meter']->value,
                                                            'price'=>$item->field_list['price']->value,
                                                            'allPrice'=>$item->field_list['allPrice']->value);
}
// var_dump($now_data);
?>
</ul>
<table class="table table-bordered">
    <tr>
        <td class="td_title"><?=$editorData->dataModel->field_list['itemName']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$editorData->dataModel->field_list['itemName']->gen_editor($editorData->editor_typ,false)?></td>
        <td class="td_title">
        <?=$editorData->dataModel->field_list['color']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$editorData->dataModel->field_list['color']->gen_editor($editorData->editor_typ,false)?></td>
    </tr>
    <tr>
        <td class="td_title"><?=$editorData->dataModel->field_list['meter']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$editorData->dataModel->field_list['meter']->gen_editor($editorData->editor_typ,false)?></td>
        <td class="td_title"><?=$editorData->dataModel->field_list['price']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$editorData->dataModel->field_list['price']->gen_editor($editorData->editor_typ,false)?></td>
    </tr>
    <tr>
        <td class="td_title"><?=$editorData->dataModel->field_list['allPrice']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$editorData->dataModel->field_list['allPrice']->gen_editor($editorData->editor_typ,false)?></td>
        <td colspan="2"><button type="button" class="btn btn-success" onclick="addSubLine(<?=$editorData->editor_typ?>,'<?=$inputName?>')">增加</button></td>
    </tr>
</table>

<input id="<?=$inputName?>" name="<?=$inputName?>" type="hidden" value="<?=$editorData->default?>"/>
<script>
var table_item_vars = <?=json_encode($editorData->listFields)?>;
var table_item_must_vars = {itemName:true,color:false,meter:true,price:true,allPrice:true};
var table_item_template = '<li class="list-group-item"><span>{itemName} ({color}) X{meter}米 X{price}元 = {allPrice}元 </span> <a href="javascript:void(0);" onclick="removeSubLine(\'<?=$inputName?>\',\'{_id}\')"><span class="glyphicon glyphicon-remove pull-right"></span></a></li>';
<?
if ($now_data_counter<=0){
?>
var table_all_data = {};
<?
} else {
?>
var table_all_data = <?=json_encode($now_data)?>;
<?
}
if ($editorData->editor_typ==0){
?>
var price_id_pre = 'creator_';
<?
} else {
?>
var price_id_pre = 'modify_';
<?
}
?>
resetTable('<?=$inputName?>');
var allPriceId = "#"+price_id_pre+'allPrice';
var meterId = "#"+price_id_pre+'meter';
var priceId = "#"+price_id_pre+'price';
$(allPriceId).focus(function(){
    var meterVal = $(meterId).val();
    var priceVal = $(priceId).val();
    var allPriceVal = meterVal*priceVal;
    if (allPriceVal>0){
        $(allPriceId).val(allPriceVal);
    }
});
$(meterId).blur(function(){
    var meterVal = $(meterId).val();
    var priceVal = $(priceId).val();
    var allPriceVal = meterVal*priceVal;
    if (allPriceVal>0){
        $(allPriceId).val(allPriceVal);
    }
});
$(priceId).blur(function(){
    var meterVal = $(meterId).val();
    var priceVal = $(priceId).val();
    var allPriceVal = meterVal*priceVal;
    if (allPriceVal>0){
        $(allPriceId).val(allPriceVal);
    }
});
</script>
