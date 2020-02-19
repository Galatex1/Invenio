
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="style.css">
    <script src="Jquery.js"></script>
    <script src="script.js"></script>
    <title>Databáze absolventů</title>
</head>
<body>
    

    <?php
    header('Content-Type: text/html; charset=utf-8');
    session_start();
    require_once "links.php";

    if(isset($_POST["table"]) && $_POST["table"] != "")
    {
        $table = $tables[$_POST["table"]];
        $_SESSION['table'] = $_POST["table"];
    }


    ?>

    <header>
        
        <h1><img class="icon" src="icon.png"> ABSOLVENT</h1>
        <h3>MySQL klient by Galatex</h3>
        <span>
            <form id="tbls" action="" method="post">
                <span>Databáze: </span> 
                <select name="DB" id="DB" class="DB">
                    <?php $selected = end(Table::getDB($conn)); print_r(end($selected)); ?>
                    <?php foreach (Table::getDBs($conn) as $key => $value): ?>                  
                    <option value="<?php echo end($value); ?>"  <?php echo $selected == end($value) ? "selected" : "disabled";   ?>  ><?php echo end($value); ?></option>
                    <?php endforeach; ?>
                    
                </select>
            </form>
        </span>
        <span>
            <form id="tbls" action="" method="post">
                <span>Tabulka: </span> 
                <select name="table" id="table" class="table">
                    
                    <?php foreach (Table::getTables($conn) as $key => $value): ?>
                    
                    <option value="<?php echo end($value); ?>"  <?php echo $table->tblName == end($value) ? "selected" : "";   ?>  ><?php echo $tables[end($value)]->name; ?></option>
                    <?php endforeach; ?>
                    
                </select>
            </form>
        </span>
        
        <button class="btn btnNew"> <big>+</big> Nový Záznam</button>
        <button class="btn btnNapoveda">? Nápověda</button>
        <button class="btn btnSettings"> <img src="cog.png" alt="Nastavení" title="Nastavení"></button>
    </header>

    <form id="filtering" action="" method="get" style="margin:auto;">
        <div class="columns">
            <div class="form">
                <ul id="main">
                <?php

                    $struct = array();
                    $html = "";
                    $table->getStructure($struct);
                    Table::makeTreeView($struct, $html);
                    echo $html;

                ?>
                </ul>           
            </div>
            <div class="filter">
                <h3>Filtry</h3>

                <div class="btns">
                    <button class="btn btnFilter" value="<?php echo (+1) ?>">Přidat Filtr</button>
                    <button class="btn btnFilter" value="<?php echo (-1) ?>">Odebrat Filtr</button>
                </div>

            </div>
            <input class="btn btnSend" type="submit" value="obnovit" name="odeslat">
        </div>
    </form>


    <div class="data-container">
        <script>
            $(document).ready(()=>{
                $('.btnNew').trigger('click');
            })
        
        </script>
        <pre>
        <?php
        

        ?>
        </pre>

    </div>

</body>
</html>