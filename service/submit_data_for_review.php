<?php
session_start();
$sql = "UPDATE `enterprise` set  `status` = 2 WHERE `loginid` = '$_SESSION[loginid]'";
$_SESSION['status'] = 2;
require_once "db.php";
$db = new DB();
die($db->query($sql));
