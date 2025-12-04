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
* -------------------------------------------------------------------
*
* Description :
*   This is a PHP class for replace SEO tags.
*/

class APIFAQ
{
    public function getData($m)
    {
        $content = '';
        if (function_exists('curl_init')) {
            $context = Context::getContext();
            $iso_code = Language::getIsoById($context->language->id);
            $url = 'https://api.addons.prestashop.com/request/faq/'.$m->module_key.'/'.$m->version.'/'.$iso_code;

            $options = array(
              CURLOPT_URL             => $url,
              CURLOPT_RETURNTRANSFER  => true,
              CURLOPT_HEADER          => false,
              CURLOPT_SSL_VERIFYHOST  => 2,
              CURLOPT_SSL_VERIFYPEER  => false
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $content = curl_exec($curl);
            curl_close($curl);

            return $content;
        }
    }
}
