<?php

if(session_status() !== PHP_SESSION_ACTIVE)
session_start();

$_SESSION['DB'] = "";
$_SESSION['table'] = "";
$_SESSION['host'] = "";
$_SESSION['user'] = "";
$_SESSION['pass'] = "";
$_SESSION['port'] = "";

//header("Refresh:0");


?>