<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype'])) {
    require_once 'db.php';
    $db = new DB();
    $sql = "UPDATE `enterprise` 
        SET `name` = '" . $db->escape($_POST["name"]) . "', 
            `address` = '" . $db->query($_POST["address"]) . "', 
            `numofsub` = '" . $db->escape($_POST["numofsub"]) . "', 
            `mainbus` = '" . $db->escape($_POST["mainbus"]) . "]', 
            `industry` = '" . $db->escape($_POST["industry"]) . "', 
            `created_year` = '" . $db->escape($_POST["created_year"]) . "', 
            `chargeperson` = '" . $db->escape($_POST["chargeperson"]) . "', 
            `phone` = '" . $db->escape($_POST["phone"]) . "', 
            `remark` = '" . $db->escape($_POST["remark"]) . "',
            `status` = 1
        WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
    $_SESSION['status'] = 1;
    $response['status'] = "isLogin";
    $response['data'] = $db->query($sql);
}
die(json_encode($response));