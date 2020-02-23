<button class="btn btnReload"> <big>#</big> Změnit připojení</button>
<span>
    <form class="tbls" id="tblDB" action="action/DbAndTable.php" method="post">
        <span><!-- Databáze: --><img src="assets/img/DB.png" alt="Databáze" title="Databáze"></span> 
        <select name="DB" id="DB" class="DB">
            <!-- <option value="">-</option> -->
            <?php 
            $selected = Table::getDB($conn)['DB'];
            ?>
            <?php foreach (Table::getDBs($conn) as $key => $value): ?>                  
            <option value="<?php echo $value; ?>"  <?php echo $selected == $value ? "selected" : "";   ?>  ><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</span>