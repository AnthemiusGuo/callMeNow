<div class="col-lg-12 col-md-12">
    <form role="form" id="createForm" novalidate="novalidate">
        <div class="panel panel-default">
            <div class="panel-heading">

            </div>
            <div class="panel-body">
                <input id="creator_crmId" name="creator_crmId" type="hidden" value="54fd0454511dee9a010041bf">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4"><span class="glyphicon glyphicon-phone-alt"></span>联系记录</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td_title">
                                <?php echo $this->contactModel->field_list['typ']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->contactModel->field_list['typ']->gen_editor(0) ?>
                            </td>

                            <td class="td_title">
                                <?php echo $this->contactModel->field_list['contactMethod']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->contactModel->field_list['contactMethod']->gen_editor(0) ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="td_title">
                                <?php echo $this->contactModel->field_list['desc']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data" colspan="3">
                                <?php echo $this->contactModel->field_list['desc']->gen_editor(0) ?>
                            </td>


                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4"><span class="glyphicon glyphicon-phone-alt"></span> 订货信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td_title">
                                <?php echo $this->bookModel->field_list['items']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data" colspan="3">
                                <?php echo $this->bookModel->field_list['items']->gen_editor(0) ?>
                            </td>


                        </tr>
                        <tr>
                            <td class="td_title">
                                <?php echo $this->bookModel->field_list['endTS']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->bookModel->field_list['endTS']->gen_editor(0) ?>
                            </td>

                            <td class="td_title">
                                <?php echo $this->bookModel->field_list['totalGetting']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->bookModel->field_list['totalGetting']->gen_editor(0) ?>
                            </td>

                        </tr>

                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4"><span class="glyphicon glyphicon-phone-alt"></span> 收付款信息</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td_title">
                                <?php echo $this-> payModel->field_list['typ']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->payModel->field_list['typ']->gen_editor(0) ?>
                            </td>
                            <td class="td_title">
                                <?php echo $this-> payModel->field_list['money']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->payModel->field_list['money']->gen_editor(0) ?>
                            </td>

                        </tr>
                        <tr>
                            <td class="td_title">
                                <?php echo $this->payModel->field_list['method']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->payModel->field_list['method']->gen_editor(0) ?>
                            </td>

                            <td class="td_title">
                                <?php echo $this->payModel->field_list['status']->gen_editor_show_name(); ?>
                            </td>
                            <td class="td_data">
                                <?php echo $this->payModel->field_list['status']->gen_editor(0) ?>
                            </td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
