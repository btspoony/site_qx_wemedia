<script src="<?= jscss_path('public/js/jquery.min.js?v=2.1.4'); ?>"></script>
<script src="<?= jscss_path('public/js/bootstrap.min.js?v=3.3.6'); ?>"></script>
<script src="<?= jscss_path('public/js/content.min.js?v=1.0.0'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/chosen/chosen.jquery.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/jsKnob/jquery.knob.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/prettyfile/bootstrap-prettyfile.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/nouslider/jquery.nouislider.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/switchery/switchery.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/ionRangeSlider/ion.rangeSlider.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/iCheck/icheck.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/clockpicker/clockpicker.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/cropper/cropper.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/layer/layer.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/hplus.min.js?v=4.1.0'); ?>"></script>
<script src="<?= jscss_path('public/js/contabs.min.js'); ?>" type="text/javascript"></script>
<script src="<?= jscss_path('public/js/plugins/pace/pace.min.js'); ?>"></script>
<script src="<?= jscss_path('public/js/content.min.js?v=1.0.0'); ?>"></script>
<script src="<?= jscss_path('public/js/plugins/echarts/echarts-all.js'); ?>"></script>

<script src="<?= jscss_path('public/js/commonFunction.js'); ?>"></script>
<script type="text/javascript">
    var imgUploadServer = "/public/uploadimg"; // 图片上传接口地址
    var applicationPath = '<?= image_path(); ?>';
    $(function () {
        $(".icheckbox").click(function () {
            var value = $(this).val();
            if ($(this).is(':checked') == true) {
                //全选 
                $(".icheckbox" + value).prop('checked', true);
            } else {
                //反选
                $(".icheckbox" + value).prop('checked', false);
            }
        });
       
      
 
    });
</script>
<script type="text/javascript" src="<?= jscss_path('public/js/com/myWebUpload.js'); ?>"></script>
</body>
</html>
