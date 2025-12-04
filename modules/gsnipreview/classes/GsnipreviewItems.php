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
 * @category content_management
 * @package prodtabs
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

class GsnipreviewItems extends ObjectModel
{
    /** @var string Name */
    public $id;
    public $title;
    public $id_shop;
    public $id_product;
    public $position;
    public $shop_name;
    public $language;
    public $status;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'gsnipreview',
        'primary' => 'id',
        'fields' => array(
            'id' => array('type' => self::TYPE_INT,'validate' => 'isUnsignedInt','required' => true,),
            'id_product' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),
            'id_customer' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),
            'customer_name' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),

            'title_review' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),

            'shop_name' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),
            'language' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),
            //'id_product' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),
            'is_active' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            //'type' => array('type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isGenericName', 'size' => 128),

        ),
    );




    public function deleteSelection($selection)
    {

        foreach ($selection as $value) {
            $obj = new GsnipreviewItems($value);

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

            require_once(_PS_MODULE_DIR_ . 'gsnipreview/classes/gsnipreviewhelp.class.php');
            $gsnipreviewhelp_obj = new gsnipreviewhelp();
            $gsnipreviewhelp_obj->delete(array('id'=>(int)$this->id));


            $return = true;
        }
        return $return;
    }


    
}
?>
