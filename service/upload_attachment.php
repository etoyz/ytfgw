<?php
session_start();

mkdir("../uploads/" . $_SESSION['loginid']);
move_uploaded_file($_FILES['file']['tmp_name'],
    "../uploads/" . $_SESSION['loginid'] . "/附件_" . $_POST['indicator'] . "_" . $_FILES['file']['name']);

$_SESSION[''] = 1;
