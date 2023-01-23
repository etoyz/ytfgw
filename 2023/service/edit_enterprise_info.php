<?php
/**
 * 编辑企业信息
 */
require "../include/common.php";

session_start();
$response = array(
    "code" => 1,
    "msg" => get_string("NOT_LOGIN"),
    "data" => []
);
if (isset($_SESSION['usertype'])) {
    $db = new DB();
    $dst_status = 1;
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
    $re = $db->query($sql);
    if ($re === true)
        $response['code'] = 0;
    else {
        $response['code'] = 1;
        $response['msg'] = $re;
    }
}
die(json_encode($response));