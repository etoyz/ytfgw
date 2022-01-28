<?php
session_start();
if (isset($_SESSION['loginid']))
    die($_SESSION['loginid']);
die("");
