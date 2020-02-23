<?php

require_once (dirname(__FILE__)."/Table.php");

error_reporting(E_ERROR);

if(session_status() !== PHP_SESSION_ACTIVE)
session_start();
    
$ini = parse_ini_file('config.ini');

$host = /* $ini['db_host'] */ $_SESSION['host'];
$user = /* $ini['db_user'] */ $_SESSION['user'];
$pass = /* $ini['db_pass'] */ $_SESSION['pass'];
$port = /* $ini['db_port'] */ $_SESSION['port'];



$DB = isset($_SESSION["DB"]) ? $_SESSION["DB"] : "";


$tables = [];

$conn = mysqli_connect($host, $user, $pass, $DB, $port);

if($conn)
{

    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_query($conn, "SET CHARACTER SET 'utf8'");
    mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

}
else {
    echo "Problem connecting to Mysql";
}

if(!isset($_SESSION['DB']) || $_SESSION['DB'] == "")
{

    $_SESSION['DB'] = reset(Table::getDBs($conn));
    unset($_SESSION['table']);
    header('Location: .');

}


if(isset($_SESSION['DB']) && $_SESSION['DB'] != "")
{

    foreach (Table::getTables($conn) as $val) {
        $value = end($val);
        $tables[$value] = new Table($value, $conn, $DB);
    }

    $res = Table::getForeignKeys($conn, $DB);
        
    while($row = mysqli_fetch_assoc($res))
    {
        // echo $row['TABLE_NAME'].", ".$row['REFERENCED_TABLE_NAME'].", ".  $row['COLUMN_NAME'].", ". $row['REFERENCED_COLUMN_NAME']."<br>";
        // echo "LINKING TO: ".$tables[$row['REFERENCED_TABLE_NAME']]->tblName."<BR>";
        $Tbl = $tables[$row['TABLE_NAME']];
        $lkTbl = $tables[$row['REFERENCED_TABLE_NAME']];

        $Col = $Tbl->hasColumn($row['COLUMN_NAME']);
        $lkCol = $lkTbl->hasColumn($row['REFERENCED_COLUMN_NAME']); 

        //($linkedTbl, $link, $linkTo)
        $Tbl->makeLink($lkTbl, $Col, $lkCol);
    }

    if(!isset($_SESSION["table"]))
    {
        $_SESSION["table"] =  reset($tables)->tblName;
    }


    $table = $tables[$_SESSION["table"]];

}

