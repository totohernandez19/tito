<?php
    //--->get the app url > start
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
      $PROTO = 'https';
    }
    else {
      $PROTO = 'http';
    } 

    $app_url = ($PROTO  )
              . "://".$_SERVER['HTTP_HOST']
              //. $_SERVER["SERVER_NAME"]
              . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
              . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

    //app url
    define("APPURL", $app_url);
    //--->get the app url > end

    function app_db ()
    {
    	//get the php class from : https://github.com/codewithmark/PHP-Simple-Database-Class
    	include_once dirname(__FILE__).'/simple-database-class.php';

        $db_conn = array(
            'host'     => 'localhost', 
            'database' => 'db_mysite', 
            'user'     => 'root',
            'pass'     => '',        
        );

        $db = new SimpleDBClass($db_conn);
        return $db;     
    }
?>