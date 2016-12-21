<?php load_view('admin/elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>管理员查看</h5>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>管理员名称</th>
                                <th>最近一次登录时间</th>
                                <th>登录IP</th>
                                <th>当前状态</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v) : ?>
                                <tr class="gradeA odd">
                                    <td><?= ++$page['order_num']; ?></td>
                                    <td><?= $v['username']; ?></td>
                                    <td><?= $v['last_login']; ?></td>
                                    <td><?= $v['last_ip']; ?></td>
                                    <td><?= $v['status'] == $model_config['status_quit'] ? '离职' : '正常'; ?></td>
                                    <td><?= $v['create_time']; ?></td>
                                    <td>
                                        <a href="<?= get_url('admin/admin/edit/' . $v['admin_id']); ?>">编辑</a>
                                        <a href="<?= get_url('admin/admin/delete/' . $v['admin_id']); ?>">删除</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php load_view('admin/elements/page'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php load_view('admin/elements/footer'); ?>