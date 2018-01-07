<?php
/*
 * This file is used for configuation of Wechat pay API. Please configure this file before you use. 
 * 此文件作为配置文件，请先配置后使用
 */
//===================================================================================

/*
 * $config_mchid is the Business number, which you can see it in you payjs.cn account. 
 * $config_mchid是你的微信支付接口的商户号。
 */
$config_mchid = "XXXXX";   //商户号 Your business Number

/*
 * $config_key is the private key, which you can see it in your payjs.cn account. 
 * $config_key是你的微信支付接口的密钥。
 */
$config_key = "123asd";  //密钥 Your private Key

//====================================================================================

/*
 * $use_validation_code is whether you want to set validation before payment. 
 * If you want to set validation before payment, please make it like: 
 *                                                           $use_validation = true;
 * If not, please make it like: 
 *                                                           $use_validation = false;
 */
$use_validation = false;
/*
 * $use_validation_code是决定你是否在别人支付前启用验证码，
 * 如果你想要启用，那么请改成：
 *                                                           $use_validation = true;
 * 如果你不想，那就这样：
 *                                                           $use_validation = false;
 */

//====================================================================================

/*
 * $use_notification_of_pay is whether you want to use the asynchronous notification. 
 * If you want to use, please make it like: 
 *                                                    $use_notification_of_pay = true;
 * If not, please make it like: 
 *                                                    $use_notification_of_pay = false;
 */
$use_notification_of_pay = false;
/*
 * $use_notification_of_pay 是决定你是否使用异步通知。 
 * 如果你想用的话，请改成：
 *                                                    $use_notification_of_pay = true;
 * 如果不想，那就这样：
 *                                                    $use_notification_of_pay = false;
 */

//===========================================================================

/*
 * The following configuration, if you are not profrssional on that, please do not touch it. 
 * 以下设置，如果你对这个不了解的话，就不要动它。
 */

$create_payment_url = "https://payjs.cn/api/native";  //The API's url for creating payment. 用于创建订单的URL
$check_payment_url = "https://payjs.cn/api/check";  //The API's url for checking the payment. 用于查询订单的URL


$sign_verification_error_msg = "Sign Verification Error, please make sure that your key is correct. <br> 签字错误，建议您检查您的密钥是否正确";
$apt_connect_error_msg =  "API Call Error, please ask the the administrater of the API provider. <br> API调用错误，请联系API提供商";
$parameter_error_msg = "Parameter Error <br> 参数传递错误";
