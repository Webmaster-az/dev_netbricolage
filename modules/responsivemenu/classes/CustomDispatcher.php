<?php
/**
 * 2013-2021 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2021 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ResponsiveMenuCustomDispatcher extends Dispatcher
{
    protected static $instance2;
    public static function getInstance(SymfonyRequest $request = null)
    {
        if (!self::$instance2) {
            if (null === $request) {
                $request = SymfonyRequest::createFromGlobals();
            }
            self::$instance2 = new ResponsiveMenuCustomDispatcher($request);
        }

        return self::$instance2;
    }

    public $controller;

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Load default routes group by languages.
     *
     * @param int $id_shop
     */
    protected function loadRoutes($id_shop = null)
    {
        $context = Context::getContext();

        if (isset($context->shop) && $id_shop === null) {
            $id_shop = (int) $context->shop->id;
        }

        $language_ids = Language::getIDs();

        if (isset($context->language) && !in_array($context->language->id, $language_ids)) {
            $language_ids[] = (int) $context->language->id;
        }

        // Set default routes
        foreach ($this->default_routes as $id => $route) {
            $route = $this->computeRoute(
                $route['rule'],
                $route['controller'],
                $route['keywords'],
                isset($route['params']) ? $route['params'] : array()
            );
            foreach ($language_ids as $id_lang) {
                // the default routes are the same, whatever the language
                $this->routes[$id_shop][$id_lang][$id] = $route;
            }
        }

        // Load the custom routes prior the defaults to avoid infinite loops
        if ($this->use_routes) {
            // Load routes from meta table
            $sql = 'SELECT m.page, ml.url_rewrite, ml.id_lang
					FROM `' . _DB_PREFIX_ . 'meta` m
					LEFT JOIN `' . _DB_PREFIX_ . 'meta_lang` ml ON (m.id_meta = ml.id_meta' . Shop::addSqlRestrictionOnLang('ml', (int) $id_shop) . ')
					ORDER BY LENGTH(ml.url_rewrite) DESC';
            if ($results = Db::getInstance()->executeS($sql)) {
                foreach ($results as $row) {
                    if ($row['url_rewrite']) {
                        $this->addRoute(
                            $row['page'],
                            $row['url_rewrite'],
                            $row['page'],
                            $row['id_lang'],
                            array(),
                            array(),
                            $id_shop
                        );
                    }
                }
            }

            // Set default empty route if no empty route (that's weird I know)
            if (!$this->empty_route) {
                $this->empty_route = array(
                    'routeID' => 'index',
                    'rule' => '',
                    'controller' => 'index',
                );
            }

            // Load custom routes
            foreach ($this->default_routes as $route_id => $route_data) {
                if ($custom_route = Configuration::get('PS_ROUTE_' . $route_id, null, null, $id_shop)) {
                    if (isset($context->language) && !in_array($context->language->id, $language_ids)) {
                        $language_ids[] = (int) $context->language->id;
                    }

                    $route = $this->computeRoute(
                        $custom_route,
                        $route_data['controller'],
                        $route_data['keywords'],
                        isset($route_data['params']) ? $route_data['params'] : array()
                    );
                    foreach ($language_ids as $id_lang) {
                        // those routes are the same, whatever the language
                        $this->routes[$id_shop][$id_lang][$route_id] = $route;
                    }
                }
            }
        }
    }

    /**
     * Retrieve the controller from url or request uri if routes are activated.
     *
     * @param int $id_shop
     *
     * @return string
     */
    public function getRouteDetails($id_shop = null)
    {
        $get = array();

        if (defined('_PS_ADMIN_DIR_')) {
            $get['controllerUri'] = Tools::getValue('controller');
        }

        if ($this->controller) {
            $get['controller'] = $this->controller;

            return $this->controller;
        }

        if (isset(Context::getContext()->shop) && $id_shop === null) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $controller = Tools::getValue('controller');

        if (isset($controller)
            && is_string($controller)
            && preg_match('/^([0-9a-z_-]+)\?(.*)=(.*)$/Ui', $controller, $m)
        ) {
            $controller = $m[1];
            if (isset($get['controller'])) {
                $get[$m[2]] = $m[3];
            } elseif (isset($_POST['controller'])) {
                $_POST[$m[2]] = $m[3];
            }
        }

        if (!Validate::isControllerName($controller)) {
            $controller = false;
        }

        // Use routes ? (for url rewriting)
        if ($this->use_routes && !$controller && !defined('_PS_ADMIN_DIR_')) {
            if (!$this->request_uri) {
                return strtolower($this->controller_not_found);
            }
            $controller = $this->controller_not_found;
            $test_request_uri = preg_replace('/(=http:\/\/)/', '=', $this->request_uri);

            // If the request_uri matches a static file, then there is no need to check the routes, we keep
            // "controller_not_found" (a static file should not go through the dispatcher)
            if (!preg_match(
                '/\.(gif|jpe?g|png|css|js|ico)$/i',
                parse_url($test_request_uri, PHP_URL_PATH)
            )) {
                // Add empty route as last route to prevent this greedy regexp to match request uri before right time
                if ($this->empty_route) {
                    $this->addRoute(
                        $this->empty_route['routeID'],
                        $this->empty_route['rule'],
                        $this->empty_route['controller'],
                        Context::getContext()->language->id,
                        array(),
                        array(),
                        $id_shop
                    );
                }

                list($uri) = explode('?', $this->request_uri);

                if (isset($this->routes[$id_shop][Context::getContext()->language->id])) {
                    foreach ($this->routes[$id_shop][Context::getContext()->language->id] as $route) {
                        if (preg_match($route['regexp'], $uri, $m)) {
                            // Route found ! Now fill $get with parameters of uri
                            foreach ($m as $k => $v) {
                                if (!is_numeric($k)) {
                                    $get[$k] = $v;
                                }
                            }

                            $controller = $route['controller'] ? $route['controller'] : $get['controller'];
                            if (!empty($route['params'])) {
                                foreach ($route['params'] as $k => $v) {
                                    $get[$k] = $v;
                                }
                            }

                            // A patch for module friendly urls
                            if (preg_match('#module-([a-z0-9_-]+)-([a-z0-9_]+)$#i', $controller, $m)) {
                                $get['module'] = $m[1];
                                $get['fc'] = 'module';
                                $controller = $m[2];
                            }

                            if (isset($get['fc']) && $get['fc'] == 'module') {
                                $this->front_controller = self::FC_MODULE;
                            }

                            break;
                        }
                    }
                }
            }

            if ($controller == 'index' || preg_match('/^\/index.php(?:\?.*)?$/', $this->request_uri)) {
                $controller = $this->useDefaultController();
            }
        }

        $get['controller'] = $controller;

        return $get;
    }
}
