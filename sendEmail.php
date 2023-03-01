<?php

function sendMail($time, $email, $station, $name, $premium){
    $empfänger =$email;
    
    $start = new DateTime($time);
    $return = new DateTime('now');
    $duration = $start -> diff($return);
    $total = $duration->format('%H.%M.%I');
    
    $date = date('Y-m-d H:i:s');
    if($premium ===1 ){
        $preis = $total * 0.15;
        
    }else {
        $preis = $total * 0.1;
        echo $preis;
    }
  
  
    $betreff = "Rechnung EBike Rent Mannheim - vom $date";
    $from ="From: EBike Rent Mannheim <sophia.sauer1502@gmail.com>";
    $text = "Hallo $name, \n \n du hast am $date ein Fahrrad ausgeliehen und an der Station $station zurückgegeben. 
    \nDeine Fahrtzeit betrug $total Stunden. Bei einem Preis von 0,10 €/ 0,15€ (für das Premium-Bike) ergibt sich eine Rechnung von $preis €.
    \nBitte überweise den Betrag innerhalb der nächsten 14 Tage.
    \n\n Viele Grüße und Danke, dass Du mit uns gefahren bist!
    \n Dein EBike Rent Mannheim Team";
    
    if (mail($empfänger, $betreff, $text, $from) !== false){
        echo "Email erfolgreich versendet";
    }else{
        echo "nicht versendet";
    }
    
}

?>