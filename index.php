<?php
session_start();
if (isset($_SESSION['usertype'])) { // 已经登录
    if ($_SESSION['usertype'] == "enterprise") {
        echo "<script>location.href='web/enterprise_index.html'</script>";
    } else {
        echo "<script>location.href='web/manage_index.html'</script>";
    }
    exit();
}

echo <<<ETO
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>烟台市创新平台申报与评价系统</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="web/assets/plugins/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="web/assets/css/login2.css" media="all">
  <style>
    .layui-input {
       background-color: inherit;
       padding-left: 30px;
    }
  </style>
</head>
<body>
    <div class="zyl_login_cont"></div>
    <div class="zyl_lofo_main" style="height: 310px">
        <fieldset class="layui-elem-field layui-field-title zyl_mar_02">
            <legend>欢迎使用 - 烟台市创新平台申报与评价系统</legend>
        </fieldset>
        <div class="layui-row layui-col-space15">
            <div class="layui-form zyl_pad_01">
                <div style="width: 300px; margin: 10% auto 0;">
                    <a class="layui-btn layui-btn-fluid" href="web/enterprise_login.html">我是企业</a>
                </div>
                <div style="width: 300px; margin: 10% auto 0;">
                    <a class="layui-btn layui-btn-warm layui-btn-fluid" href="web/manage_login.html">我是管理员</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="web/assets/common/js/jquery.min.js"></script>
    <script type="text/javascript" src="web/assets/common/js/zylVerificationCode.js"></script>
    <script type="text/javascript" src="web/assets/plugins/layui/layui.js"></script>
    <script type="text/javascript" src="web/assets/common/js/jparticle.min.js"></script>
    <script>
        layui.use(['form', 'layer', 'form'], function () {
            var $ = layui.jquery,
                form = layui.form,
                layer = layui.layer;
    
            form.verify({
                userName: function (value) {
                    if (value.length < 5) {
                        return '账号至少得5个字符';
                    }
                }
                , pass: [/^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格']
                , vercodes: function (value) {
                    return null;
                    //获取验证码
                    var zylVerCode = $(".zylVerCode").html();
                    if (value != zylVerCode) {
                        return '验证码错误（区分大小写）';
                    }
                }
            });
    
            form.on('submit(loginnow)', function (data) {
                $.post({
                    url: "../service/manage_login.php",
                    data: data.field,
                    success: function (res) {
                        if (res.status === "isLogin")
                            location.href = "manage_index.html";
                        else
                            layer.alert("用户名或密码错误！");
                    },
                    dataType: 'json'
                })
                return false
            });
    
            //粒子线条
            $(".zyl_login_cont").jParticle({
                background: "rgba(0,0,0,0)",//背景颜色
                color: "#fff",//粒子和连线的颜色
                particlesNumber: 100,//粒子数量
                //disableLinks:true,//禁止粒子间连线
                //disableMouse:true,//禁止粒子间连线(鼠标)
                particle: {
                    minSize: 1,//最小粒子
                    maxSize: 3,//最大粒子
                    speed: 30,//粒子的动画速度
                }
            });
        });
    </script>
</body>
</html>
ETO;