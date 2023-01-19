<?php
/**
 * 删除附件
 */
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") { // 只有企业用户可以删除附件
    $response["status"] = "isLogin";

    require_once "../include/common.php";
    try {
        $response['data'] = unlink("../uploads/$_SESSION[loginid]/" . fetch_attachment_name($_SESSION['loginid'], $_GET['indicator']));
    } catch (Exception $e) {
        $response['data'] = false;
    }
}
die(json_encode($response));