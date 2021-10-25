<?php
session_start();

$arr = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['loginid'])) {
    $arr["status"] = $_SESSION['usertype'];
}
