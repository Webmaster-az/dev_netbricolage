<?php
    include_once ('./config/config.inc.php');
    $products = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'product_lang');
    foreach ($products as $product){
        Db::getInstance()->execute("UPDATE "._DB_PREFIX_."product_lang SET link_rewrite = '".Tools::str2url($product['name'])."' WHERE id_product = ".$product['id_product']." AND id_shop = ".$product['id_shop']." AND id_lang = ".$product['id_lang']);
    }
    echo 'done';
?>