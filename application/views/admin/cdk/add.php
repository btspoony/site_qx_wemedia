<?php load_view('elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>优惠券添加</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" onsubmit="return validate_form(this)">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠卷名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="type_name" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠卷描述</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="type_desc" maxlength="100">
                            </div>
                         </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠卷状态</label>
                            <div class="col-sm-10">
                                <?php echo selectInput($status, $cdk_status['status_use_yes'], 'id', 'text', array('class' => 'form-control m-b', 'name' => 'type_status')); ?>
                            </div>
                        </div>
                       
                       <!--
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠券卷号(表格)</label>
                            <div class="col-sm-10">
                                <div id="uploader1" name="cdk_path" style="margin-left:10px"></div>
                                <span class="help-block m-b-none">上传优惠卷</span>
                            </div>
                        </div>
                        -->
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
    <?php load_view('elements/footer'); ?>
     <script type="text/javascript">
//        $(function () {
//        //渲染容器
//            $("#uploader1").powerWebUpload({auto: false, fileNumLimit: 1});
//          
//        })
        /**
         * 表单验证
         * @returns {undefined}
         */
        function validate_form() {
            var form = $(".form-horizontal").getForm();
            var bol = false;
            if (form.type_name == '') {
                alert('优惠卷名称不能为空');
            } else {
                bol = true;
            }
            return bol;
        }
    </script>