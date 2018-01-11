<?php

/*

本页面基于Bootstrap登录模板制作： http://getbootstrap.com/docs/4.0/examples/signin/  。
此页面用于显示二维码的用户交互界面。
如果你使用移动设备访问的话，页面会自动跳转到微信支付APP

---------------------------------------------------------------------------------------

此页面需要两个通过GET传递的参数
参数1：'money' ： 付款总额，单位为分 人民币，数据类型：integer[16]
参数2：'title' ：这个参数会显示在微信支付的标题里，数据类型：String[32]
例子：https://xxx.xxx/payjs/?money=1000&title=XXX收款
 */
require "action.php";
//==============================================================
/*
 * 参数传入的过滤以及判断
 */
if (!isset($_GET["money"]) || !isset($_GET["title"])) {
    die("缺少参数 | Lack of Parameters");
}
if (strlen($_GET["money"]) > 16 || strlen($_GET["title"]) > 32) {
    die("字符长度不对 | Incorrect data length");
}
if (!is_numeric($_GET["money"])) {
    die("请在参数 'money' 中输入数字 | Please input numbers in parameter 'money'");
}

//===============================================================
/*
 * 创建订单以及创建Session
 * 注意，本软件会用到两个Session变量：
 *  $_SESSION['payjs_payment_info']
    $_SESSION['payjs_paid']
 * 其中：
 * payjs_payment_info 是用于存放账单信息的对象，具体请看API文档：https://payjs.cn/help/api-lie-biao/sao-ma-zhi-fu.html
 * payjs_paid 是用于存放此账单是否付款的信息，boolearn数据类型。True代表已支付，False代表未支付
 */
require "corn/payment.php";
$info = create_payment_return_info($_GET["title"], (int)$_GET["money"]);
if ($info == null) {
    die("Error | 错误");
}else {
    session_start();
    after_payment_created($info -> payjs_order_id, $info -> out_trade_no, $info -> total_fee, $info -> code_url);
    $_SESSION['payjs_payment_info'] = $info;
    if($use_asynchronous_payment_check) {
        create_payment_paid_index($info ->payjs_order_id);
    }else{
        $_SESSION['payjs_paid'] = false;
    }
}
//================================================================
?>
<!--HTML部分，此处相信你看得懂，我就不做过多注释了-->
<!DOCTYPE html>
<html class="gr__getbootstrap_com" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="WeChat Pay">
        <meta name="author" content="plbin97">
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/qrcode.min.js"></script>
        <title>微信付款</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #eee;
            }
            .form-signin {
                max-width: 330px;
                padding: 15px;
                margin: 0 auto;
            }
            .form-signin .form-signin-heading,
            .form-signin {
                margin-bottom: 10px;
            }
            .form-signin {
                font-weight: 400;
            }
            .form-signin {
                position: relative;
                text-align: center;
                box-sizing: border-box;
                height: auto;
                padding: 10px;
                font-size: 16px;
            }
            .form-signin .form-control:focus {
                z-index: 2;
            }
            .form-signin input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            .form-signin input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
            .light-color {
                color: #666;
            }
            .light-color-8 {
                color: #888;
            }
        </style>
    </head>

    <body data-gr-c-s-loaded="true">

        <div class="container">

            <div class="form-signin">
                <h2 class="form-signin-heading light-color">打开微信扫二维码</h2> <!--页面标题-->
                <p class="light-color-8">
                    <nobr>
                        付款金额：<?php echo $_SESSION['payjs_payment_info'] -> total_fee / 100 ?>元 <!--显示付款金额-->
                        <a href="cancel_payment.php" style="border-color:#eee;" class="btn btn-outline-secondary"> OR &nbsp;&nbsp;我不付了</a><!--取消订单按钮-->
                    </nobr>
                </p>
                <center id="qrcode"></center> <!--二维码位置-->
                <br>
                <p id="title" class="light-color">
                    <?php
                    if ($use_asynchronous_payment_check) {
                        echo "请扫描二维码，付款后，页面会自动跳转";
                    } else {
                        echo "扫描完成后，请点击按钮";
                    }
                    ?>
                </p> <!--一个以呼吸模式出现的字体，若影若现的-->
                <button id="check" class="btn btn-dark btn-block" type="button" onclick="check_payment();">
                    <?php
                    if ($use_asynchronous_payment_check) {
                        echo "付了，但还没跳转";
                    }else{
                        echo "付好啦";
                    }
                    ?>
                </button> <!--支付完毕后需要点击的按钮，用于查询订单-->
                <br>
                <div id="alert" style="opacity : 0;" class="alert alert-danger alert-dismissible" role="alert"> <!--查询订单后发现没有支付的时候，这个DIV框框就会出现-->
                    <button type="button" class="close" onclick="close_alert();"><span aria-hidden="true">&times;</span></button>
                    <strong>扯淡</strong> 你根本没付
                </div>
            </div>
        </div> 
        <script type="text/javascript">
            //JS的二维码设置：
            new QRCode(document.getElementById("qrcode"), {
                text: "<?php echo $_SESSION['payjs_payment_info'] -> code_url ?>",
                width: 200,
                height: 200,
                colorDark: "#222",
                colorLight: "#eee"
            });
            title_change(0);
            function close_alert(){
                $("#alert").animate({
                    opacity : 0
                },300);
            }

            <?php if ($use_asynchronous_payment_check) { ?>
                window.onload = function() {
                    a_check();
                }
                function a_check() {
                    if (ajax_check() == "1") {
                        payment_success();
                    }else {
                        setTimeout('a_check()', 500);
                    }
                }
            <?php } ?>

            //=====================================================
            //检查账单是否已支付

            function check_payment(){
                $("#check").attr("disabled", true);
                $("#check").text("正在检查");
                setTimeout("return_payment_status()",200);
            }
            function return_payment_status() {
                if (ajax_check() == "1") {
                    payment_success();
                }else{
                    payment_fail();
                }
            }

            /*
            * 为啥不用JQ的ajax？因为JQ的ajax效率太低，性能太差，干脆直接调用XMLHttpRequest类
            * */

            payment_status = "0";
            function ajax_check() {
                var ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200) {
                        payment_status = this.responseText;
                    }
                };
                ajax.open("GET","check_payment.php",true);
                ajax.send();
                return payment_status;
            }

            //========================================================
            /*
             * 当订单查询后，确认已支付时：
             */
            function payment_success(){
                $("div").animate({
                    opacity: 0
                },600);
                setTimeout("window.location.href= 'payment_success.php';",600);
            }
                        /*
             * 当订单查询，确认未支付时：
             */
            function payment_fail(){
                $("#check").attr("disabled", false);
                $("#check").text("这次我付好啦");
                $("#alert").animate({
                    opacity : 1
                },300);
            }
            //=========================================================
            /*
             * 实现文字的呼吸灯特效：
             */
            function title_change() {
                title_light_dark();
                return setTimeout("title_change()", 4000);
            }
            function title_light_dark(){
                $("#title").animate({
                    opacity : 0.2
                }, 2000);
                setTimeout("title_dark()", 2000);
            }
            function title_dark(){
                $("#title").animate({
                    opacity : 1
                }, 2000);
            }
            //==========================================================
        </script>
    </body>
</html>
