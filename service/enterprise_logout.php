<?php
/**
 * 企业登出
 */
session_start();
session_destroy();
echo "<script>location.href='../web/enterprise_login.html'</script>";
