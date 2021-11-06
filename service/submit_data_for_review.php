<?php
session_start();
require_once "db.php";
$db = new DB();
$_SESSION['status'] = 2;
$sql = "UPDATE `enterprise` set  `status` = 2 WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
die($db->query($sql));
