<?php
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    if ($_SESSION['status'] == 0)
        die("1");
}
die("0");
