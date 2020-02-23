<?php

foreach ($_POST as $key => $value) {

    setcookie($key, $value, time() + (86400 * 365), "/");
}

echo "Uloženo";

?>