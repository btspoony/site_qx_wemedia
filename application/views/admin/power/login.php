<?php load_view('admin/elements/header'); ?>
<script>if (window.top !== window.self) {
        window.top.location = window.location;
    }
    </script>
<body class="gray-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">WM</h1>
        </div>
        <h3>欢迎使用后台管理系统</h3>

        <form class="m-t" method="post" role="form" action="<?= get_url('admin/power/login'); ?>">
            <div class="form-group">
                <?= $error_message; ?>
            </div>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="用户名" required="">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="密码" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
            <!--<p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>-->
            </p>
        </form>
    </div>
</div>
<?php load_view('admin/elements/footer'); ?>