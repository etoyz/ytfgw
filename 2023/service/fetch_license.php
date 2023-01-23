<?php
/**
 * 获取指定企业的营业执照信息（相对路径）
 */
session_start();

require "../include/common.php";

$response = array(
    "license" => null,
    "license_sub" => null
);

if (is_enterprise()) { // 企业只能看自己的
    $user = $_SESSION['loginid'];
} else {
    $user = $_GET['user'];
}

if (fetch_attachment_name($user, "企业营业执照") != "未上传")
    $response['license'] = "../uploads/$user/" . fetch_attachment_name($user, "企业营业执照");
if (fetch_attachment_name($user, "下属企业营业执照") != "未上传")
    $response['license_sub'] = "../uploads/$user/" . fetch_attachment_name($user, "下属企业营业执照");

die(json_encode($response));