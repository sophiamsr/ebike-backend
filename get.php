<?php
    header('Access-Control-Allow-Origin: *');
    header("HTTP/1.1 200 OK");

    require 'validation.php';

    if (function_exists($_GET['f'])){
        $_GET['f']();
    }
    //Abfrage von User
    function getUser(){

        $token = $_GET['token'];
        $email = $_GET['email'];
        
        if (! validateToken($token, $email)) {
            die("Invalid token."); 
        }

        $con = mysqli_connect("localhost", "root", "", "EBikeRental");
        $response = array();
        if($con) {
            $sql = "select * from User where email = '$email'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_assoc($result);
            if ($row['id']!== null){
                http_response_code(200);

                $response['id'] = $row['id']; 
                $response['username'] = $row['username'];
                $response['firstname']  = $row['first name'];
                $response['lastname'] = $row['last name'];
                $response['address'] = $row['address'];
                $response['email'] = $row['email'];
                $response['bike'] = $row['bike'];
                $response['timeOfRent'] = $row['timeOfRent']; 
                echo json_encode($response,JSON_PRETTY_PRINT);      
                }
                
            else{
                echo http_response_code(404);
            }
        }
        
    }
    //Abfrage von Station 
    function getStation(){
        $con = mysqli_connect("localhost", "root", "", "EBikeRental"); 
        if($con) {
            $sql1 = "select * from Station";
            $result1 = mysqli_query($con,$sql1);
             if($result1){
                 $y = 0;
                 while ($row = mysqli_fetch_assoc($result1)){
                     $response1[$y]['id'] = $row['id'];
                     $response1[$y]['name'] = $row['name'];
                     $response1[$y]['longitude'] = $row['longitude'];
                     $response1[$y]['latitude'] = $row['latitude'];
                     $response1[$y]['countOfBikes'] = $row['countOfBikes'];
                     $response1[$y]['maxCountOfBikes'] = $row['maxCountOfBikes'];
                     $y++;
                 }
                echo json_encode($response1, JSON_PRETTY_PRINT);  
             }
             else{
                 echo "Datebase connection failed";
             }
         }
    }
    //Abfrage von EBike 
    function getEBike(){
        $con = mysqli_connect("localhost", "root", "", "EBikeRental"); 
        if($con) {
            $sql2 = "select * from EBike";
            $result2 = mysqli_query($con,$sql2);
             if($result2){
                 $z = 0;
                 while ($row = mysqli_fetch_assoc($result2)){
                     $response2[$z]['id'] = $row['id'];
                     $response2[$z]['premium']= $row['premium'];
                     $response2[$z]['name'] = $row['name'];
                     $response2[$z]['station'] = $row['station'];
                     $response2[$z]['battery level'] = $row['battery level'];
                     $response2[$z]['lentto'] = $row['lentto'];
                     $z++;
                 }
                echo json_encode($response2, JSON_PRETTY_PRINT); 
                    
             }
             else{
                 echo "Datebase connection failed";
             }
         }
    }

?>    