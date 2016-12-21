<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>欢迎光临后台管理系统</title>
        <meta http-equiv="refresh" content="<?= $time; ?>;URL=<?= $url; ?>">
        <link rel="shortcut icon" href="favicon.ico"> 
        <link href="<?= jscss_path('public/css/bootstrap.min.css?v=3.3.6'); ?>" rel="stylesheet">
        <link href="<?= jscss_path('public/css/font-awesome.min.css?v=4.4.0'); ?>" rel="stylesheet">
        <link href="<?= jscss_path('public/css/plugins/morris/morris-0.4.3.min.css'); ?>" rel="stylesheet">
        <link href="<?= jscss_path('public/css/animate.min.css'); ?>" rel="stylesheet">
        <link href="<?= jscss_path('public/css/style.min.css?v=4.1.0'); ?>" rel="stylesheet">
    </head>
    <body class="gray-bg">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content text-center p-md">
                    <h2>
                        <span class="text-navy"><?php echo $msg; ?></span>
                        提供两种主要布局和3套皮肤可供选择
                    </h2>
                    <h3>
                        系统将在 <span id="time">3</span> 秒钟后自动跳转至新网址，如果未能跳转，<a href="<?= $url;?>" title="点击访问">请点击</a>。
                    </h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
    delayURL();
    function delayURL() {
        var delay = document.getElementById("time").innerHTML;
        var t = setTimeout("delayURL()", 1000);
        if (delay > 0) {
            delay--;
            document.getElementById("time").innerHTML = delay;
        } else {
            clearTimeout(t);
            window.location.href = "<?= $url; ?>";
        }
    }
</script>