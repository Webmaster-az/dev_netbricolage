<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */
abstract class ProductListingFrontController extends ProductListingFrontControllerCore
{
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:22
    * version: 7.8.3
    */
    protected function doProductSearch($template, $params = array(), $locale = null)
    {
        if (!Tools::getIsset('page_cache_dynamics_mods')) {
            return parent::doProductSearch($template, $params, $locale);
        }
    }
}
