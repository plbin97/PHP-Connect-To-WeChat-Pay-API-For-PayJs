<?php
/*
 * 此页面用于AJAX交互。
 * 以获取Session数据，然后判断这个账单是否被支付。
 * 返回“1”代表已支付，返回“0”代表未支付，出错时，也会返回0。
 */
session_start();

//判断Session变量是否存在
if (!isset($_SESSION['payjs_payment_info'])) {
    die("No Payment data");
}

require "corn/payment.php";

//调用corn文件夹里的函数来判断账单是否已付款
if (does_payment_has_been_paid($_SESSION['payjs_payment_info'] -> payjs_order_id)) {
    if(!$use_asynchronous_payment_check) {
        $_SESSION['payjs_paid'] = true;
    }
    echo "1";
}else {
    if(!$use_asynchronous_payment_check) {
        $_SESSION['payjs_paid'] = FALSE;
    }
    echo "0";
}