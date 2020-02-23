
<span>
    <form class="tbls" id="tbls" action="action/DbAndTable.php" method="post">
        <span><!-- Tabulka:  --><img src="assets/img/table.png" alt="Tabulka" title="Tabulka"> </span> 
        <select name="table" id="table" class="table" >
            <?php foreach (Table::getTables($conn) as $key => $value): ?>
            <?php //echo end($value); ?>
            <option value="<?php echo end($value); ?>"  <?php echo $table->tblName == end($value) ? "selected" : "";   ?>  ><?php echo end($value); //echo $tables[end($value)]->name; ?></option>
            
            <?php endforeach; ?>
            
        </select>
    </form>
</span>
<button class="btn btnRefresh" title="Obnovit tabulku."><img src="assets/img/reload.png" height="20px"/></button>
<!-- <button class="btn btnQuery" title="Vypsat data z tabulky podle filtrů."><img src="assets/img/icon.png"/><span>Najít</span></button>

<?php if(isset($_SESSION['table']) && $_SESSION['table'] != ""): ?>
    <button class="btn btnNew"> <big>+</big> Nový Záznam</button>
<?php endif; ?>


<button class="btn btnNapoveda">? Nápověda</button>
<button class="btn btnSettings"> <img src="assets/img/cog.png" alt="Nastavení" title="Nastavení"/></button> -->