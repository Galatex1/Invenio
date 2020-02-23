<form id="filtering" action="" method="get" style="margin:auto;">
        <div class="columns">
            <div class="form">
                <div>
                    <h3><?php echo $table->tblName;?></h3>
                    <ul id="main">
                    <?php

                        $html = $table->makeTreeView();
                        echo $html;

                    ?>
                    </ul>
                </div>           
            </div>
            <div class="filter">
                <h3>Filtry</h3>
                <input id="orderBy" type="hidden" name="o_r_d_e_rBy" value=""/>
                <input id="orderDir" type="hidden" name="o_r_d_e_rDir" value="ASC"/>
                <span class="max">Max záznamů: <input type="number" name="l_i_m_i_t" id="l_i_m_i_t" value="<?php echo isset($_COOKIE['limit']) ? $_COOKIE['limit'] : "1000"; ?>"></span>
                <div class="btns">
                    <button class="btn btnFilter" value="<?php echo (+1) ?>">Přidat Filtr</button>
                    <button class="btn btnFilter remove" value="<?php echo (-1) ?>">Odebrat Filtr</button>
                </div>

            </div>
            <!-- <input class="btn btnSend" type="submit" value="obnovit" name="odeslat"> -->
        </div>
</form>