<?php
session_start();
session_destroy();
echo "<script>location.href='../web/enterprise_login.html'</script>";
