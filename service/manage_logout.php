<?php
session_start();
session_destroy();
echo "<script>location.href='../web/manage_login.html'</script>";
