<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PPBSConfigModel
{
    /** @var Display total area */
    private $display_total_area;

    /**
     * LRPConfigModel constructor.
     * @param string $currency_iso
     * @param int $id_group Customer Group
     */
    public function __construct($currency_iso = '', $id_group = 0, $id_shop = 0)
    {
        if ($currency_iso == '') {
            if (Validate::isLoadedObject(Context::getContext()->currency)) {
                $currency_iso = Context::getContext()->currency->iso_code;
            } else {
                $currency_iso = Currency::getDefaultCurrency()->iso_code;
            }
        }

        if ($id_shop == 0) {
            $id_shop = Context::getContext()->shop->id;
        }

        $this->currency_iso = $currency_iso;
        $this->id_group = (int)$id_group;
        $this->id_shop = (int)$id_shop;

        $this->setDisplayTotalArea(Configuration::get($this->getKey('ppbs_display_total_area', false), null, null, $this->id_shop));
    }

    /**
     * Get the configuration key for the Customer Group and Currency ISO Code
     * @param $key
     * @return string
     */
    private function getKey($key, $use_currency)
    {
        if ($use_currency) {
            return $key . '_' . $this->id_group . '_' . $this->id_shop . '_' . $this->currency_iso;
        } else {
            return $key . '_' . $this->id_group . '_' . $this->id_shop;
        }
    }

    /**
     * @return int
     */
    public function getDisplayTotalArea()
    {
        return $this->display_total_area;
    }

    /**
     * @param int $ratio
     * @return LRPConfigModel
     */
    public function setDisplayTotalArea($display_total_area)
    {
        $this->display_total_area = $display_total_area;
        return $this;
    }

    /**
     * Save a configuration value to storage
     * @param $key
     * @param $value
     */
    public function update($key, $value, $use_currency, $id_shop)
    {
        $key = $this->getKey($key, $use_currency);
        Configuration::updateValue($key, $value, false, null, (int)$id_shop);
    }

    /**
     * Savw all
     * @param bool $use_currency
     */
    public function updateAll($use_currency = false)
    {
        Configuration::updateValue($this->getKey('ppbs_display_total_area', $use_currency), $this->display_total_area, false, null, (int)$this->id_shop);
    }
}
