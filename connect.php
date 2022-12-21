<?php  
    //enable error detail output instead of "Page not found"
    ini_set('display_errors', 1);
    error_reporting(~0);
    
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "exercise-db";

    try {
        $dsn = "mysql:host=" . $dbHost . ";dbname=" . $dbName;
        $pdo = new PDO($dsn, $dbUser, $dbPassword);
        //echo "db connection success!";
    } catch(PDOException $e) {
        echo "DB Connection Failed: " . $e->getMessage();
    }              
            
?>