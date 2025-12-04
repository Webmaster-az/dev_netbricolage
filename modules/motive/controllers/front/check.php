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

use Motive\Prestashop\Config;
use Motive\Prestashop\MotiveApiController;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class MotiveCheckModuleFrontController
 * /motive-check endpoint
 */
class MotiveCheckModuleFrontController extends MotiveApiController
{
    /**
     * Handle requests without params
     * Simple check if module is active
     */
    public function displayAjax()
    {
        $this->ajaxRenderJson([
            'status' => 'ok',
        ]);
    }

    /**
     * Check if the controller is available for the current user/visitor.
     *
     * @return bool
     *
     * @see Controller::checkAccess()
     */
    public function checkAccess()
    {
        // Default /motive-check is public
        if (!Tools::isSubmit('action')) {
            return true;
        }

        return parent::checkAccess();
    }

    /**
     * Find and list issues.
     * Url: /module/motive/check?action=issues
     * Fix issue by id.
     * Url: /module/motive/check?action=issues&fix={issueId}
     */
    public function displayAjaxIssues()
    {
        $prefix = _DB_PREFIX_;
        $db = Db::getInstance();
        $issueId = Tools::getValue('fix');
        switch ($issueId) {
            case 'product_attribute':
                $sql = "DELETE FROM {$prefix}product_attribute
                    WHERE id_product NOT IN (SELECT id_product FROM {$prefix}product_attribute_shop)";
                $status = $db->execute($sql);
                break;

            case 'empty_engine_id':
                $sql = "DELETE c FROM {$prefix}configuration c
                    LEFT JOIN {$prefix}configuration_lang cl ON c.id_configuration = cl.id_configuration
                    WHERE c.name = 'MOTIVE_ENGINE_ID' AND cl.id_configuration IS NULL";
                $status = $db->execute($sql);
                break;

            case 'empty_engine_id_2':
                $sql = "DELETE c, cl FROM {$prefix}configuration c
                    LEFT JOIN {$prefix}configuration_lang cl ON c.id_configuration = cl.id_configuration
                    WHERE c.name = 'MOTIVE_ENGINE_ID'";
                $status = $db->execute($sql);
                break;

            default:
                // List issues
                $this->ajaxRenderJson([
                    'product_attribute' => (bool) $db->getValue(
                        "SELECT id_product FROM {$prefix}product_attribute
                        WHERE id_product NOT IN (SELECT id_product FROM {$prefix}product_attribute_shop)"
                    ),
                    'empty_engine_id' => !Configuration::isLangKey('MOTIVE_ENGINE_ID'),
                    'empty_engine_id_2' => $db->getValue(
                        "SELECT id_shop, id_shop_group FROM {$prefix}configuration c
                        WHERE c.name = 'MOTIVE_ENGINE_ID'
                        GROUP BY id_shop, id_shop_group
                        HAVING COUNT(*) > 1"
                    ) !== false,
                ]);
                break;
        }
        $this->ajaxRenderJson(['status' => $status]);
    }

    /**
     * Install meta
     * Url: /module/motive/check?action=metaInstall
     */
    public function displayAjaxMetaInstall()
    {
        $this->ajaxRenderJson([
            'status' => Module::getInstanceByName('motive')->installMeta(),
        ]);
    }

    /**
     * Uninstall meta
     * Url: /module/motive/check?action=metaUninstall
     */
    public function displayAjaxMetaUninstall()
    {
        $this->ajaxRenderJson([
            'status' => Module::getInstanceByName('motive')->uninstallMeta(),
        ]);
    }

    /**
     * Utility function to check user max execution/response time allowed by PHP, Server, Reverse proxy...
     * /motive-check?action=timeLimit&max_time=1000
     * - update : Update config value
     * - bad_sleep : Use sleep function that consumes cpu time to test PHP max execution time
     * - max_time : max test time (Max value: 2h = 3600*2)
     */
    public function displayAjaxTimeLimit()
    {
        $updateConfig = Tools::isSubmit('update');
        $badSleep = Tools::isSubmit('bad_sleep');
        $maxTime = (int) Tools::getValue('max_time');
        if ($maxTime <= 0 || $maxTime > 3600 * 2) {
            $maxTime = 3600 * 2;
        }

        function_exists('set_time_limit') && set_time_limit($maxTime);

        // Turn off output buffering & compression
        function_exists('apache_setenv') && apache_setenv('no-gzip', '1');
        ini_set('output_buffering', 'off');
        ini_set('zlib.output_compression', false);

        // Flush the output buffer and turn off output buffering
        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush();

        // Set Headers
        header('Content-type: text/plain');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        // Safari and Internet Explorer have an internal 1K buffer
        for ($i = 0; $i < 1024; ++$i) {
            echo ' ';
        }
        flush();

        // Start the program output
        echo 'Start: ' . date('Y-m-d H:i:s') . PHP_EOL;

        $time = 0;
        while (++$time < $maxTime) {
            $updateConfig && Configuration::updateValue(Config::TIME_LIMIT, $time);
            echo "$time\n"; // \n do the implicit flush()
            $badSleep ? static::badSleep(1) : sleep(1);
        }

        echo 'End: ' . date('Y-m-d H:i:s') . PHP_EOL;
    }

    /**
     * Bad sleep function that uses CPU time.
     *
     * @param float $seconds
     */
    public static function badSleep($seconds)
    {
        $until = microtime(true) + $seconds;
        while (microtime(true) < $until) {
        }
    }
}
