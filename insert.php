<?php
    header('Access-Control-Allow-Origin: *');
       //Einfügen in DB 
   
        require 'vendor/autoload.php';

        $conn = mysqli_connect("localhost", "root", "", "EBikeRental");
        $username = $_GET['username'];
        $firstname = $_GET['firstname'];
        $lastname = $_GET['lastname'];
        $address = $_GET['address'];
        $email = $_GET['email'];
       
        if(! $conn ){
            die('Could not connect: ' . mysqli_connect_error());
        }
        
        $sql = "INSERT INTO `User` (`id`, `username`, `first name`, `last name`, `address`, `email`, `bike`) VALUES (NULL, '$username', '$firstname', '$lastname','$address', '$email', NULL);";
        echo $sql;

        if (mysqli_query($conn, $sql)){
            echo "Records inserted successfully.";
        }else{
            echo "Error: Could not able to execute §sql." . 
        mysqli_error($conn);    
        }
        mysqli_close($conn);
    
?>  