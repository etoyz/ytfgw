<?php
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    if ($_SESSION['status'] == 1)  // 首次申报 或者 退回
        die("1");
}
die("0");
