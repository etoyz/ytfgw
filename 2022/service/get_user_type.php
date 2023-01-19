<?php
/**
 * 获取登录用户的类型是管理员还是企业
 */
session_start();
die($_SESSION['usertype']);
