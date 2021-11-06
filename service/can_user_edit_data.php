<?php
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    $status = $_SESSION['status'] % 5;
    if ($status == 1 || $status == 3)  // 首次申报 或者 退回
        die("1");
}
die("0");
