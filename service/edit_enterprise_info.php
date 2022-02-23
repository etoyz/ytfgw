<?php
/**
 * 编辑企业信息
 */
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype'])) {
    require_once '../include/db.php';
    $db = new DB();
    $dst_status = 1;
    require_once "../include/common.php";
    if (get_user_status($_SESSION['loginid']) > 4)
        $dst_status = 6;
    $sql = "UPDATE `enterprise` 
        SET `name` = '" . $db->escape($_POST["name"]) . "', 
            `address` = '" . $db->escape($_POST["address"]) . "', 
            `numofsub` = '" . $db->escape($_POST["numofsub"]) . "', 
            `mainbus` = '" . $db->escape($_POST["mainbus"]) . "', 
            `industry` = '" . $db->escape($_POST["industry"]) . "', 
            `created_year` = '" . $db->escape($_POST["created_year"]) . "', 
            `chargeperson` = '" . $db->escape($_POST["chargeperson"]) . "', 
            `phone` = '" . $db->escape($_POST["phone"]) . "', 
            `remark` = '" . $db->escape($_POST["remark"]) . "',
            `status` = $dst_status
        WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "';";
    $response['status'] = "isLogin";
    $response['data'] = $db->query($sql);
}
die(json_encode($response));