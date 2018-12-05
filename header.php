<?php 
    // Create connection
    $conn = mysqli_connect ("localhost", "root", "Ale91Marchesi", "horseracingsim");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    // functions
    function db () {
    $conn = mysqli_connect ("localhost", "root", "Ale91Marchesi", "horseracingsim");
    return $conn;
    }
    
?>