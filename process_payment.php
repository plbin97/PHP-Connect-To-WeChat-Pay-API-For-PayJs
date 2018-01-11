<?php
/*
 * 用户的网页上显示支付成功之后，这里就开始执行
 * 用于处理数据，删除Session，以及跳转回指定页面。
 */
session_start();
//==================================================================
//Session验证以及判断账单是否付款
if(!isset($_SESSION['payjs_payment_info'])) {
    die("error | 错误");
}
require "corn/payment.php";
if(!$use_asynchronous_payment_check) {
    if(!isset($_SESSION['payjs_paid'])) {
        die("error | 错误");
    }elseif(!$_SESSION['payjs_paid']) {
        die("error | 错误");
    }
}else{
    if(!check_payment_statue_by_local_temp($_SESSION['payjs_payment_info'] -> payjs_order_id)) {
        die("error | 错误");
    }
}
//===================================================================
if(!$use_asynchronous_payment_check){
    $_SESSION['payjs_paid'] = FALSE;
}
require "action.php";
$payment = $_SESSION['payjs_payment_info'];

//删除Session
unset($_SESSION['payjs_payment_info']);
if(!$use_asynchronous_payment_check) {
    unset($_SESSION['payjs_paid']);
    after_payment_has_been_paid($payment -> payjs_order_id, $payment -> out_trade_no, $payment -> total_fee); //执行action.php的代码
}

//跳转到指定URL
echo "<script language='javascript' type='text/javascript'>";  
echo "window.location.href='" . $after_payment_url . "'";  
echo "</script>";  