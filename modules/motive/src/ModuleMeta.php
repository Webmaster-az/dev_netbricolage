<?php
/**
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Motive\Prestashop;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class ModuleMeta
 * Helper class to re register front office routes like {base}/{module_name}-{controller_name}
 */
class ModuleMeta
{
    protected $module;
    protected $urlPrefix;

    /**
     * ModuleMeta constructor.
     *
     * @param \Module $module
     * @param string $urlPrefix
     */
    public function __construct($module, $urlPrefix = '')
    {
        $this->module = $module;
        $this->urlPrefix = $urlPrefix;
    }

    /**
     * Associates url path with module controller
     *
     * @param $controllerName
     *
     * @return bool success
     */
    public function install($controllerName)
    {
        $page = $this->pageNameFromController($controllerName);

        if ($this->findByPageName($page)) {
            return true;
        }

        $meta = new \Meta();
        $meta->page = $page;
        $meta->configurable = 0;
        $meta->title = [];
        $meta->url_rewrite = [];
        foreach (\Language::getLanguages(false, false, true) as $idLang) {
            $meta->title[$idLang] = "{$this->module->displayName} $controllerName";
            $meta->url_rewrite[$idLang] = $this->urlPrefix . $controllerName;
        }

        try {
            return $meta->add();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Deletes $controllerName Meta from the database.
     *
     * @param string $controllerName
     *
     * @return bool `true` if uninstall was successful
     */
    public function uninstall($controllerName)
    {
        $page = $this->pageNameFromController($controllerName);

        $idMeta = $this->findByPageName($page);
        if ($idMeta <= 0) {
            return true;
        }

        try {
            $obj = new \Meta($idMeta);

            return $obj->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Make Meta page name from module controller name
     *
     * @param $controllerName
     *
     * @return string
     */
    private function pageNameFromController($controllerName)
    {
        return "module-{$this->module->name}-$controllerName";
    }

    /**
     * Find ID Meta by page name
     *
     * @param $metaPageName
     *
     * @return int
     */
    private function findByPageName($metaPageName)
    {
        $query = 'SELECT id_meta FROM ' . _DB_PREFIX_ . 'meta WHERE page="' . \pSQL($metaPageName) . '"';

        return (int) \Db::getInstance()->getValue($query);
    }
}
