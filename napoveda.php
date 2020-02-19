<div class="napoveda">
    <h1>Nápověda</h1>
    <h3>Tohle je prostě obyčejná aplikace, která něco umí.</h3>
    <p><b><u>+ Nový záznam</u></b> otevře formulář pro přidání nového záznamu do momentálně vybrané tabulky.</p>
    <p><b><u>Řazení</u></b> výsledků se provádí kliknutím na jméno sloupce, podle kterého chceme výsledky seřadit. Opětovné kliknutí na stejný sloupec řazení otočí.</p>
    <p><b><u>Mazání a editování</u></b> je možné pouze máte-li vybraný sloupec <b><i>id</i></b> pro danou tabulku.</p>
    <p><b><u>Filtry</u></b> jsou spojeny podmínkou <i><b>AND</b></i>. Např.: <i>"WHERE id > 0 AND rok < 2000"</i>. </p>
    <p><b><u>Jak použít pro jinou Databázi?</u></b> Pro použití s jinout databází je potřeba nejdříve nastavit tabulky a jejich vzájemné <i>Linky</i> (cizí klíče). Aplikace má jednu podmínku na tabulky: Každá tabulka musí mít sloupec <i>'id'</i>, který je používán jako identifikační a měl by být nastaven na Auto-Increment.</p>
    <p><b><u>Jak je aplikace napsaná?</u></b> Od píky. Žádné frameworky. Žádné knihovny.<span class="sub">(až na Jquery, ehm...)</span> Prostě jenom PHP, něco málo JavaScriptu, HTML a CSS.</p>
    <p><b><u>Proč má aplikace zrovna tohle barevné schéma?</u></b> Už delší dobu pracuju s programem VS Code, který jsem si hodně oblíbil. No a tohle je přesně to barevné schéma, které tam používám.</p>
    <p><b><u>V aplikaci něco nefunguje!</u></b> No to se stává... Na to, že jsem nepoužil žádný framework aplikace funguje dost. ;)</p>
    <p><b><u><i>"Nic dalšího už mě nenapadá..."</i> - Galatex 2020</u></b></p>
    <p><a target="_blank" href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Kompletní dokumentace</a></p>
    <p><a target="_blank" href="https://www.youtube.com/watch?v=FTQbiNvZqaY">Zdrojový kód</a></p>
</div>
<style>
.data-container{
    display:grid;
    grid-row-template: min-content 1fr;
}
</style>
<div class="copyright" title="General Kenobi! You are a bold one.">Hello there.</div>