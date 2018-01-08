<?php
/*
 * 此页面用于订单查询后，支付成功显示的界面。
 * 如果检测到$_SESSION['payjs_payment_info'] 和 $_SESSION['payjs_paid'] 两个Session变量不存的的时候，或者检测到此订单未支付的时候，页面自动报错。
 * 
 * 为啥要有这个页面呢？
 * 因为如果通常订单处理需要一段时间，我们总不好意思让用户看一个空白界面吧，于是我们就让用户先在一个好看的界面待着，当处理完成之后再跳转。
 */
session_start();
//=====================================================================
//判断Session变量是否存在，以及账单是否支付。
if(!isset($_SESSION['payjs_payment_info']) || !isset($_SESSION['payjs_paid'])) {
    die("error | 错误");
}
if($_SESSION['payjs_paid'] == FALSE) {
    die("error | 错误");
}
?>
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
        <title>付款成功</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style type="text/css">
/*            利用CSS3做出来的一个转来转去的动画*/
            @keyframes lds-dual-ring {
                0% {
                    -webkit-transform: rotate(0);
                    transform: rotate(0);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            @-webkit-keyframes lds-dual-ring {
                0% {
                    -webkit-transform: rotate(0);
                    transform: rotate(0);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            .lds-dual-ring {
                position: relative;
                left: 50%;
                margin-left: -30px;
            }
            .lds-dual-ring div {
                position: relative;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                border: 8px solid #000;
                border-color: #a1a1a1 transparent #a1a1a1 transparent;
                -webkit-animation: lds-dual-ring 1.7s linear infinite;
                animation: lds-dual-ring 1.7s linear infinite;
            }
            .lds-dual-ring {
                width: 60px !important;
                height: 60px !important;
                -webkit-transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
                transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
            }

            body {
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: -100px;
                margin-left: -165px;
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

            <div id="icon" style="opacity : 0;" class="form-signin"> <!--一个转来转去的图标，养眼-->
                <div class="lds-css ng-scope">
                    <div class="lds-dual-ring">
                        <div></div>
                    </div>
                </div>
                <br>
                <h2 id="title" style="opacity : 0;" class="form-signin-heading light-color">您成功已支付 <?php echo $_SESSION['payjs_payment_info'] -> total_fee / 100 ?>元</h2><!--主标题-->
                <p id="subtitle" style="opacity : 0;">我们正在处理一些数据，请不要关闭</p> <!--副标题-->
            </div>
        </div>
        <script type="text/javascript">
        //当页面加载完的时候，我们顺手加一些动画：同时，开始加载后台
        window.onload = function(){
            appear_title();
            setTimeout("appear_subtitle()",100);
            setTimeout("appear_icon()",200);
            setTimeout("window.location.href='process_payment.php'",1000)  //为啥是跳转？对于正常浏览器，除非跳转页面返回了一个HTML，否则这个页面会一直显示的。当那个页面返回HTML的时候，就意味着已经处理完了。
        };
        //====================================================
        各种动画：
        function appear_title(){
            $("#title").animate({
                opacity : 1
            },400);
        }
        function appear_subtitle(){
            $("#subtitle").animate({
                opacity : 1
            },400);
        }
        function appear_icon(){
            $("#icon").animate({
                opacity : 1
            },400);
        }
        </script>
    </body>
</html>