<?php load_view('elements/header'); ?>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
        <?php load_view('elements/nav'); ?>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <?php load_view('elements/right'); ?>
        <!--右侧部分结束-->
        
        <!--右侧边栏开始-->
        <?php load_view('elements/right_top'); ?>
        <!--右侧边栏结束-->
    </div>

<?php load_view('elements/footer'); ?>