<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta content="yes" name="apple-mobile-web-app-capable" />  
        <meta content="black" name="apple-mobile-web-app-status-bar-style" /> 
        <meta name="format-detection" content="telephone=no">

        <title>王与异界骑士<?= empty($type_name) ? "" : " - ".$type_name; ?></title>
        
        <link href="<?= jscss_path('css/bootstrap.min.css?v=3.3.6'); ?>" rel="stylesheet">
        <link href="<?= jscss_path('css/animate.min.css'); ?>" rel="stylesheet">
        
        <script type="text/javascript">
            var envOpt = {
                type: '<?= !empty($type) ? $type : '' ?>'
            };
            var site_url = '<?= get_url(); ?>';
            var img_base_url = '<?= image_path(''); ?>';
        </script>
        <!--[if lt IE 9]>
        <meta http-equiv="refresh" content="0;ie.html" />
        <![endif]-->
    </head>