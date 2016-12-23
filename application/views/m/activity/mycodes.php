<?php load_view('elements/header'); ?>
<style>
    html, body{
        width: 100%;
        height: 100%;
    }
    
    body{
        background: url(<?= image_path('m/xmas/bg.jpg'); ?>) no-repeat center;
        background-size: cover;
        color:white!important;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-8">
                <img alt="Brand"  src="<?= image_path('m/xmas/logo.png'); ?>" class="img-responsive" alt="Responsive image">
            </div>
        </div>
        <table class="table">
            <h3><strong>您领取的礼包码</strong></h3>
            <thead>
                <tr>
                    <th>#</th>
                    <th>礼包名称</th>
                    <th>礼包码</th>
                    <th>领取时间</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    if (!empty($data)) :
                        foreach ($data as $k => $v) :
                            ?>
                            <td><?= $k + 1; ?></td>
                            <td><?= $v['type_name']; ?></td>
                            <td><?= $v['cdk_code']; ?></td>
                            <td><?= $v['cdk_receive_time']; ?></td>
                            <?php
                        endforeach;
                    else:
                        ?>
                        <td colspan="3">您还未礼包码</td>
                    <?php
                    endif;
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php load_view('elements/footer'); ?>