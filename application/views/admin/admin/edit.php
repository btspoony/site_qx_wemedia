<?php load_view('elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>管理员添加</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" onsubmit="return validate_form(this)">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">登陆名</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="username" maxlength="16" value="<?php echo set_value('username');?>"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">操作权限</label>
                            <div class="col-sm-10">
                                <?php
                                $i = 1;
                                foreach ($powers as $v) :
                                    if ($i != 1):
                                        echo '<br /><br />';
                                    endif;
                                    $i = 2
                                    ?> 
                                    <label class="checkbox-inline"><input type="checkbox"  class="icheckbox" value="<?= $v['actionid'] ?>"><?= $v['description'] ?></label>
                                    <br />
                                    <?php
                                    if (!empty($v['child'])):
                                        foreach ($v['child'] as $v1) :
                                            ?>
                                            <label class="checkbox-inline">
                                                <input type="checkbox"  value="<?= $v1['actionid'] ?>" <?php echo in_array($v1['actionid'], explode(',', $_POST['powers'])) ? 'checked' : null; ?> class="icheckbox<?= $v1['parentid']; ?>" name="powers[]"><?= $v1['description'] ?>
                                            </label>
                                            <?php
                                        endforeach;
                                    endif
                                    ?>
                                <?php endforeach; ?>
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
        var username = "<?php echo set_value('username');?>";
        /**
         * 表单验证
         * @returns {undefined}
         */
        function validate_form() {
            var form = $(".form-horizontal").getForm();
            var bol = false;
            if (!isUname(form.username)) {
                alert('登陆名6-16位,不能包含中文');
            } else if (!isPwd(form.new_password)) {
                alert('密码6~16位,不能包含中文,不能全数字');
            } else if(form.powers.length<=0) {
                alert('请选择用户权限');
            }else {
                if (username != form.username) {
                    $.ajax({
                        url: site_url + '/admin/check_username',
                        data: form,
                        type: 'post',
                        async: false,
                        cache: false,
                        success: function (data) {
                            data = evalJson(data);
                            if (data.code == 1) {
                                alert('该用户已存在, 请重新输入');
                            } else {
                                bol = true;
                            }
                        },
                        error: function () {
                            alert('系统错误, 请联系管理员');
                        }
                    });
                } else {
                    bol = true;
                }
            }
            return bol;
        }
    </script>