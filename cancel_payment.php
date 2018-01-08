<?php
/*
 * 当用户创建订单后，就想取消这个订单的时候，这边就会执行。
*/
require "action.php";
require "config.php";
session_start();

//通过Session验证订单是否以及创建
if(!isset($_SESSION['payjs_payment_info']) || !isset($_SESSION['payjs_paid'])) {
    die("error | 错误");
}
$payment = $_SESSION['payjs_payment_info'];

//取消Session变量
unset($_SESSION['payjs_payment_info']);
unset($_SESSION['payjs_paid']);

//执行action.php里面的代码
after_cancel_payment($payment -> payjs_order_id, $payment -> out_trade_no, $payment -> total_fee, $payment -> code_url);

//跳转到指定URL
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='" . $quit_payment_url . "'";  
echo "</script>";  