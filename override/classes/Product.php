<?php

class Product extends ProductCore
{
    /*
    * module: minpurchase
    * date: 2023-02-17 17:12:13
    * version: 1.2.2
    */
    public static function getProductProperties($id_lang, $row, Context $context = null)
    {
        if ($context == null) {
            $context = Context::getContext();
        }
        $row = parent::getProductProperties($id_lang, $row, $context);
        if (Module::isEnabled('minpurchase')) {
            if (!empty($row)) {
                include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
                $row = MinpurchaseConfiguration::setProductProperties($row);
            }
        }
        return $row;
    }
    
}