<?php

require_once "DB.php";

header("Content-Type: text/html; charset=UTF-8");

$conn = mysqli_connect("localhost", "root", "", "absolvent");


mysqli_set_charset($conn, "utf8");
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_query($conn, "SET CHARACTER SET 'utf8'");

$tridy = ["BF", "B", "F", "BE", "BEF", "E", "EF"];

$mjmena = ["Jakub", "Jan", "Tomáš", "Adam", "Matyáš", "Filip", "Vojtěch", "Ondřej", "David", "Lukáš", "Matěj", "Daniel", "Martin", "Šimon", "Dominik", "Petr", "Štěpán", "Marek", "Jiří", "Michal", "Antonín", "Václav", "Kryštof", "Tobiáš", "Patrik", "Josef", "František", "Pavel", "Samuel", "Mikuláš", "Tadeáš", "Sebastian", "Oliver", "Jáchym", "Jaroslav", "Vít", "Jonáš", "Michael", "Richard", "Viktor"];

$mprijmeni = [ "Novák", "Svoboda", "Novotný", "Dvořák", "Černý", "Procházka", "Kučera", "Veselý", "Krejčí", "Horák", "Němec", "Marek", "Pospíšil", "Pokorný", "Hájek", "Král", "Jelínek", "Růžička", "Beneš", "Fiala" ];


$zjmeno = [ "Eliška", "Tereza", "Anna", "Adéla", "Natálie", "Sofie", "Kristýna", "Ema", "Karolína", "Viktorie", "Barbora", "Nela", "Veronika", "Lucie", "Kateřina", "Klára", "Marie", "Laura", "Aneta", "Julie", "Zuzana", "Amálie", "Anežka", "Nikola", "Nikol", "Sára", "Emma", "Michaela", "Markéta", "Gabriela", "Vanesa", "Alžběta", "Ella", "Elena", "Magdaléna", "Rozálie", "Simona", "Denisa", "Elen", "Hana" ];


$zprijmeni = [ "Nováková", "Svobodová", "Novotná", "Dvořáková", "Černá", "Procházková", "Kučerová", "Veselá", "Horáková", "Němcová", "Marková", "Pokorná", "Pospíšilová", "Hájková", "Králová", "Jelínková", "Růžičková", "Benešová", "Fialová", "Sedláčková" ];

$mesta = [ "Lipník nab bečvou", "Přerov", "Olomouc", "Prostějov", "Hranice na Moravě", "Bělotín", "Kojetín", "Osek", "Prosenice", "Týn" ];
$ulice = [ "Na hrázi", "Olomoucká", "Hranická", "U lesa", "Polní", "Ostravská", "Nová", "Seifertova", "Kozlovská", "Dlouhá", "Pod Skalkou", "Boženy Němcová", "Dr. Horákové", "Dolní", "Horní", "U potoka", "Nádražní", "Krátká", "Klapkova", "Masarykovo nábřeží", "Melantrichova", "Mělnická", "Michalská", "Michelská", "Mikulandská", "Široká", "Školská", "Šporkova", "Štěpánská", "Štupartská", "Jánský vršek", "Jelení", "Jilská", "Jindřišská", "Jiřská", "Josefská" ];



for ($i=0; $i < 10; $i++) {
    $nazev = "4". $tridy[rand(0, 6)];
    $rok =  rand(1950, 2019);
    //$resp = mysqli_query($conn, "INSERT INTO tridy (nazev, zacatek) VALUES ('".$nazev."', ".$rok." ) ");
    echo $nazev." ".$rok;
}



for ($i=0; $i < 10; $i++) {
    $jmeno = $mjmena[rand(0, 39)];
    $prijmeni =  $mprijmeni[rand(0, 19)];

    $zmeno = $zjmeno[rand(0, 39)];
    $zrijmeni =  $zprijmeni[rand(0, 19)];

    //$resp = mysqli_query($conn, "INSERT INTO ucitele (jmeno, prijmeni) VALUES ('".$jmeno."', '".$prijmeni."' ) ");
    //$resp = mysqli_query($conn, "INSERT INTO ucitele (jmeno, prijmeni) VALUES ('".$zmeno."', '".$zrijmeni."' ) ");
}


//1	David	Minařík	Přerov	Seifertova 8	750 02	2017	2020	1	1	1	sadad@sadv.cz	123456789

//ucitele 1-21
//tridy 1-21
//obor 1-3

//F 1
//E 2
//B 3
for ($i=0; $i < 21; $i++) {

    $trida = rand(1, 21);
    $ucitel = rand(1, 21);

    $resp = mysqli_query($conn, "SELECT nazev FROM tridy WHERE id =".$trida);
    $canUse = [];

    while($row = mysqli_fetch_assoc($resp))
    {
        if (strpos($row['nazev'], 'B') !== false) {
            array_push($canUse, 3);
        }
        if (strpos($row['nazev'], 'E') !== false) {
            array_push($canUse, 2);
        }
        if (strpos($row['nazev'], 'F') !== false) {
            array_push($canUse, 1);
        }
    }
    echo "<BR> CAN USE: ";
    var_dump($canUse);
        



    for ($j=0; $j < 10; $j++) {
        $jmeno = $mjmena[rand(0, 39)];
        $prijmeni =  $mprijmeni[rand(0, 19)];
        $mesto = $mesta[rand(0, 9)];
        $ulic = $ulice[rand(0, 35)]." ". rand(10, 1999);
        $psc = rand(10000, 99999);
        $nastup = rand(1950, 2015);
        $ukonceni = rand($nastup+4, $nastup+8);
        $email = $jmeno.$prijmeni."@"."seznam.cz";
        $tel = rand(100000000, 999999999);
        $obor = $canUse[rand(0, count($canUse)-1)];

        $zmeno = $zjmeno[rand(0, 39)];
        $zrijmeni =  $zprijmeni[rand(0, 19)];
        $zesto = $mesta[rand(0, 9)];
        $zlic = $ulice[rand(0, 35)]." ". rand(10, 1999);
        $zsc = rand(10000, 99999);
        $zastup = rand(1950, 2015);
        $zkonceni = rand($zastup+4, $zastup+8);
        $zmail = $zmeno.$zrijmeni."@"."seznam.cz";
        $zel = rand(100000000, 999999999);
        $zbor = $canUse[rand(0, count($canUse)-1)];

        $resp = mysqli_query($conn, "INSERT INTO zak (jmeno, prijmeni, mesto, ulice, psc, nastup, ukonceni, trida, ucitel, obor, email, telefon) VALUES ('".$jmeno."', '".$prijmeni."', '".$mesto."', '".$ulic."', '".$psc."', '".$nastup."', '".$ukonceni."', '".$trida."', '".$ucitel."', '".$obor."', '".$email."', '".$tel."' ) ");
        $resp = mysqli_query($conn, "INSERT INTO zak (jmeno, prijmeni, mesto, ulice, psc, nastup, ukonceni, trida, ucitel, obor, email, telefon) VALUES ('".$zmeno."', '".$zrijmeni."', '".$zesto."', '".$zlic."', '".$zsc."', '".$zastup."', '".$zkonceni."', '".$trida."', '".$ucitel."', '".$zbor."', '".$zmail."', '".$zel."' ) ");
    }

}









?>