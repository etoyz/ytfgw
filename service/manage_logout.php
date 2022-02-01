<?php
/**
 * 管理员登出
 */
session_start();
session_destroy();
echo "<script>location.href='../web/manage_login.html'</script>";
