<style>
    .data-container{
        grid-row: 2/3;
        grid-column: 1/3;
        display: grid;
        justify-items: center;
        align-items: center;
    }
</style>
<fieldset class="connection">
    <legend>Připojení</legend>
    <form id="connForm" action="">
        <span>Host:</span><input type="text" name="host" id="host" value="<?php echo isset($_COOKIE['host']) ? $_COOKIE['host'] : "localhost"; ?>">
        <span>Uživatel:</span><input type="text" name="user" id="user" value="<?php echo isset($_COOKIE['user']) ? $_COOKIE['user'] : "root"; ?>">
        <span>Heslo:</span><input type="password" name="pass" id="pass" value="">
        <span>Port:</span><input type="number" name="port" id="port" value=<?php echo isset($_COOKIE['port']) ? $_COOKIE['port'] : "3306"; ?> min=0>
        <input class="btn btnDark btnConnect" type="submit" value="Připojit se"/>
    </form>
</fieldset>