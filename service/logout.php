<?php
session_start();
if ($_SESSION['usertype'] === "manager")
    echo "<script>location.href='../web/manage_login.html'</script>";
else
    echo "<script>location.href='../web/enterprise_login.html'</script>";
session_destroy();
