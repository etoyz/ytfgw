<?php
session_start();

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

var_dump($sql);

die(json_encode($_POST));
