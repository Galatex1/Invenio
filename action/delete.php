<?php

require_once "../links.php";

if(isset($_POST['delete']) && $_POST['delete'] !="")
{
    $id = $_POST['delete'];
    $table->delete($id);
}
else {
    die("Error - Not deleted. Delete variables not set.");
}


?>