<?php load_view('admin/elements/header'); ?>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>活动类型</h5>
                    </div>
                    <table class="table table-striped table-bordered table-hover dataTables-example dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>活动名称</th>
                                <!--<th>活动code<br /><已对外的url code不能修改><br/></th>-->
                                <th>活动type<br /><对应接口type><br/></th>
                                <th>活动对应url<br/></th>
                                <th>活动描述</th>
                                <th>优惠卷总数</th>
                                <th>优惠卷已领取数量</th>
                                <th>优惠卷未领取数量</th>
                                <th>当前状态</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $k => $v) : ?>
                                <tr class="gradeA odd">
                                    <td><?= ++$page['order_num']; ?></td>
                                    <td><?= $v['type_name']; ?></td>
                                    <!--<td><?= $v['type_code']; ?></td>-->
                                    <td><?= $v['type_id']; ?></td>
                                    <td><?= $v['type_url']; ?></td>
                                    <td><?= $v['type_desc']; ?></td>
                                    <td><?= isset( $v['total']) ?  $v['total'] : 0; ?></td>
                                    <td><?= isset( $v['use_total']) ?  $v['use_total'] : 0; ?></td>
                                    <td><?= isset( $v['use_no_total']) ?  $v['use_no_total'] : 0; ?></td>
                                    <td><?= $v['type_status'] == $model_config['status_use_yes'] ? '领取中' : '已关闭'; ?></td>
                                    <td><?= $v['create_time']; ?></td>
                                    <td>
                                        <a href="<?= get_url('admin/cdk/edit/' . $v['type_id']); ?>">编辑</a>
                                        <a href="<?= get_url('admin/cdk/add_code/' . $v['type_id']); ?>">添加卷号</a>
                                        <a href="<?= get_url('admin/cdk/see_code/' . $v['type_id']); ?>">查看卷号</a>
                                        <a href="<?= get_url('admin/cdk/delete/' . $v['type_id']); ?>">删除</a>
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