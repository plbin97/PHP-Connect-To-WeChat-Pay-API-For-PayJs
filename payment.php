<?php

/*
 * This file is used for payment action, which based on the methods from pay_js_conn.php
 * 这个文件用于操作微信支付订单，其操作都基于pay_js_conn.php
 */
//====================================================================================


require "pay_js_conn.php";


function create_payment_return_info($body,$total_fee){
    /*
    * Method create_payment_return_info() is used for creating a bill and returning the bill information. 
    * Paramater $body is the bill title which would display on Wechat while you pay for it. String type. 
    * Parameter $total_fee is the money for this bill, integer type. The unit of money is 0.01 RMB
    * The method would return an object which generate form API server, for this object's detail, please take a look at https://payjs.cn/help/api-lie-biao/sao-ma-zhi-fu.html. 
     * If there is an error happened, the method would return null, and print the error informationm in the page. 
    */
   /*
    * 函数create_payment_return_info()是用于创建订单并且返回订单数据的函数
    * 参数$body是订单标题，支付的时候显示在微信界面上。String数据类型。
    * 参数$total_fee是支付多少钱，单位是分，integer数据类型
    * 函数会返回一个对象，这个对象是来自于API服务器，具体请参考https://payjs.cn/help/api-lie-biao/sao-ma-zhi-fu.html。
    * 如果函数执行出现错误，会返回空（null），且会吧数据打印到页面上。
    */
    if (!is_numeric($total_fee) || !is_string($body)) {
        echo $GLOBALS['parameter_error_msg'];
        return null;
    }
    $data = ['body' => $body, 'out_trade_no' => time(), 'total_fee' => $total_fee, 'mchid' => $GLOBALS['config_mchid']];
    $data['sign'] = sign($data);
    $return_data = post_to_payjs($data, $GLOBALS['create_payment_url']);
    if ($return_data -> return_code !=1 || $return_data -> return_msg != "SUCCESS") {
        echo $GLOBALS['apt_connect_error_msg'];
        return null;
    }else{
        return $return_data;
    }
}


function return_payment_info($payjs_order_id){
    /*
     * Method return_payment_info() is used for looking up the bill information by order id. 
     * The parameter is the order id. According to the API document, it names "payjs_order_id"
     * This method would return an object which contains the information of the bill, which has returned from API server. For detail, please take a loot at : https://payjs.cn/help/api-lie-biao/ding-dan-cha-xun.html. 
     * However, if the information returned from API server has not pass the signiture verification, the method would return null, and print the error information on the page. 
     */
    /*
     * 函数return_payment_info()用于查找账单信息。
     * 传递参数为账单编号，也就是文档里所说的“payjs_order_id”。 
     * 函数返回值为一个对象，此对象来源于API接口，具体请看：https://payjs.cn/help/api-lie-biao/ding-dan-cha-xun.html。
     * 如果API服务器返回的对象不通过数字签名验证的话，函数会返回空（null）。
     */
    if (!is_string($payjs_order_id)) {
        echo $GLOBALS['parameter_error_msg'];
        return null;
    }
    $data['payjs_order_id'] = $payjs_order_id;
    $data['sign'] = sign($data);
    $return_data = post_to_payjs($data, $GLOBALS['check_payment_url']);
    if($return_data -> return_code ==1){
        return $return_data;
    } else {
        echo $GLOBALS['apt_connect_error_msg'];
        return null;
    }
}

function does_payment_has_been_paid($payjs_order_id) {
    /*
     * This method is based on the method "return_payment_info()", which used for check if a bill has already been paid or not. 
     * The parameter is the order id. According to the API document, it names "payjs_order_id".
     * The method would return true if the bill has already been paid. 
     * The method wouldreturn false if the bill has not been paid yet. 
     * However, it would return false if there is some error occur. 
     */
    /*
     * 函数does_payment_has_been_paid()用于查看此账单是否已付款。
     * 传递参数为账单编号，也就是文档里所说的“payjs_order_id”。
     * 函数返回true意味着账单已付，
     * 如果函数返回false意味着账单未付，或者执行出错。
     */
    if (!is_string($payjs_order_id)) {
        echo $GLOBALS['parameter_error_msg'];
        return null;
    }
    $return_data = return_payment_info($payjs_order_id);
    if ($return_data == null) {
        return FALSE;
    }elseif($return_data -> return_code == 0){
        return FALSE;
    }elseif($return_data -> status == 0){
        return FALSE;
    }else{
        return TRUE;
    }
}