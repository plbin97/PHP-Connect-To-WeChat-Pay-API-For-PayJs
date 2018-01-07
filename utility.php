<?php
/*
 * This file contains the methods of utility we might need. 
 * 此文件包含了一些我们可能会用到的一些函数。
 */

function object_to_array($e){
    /*
    * Method object_to_array() is quoted from http://www.phpernote.com/php-function/1285.html. 
    * Used for convert the object into array. 
    * The parameter is any object. 
    * The return is the Array form of that object. The array index is the variable name in object, and the array value is the variable value. 
   */
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
