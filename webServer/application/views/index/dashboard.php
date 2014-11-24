<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<?php
include_once("dashboardHelper.php");
?>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-globe"></span>
                    已加入的组织</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <table class="table table-striped  simplePagerContainer">
                    <thead>
                        <tr>
                            <?
                            foreach ($this->listAttendInfo->list_titles as $key_names):
                            ?>
                                <th>
                                    <?php
                                    echo $this->listAttendInfo->dataModel[$key_names]->gen_show_name();;
                                    ?>
                                </th>
                            <?
                            endforeach;
                            ?>
                        </tr>
                    </thead>
                    <tbody id="org-table-paged" class="table-paged">
                        <?php 
                        $i = 1;
                        foreach($this->listAttendInfo->record_list as  $this_record): ?>
                            <tr>
                                <?
                                foreach ($this->listAttendInfo->list_titles as $key_names):
                                ?>
                                    <td>
                                        <?php
                                        if ($this_record->field_list[$key_names]->typ=="Field_title"):
                                        
                                            echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this->org_info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                        elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                            echo $this_record->field_list[$key_names]->gen_list_html(8);
                                        else :                         
                                            echo $this_record->field_list[$key_names]->gen_list_html();

                                        endif;
                                        ?>
                                    </td>
                                <?
                                endforeach;
                                ?>
                            </tr>        
                        <?php $i++;
                        endforeach; ?>
                        
                    </tbody>
                </table>
                <div id="org_pager">

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-data">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-th-list"></span>
                    我正参与的项目</h3>
            </div>
            <div class="panel-body dashboard-panel">
                <dl>
                    <?php 
                    foreach($this->project_list->record_list as  $orgId=>$this_org): 
                    ?>
                    <dt><?=$this->orgList[$orgId]['name']?></dt>
                    <dd>
                        <ul>
                        <?php 
                        foreach($this_org as  $this_record):
                            echo '<li><a href="'.site_url('project/info/'.$this_record->field_list['id']->value).'">';
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
    <div class="col-lg-6">
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
    <div class="col-lg-6">
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