<?php
session_start();
require_once 'db.php';
$db = new DB();
$sql = "SELECT `name` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_SESSION['loginid']) . "';";
$re = $db->query($sql);
die($re->fetch_array()['name']);