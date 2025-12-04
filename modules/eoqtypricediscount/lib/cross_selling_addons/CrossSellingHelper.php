<?php
/**
 *  2015-2020 Profileo
 *
 *  @author    Profileo <contact@profileo.com>
 *  @copyright 2015-2020 Profileo
 *  @license   Profileo
 *  @link  http://www.profileo.com
 */
if (!class_exists('CrossSellingHelper')) {
    class CrossSellingHelper
    {
    
        private static $ourModule;
    
        private static $otherModules = array();
    
        public static function preLoadExternalDatas($module)
        {
            $cache_file = dirname(__FILE__) . '/cache.cache';
            $now = time();
            if (file_exists($cache_file)) {
                $fichier_date = filemtime($cache_file);
            }
    
            $timeDiff = 86400;
            $difference_day = isset($fichier_date) ? ($now - $fichier_date) : ($timeDiff);
            if ($difference_day >= $timeDiff) {
                $datas = CrossSellingHelper::getExternalData($module->module_key);
                
                // Check, because the module can be not plubished for now
                $dataCheck = Tools::jsonDecode($datas);
                if (isset($dataCheck->errors) && isset($dataCheck->errors->code)) {
                    $datas = CrossSellingHelper::getExternalData('8999c2693d17e83b32f0f1cfbf38a131');
                }
                
                $fp = fopen($cache_file, 'w');
                fwrite($fp, $datas);
                fclose($fp);
            }
    
            $dataArray = Tools::jsonDecode(Tools::file_get_contents($cache_file));
    
            $module_name = $module->name;
            
            foreach ($dataArray->products as $currentModule) {
                if ($currentModule->name == $module_name) {
                    CrossSellingHelper::$ourModule = $currentModule;
                } else {
                    CrossSellingHelper::$otherModules[] = $currentModule;
                }
            }
            // In case the module has not plubished for now
            if (Tools::isEmpty(CrossSellingHelper::$ourModule)) {
                CrossSellingHelper::$ourModule = $dataArray->products[0];
            }
            
            CrossSellingHelper::$otherModules = array_slice(CrossSellingHelper::$otherModules, 0, 8);
        }
    
        private static function getExternalData($module_key)
        {
            $lang_code = Context::getContext()->language->language_code;
            $lang_code = str_replace('-', '/', $lang_code);
    
            $url = 'http://api-addons.prestashop.com/' . _PS_VERSION_ . '/contributor/all_products/'
                . $module_key . '/' . $lang_code;
    
            $return = Tools::file_get_contents($url);
            return $return;
        }
    
        public static function getHeader($module, $file)
        {
            // Datas from addons
            CrossSellingHelper::preLoadExternalDatas($module);
    
            // Logo path
            $logo_url = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'modules/' . $module->name . '/logo.png';
    
            // Readme
            $documentation_url = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'modules/' . $module->name . '/readme_' .
                 Context::getContext()->language->iso_code . '.pdf';
    
            // Img path
            $img_path = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'modules/' . $module->name . '/views/img/';
    
            // Variables
            Context::getContext()->smarty->assign(
                array(
                    'logo_url' => $logo_url,
                    'img_path' => $img_path,
                    'display_name' => $module->displayName,
                    'description' => $module->description,
                    'module_name' => $module->name,
                    'documentation_url' => $documentation_url,
                    'module_id' => CrossSellingHelper::$ourModule->id,
                    'version' => $module->version
                )
            );
    
            $tplCss = $module->display($file, 'cross_selling_css.tpl');
            return  $tplCss . $module->display($file, 'cross_selling_header.tpl');
        }
    
        public static function getFooter($module, $file)
        {
            // Img path
            $img_path = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'modules/' . $module->name . '/views/img/';
    
            // Variables
            Context::getContext()->smarty->assign(
                array(
                    'img_path' => $img_path,
                    'module_name' => $module->name,
                    'cross_selling' => CrossSellingHelper::$otherModules,
                    'lang_iso' => Context::getContext()->language->iso_code
                )
            );
    
            return $module->display($file, 'cross_selling_footer.tpl');
        }
    }
}
