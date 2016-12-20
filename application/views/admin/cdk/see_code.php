<?php load_view('elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>查看卷号</h5>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>优惠卷名称</th>
                                <th>当前状态</th>
                                <th>领取IP</th>
                                <th>领取时间</th>
                                <th>创建时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v) : ?>
                                <tr class="gradeA odd">
                                    <td><?= ++$page['order_num']; ?></td>
                                    <td><?= $v['cdk_code']; ?></td>
                                    <td><?= $v['cdk_status'] == $model_config['status_use_yes'] ? '已领取' : '未领取'; ?></td>
                                    <td><?= $v['cdk_receive_ip']; ?></td>
                                    <td><?= $v['cdk_receive_time']; ?></td>
                                    <td><?= $v['create_time']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php load_view('elements/page'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php load_view('elements/footer'); ?>