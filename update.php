<?php

require 'validation.php';

    header('Access-Control-Allow-Origin: *');
   
    if (function_exists($_GET['f'])){
        $_GET['f']();
    }

    function rent(){
        $conn = mysqli_connect("localhost", "root", "", "EBikeRental");
        $email = $_GET['email'];
        $bike = $_GET['bike'];
        $currentTime = date('Y-m-d H:i:s');
        
      
        if (! $conn) {
            die('Could not connect: ' . mysqli_connect_error());
        }
       
        // Validate the JWT token
        $token = $_GET['token'];
        
        if (! validateToken($token)) {
            die("Invalid token."); 
        }
        // Fahrrad wird bei User eingetragen und Zeit 
        $sql = "UPDATE `User` SET bike = $bike, timeOfRent = '$currentTime' WHERE email= '$email';"; 
        
        //Sucht id raus, um zweite Query aufzustellen 
        $sql1 = "SELECT id FROM User WHERE email = '$email'"; 
        $result = mysqli_query($conn, $sql1);
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
    
        // sucht die Station raus, an der sich Fahrrad befindet 
        $sqlStation = "SELECT station FROM EBike WHERE id = $bike"; 
        echo $sqlStation;
        $result2 = mysqli_query($conn, $sqlStation);
        echo $result2;

        // User wird dem Fahrrad hinzugefügt und station wird auf null gesetzt
        $sql2 = "UPDATE `EBike` SET lentto = $id, station = NULL WHERE id = $bike;";
        
        // bei Station wird count um 1 minimiert 
        $sql3= "UPDATE `Station` SET countOfBikes = (countOfBikes -1) WHERE id =$result2;";
        echo $sql3;
    
        if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3)) {
            echo "User hat erfolgreich ausgeliehen.";
        } else {
            echo "Error: Could not able to execute SQL." . mysqli_error($conn);    
        }
    
        mysqli_close($conn);
    }    
    

    function returnbike (){
        $conn = mysqli_connect("localhost", "root", "", "EBikeRental");
        $email = $_GET['email'];
        $bike = $_GET['bike'];
        $station = $_GET['station'];
        if(! $conn ){
        die('Could not connect: ' . mysqli_connect_error());
        }
   
        $sql = "UPDATE `User` SET bike = NULL WHERE email= '$email';"; 
        $sql2 = "UPDATE `EBike` SET lentto = NULL, station = $station WHERE id = $bike;";

        if (mysqli_query($conn, $sql) ){
            echo "User hat erfolgreich zurückgegeben.";
        }else{
            echo "Error: Could not able to execute §sql." . 
        mysqli_error($conn);    
        }

        if(mysqli_query($conn, $sql2)){
            echo "User hat erfolgreich ausgeliehen.";
        }else{
            echo "Error: Could not able to execute §sql." . 
        mysqli_error($conn);   
        }
        mysqli_close($conn);
    }
    
?>
