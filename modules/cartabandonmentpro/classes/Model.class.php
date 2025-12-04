<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class CartAbandonmentProModel
{
    private $leftColumn     = false;
    private $rightColumn    = false;
    private $txtsRight      = 0;
    private $txtsLeft       = 0;
    private $txtsCenter     = 0;
    private $colors         = 0;
    private $id_model       = null;
    private $content        = '';
    private $contentEdit    = array();
    private $cartProducts   = '';

    public function __construct($id_model = null)
    {
        if (is_null($id_model)) {
            return false;
        }
        $this->id_model = $id_model;
        $this->initConf();
    }

    private function initConf()
    {
        require dirname(__FILE__).'/../model/'.$this->id_model.'.conf.php';
        $this->leftColumn       = $leftColumn;
        $this->rightColumn      = $rightColumn;
        $this->txtsRight        = $txtsRight;
        $this->txtsLeft         = $txtsLeft;
        $this->txtsCenter       = $txtsCenter;
        $this->colors           = $colors;
        $this->content          = $content;
        $this->contentEdit[1]   = $contentEdit1;
        $this->contentEdit[2]   = $contentEdit2;
        $this->contentEdit[3]   = $contentEdit3;
        $this->cartProducts     = $cartProducts;
    }

    public function getLeftColumn()
    {
        return $this->leftColumn;
    }
    public function getRightColumn()
    {
        return $this->rightColumn;
    }
    public function getTxtsRight()
    {
        return $this->txtsRight;
    }
    public function getTxtsLeft()
    {
        return $this->txtsLeft;
    }
    public function getTxtsCenter()
    {
        return $this->txtsCenter;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getContentEdit($wichOne)
    {
        return $this->contentEdit[$wichOne];
    }
    public function getId()
    {
        return $this->id_model;
    }
    public function getColors()
    {
        return $this->colors;
    }
    public function getCartProducts()
    {
        return $this->cartProducts;
    }
}
