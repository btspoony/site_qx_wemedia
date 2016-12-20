<?php load_view('elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>修改密码</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" onsubmit="return validate_form(this)">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">原始密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="old_password" maxlength="16">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">新密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="new_password" maxlength="16">
                                <span class="help-block m-b-none">6~16位,不能包含中文,不能全数字</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="check_password" maxlength="16">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" type="submit">修改</button>
                                <button class="btn btn-white" type="reset">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php load_view('elements/footer'); ?>
    <script type="text/javascript">
        /**
         * 表单验证
         * @returns {undefined}
         */
        function validate_form() {
            var form = $(".form-horizontal").getForm();
            var bol = false;
            if (!isPwd(form.new_password)) {
                alert('新密码6~16位,不能包含中文,不能全数字');
            } else if (form.new_password != form.check_password) {
                alert('新密码与确认密码不相同');
            } else {
                $.ajax({
                    url: site_url + '/admin/check_pwd',
                    data: form,
                    type: 'post',
                    async: false,
                    cache: false,
                    success: function (data) {
                        data = evalJson(data);
                        if (data.code == 1) {
                            alert('原始密码错误, 请重新输入');
                        } else {
                            bol = true;
                        }
                    },
                    error: function () {
                        alert('系统错误, 请联系管理员');
                    }
                });
            }
            return bol;
        }
    </script>