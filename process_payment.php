<?php
/*
 * 用户的网页上显示支付成功之后，这里就开始执行
 * 用于处理数据，删除Session，以及跳转回指定页面。
 */
session_start();
//==================================================================
//Session验证以及判断账单是否付款
if(!isset($_SESSION['payjs_payment_info']) || !isset($_SESSION['payjs_paid'])) {
    die("error | 错误");
}
if($_SESSION['payjs_paid'] == FALSE) {
    die("error | 错误");
}
//===================================================================

$_SESSION['payjs_paid'] = FALSE;
require "action.php";
require "config.php";
$payment = $_SESSION['payjs_payment_info'];

//删除Session
unset($_SESSION['payjs_payment_info']); 
unset($_SESSION['payjs_paid']);

after_payment_has_been_paid($payment -> payjs_order_id, $payment -> out_trade_no, $payment -> total_fee, $payment -> code_url); //执行action.php的代码

//跳转到指定URL
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='" . $after_payment_url . "'";  
echo "</script>";  