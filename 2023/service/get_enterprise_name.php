<?php
/**
 * 获取企业名称
 */
session_start();
require '../include/db.php';
$db = new DB();
$sql = "SELECT `name` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_SESSION['loginid']) . "';";
$re = $db->query($sql);
die($re->fetch_array()['name']);