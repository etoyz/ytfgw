<?php
/**
 * 获取企业账号类型
 */
session_start();
if ($_SESSION['status'] <= 4)
    die("申报");
else
    die("评价");
