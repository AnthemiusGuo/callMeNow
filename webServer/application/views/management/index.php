<div>
<?php
include_once(APPPATH."views/common/bread.php");
?>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <span class="glyphicon glyphicon-shopping-cart"></span>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->any_book['totalGetting']?>
                </div>
                <div class="desc">
                    本月订单金额
                </div>
            </div>
            <a class="more" href="<?=site_url('crm/order')?>">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <span class="glyphicon glyphicon-shopping-cart"></span>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->any_send['totalGetting']?>
                </div>
                <div class="desc">
                    本月已发货金额
                </div>
            </div>
            <a class="more" href="<?=site_url('crm/send')?>">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <span class="glyphicon glyphicon-usd"></span>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->any_pay['PayIn']?>/<?=$this->any_pay['PayOut']?>
                </div>
                <div class="desc">
                    本月进账/出帐
                </div>
            </div>
            <a class="more" href="<?=site_url('crm/pay')?>">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <span class="glyphicon glyphicon-credit-card"></span>
            </div>
            <div class="details">
                <div class="number">
                    <?=$this->any_crm['needPayIn']?>/<?=$this->any_crm['needPayOut']?>
                </div>
                <div class="desc">
                    迄今待收/待付
                </div>
            </div>
            <a class="more" href="<?=site_url('crm/index')?>">
            查看详情<span class="glyphicon glyphicon-list"></span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    您的商户信息
                </div>
                <div class="tools">
                    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('org/editOrg/')?>'})"><span class="glyphicon glyphicon-edit"></span> 编辑</a>

                </div>
            </div>
            <div class="portlet-body">
                <?=$this->myOrgInfo->buildShowCardAdmin()?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-phone-alt"></span>
                    最新电话记录
                </div>
                <div class="tools">
                    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('crm/contactList')?>'})"><span class="glyphicon glyphicon-list"></span> 详情</a>

                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped simplePagerContainer">
                    <thead>
                        <tr>
                            <?
                            foreach ($this->contactList->build_short_list_titles() as $key_names):
                            ?>
                                <th>
                                    <?php
                                    echo $this->contactList->dataModel[$key_names]->gen_show_name();;
                                    ?>
                                </th>
                            <?
                            endforeach;
                            ?>
                        </tr>
                    </thead>
                    <tbody class="table-paged">
                        <?php
                        $i = 1;
                        foreach($this->contactList->record_list as  $this_record): ?>
                            <tr>

                                <?
                                foreach ($this->contactList->build_short_list_titles() as $key_names):
                                ?>
                                    <td>
                                        <?php
                                        if ($this_record->field_list[$key_names]->is_title):
                                            if ($this->contactList->is_lightbox):
                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                            else :
                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                            endif;
                                        elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                            echo $this_record->field_list[$key_names]->gen_list_html();
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
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-list-alt"></span>订单管理
                </div>
                <div class="tools">
                    <a href="#" onclick="lightbox({size:'m',url:'<?=site_url('crm/order')?>'})"><span class="glyphicon glyphicon-list"></span> 详情</a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#overview_1" data-toggle="tab">
                                未发货订单</a>
                        </li>
                        <li>
                            <a href="#overview_2" data-toggle="tab">
                                最近订单</a>
                        </li>
                        <li>
                            <a href="#overview_3" data-toggle="tab">
                                最近发货记录</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="overview_1">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->bookNoSendList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->bookNoSendList->dataModel[$key_names]->gen_show_name();;
                                                    ?>
                                                </th>
                                            <?
                                            endforeach;
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody class="table-paged">
                                        <?php
                                        $i = 1;
                                        foreach($this->bookNoSendList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->bookNoSendList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->bookNoSendList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            endif;
                                                        elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                                            echo $this_record->field_list[$key_names]->gen_list_html();
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
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_2">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->bookList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->bookList->dataModel[$key_names]->gen_show_name();;
                                                    ?>
                                                </th>
                                            <?
                                            endforeach;
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody class="table-paged">
                                        <?php
                                        $i = 1;
                                        foreach($this->bookList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->bookList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->bookList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            endif;
                                                        elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                                            echo $this_record->field_list[$key_names]->gen_list_html();
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
                            </div>
                        </div>
                        <div class="tab-pane" id="overview_3">
                            <div class="table-responsive">
                                <table class="table table-striped simplePagerContainer">
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($this->sendList->build_list_titles() as $key_names):
                                            ?>
                                                <th>
                                                    <?php
                                                    echo $this->sendList->dataModel[$key_names]->gen_show_name();;
                                                    ?>
                                                </th>
                                            <?
                                            endforeach;
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody class="table-paged">
                                        <?php
                                        $i = 1;
                                        foreach($this->sendList->record_list as  $this_record): ?>
                                            <tr>

                                                <?
                                                foreach ($this->sendList->build_list_titles() as $key_names):
                                                ?>
                                                    <td>
                                                        <?php
                                                        if ($this_record->field_list[$key_names]->is_title):
                                                            if ($this->sendList->is_lightbox):
                                                                echo '<a href="javascript:void(0)" onclick="lightbox({size:\'m\',url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            else :
                                                                echo '<a href="'.site_url($this_record->info_link.$this_record->id).'">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
                                                            endif;
                                                        elseif ($this_record->field_list[$key_names]->typ=="Field_text"):
                                                            echo $this_record->field_list[$key_names]->gen_list_html();
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="portlet box red-sunglo">
            <div class="portlet-title">
                <div class="caption">
                    <span class="glyphicon glyphicon-globe"></span>
                    商户留言板
                </div>
            </div>
            <div class="portlet-body">
            </div>
        </div>
    </div> -->
</div>
