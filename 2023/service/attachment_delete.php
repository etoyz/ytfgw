<?php
/**
 * 删除附件
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_is_enterprise.php";

$response = array(
    "code" => 1,
    "msg" => ""
);

try {
    $loginid = $_SESSION['loginid'];
    $indicator = $_GET['indicator'];
    // url 限定在企业专属文件夹下
    $url = "../uploads/$loginid/" . fetch_attachment_name($loginid, $indicator);
    $re = unlink($url);
    if ($re === true)
        $response['code'] = 0;
    else
        $response['msg'] = "删除失败";
} catch (Exception $e) {
    $response['msg'] = $e->getMessage();
}
die(json_encode($response));