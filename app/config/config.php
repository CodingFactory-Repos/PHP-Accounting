<?php
    // Database Settings
    define('DB_HOST', 'mysql-codingfactory.alwaysdata.net');
    define('DB_USER', '253973_website');
    define('DB_PASS', 'h&@38!7P6ttk5NJYxKtJ$QknMY4L9g');
    define('DB_NAME', 'codingfactory_petit_comptable');
    
    //define('DB_HOST', 'localhost');
    //define('DB_USER', 'root');
    //define('DB_PASS', '');
    //define('DB_NAME', 'MyDataBase');
    
    // APP ROOT
    define('APP_ROOT', dirname(dirname(__FILE__)));
    
    // URL ROOT
    if(strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false){
        define('URL_ROOT', 'http://'.$_SERVER['HTTP_HOST'].str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']));
    } else {
        define('URL_ROOT', 'https://'.$_SERVER['HTTP_HOST'].str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']));
    }
    //define('URL_ROOT', 'https://PetitComptable.com');
    
    // Nom du site
    define('SITE_NAME', 'PetitComptable');
    
	//Meta
    define('CARD_DESCRIPTION', 'Une platforme ludique pour faire ses comptes et garder le sourir');
    define('CARD_IMAGE', 'https://');