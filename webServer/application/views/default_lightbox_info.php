<!-- Modal -->
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header logo-small">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><? echo $this->infoTitle; ?>
            <!--<div class="btn-group pull-right margin-right-20">
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span>编辑</button>
                <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span>删除</button>
            </div>-->
            </h4>
            
        </div>
        <div class="modal-body">
            <?php echo $contents; ?>
            <hr/>
            <div id="light_comments">
            </div>
        </div>
        <div class="modal-footer">
            <?php
            if (isset($this->dataInfo->field_list['createUid'])):

            ?>
            <div class="note note-success text-left">
                <h5>条目编辑历史</h5>
                <dl class="editor_info">
                    <dt><?php echo $this->dataInfo->field_list['createUid']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['createUid']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['createTS']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['createTS']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['lastModifyUid']->gen_show_html() ?></dd>
                    <dt><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_name(); ?></dt>
                    <dd><?php echo $this->dataInfo->field_list['lastModifyTS']->gen_show_html() ?></dd>
                </dl>
                <div class="clearfix"></div>
            </div>
            <?
            endif;
            ?>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
$('.tooltips').powerTip({offset:20});
<?
if (in_array($this->controller_name,array('crm','document','donation','task','schedule'))) {
?>

var comments_url = req_url_template.str_supplant({ctrller:'comments',action:'<?="index/".$this->controller_name."/".$this->id?>'});
var comment_id = "#light_comments";
$(comment_id).html('载入评论中').load(comments_url);
<?
}
?>

</script>