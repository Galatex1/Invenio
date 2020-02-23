<?php

if(session_status() !== PHP_SESSION_ACTIVE)
    session_start();

if(isset($_POST["DB"]) /* && $_POST["DB"] != "" */)
{
    $_SESSION['DB'] = $_POST["DB"];
    unset($_SESSION['table']);

}


if(isset($_POST["table"]) && $_POST["table"] != "")
{
    $_SESSION['table'] = $_POST["table"];
}

header('Location: ../');



?>