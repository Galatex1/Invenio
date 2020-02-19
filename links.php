<?php
require_once "DB.php";
require_once "./Table.php";


if(session_status() !== PHP_SESSION_ACTIVE)
session_start();

if($conn)
{

    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_query($conn, "SET CHARACTER SET 'utf8'");

}


$ucitel = new Table("ucitele", $conn);
$ucitel->showColumns = ['jmeno', 'prijmeni'];

$obor = new Table("obor", $conn);
$obor->showColumns = ['zkratka', 'nazev', 'popis'];

$tridy = new Table("tridy", $conn);
$tridy->showColumns = ['nazev', 'zacatek'];
$tridy->makeLink($ucitel, "ucitel", "id");

$zak = new Table("zak", $conn);
$zak->makeLink($tridy, "trida", "id");
$zak->makeLink($obor, "obor", "id");

$tables = ['ucitele' => $ucitel, 'obor' =>  $obor, 'tridy' =>  $tridy, 'zak' =>  $zak];

$table = isset($_SESSION["table"]) ? $tables[$_SESSION["table"]] : $zak;



