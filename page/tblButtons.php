<button class="btn btnQuery" title="Vypsat data z tabulky podle filtrů."><img src="assets/img/icon.png"/><span>Najít</span></button>

<?php if(isset($_SESSION['table']) && $_SESSION['table'] != ""): ?>
    <button class="btn btnNew"><big>+</big> <span>Nový Záznam</span></button>
<?php endif; ?>