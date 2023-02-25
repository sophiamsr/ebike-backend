<?php
    header('Access-Control-Allow-Origin: *');

    if (function_exists($_GET['f'])){
        $_GET['f']();
    }
    //Abfrage von User
    function getUser(){
        $con = mysqli_connect("localhost", "root", "", "EBikeRental");
        $response = array();
        if($con) {
            $sql = "select * from User";
            $result = mysqli_query($con,$sql);
             if($result){
                 $x = 0;
                 while ($row = mysqli_fetch_assoc($result)){
                     $response[$x]['id'] = $row['id'];
                     $response[$x]['username'] = $row['username'];
                     $response[$x]['first name'] = $row['first name'];
                     $response[$x]['last name'] = $row['last name'];
                     $response[$x]['address'] = $row['address'];
                     $response[$x]['email'] = $row['email'];
                     $response[$x]['bike'] = $row['bike'];
                     $x++;
                 }
                echo json_encode($response, JSON_PRETTY_PRINT);
             }
             else{
                echo "Datebase connection failed";
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