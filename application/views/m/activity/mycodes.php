<?php load_view('elements/header'); ?>
<style>
    html, body{
        width: 100%;
        height: 100%;
    }

    body{
        background: url(<?= image_path('m/common/bj.jpg'); ?>) no-repeat top;
        background-size: cover;

        color:#22a!important;
        font-family: Helvetica, Tahoma, Arial, "Myriad Set Pro", "Noto Sans S Chinese", "PingFang SC","苹方-简", "Microsoft YaHei", "微软雅黑", "PingFang TC", "STHeiti Light", Heiti, "黑体", sans-serif;

        padding: 0px 5%;
    }
    .logo{
        margin: 6% 0;
    }
    .codes-header{
        background: url(<?= image_path('m/common/frame_top.png'); ?>) no-repeat top;
        background-size: cover;
        padding-top: 15%;

        text-align: center;
    }
    .codes-header>div{
        padding-left: 5px;
        padding-right: 5px;
    }
    .codes-header>div>strong{
        display: inline-block;
        background-color: #ddd;
        border-radius: 5px;
        padding: 5px 0px;
        width: 100%;
    }
    .codes-content{
        background: url(<?= image_path('m/common/frame_line.png'); ?>) repeat-y top;
        background-size: contain;
        text-align: right;
    }
    .codes-content .row{
        margin: 8px -10px 0px;
    }
    .codes-content .table{
        background-color: rgba(255,255,255,0.5);
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .codes-content .table>tbody>tr>td{
        border-top: 0;
    }
    .codes-content .table>tbody>tr>td:first-child{
        text-align: left;
    }

    .codes-footer{
        background: url(<?= image_path('m/common/frame_bottom.png'); ?>) no-repeat bottom;
        background-size: cover;
        padding-bottom: 10px;
    }
    .prompt{
        color:#ffffff;
        font-size: small;
        float: left; 
        text-align:left;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row logo">
            <div class="col-xs-12">
                <img src="<?= image_path('m/xmas/logo.png'); ?>" class="img-responsive" alt="Responsive image">
            </div>
        </div>
        <div class="row codes-header">
            <div class="col-xs-3 col-xs-offset-1"><strong>名称</strong></div>
            <div class="col-xs-3"><strong>领取时间</strong></div>
            <div class="col-xs-4"><strong>礼包代码</strong></div>
        </div>
        <div class="row codes-content">
            <div class="col-xs-10 col-xs-offset-1">
                <div class="row">
                    <table class="table">
                        <tbody>
                            <?php
                            if (!empty($data)) :
                                foreach ($data as $k => $v) :
                                    ?>
                                    <tr>
                                        <td><?= $v['type_name']; ?></td>
                                        <td><?= $v['cdk_receive_time']; ?></td>
                                        <td><?= $v['cdk_code']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <tr>
                                    <td colspan="3">您还未礼包码</td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                    <div class="row prompt">
                        <table>
                            <tr>
                                <td style="width:25%" valign="top"><b>友情提醒：</b></td>
                                <td>获取游戏礼包请尽快登陆游戏兑换，逾期不补！</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row codes-footer"></div>
    </div>
    <?php load_view('elements/footer'); ?>