<?php
/**
 * 获取系统公告
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

$a = (new INI("../app.ini"))->data['Function']['announcement'];
$a = str_replace(["\r\n", "\r", "\n"], '<br/>', $a);

die($a);
//$ini = new INI("../app.ini");
//
//$a = $ini->data['Function']['warning'];
//$a = str_replace(["\r\n", "\r", "\n"], '<br/>', $a);
//
//$b = $ini->data['Function']['announcement'];
//$b = str_replace(["\r\n", "\r", "\n"], '<br/>', $b);
//
//$output = '';
//
//foreach ([$a, $b] as $value) {
//    if (!empty($value)) {
//        $output .= $value . '<br/>';
//    }
//}
//
//die($output);
