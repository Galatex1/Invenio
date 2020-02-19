
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <script src="Jquery.js"></script>
    <script src="script.js"></script>
    <title>Databáze absolventů</title>
</head>
<body>
    

<?php

session_start();
require_once "links.php";

if(isset($_POST["table"]) && $_POST["table"] != "")
{
    $table = $tables[$_POST["table"]];
    $_SESSION['table'] = $_POST["table"];
}


?>

<header>
    
    <span>
        
        <form id="tbls" action="" method="post">
            <span>Tabulka: </span> 
            <select name="table" id="table" class="table">
                
                <?php foreach (Table::getTables($conn) as $key => $value): ?>
                
                <option value="<?php echo end($value); ?>"  <?php echo $table->tblName == end($value) ? "selected" : "";   ?>  ><?php echo end($value); ?></option>
                <?php endforeach; ?>
                
            </select>
        </form>
    </span>
       
    <button class="btn"> <big>+</big> Novy Zaznam</button>
</header>

<form id="filtering" action="" method="get" style="margin:auto;">
    <div class="columns">
        <div class="form">
            <ul id="main">
            <?php

                // echo "<legend>".$table->tblName."</legend>";

                foreach ($table->columns as $key) {

                    $isLinked = $table->isLinked($key);

                    if($isLinked === false)
                    {
                        echo "<li class=\"chkBox\" style=\"white-space:nowrap;\"><input type=\"checkbox\" name=\"$key\" id=\"$key\" checked/> $key</li> ";
                    }
                    else {
                        echo "<li>";
                        echo "<span class=\"caret\"><input type=\"checkbox\" name=\"$key\" id=\"$key\" checked/> $key</span>";
                        echo "<ul class=\"nested\">";
                        foreach ($table->links[$isLinked]->linkedTbl->columns as $cl) {
                            echo "<li class=\"chkBox\" style=\"white-space:nowrap;\"><input type=\"checkbox\" name=\"$isLinked.$cl\" id=\"$cl\" checked/> $cl</li> ";
                        }
                        echo "</ul></li>";
                    }
                }

            ?>
            </ul>           
        </div>
        <div class="filter">
            <h3>Filtry</h3>

            <div class="btns">
                <button class="btn btnFilter" value="<?php echo (+1) ?>">Add Filter</button>
                <button class="btn btnFilter" value="<?php echo (-1) ?>">Remove Filter</button>
            </div>

            <!-- <div>WHERE</div> -->

        </div>
        <input class="btn btnSend" type="submit" value="obnovit" name="odeslat">
    </div>
</form>


<?php

// $sql="SELECT ";
// $cols = array();
// $index = 0;
// foreach ($_GET as $key => $value) {

//     if($key != "odeslat" && strpos($key, "column-") === false && strpos($key, "operand-") === false && strpos($key, "value-") === false)
//     {
//         if(count($_GET) - 2 == $index)
//             $sql .= "$key ";
//         else
//             $sql .= "$key, ";

//         $cols[] = $key;
//     }

//     $index++;
// }

// $sql .= " FROM zak JOIN ucitele ON zak.ucitel = ucitele.id JOIN tridy ON zak.trida = tridy.id JOIN obor ON obor.id = zak.obor";

// // $resp2 = $table->getAllLinked(["*"], 
// // [
// //     ["id","jmeno", "prijmeni"],
// //     ["nazev", "zkratka", "popis"],
// //     ["nazev", "zacatek"]
// // ], ["ucitele", "obor", "tridy"], ["ucitel", "obor", "trida"], ["id", "id", "id"], "1", "1");

// $resp2 = $table->getAllLinked($cols,  "1");


// //$resp2 = $table->getAllLinked(["jmeno", "prijmeni", "ucitele.jmeno", "ucitele.prijmeni"],  "1", "1");

?>

<div class="data-container">



    <!-- <table>

    <?php

    // if(!isset($_GET['odeslat']))
    // die("No sql was sent");

    // $first = true;

    // while($row = mysqli_fetch_assoc($resp2))
    // {

    //     if($first)
    //     {
    //         echo "<tr>";
    //         foreach ($row as $key => $value) {
    //             echo "<th style=\"white-space:nowrap;\">$key</th>";
    //         }
    //         echo "</tr>";
    //     $first = false;
    //     }

        
    //     echo "<tr>";
    //     foreach ($row as $key => $value) {
    //         echo "<td style=\"white-space:nowrap;\">$value</td>";
    //     }
    //     echo "</tr>";
        
    
    // }

    ?>


    </table> -->
</div>

</body>
</html>