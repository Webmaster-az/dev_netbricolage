<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

class ShopreviewsItems extends ObjectModel
{


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'gsnipreview_storereviews',
        'primary' => 'id',

    );




    public function deleteSelection($selection)
    {
        foreach ($selection as $value) {
            $obj = new ShopreviewsItems($value);
            if (!$obj->delete()) {
                return false;
            }
        }
        return true;
    }

    public function delete()
    {
        $return = false;

        if (!$this->hasMultishopEntries() || Shop::getContext() == Shop::CONTEXT_ALL) {

            require_once(_PS_MODULE_DIR_ . 'gsnipreview/classes/storereviews.class.php');
            $shopreviews = new storereviews();
            $shopreviews->deteleItem(array('id'=>(int)$this->id));


            $return = true;
        }
        return $return;
    }


    
}
?>
