<?php load_view('admin/elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>优惠券添加</h5>
                </div>
                <div class="ibox-content">
                    <form method="post" class="form-horizontal" onsubmit="return validate_form(this)">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">优惠券卷号(表格)</label>
                            <div class="col-sm-10">
                                <div id="uploader1" name="cdk_path" style="margin-left:10px"></div>
                                <span class="help-block m-b-none">上传优惠卷</span>
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
        $(function () {
            //渲染容器
            $("#uploader1").powerWebUpload({auto: false, fileNumLimit: 1});
        })
        /**
         * 表单验证
         * @returns {undefined}
         */
        function validate_form() {
            var form = $(".form-horizontal").getForm();
            var bol = false;
            if (form.cdk_path == undefined) {
                alert('请上传优惠卷文件！');
            } else if (form.cdk_path == '') {
                alert('文件上传失败！');
            }else {
                bol = true;
            }
            return bol;
        }
    </script>