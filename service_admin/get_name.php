<?php
/**
 * 获取管理员名称
 */
session_start();
if (isset($_SESSION['loginid'])) {
    require_once 'db.php';
    $db = new DB();
    $sql = "SELECT `name` FROM `user` WHERE `loginid` = '" . $db->escape($_SESSION['loginid']) . "';";
    $re = $db->query($sql);
    die($re->fetch_array()['name']);
}