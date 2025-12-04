<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
class Supplier extends SupplierCore
{
    /*
    * module: creativeelements
    * date: 2024-09-04 17:40:08
    * version: 2.10.1
    */
    const CE_OVERRIDE = true;
    /*
    * module: creativeelements
    * date: 2024-09-04 17:40:08
    * version: 2.10.1
    */
    public function __construct($id = null, $idLang = null)
    {
        parent::__construct($id, $idLang);
        $ctrl = Context::getContext()->controller;
        if ($ctrl instanceof SupplierController && !SupplierController::$initialized && !$this->active && Tools::getIsset('id_employee') && Tools::getIsset('adtoken')) {
            $tab = 'AdminSuppliers';
            if (Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee')) == Tools::getValue('adtoken')) {
                $this->active = 1;
            }
        }
    }
}
