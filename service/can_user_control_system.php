<?php
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == "0")
    die("1");
else
    die("0");