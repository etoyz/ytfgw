<?php
session_start();
require_once '../include/db.php';
$db = new DB();
$res = "无权限";

if (isset($_SESSION['loginid']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == '0') {
    // 数据插入数据库
    $sql = "INSERT INTO `enterprise_score`(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "`" . $db->escape($key) . "`, ";
    }
    $sql .= "`type`) VALUES(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "'" . $db->escape($_POST[$key]) . "', ";
    }
    //$sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= "'expert');";
    $res = $db->query($sql); // 成功返回true，失败返回错误码
    if ($res == 1062) { // 如果已经存在，则尝试进行更新
        $sql = "UPDATE `enterprise_score` SET ";
        foreach (array_keys($_POST) as $key) {
            $sql .= "`" . $db->escape($key) . "` = '" . $db->escape($_POST[$key]) . "', ";
        }
        $sql .= "`loginid` = '$_POST[loginid]' WHERE `loginid` = '$_POST[loginid]' AND `type` = 'expert'";
        $res = $db->query($sql); // 成功返回1，失败返回错误码
    }
}
die($res);

