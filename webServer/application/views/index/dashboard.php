<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<?php
include_once("dashboardHelper.php");
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-phone-alt"></span>
                    来电快捷输入</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <form role="form" action="<?=site_url("index/doLogin")?>" method="post">

                    <h3 class="form-title">请输入来电号码</h3>
                    <div class="form-group">
                        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                        <label class="control-label visible-ie8 visible-ie9">来电号码</label>
                        <div class="input-icon">
                            <span class="glyphicon glyphicon-ok-circle"></span>
                            <input class="form-control placeholder-no-fix" type="text" placeholder="来电号码" id="enterCode" name="enterCode">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        
                            <button type="button" class="btn green-meadow pull-right" onclick="req_login()">
                            查询 <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-globe"></span>
                    您的商户信息</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <?=$this->myOrgInfo->buildCardShowFields()?>                
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-list-alt"></span>
                    任务</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <dl>
                    <?php 
                    $configNames = array('今日任务','本周任务','本月任务','未来任务');
                    foreach($this->task_list->record_list as  $key=>$value): 
                    ?>
                    <dt><?=$configNames[$key]?></dt>
                    <dd>
                        <ul>
                        <?php 
                        if (count($value)==0) {
                            echo "无任务";
                        }
                        foreach($value as  $this_record):
                            echo '<li><a href="javascript:void(0)" onclick="lightbox({url:\''.site_url('task/info/'.$this_record->field_list['id']->value).'\'})">';
                            echo $this_record->field_list['name']->gen_show_html();
                            echo '</a></li>';
                        endforeach;
                        ?>
                        </ul>
                    </dd>
                    <?
                    endforeach;
                    ?>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-calendar"></span>
                    日程</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <dl>
                    <?php 
                    foreach($this->schedule_list->record_list as  $key=>$value): 
                    ?>
                    <dt><?=$key?></dt>
                    <dd>
                        <ul>
                        <?php 
                        foreach($value as  $this_record):
                            echo '<li><a href="javascript:void(0)" onclick="lightbox({url:\''.site_url('schedule/info/'.$this_record->field_list['id']->value).'\'})">';
                            echo $this_record->field_list['name']->gen_show_html();
                            echo $this_record->field_list['name']->gen_show_html();
                            echo ' , ';
                            echo $this_record->field_list['place']->gen_show_html();
                            echo '</a></li>';
                        endforeach;
                        ?>
                        </ul>
                    </dd>
                    <?
                    endforeach;
                    ?>
                </dl>
            </div>
        </div>
    </div>
</div>