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
$obor = new Table("obor", $conn);
$tridy = new Table("tridy", $conn);
$tridy->makeLink($ucitel, "ucitel", "id");

$zak = new Table("zak", $conn);
// $zak->makeLinks([$ucitel, $obor/* , $tridy */], ["ucitel", "obor"/* , "trida" */], ["id", "id"/* , "id" */]);
$zak->makeLink($tridy, "trida", "id");
$zak->makeLink($obor, "obor", "id");

$tables = ['ucitele' => $ucitel, 'obor' =>  $obor, 'tridy' =>  $tridy, 'zak' =>  $zak];

$table = isset($_SESSION["table"]) ? $tables[$_SESSION["table"]] : $zak;



