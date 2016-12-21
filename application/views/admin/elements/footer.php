<script src="<?= jscss_path('js/jquery.min.js?v=2.1.4'); ?>"></script>
<script src="<?= jscss_path('js/bootstrap.min.js?v=3.3.6'); ?>"></script>
<script src="<?= jscss_path('js/content.min.js?v=1.0.0'); ?>"></script>
<script src="<?= jscss_path('js/plugins/chosen/chosen.jquery.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/jsKnob/jquery.knob.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/prettyfile/bootstrap-prettyfile.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/nouslider/jquery.nouislider.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/switchery/switchery.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/ionRangeSlider/ion.rangeSlider.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/iCheck/icheck.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/clockpicker/clockpicker.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/cropper/cropper.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?= jscss_path('js/plugins/layer/layer.min.js'); ?>"></script>
<script src="<?= jscss_path('js/hplus.min.js?v=4.1.0'); ?>"></script>
<script src="<?= jscss_path('js/contabs.min.js'); ?>" type="text/javascript"></script>
<script src="<?= jscss_path('js/plugins/pace/pace.min.js'); ?>"></script>
<script src="<?= jscss_path('js/content.min.js?v=1.0.0'); ?>"></script>
<script src="<?= jscss_path('js/plugins/echarts/echarts-all.js'); ?>"></script>

<script src="<?= jscss_path('js/commonFunction.js'); ?>"></script>
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
<script type="text/javascript" src="<?= jscss_path('js/com/myWebUpload.js'); ?>"></script>
</body>
</html>
