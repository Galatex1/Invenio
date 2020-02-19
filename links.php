<?php
require_once "DB.php";

if($conn)
{

    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_query($conn, "SET CHARACTER SET 'utf8'");
    mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

}

require_once "./Table.php";


if(session_status() !== PHP_SESSION_ACTIVE)
session_start();




$ucitel = new Table("ucitele", $conn);
$ucitel->name = "Učitel";
$ucitel->showColumns = ['jmeno', 'prijmeni'];

$obor = new Table("obor", $conn);
$obor->showColumns = [/* 'zkratka', 'nazev',  */'popis'];
$obor->name = "Obor";

$tridy = new Table("tridy", $conn);
$tridy->showColumns = ['nazev', 'zacatek'];
$tridy->makeLink($ucitel, "ucitel", "id");
$tridy->name = "Třída";

$zak = new Table("zak", $conn);
$zak->makeLink($tridy, "trida", "id");
$zak->makeLink($obor, "obor", "id");
$zak->name = "Žák";

$tables = ['ucitele' => $ucitel, 'obor' =>  $obor, 'tridy' =>  $tridy, 'zak' =>  $zak];

$table = isset($_SESSION["table"]) ? $tables[$_SESSION["table"]] : $zak;



