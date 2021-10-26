<?php
session_start();
$arr = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    $arr["status"] = "isLogin";
    $sql = "INSERT INTO `enterprise_data`(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "`$key`, ";
    }
    $sql .= "`loginid`) VALUES(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "'$_POST[$key]', ";
    }
    //$sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= "'$_SESSION[loginid]');";

    require_once 'db.php';
    $db = new DB();
    $arr["data"] = $db->query($sql);

    // update status
    if ($arr["data"] == true) {
        $sql = "UPDATE `enterprise` set `status`=2 WHERE `loginid` = '$_SESSION[loginid]'";
        $db->query($sql);
    }
}
die(json_encode($arr));
