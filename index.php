
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/js/Jquery.js"></script>
    <script src="assets/js/script.js"></script>
    <title>Invenio - MySQL Client</title>
</head>
<body>
    

    <?php
    //error_reporting(E_ERROR);
    header('Content-Type: text/html; charset=utf-8');
    session_start();

    if(isset($_SESSION['host']) && $_SESSION['host'] != "")
        require_once "links.php"; 

    ?>

    <header>
        
        <h1><a href=""><img class="icon" src="assets/img/icon.png">INVENIO</a></h1>
        <h3>MySQL klient by Galatex</h3>
        <?php
            if(isset($_SESSION['host']) && $_SESSION['host'] != "")
                require_once "page/dbSelect.php";
            
            if(isset($_SESSION['DB']) && $_SESSION['DB'] != "")
                require_once "page/tblSelect.php";

            if(isset($_SESSION['DB']) && $_SESSION['DB'] != "")
                require_once "page/tblButtons.php";

            if(isset($_SESSION['DB']) && $_SESSION['DB'] != "")
                require_once "page/buttons.php";
                
        ?>
        
    </header>

    <?php
    if(isset($_SESSION['table']) && $_SESSION['table'] != "")
        require_once "page/treeView.php";
    ?>


    <div class="data-container">
        <?php
        if(!isset($_SESSION['host']) || $_SESSION['host'] == "")
            require_once "page/connection.php";
        else {
            include "page/tblInfo.php";          
        }
        ?>
        
    

        <script>
            $(document).ready(()=>{
                // $('.btnSettings').trigger('click');
            })
        
        </script>
        <!-- <pre>
        <?php
        

        ?>
        </pre> -->

    </div>
    <button class="export btn" title="Exportovat data"><img src="assets/img/export.png" alt="Export"></button>
    <div class="response"></div>
    <div class="loading"><span class="spinner"></span></div>
</body>
</html>