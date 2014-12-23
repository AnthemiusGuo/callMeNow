<!-- Modal -->
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header logo-small">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->title_create ?></h4>
        </div>
        <div class="modal-body">
            <form role="form" id="editForm">
                <input type="hidden" id="modify_id" name="modify_id" value="<?=$this->dataInfo->field_list['id']->value?>"/>
                <?php echo $contents; ?>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" onclick="reqEdit('<?=$this->createUrlC?>','<?=$this->createUrlF?>',reqEditFields,editFormValidator)">保存</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
var editFormValidator = $("#editForm").validate();
var reqEditFields = [];
<?php
foreach ($this->createPostFields as $key => $value) {
    echo 'reqEditFields.push({name:"'.$value.'",type:"'.$this->dataInfo->field_list[$value]->typ.'"});';
}
?>
</script>