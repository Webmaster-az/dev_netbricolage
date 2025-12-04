<?php
    /* include_once(dirname(__FILE__).'/pancmultitracking.php'); */

  
        $result =  Db::getInstance()->insert('panc_multitracking', [
            'order_id' => 1,
        ]);
    
        echo "123";
        return $result;
    
    
    