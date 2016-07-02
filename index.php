<?php
    $config = array(
        "path_framework" =>  "/var/www/html/framerowk",
        "path_user"      =>  "/var/www/html/user",
    );

    include $config["path_framework"]."/core/CAPPLICATION.php";
    CAPPLICATION::run($config);
?>
