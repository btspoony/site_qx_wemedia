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
                            <label class="col-sm-2 control-label">优惠卷名称</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="type_name" maxlength="50" value="<?php echo set_value('type_name');?>"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠卷描述</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="type_desc" maxlength="100" value="<?php echo set_value('type_desc');?>"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠卷状态</label>
                            <div class="col-sm-10">
                                <?php echo selectInput($status, set_value('type_status'), 'id', 'text', array('class' => 'form-control m-b', 'name' => 'type_status')); ?>
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
            if (form.type_name == '') {
                alert('优惠卷名称不能为空');
            }else {
                bol = true;
            }
            return bol;
        }
    </script>