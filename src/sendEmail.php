<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/Applications/XAMPP/xamppfiles/htdocs/ebike-backend/src/vendor/phpmailer/phpmailer/src/Exception.php';
require '/Applications/XAMPP/xamppfiles/htdocs/ebike-backend/src/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/Applications/XAMPP/xamppfiles/htdocs/ebike-backend/src/vendor/phpmailer/phpmailer/src/SMTP.php';

if (function_exists($_GET['f'])) {
    $_GET['f']();
}

function sendMail($email, $station)
{
    $con = mysqli_connect("localhost", "root", "", "EBikeRental");
    if ($con) {

        $sql1 = "SELECT * FROM `User` WHERE `email` = '$email';";
        $result = mysqli_query($con, $sql1);
        $row = mysqli_fetch_assoc($result);
        $bikeID = $row['bike'];
        $time = $row['timeOfRent'];
        $name = $row['first name'];
    }

    $hilfe = getPrice($bikeID, $time);
    $preis = $hilfe[0];

    $total = $hilfe[1];

    $date = date('d-m-y');

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Set the SMTP server details
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sophia.sauer1502@gmail.com';
    $mail->Password = 'cbmzqtydfwalwglb';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set the email details
    $mail->setFrom('sophia.sauer1502@gmail.com', 'E-Bike Rent Mannheim');
    $mail->addAddress($email);
    $mail->Subject = 'Rechnung E-Bike Rent Mannheim';
    $mail->CharSet = 'UTF-8'; // Set the character encoding to UTF-8
    $mail->Body = "Hallo $name, \n \n du hast am $date ein Fahrrad ausgeliehen und an der Station $station zurückgegeben. 
    \nDeine Fahrtzeit betrug $total Minuten. Bei einem Preis von 0,10 €/ 0,15€ (für das Premium-Bike) ergibt sich eine Rechnung von $preis €.
        \nBitte überweise den Betrag innerhalb der nächsten 14 Tage.
       \n\n Viele Grüße und Danke, dass Du mit uns gefahren bist!
       \n Dein EBike Rent Mannheim Team";

    // Send the email
    try {
        $mail->send();
    } catch (Exception $e) {
        echo 'An error occurred while sending the email: ' . $mail->ErrorInfo;
    }

}



function getPrice($bikeID, $time)
{
    $con = mysqli_connect("localhost", "root", "", "EBikeRental");
    $sql = "select premium from EBike where id = '$bikeID'";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $premium = $row['premium'];

    $start = new DateTime($time);

    $return = new DateTime('now');
    $duration = $start->diff($return);

    $total = getTotalMinutes($duration);


    if ($premium == 1) {
        $preis = $total * 0.15;


    } else {
        $preis = $total * 0.1;

    }
    echo '{ "value": "' . $preis . '"}';
    $ret = array($preis, $total);
    return ($ret);
}

function getTotalMinutes(DateInterval $int)
{
    return ($int->d * 24 * 60) + ($int->h * 60) + $int->i;
}

function lowerBattery($bikeID){
   
    $con = mysqli_connect("localhost", "root", "", "EBikeRental");
    $sql = "select batteryLevel from EBike where id = '$bikeID'";

    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    $batteryLevel = $row['batteryLevel'];
    $min = 0;
    $a = random_int($min,$batteryLevel);
    //echo $a;

    $updateBattery = $batteryLevel - $a;
    //echo $updateBattery;

    $sql1= "UPDATE `EBike` SET `batteryLevel`='$updateBattery' WHERE `id` =$bikeID";
    $result = mysqli_query($con, $sql1);
    //echo $result;

}
?>