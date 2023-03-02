<?php
if (function_exists($_GET['f'])){
    $_GET['f']();
}
function sendMail($time, $email, $station, $name, $bikeID){
    $empfänger =$email;
    $hilfe = getPrice($bikeID,$time);
    $preis = $hilfe[0];

    $total = $hilfe[1];
    
    $date = date('Y-m-d H:i:s');
  
    $betreff = "Rechnung EBike Rent Mannheim - vom $date";
    $from ="From: EBike Rent Mannheim <sophia.sauer1502@gmail.com>";
    $text = "Hallo $name, \n \n du hast am $date ein Fahrrad ausgeliehen und an der Station $station zurückgegeben. 
    \nDeine Fahrtzeit betrug  $total Minuten. Bei einem Preis von 0,10 €/ 0,15€ (für das Premium-Bike) ergibt sich eine Rechnung von $preis €.
    \nBitte überweise den Betrag innerhalb der nächsten 14 Tage.
    \n\n Viele Grüße und Danke, dass Du mit uns gefahren bist!
    \n Dein EBike Rent Mannheim Team";
    
    if (mail($empfänger, $betreff, $text, $from) !== false){
        echo "Email erfolgreich versendet";
    }else{
        echo "nicht versendet";
    }

    
    
}

function getPrice($bikeID, $time){
    $con = mysqli_connect("localhost", "root", "", "EBikeRental");
    
    $sql = "select premium from EBike where id = '$bikeID'";

    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);
    $premium = $row['premium'];
   

    
    $start = new DateTime($time);
    
    $return = new DateTime('now');
    $duration = $start -> diff($return);
  
    $total = getTotalMinutes($duration);


    if($premium ==1 ){
        $preis = $total * 0.15;
        
        
    }else {
        $preis = $total * 0.1;
        
    }
    echo "\n Preis:".$preis;
    $ret = array('$preis', '$total');
    return($ret);
}

function getTotalMinutes(DateInterval $int){
    return ($int->d * 24 * 60) + ($int->h * 60) + $int->i;
}



?>