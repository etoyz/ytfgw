<?php
session_start();
$arr = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['loginid'])) {
    $sql = "INSERT INTO `enterprise_data`(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "`$key`, ";
    }
    $sql .= "`loginid`)";
    $sql .= " VALUES(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "'$_POST[$key]', ";
    }
    //$sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= "'$_SESSION[loginid]');";

    require_once 'db.php';
    $db = new DB();
    $re = $db->query($sql);
    $arr["data"] = $re;
    $arr["status"] = "isLogin";
}
die(json_encode($arr));
