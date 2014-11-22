<?php
require("./settings.php");
$settings = new Settings();

switch($_REQUEST['do']) {
    case 'delEmail':
        $settings->delEmail($_REQUEST['id']);
}


?>