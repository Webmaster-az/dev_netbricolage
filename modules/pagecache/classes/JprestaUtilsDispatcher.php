<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaUtilsDispatcher')) {

    class JprestaUtilsDispatcher extends Dispatcher
    {
        /**
         * @var JprestaUtilsDispatcher
         */
        public static $pc_instance = null;

        public static function getPageCacheInstance()
        {
            if (!self::$pc_instance) {
                self::$pc_instance = new JprestaUtilsDispatcher();
            }

            return self::$pc_instance;
        }

        public function getControllerFromURL($url, $id_shop = null)
        {

            $controller = false;
            $is_fc_module = false;

            if (isset(Context::getContext()->shop) && $id_shop === null) {
                $id_shop = (int)Context::getContext()->shop->id;
            }

            // Try to find it in URL query string (if no URL rewritting)
            $query = parse_url($url, PHP_URL_QUERY);
            if ($query) {
                $query = html_entity_decode($query);
                $keyvaluepairs = explode('&', $query);
                if ($keyvaluepairs !== false) {
                    foreach ($keyvaluepairs as $keyvaluepair) {
                        if (strstr($keyvaluepair, '=') !== false) {
                            list($key, $value) = explode('=', $keyvaluepair);

                            if (strcmp('controller', $key) === 0) {
                                $controller = $value;
                            } elseif (strcmp('fc', $key) === 0) {
                                $is_fc_module = strcmp('module', $value) !== false;
                            }
                        }
                    }
                }
            }

            if (!Validate::isControllerName($controller)) {
                $controller = false;
            }

            // If not found, try routes (if URL rewritting)
            if (!$controller && $this->use_routes) {
                // Language removed in pagecache.php
                $url_without_lang = $url;
                if (isset($this->routes[$id_shop][Context::getContext()->language->id])) {
                    $routes = $this->routes[$id_shop][Context::getContext()->language->id];
                } else {
                    $routes = $this->routes[$id_shop];
                }
                foreach ($routes as $route) {
                    if (preg_match($route['regexp'], $url_without_lang, $m)) {
                        // Route found!
                        $controller = $route['controller'] ? $route['controller'] : false;

                        // A patch for module friendly urls
                        if (preg_match('#module-([a-z0-9_-]+)-([a-z0-9_]+)$#i', $controller, $m)) {
                            $controller = $m[2];
                        }

                        if ($is_fc_module) {
                            $controller = false;
                        }

                        break;
                    }
                }
                if ((!$controller && Tools::strlen($url_without_lang) == 0) || $url_without_lang === '/') {
                    $controller = 'index';
                } elseif ($controller == 'index' || preg_match('/^\/index.php(?:\?.*)?$/', $url_without_lang)) {
                    if ($is_fc_module) {
                        $controller = false;
                    }
                }
            }

            return $controller;
        }
    }
}