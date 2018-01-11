<?php
/*
 * 此文件包含了一些我们可能会用到的一些函数。
 */

function object_to_array($e){
   /*
    * 函数object_to_array()用于将对象转换为数组，此代码来自于：http://www.phpernote.com/php-function/1285.html
    * 参数是任意一个对象。
    * 返回值是一个基于对象的数组，其数组下标对应对象变量名，数组值对应变量值。
    */
    $e=(array)$e;
    foreach($e as $k=>$v){
        if( gettype($v)=='resource' ){
            return;
        }
        if( gettype($v)=='object' || gettype($v)=='array' ) {
            $e[$k]=(array)objectToArray($v);
        }
    }
    return $e;
}

//=====================================================================================
/*
 * 以下函数是用于异步账单信息推送，且这些函数执行条件是在有读写权限的条件下。
 * PHP会在本地文件夹temp下创建一个以订单号（官方文档命名为payjs_order_id）为文件名的文件，用于记录这个账单是否被付款。
 * 文件刚刚创立的时候是账单刚刚创建的时候，文件内容为"0"，代表未支付。
 * 当账单已经被支付后，文件内容会变成"1"，这个过程是异步账单查询接口实现。
 * */


function create_payment_paid_index($payjs_order_id) {
    /*
     * 函数create_payment_paid_index()用于创建一个本地文件，位于执行文件的当前目录的temp文件夹中
     * 主要用于创建一个账单是否被支付的记录
     * 参数$payjs_order_id为文件名，通常是账单ID，在payjs的官方文档中，这个指payjs_order_id（订单号），数据类型String[32]
     * 文件内容为“0”（代表未支付）
     * 没有返回值
     * */
    $file = fopen("temp/" . $payjs_order_id, "w") or die("没有文件读写权限");
    fwrite($file, "0");
    fclose($file);
}
function check_payment_by_paid_index($payjs_order_id) {
    /*
     * 函数check_payment_by_paid_index()用于读取一个本地文件，位于执行文件的当前目录的temp文件夹中
     * 主要用于查询一个账单是否支付
     * 参数$payjs_order_id为文件名，通常是账单ID，在payjs的官方文档中，这个指payjs_order_id（订单号），数据类型String[32]
     * 返回值为"0"时代表未支付，"1"代表已支付，数据格式为String[1]
     * */
    $file = fopen("temp/" . $payjs_order_id,"r") or die("运行出错，找不到文件");
    return fgetc($file);
    fclose($file);
}

function change_payment_paid_index_statue($payjs_order_id) {
    /*
     * 函数change_payment_paid_index_statue()用于改变一个本地文件的内容变成"1"，位于执行文件的当前目录的temp文件夹中
     * 用于改变一个账单的支付状态，从未支付变成已支付。
     * 参数$payjs_order_id为文件名，通常是账单ID，在payjs的官方文档中，这个指payjs_order_id（订单号），数据类型String[32]
     * 无返回值
     * */
    $file = fopen("temp/" . $payjs_order_id, "w") or die("没有文件读写权限");
    fwrite($file, "1");
    fclose($file);
}

//=========================================================================================