<?php

error_reporting(0);

if(session_status() !== PHP_SESSION_ACTIVE)
    session_start();


if(isset($_POST['host']) &&  isset($_POST['user']) &&  isset($_POST['port']))
{
    $c = mysqli_connect($_POST['host'], $_POST['user'], $_POST['pass'], "", $_POST['port']);

    if($c)
    {
        $_SESSION['host'] = $_POST['host'];
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pass'] = $_POST['pass'];
        $_SESSION['port'] = $_POST['port'];
        echo " ";
    }
    else {
        die("Error - Problém s připojením do databáze.<br><br>".mysqli_connect_error());
    }
    
}
else {
    die("Error - Vyplňte všechny informace.");
}


?>