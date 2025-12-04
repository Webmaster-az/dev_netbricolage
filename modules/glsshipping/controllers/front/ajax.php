<?php
/**
* 2019-2020 GLS
*
* NOTICE OF LICENSE
*
*  @author    GLS
*  @copyright 2019-2020 YDRAL.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/

class GlsshippingAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        if (!$this->isXmlHttpRequest()) {
            Tools::redirect(__PS_BASE_URI__);
        }
		switch (Tools::getValue('method')) {
            case 'saveParcel':
				$codigo = Tools::getValue('codigo');
				$nombre = Tools::getValue('nombre');
				$direccion = Tools::getValue('direccion');
				$cp = Tools::getValue('cp');
				$localidad = Tools::getValue('localidad');
				if (!empty($codigo) && $codigo != '[object Object]'){
					$context = Context::getContext();
					$cartId = $this->context->cookie->id_cart;
					$parcel = Tools::getValue('parcel');
													
					$sql = sprintf('SELECT * FROM '._DB_PREFIX_.'gls_parcels WHERE id_cart = %d',
						pSQL($cartId)
					);
					if ($row = Db::getInstance()->getRow($sql)){
						Db::getInstance()->update('gls_parcels', array(
								'codigo'	=> pSQL($codigo),
								'nombre'	=> pSQL($nombre),
								'direccion'	=> pSQL($direccion),
								'cp'		=> pSQL($cp),
								'localidad'	=> pSQL($localidad),
							), 
							'id='.pSQL($row['id']), 
							1, 
							true
						);							
					} else {
						Db::getInstance()->insert('gls_parcels', array(
								'id_cart'	=> pSQL($cartId),
								'codigo'	=> pSQL($codigo),
								'nombre'	=> pSQL($nombre),
								'direccion'	=> pSQL($direccion),
								'cp'		=> pSQL($cp),
								'localidad'	=> pSQL($localidad),
							)
						);
					}
				}
				break;
        }
        exit;
    }
}
