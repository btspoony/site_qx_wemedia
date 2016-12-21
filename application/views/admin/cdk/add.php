<?php load_view('admin/elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>优惠券活动添加</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" onsubmit="return validate_form(this)">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">活动名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="type_name" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">活动code</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="type_code" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">活动描述</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="type_desc" maxlength="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">活动状态</label>
                            <div class="col-sm-10">
                                <?php echo selectInput($status, $cdk_status['status_use_yes'], 'id', 'text', array('class' => 'form-control m-b', 'name' => 'type_status')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" type="submit">添加</button>
                                <button class="btn btn-white" type="reset">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php load_view('admin/elements/footer'); ?>
    <script type="text/javascript">
        /**
         * 表单验证
         * @returns {undefined}
         */
        function validate_form() {
            var form = $(".form-horizontal").getForm();
            var bol = false;
            if (form.type_name == '') {
                alert('优惠卷名称不能为空');
            } else if(form.type_code == '' || !funcChina(form.type_code)) {
                alert('优惠卷code不能为空,不能有中文');
            }else {
//                if (type != form.type) {
                    $.ajax({
                        url: site_url + '/cdk/check_type_code',
                        data: form,
                        type: 'post',
                        async: false,
                        cache: false,
                        success: function (data) {
                            data = evalJson(data);
                            if (data.code == 1) {
                                alert('优惠卷code已存在');
                            } else {
                                bol = true;
                            }
                        },
                        error: function () {
                            alert('系统错误, 请联系管理员');
                        }
                    });
//                } else {
//                    bol = true;
//                }
            }
            return bol;
        }
    </script>