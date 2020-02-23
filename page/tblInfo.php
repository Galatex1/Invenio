<div class="btnsAction">
    
<?php include "page/tblButtons.php"; ?>

<table id="main">

<?php
$info = [
    'Vybraná databáze' => $table->Db, 
    'Vybraná tabulka' => $table->tblName,  /* $table->name, */ 
    // 'Připojené tabulky' => $table->links, 
    'Sloupce' => $table->columns, 
    'Primární klíče' => $table->primary, 
    'Auto incrementované' => $table->auto_incremented,
    'Počet záznamů' => $table->count() 
];

function makeInfoTree(array $structure = [])
{
    $html = "<tr>";

    foreach ($structure as $key => $value) {
        if(!is_numeric($key))
        {
            $html .= "<th style=\"white-space:nowrap;\">";        
            $html .= "$key</th> ";
        }
    }

    $html .= "</tr><tr>";

    foreach ($structure as $key => $value) {

        if(is_array($value)){
    
            
            $html .=  "<td><table>";
            //$html .= makeInfoTree($value);

            foreach ($value as $k => $v) {
                $html .= "<tr><td>$v</td></tr>";
            }

            $html .=  "</table></td>";
        }
        else if(is_object($value)){

        }
        else
        {
            $html .= "<td style=\"white-space:nowrap;\">";        
            $html .= "$value</td> ";
        }
        
         
    }
    $html .= "<tr>";


    return $html;
}



echo makeInfoTree($info);

?>

</table>

    <!-- <h2>Možné akce:</h2> -->
           
</div>