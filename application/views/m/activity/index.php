<?php load_view('elements/header'); ?>

<body>
    <div>
        this index page
        <input type="button" value="我点。。。。。" onclick="dianji()"/>
    </div>

    <?php load_view('elements/footer'); ?>
    <script type="text/javascript">
        function dianji() {
            $.ajax({
                url: site_url + 'api/cdk/getcdkcode',
                data: envOpt,
                type: 'post',
                async: false,
                cache: false,
                success: function (res) {
                    res = evalJson(res);
                    console.log(res);
                    if (res.code == 200) {
                        alert('领取成功');
                    } else {
                            alert('....');
                    }
                },
                error: function () {
                    alert('系统错误, 请联系管理员');
                }
            });
        }
        
        $.ajax({
                url: site_url + 'api/cdk/checkNoCdk',
                data: data,
                type: 'post',
                async: false,
                cache: false,
                success: function (res) {
                    res = evalJson(res);
                    console.log(res);
                    if (res.code == 200) {
                        alert('有卷');
                    } else {
                        alert('没有');
                    }
                },
                error: function () {
                    alert('系统错误, 请联系管理员');
                }
            });
    </script>