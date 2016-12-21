<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" src="<?= image_path('public/img/profile_small.jpg'); ?>" /></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                        <span class="clear">
                            <span class="block m-t-xs"><strong class="font-bold"><?= $admin_info['username']; ?></strong></span>
                            <span class="text-muted text-xs block">管理员<b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="J_menuItem" href="<?= get_url('admin/admin/modify_pwd'); ?>">修改密码</a></li>
                        
                        <!--<li><a class="J_menuItem" href="profile.html">个人资料</a></li>-->
                        <li class="divider"></li>
                        <li><a href="<?= get_url('admin/power/logout'); ?>">安全退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">H+</div>
            </li>
            <li><a class="J_menuItem" href="<?= get_url('admin/main/index'); ?>"><i class="fa fa-home"></i> <span class="nav-label">主页</span></a><li>
                <?php foreach ($nav as $v) : ?>
                <li>
                    <a href="javascript:void(0)">
                        <i class="fa fa-table"></i>
                        <span class="nav-label"><?= $v['description'] ?></span>
                        <span class="fa arrow"></span>
                    </a>

                    <ul class="nav nav-second-level">
                        <?php
                        if (!empty($v['child'])):
                            foreach ($v['child'] as $v1) :
                                ?>
                                <li>
                                    <a class="J_menuItem" href="<?= get_url('admin/'.$v['name'] . '/' . $v1['name']); ?>"><?= $v1['description']; ?></a>
                                </li>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <!--
                        <li>
                            <a class="J_menuItem" href="<?= get_url('admin/statistics/see'); ?>">数据查看</a>
                        </li>
                        -->
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>