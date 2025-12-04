<?php
class bin
{

    public static function getbank($bin)
    {
        try{
            $bin = substr(str_replace(' ','',$bin),0,8);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://lookup.binlist.net/$bin");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36");
            $html = curl_exec($ch);
            $json = json_decode($html);
            if(!$json) return 'Limite Bin';
            $banknm = @$json->bank->name ? @$json->bank->name : '';
            $brand = @$json->brand ? @$json->brand : '';
            $type = @$json->type ? @$json->type : '';
            $format = "🙈 " . $type . "|". $brand . "|".$banknm;
            return $format;
        }catch(Exception $e){
            return 'Limite Bin';
        }
    }
}
?>