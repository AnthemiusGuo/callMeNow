<div class="col-lg-12 col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-phone-alt"></span> 最近联系记录
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'l',url:'<?=site_url("crm/createContactHis/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a>
            </div>
        <div class="panel-body">
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <?
                foreach ($this->contactList->build_inline_list_titles() as $key_names):
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
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->contactList->record_list as  $this_record): ?>
                <tr>

                    <?
                    foreach ($this->contactList->build_inline_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
</div>
</div>

    <? if (isset($this->bookList)){
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> 最近订货记录 <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createBook/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a></div>
        <div class="panel-body">
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <?
                foreach ($this->bookList->build_inline_list_titles() as $key_names):
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
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->bookList->record_list as  $this_record): ?>
                <tr>

                    <?
                    foreach ($this->bookList->build_inline_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
</div>
</div>
    <?
    }
    ?>
    <? if (isset($this->bookInList)){
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span>
            最近向上游订货记录 <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createBookIn/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a></div>
        <div class="panel-body">
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <?
                foreach ($this->bookInList->build_inline_list_titles() as $key_names):
                ?>
                    <th>
                        <?php
                        echo $this->bookInList->dataModel[$key_names]->gen_show_name();;
                        ?>
                    </th>
                <?
                endforeach;
                ?>
            </tr>
        </thead>
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->bookInList->record_list as  $this_record): ?>
                <tr>

                    <?
                    foreach ($this->bookInList->build_inline_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
</div>
</div>
    <?
    }
    ?>
    <? if (isset($this->sendList)){
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-plane"></span> 最近发货记录 <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createSend/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a></div>
        <div class="panel-body">
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <?
                foreach ($this->sendList->build_inline_list_titles() as $key_names):
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
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->sendList->record_list as  $this_record): ?>
                <tr>

                    <?
                    foreach ($this->sendList->build_inline_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
</div>
</div>
    <?
    }
    ?>
    <? if (isset($this->payList)){
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><span class="glyphicon glyphicon-credit-card"></span> 最近付款记录
             <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="lightbox({size:'m',url:'<?=site_url("crm/createPay/".$this->id)?>'})"><span class="glyphicon glyphicon-file"></span> 新建</a></div>
        <div class="panel-body">
    <table class="table table-striped simplePagerContainer">
        <thead>
            <tr>
                <?
                foreach ($this->payList->build_inline_list_titles() as $key_names):
                ?>
                    <th>
                        <?php
                        echo $this->payList->dataModel[$key_names]->gen_show_name();;
                        ?>
                    </th>
                <?
                endforeach;
                ?>
            </tr>
        </thead>
        <tbody class="crm-book-table-paged" id="crm-book-table">
            <?php
            $i = 1;
            foreach($this->payList->record_list as  $this_record): ?>
                <tr>

                    <?
                    foreach ($this->payList->build_inline_list_titles() as $key_names):
                    ?>
                        <td>
                            <?php
                            if ($this_record->field_list[$key_names]->is_title):

                                echo '<a href="javascript:void(0)" onclick="lightbox({url:\''. site_url($this_record->info_link.$this_record->id).'\'})">'.$this_record->field_list[$key_names]->gen_list_html().'</a>';
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
</div>
</div>
    <?
    }
    ?>
</div>
</div>
