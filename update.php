<?php
 header('Access-Control-Allow-Origin: *');

require 'validation.php';
require 'sendEmail.php';
   
   
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
        
        if (! validateToken($token, $email)) {
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
        $result2 = mysqli_query($conn, $sqlStation);
        $row2 = mysqli_fetch_assoc($result2);
        $station = $row2['station'];

        // User wird dem Fahrrad hinzugefügt und station wird auf null gesetzt
        $sql2 = "UPDATE `EBike` SET lentto = $id, station = NULL WHERE id = $bike;";
        
        // bei Station wird count um 1 minimiert 
        $sql3= "UPDATE `Station` SET countOfBikes = (countOfBikes -1) WHERE id = $station;";
    
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
        
        if (! $conn) {
            die('Could not connect: ' . mysqli_connect_error());
        }
       
        // Validate the JWT token
        $token = $_GET['token'];
        
        if (! validateToken($token, $email)) {
            die("Invalid token."); 
        }
        
        $sqlTime = "SELECT `timeOfRent`, `first name`FROM User WHERE email = '$email'";
        $resultTime = mysqli_query($conn, $sqlTime);
        $rowTime = mysqli_fetch_assoc($resultTime);
        $time = $rowTime['timeOfRent'];
        $name =$rowTime['first name'];
        

        $sqlPremium = "SELECT `premium` FROM `EBike` WHERE id = $bike";
        $resultPremium = mysqli_query($conn, $sqlPremium);
        $rowPremium = mysqli_fetch_assoc($resultPremium);
        $premium = $rowPremium ['premium'];
      
        sendMail($email, $station);
        getPrice($bike, $time);

        $sql = "UPDATE `User` SET bike = NULL, timeOfRent = null WHERE email= '$email';"; 

        $sqlStation = "SELECT `countOfBikes`, `maxCountOfBikes` FROM `Station` WHERE id = $station";
        $resultStation = mysqli_query($conn, $sqlStation);
        $rowTime = mysqli_fetch_assoc($resultStation);
       
        $countOfBikes = $rowTime['countOfBikes'];
        $maxCountOfBikes = $rowTime['maxCountOfBikes'];
        if(($countOfBikes)<$maxCountOfBikes){
            ++ $countOfBikes; 
            
            $sql2 = "UPDATE `EBike` SET lentto = NULL, station = $station WHERE id = $bike;";
            $sql3 = "UPDATE `station` SET countOfBikes = $countOfBikes  where id = $station;";
           

            if (mysqli_query($conn, $sql)&&mysqli_query($conn, $sql2) &&mysqli_query($conn, $sql3)){
                die(" ") ;
            }else{
                echo "Error: Could not able to execute $sql." . 
            mysqli_error($conn);    
            }
        }
      
        mysqli_close($conn);
    }
    
?>
