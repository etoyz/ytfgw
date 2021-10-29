<?php
session_start();

$randstr = rand();

mkdir("../uploads/" . $_SESSION['loginid']);
move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/" . $_SESSION['loginid'] . '/' . $randstr . "_" . $_POST['indicator']);

var_dump("../uploads/" . $_SESSION['loginid'] . '/' . $randstr . "_" . $_POST['indicator']);
var_dump($_FILES['file']['tmp_name']);
$_SESSION[''] = 1;
