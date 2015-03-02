<?
$inputName = $this->editorData->build_input_name($typ);
$validates = $this->editorData->build_validator();
if ($typ==1){
    $this->editorData->default = $this->editorData->value;
}
?>
<ul class="list-group"  id="table_<?=$inputName?>">
<?
$now_data = array();
foreach ($this->editorData->datas as $item) {
    echo '<li class="list-group-item">'; 
    echo  $item['itemName']->gen_show_html()."(".$item['color']->gen_show_html().") X ".$item['meter']->gen_show_html().'米 X'.$item['price']->gen_show_html().'元 = '.$item['allPrice']->gen_show_html().'元';
    echo '</li>'; 
    $now_data[$item['_id']->gen_show_html()] = array($item['itemName']->value,$item['color']->value,$item['meter']->value,$item['price']->value,$item['allPrice']->value);
}
?>
</ul>
<table class="table table-bordered">
    <tr>
        <td class="td_title"><?=$this->editorData->dataModel->field_list['itemName']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$this->editorData->dataModel->field_list['itemName']->gen_editor($this->editorData->editor_typ,false)?></td>
        <td class="td_title"><?=$this->editorData->dataModel->field_list['color']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$this->editorData->dataModel->field_list['color']->gen_editor($this->editorData->editor_typ,false)?></td>
    </tr>
    <tr>
        <td class="td_title"><?=$this->editorData->dataModel->field_list['meter']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$this->editorData->dataModel->field_list['meter']->gen_editor($this->editorData->editor_typ,false)?></td>
        <td class="td_title"><?=$this->editorData->dataModel->field_list['price']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$this->editorData->dataModel->field_list['price']->gen_editor($this->editorData->editor_typ,false)?></td>
    </tr>
    <tr>
        <td class="td_title"><?=$this->editorData->dataModel->field_list['allPrice']->gen_editor_show_name()?></td>
        <td class="td_data"><?=$this->editorData->dataModel->field_list['allPrice']->gen_editor($this->editorData->editor_typ,false)?></td>
        <td colspan="2"><button type="button" class="btn btn-success" onclick="addSubLine(<?=$this->editorData->editor_typ?>,'<?=$inputName?>')">增加</button></td>
    </tr>
</table>

<input id="<?=$inputName?>" name="<?=$inputName?>" type="hidden" value="<?=$this->editorData->default?>"/>
<script>
var table_item_vars = <?=json_encode($this->editorData->listFields)?>;
var table_item_must_vars = {itemName:true,color:false,meter:true,price:true,allPrice:true};
var table_item_template = '<li class="list-group-item"><span>{itemName} ({color}) X{meter}米 X{price}元 = {allPrice}元 </span> <a href="javascript:void(0);" onclick="removeSubLine(\'<?=$inputName?>\',\'{_id}\')"><span class="glyphicon glyphicon-remove pull-right"></span></a></li>';
<?
if (count($now_data==0)){
?>
var table_all_data = {};
<?
} else {
?>
var table_all_data = <?=json_encode($now_data)?>;
<?
}
if ($this->editorData->editor_typ==0){
?>
var price_id_pre = 'creator_';
<?
} else {
?>
var price_id_pre = 'modifier_';
<?
}
?>
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