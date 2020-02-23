<?php
    //require_once "../links.php";
?>
<style>
.settings input[type=text], .settings input[type=number], .settings input[type=email], .settings input[type=tel], .settings select, .settings option {
        background: #15232d;
        color: #ffc600;
        border: 1px solid #0d3a58;
        border-radius: 2px;
        padding: 6px;
        box-sizing: border-box;
        width: 100%;
}

</style>
<form id="settingsForm"  action="">

    <div class="settings">


        <h1>Nastavení</h1><span></span>
        <span>Defaultní server:</span> <input type="text" name="host" id="host"  value="<?php echo isset($_COOKIE['host']) ? $_COOKIE['host'] : "localhost"; ?>"/>
        <span>Defaultní uživatel:</span> <input type="text" name="user" id="user"  value="<?php echo isset($_COOKIE['user']) ? $_COOKIE['user'] : "root"; ?>"/>
        <span>Defaultní port:</span> <input type="number" name="port" id="port" min="0" max="100000" value="<?php echo isset($_COOKIE['port']) ? $_COOKIE['port'] : "3306"; ?>"/>
        
        <span>Defaultní maximum záznamů:</span> <input type="number" name="limit" id="limit" min="0" max="100000" value="<?php echo isset($_COOKIE['limit']) ? $_COOKIE['limit'] : "1000"; ?>"/>
        <input class="btn btnDark btnCookies" type="submit" value="Uložit"/>




    </div>
</form> 




