<?php
    $host = "localhost:3307";
    $user = "root";
    $pass = "";
    $dbname = "project";
    $conn = new mysqli($host , $user, $pass, $dbname);
    mysqli_query($conn , "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
    if($conn->connect_error){
        die("Database Error : " . $conn->connect_error);
    }
?>