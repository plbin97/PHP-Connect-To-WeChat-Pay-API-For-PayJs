<?php
/*
 * 此页面用于接收来自payjs的异步账单推送支付通知，具体文档请看：https://payjs.cn/help/api-lie-biao/jiao-yi-xin-xi-tui-song.html
 * */

//用于判断所有post进来的参数，长度是否符合，防止有人输入特别长的参数来攻击。
if(strlen($_POST['return_code']) > 1 && strlen($_POST['total_fee']) > 16 && strlen($_POST['out_trade_no']) > 32 && strlen($_POST['payjs_order_id']) > 32 && strlen($_POST['transaction_id']) > 32 && strlen($_POST['time_end']) > 32 && strlen($_POST['openid']) > 32 && strlen($_POST['mchid']) > 16 && strlen($_POST['sign']) > 32) {
    die("长度错误");
}

$data = ["return_code" => $_POST['return_code'], "total_fee" => $_POST['total_fee'], "out_trade_no" => $_POST['out_trade_no'], "payjs_order_id" => $_POST['payjs_order_id'], "transaction_id" => $_POST['transaction_id'], "time_end" => $_POST['time_end'], "openid" => $_POST['openid'], "mchid" => $_POST['mchid'], "sign" => $_POST['sign']];
require "corn/pay_js_conn.php";
require "action.php";

//签字验证
if(!sign_verify_for_array($data)) {
    die("验证错误");
}

//确认后需要做的事情
change_payment_paid_index_statue($data["payjs_order_id"]);
after_payment_has_been_paid($data["payjs_order_id"], $data["out_trade_no"], $data["total_fee"]);
