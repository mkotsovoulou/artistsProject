<?php
include "config.php";

try{
    $db = new PDO("mysql:host=" . DB_HOST .
        ";dbname=" . DB_NAME .
        ";port=" . DB_PORT,DB_USER,DB_PASS);

    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "connected" . '<br/>';
}
catch (Exception $e){
    echo "Database Connection Error.";
    exit;
}