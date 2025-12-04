<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('PageCacheDAO')) {

    class PageCacheDAO
    {
        /**
         * InnoDb is too slow, keep MyIsam
         */
        const MYSQL_ENGINE = 'MyIsam';

        const TABLE = 'jm_pagecache';
        const TABLE_DETAILS = 'jm_pagecache_details';
        const TABLE_BACKLINK = 'jm_pagecache_bl';
        const TABLE_MODULE = 'jm_pagecache_mods';

        // This table is created to store state of the cache refreshment concerning a specific price
        const TABLE_SPECIFIC_PRICES = 'jm_pagecache_sp';

        // Store date for profiling
        const TABLE_PROFILING = 'jm_pagecache_prof';

        static private $controllersToId = array(
            'index' => 1,
            'category' => 2,
            'product' => 3,
            'cms' => 4,
            'newproducts' => 5,
            'bestsales' => 6,
            'supplier' => 7,
            'manufacturer' => 8,
            'contact' => 9,
            'pricesdrop' => 10,
            'sitemap' => 11
        );

        /**
         * @throws PrestaShopDatabaseException
         */
        public static function createTables()
        {
            $db = Db::getInstance();

            JprestaUtils::dbExecuteSQL('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_DETAILS . '`(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `details` TEXT NOT NULL,
            `details_md5` VARCHAR(32) DEFAULT NULL,
            PRIMARY KEY (`id`),
            INDEX details_md5 (`details_md5`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8', true, true);

            $sqlCreateMainTable = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE . '`(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_shop` TINYINT UNSIGNED NOT NULL DEFAULT 1,
            `cache_key` INT UNSIGNED NOT NULL,
            `url` VARCHAR(1000) NOT NULL,
            `id_controller` TINYINT(1) UNSIGNED DEFAULT NULL,
            `id_object` INT UNSIGNED,
            `id_currency` INT(10) UNSIGNED,
            `id_lang` INT(10) UNSIGNED,
            `id_fake_customer` INT(10) UNSIGNED DEFAULT NULL,
            `id_device` TINYINT(1) UNSIGNED,
            `id_country` INT(10) UNSIGNED DEFAULT NULL,
            `id_tax_csz` INT(11) UNSIGNED DEFAULT NULL,
            `id_specifics` INT(11) UNSIGNED DEFAULT NULL,
            `v_css` SMALLINT UNSIGNED DEFAULT NULL,
            `v_js` SMALLINT UNSIGNED DEFAULT NULL,
            `count_missed` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
            `count_hit` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
            `last_gen` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `id_controller_object` (`id_controller`,`id_object`),
            KEY (`id_shop`),
            KEY (`id_country`),
            KEY (`last_gen`),
            KEY (`deleted`),
            KEY (`url`),
            KEY (`v_css`),
            KEY (`v_js`),
            UNIQUE (`cache_key`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8';
            try {
                JprestaUtils::dbExecuteSQL($sqlCreateMainTable, true, true);
            } catch (Throwable $e) {
                // Some databases do not allow index > 767 bytes
                JprestaUtils::dbExecuteSQL(str_replace('`url` VARCHAR(1000)', '`url` VARCHAR(255)',
                    $sqlCreateMainTable), true, true);
            }

            JprestaUtils::dbExecuteSQL('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '`(
            `id` int(11) UNSIGNED NOT NULL,
            `backlink_key` INT UNSIGNED NOT NULL,
            KEY (`id`),
            KEY (`backlink_key`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8', true, true);

            JprestaUtils::dbExecuteSQL('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_MODULE . '`(
            `id` int(11) UNSIGNED NOT NULL,
            `id_module` int(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`,`id_module`),
            KEY (`id_module`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8', true, true);

            JprestaUtils::dbExecuteSQL('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '`(
            `id_specific_price` int(10) unsigned NOT NULL,
            `id_product` int(10) unsigned NOT NULL,
            `date_from` datetime,
            `date_to` datetime,
            PRIMARY KEY (`id_specific_price`),
            KEY `idxfrom` (`date_from`),
            KEY `idxto` (`date_to`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8', true, true);

            JprestaUtils::dbExecuteSQL('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::TABLE_PROFILING . '`(
            `id_profiling` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_module` int(10) unsigned NOT NULL,
            `description` varchar(255) NOT NULL,
            `date_exec` timestamp DEFAULT CURRENT_TIMESTAMP,
            `duration_ms` mediumint unsigned NOT NULL,
            PRIMARY KEY (`id_profiling`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8', true, true);

            // Feed TABLE_SPECIFIC_PRICES to trigger cache reffreshment when a reduction starts or ends
            $now = date('Y-m-d H:i:00');
            $inserts = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` (`id_specific_price`,`id_product`,`date_from`,`date_to`) VALUES ';
            $select_existing = 'SELECT * FROM `' . _DB_PREFIX_ . 'specific_price` WHERE `from`>\'' . pSQL($now) . '\' OR `to`>\'' . pSQL($now) . '\'';
            $rows = $db->executeS($select_existing);
            $index = 0;
            if (JprestaUtils::isIterable($rows)) {
                foreach ($rows as $row) {
                    $inserts .= '(' . (int)$row['id_specific_price'] . ',' . (int)$row['id_product'] . ',\'' . pSQL($row['from']) . '\',\'' . pSQL($row['to']) . '\')';
                    $index++;
                    if ($index < count($rows)) {
                        $inserts .= ',';
                    }
                }
            }
            if ($index > 0) {
                JprestaUtils::dbExecuteSQL($inserts . ';', true, true);
            }
        }

        public static function optimizeHash2_39()
        {
            // Make it reentrant, try to delete before creating
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE . '` DROP COLUMN `url_crc32`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` DROP COLUMN `backlink_key`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_MODULE . '` DROP COLUMN `id_module`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }

            // Creates new columns
            $result = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE . '` ADD `url_crc32` INT NOT NULL;');
            $result = $result && Db::getInstance()->execute('CREATE UNIQUE INDEX `url_crc32` ON `' . _DB_PREFIX_ . self::TABLE . '` (`url_crc32`);');

            $result = $result && Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` ADD `backlink_crc32` INT NOT NULL;');
            $result = $result && Db::getInstance()->execute('CREATE INDEX `backlink_crc32` ON `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` (`backlink_crc32`);');

            $result = $result && Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_MODULE . '` ADD `id_module` int(11) UNSIGNED NOT NULL;');
            $result = $result && Db::getInstance()->execute('CREATE INDEX `id_module` ON `' . _DB_PREFIX_ . self::TABLE_MODULE . '` (`id_module`);');
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_MODULE . '` DROP PRIMARY KEY;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }
            $result = $result && Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_MODULE . '` ADD PRIMARY KEY (`id`,`id_module`);');

            // Delete old columns.
            // Be tolerent, do not check result here since it will not block the module
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE . '` DROP COLUMN `url_hash`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` DROP COLUMN `backlink_hash`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }
            try {
                Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . self::TABLE_MODULE . '` DROP COLUMN `module`;');
            } catch (PrestaShopDatabaseException $e) {
                // Just ignore it
            }

            return $result;
        }

        public static function insertSpecificPrice($id, $id_product, $from, $to)
        {
            $query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` (id_specific_price,id_product,date_from,date_to) VALUES ';
            $query .= '(' . (int)$id . ',' . (int)$id_product . ',\'' . pSQL($from) . '\',\'' . pSQL($to) . '\');';
            JprestaUtils::dbExecuteSQL($query);
        }

        public static function updateSpecificPrice($id, $id_product, $from, $to)
        {
            $query = 'UPDATE `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` SET id_product=' . (int)$id_product . ', date_from=\'' . pSQL($from) . '\', date_to=\'' . pSQL($to) . '\'
            WHERE id_specific_price=' . (int)$id . ';';
            JprestaUtils::dbExecuteSQL($query);
        }

        public static function deleteSpecificPrice($id)
        {
            $query = 'DELETE FROM `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` WHERE id_specific_price=' . (int)$id . ';';
            JprestaUtils::dbExecuteSQL($query);
        }

        /**
         * Reffresh cache if any specific sprice started or ended since last check
         */
        public static function triggerReffreshment()
        {
            $now = date('Y-m-d H:i:00');
            $query = 'SELECT DISTINCT id_product FROM `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` WHERE date_from<=\'' . pSQL($now) . '\' OR date_to<\'' . pSQL($now) . '\';';
            $rows = Db::getInstance()->executeS($query);
            if (JprestaUtils::isIterable($rows)) {
                if (count($rows) > 0) {
                    // Change date now to avoid other visitors to trigger refreshment
                    $query = 'UPDATE `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` SET date_from=\'6666-01-01 00:00:00\' WHERE date_from<=\'' . pSQL($now) . '\';';
                    JprestaUtils::dbExecuteSQL($query);
                    $query = 'UPDATE `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` SET date_to=\'6666-01-01 00:00:00\' WHERE date_to<\'' . pSQL($now) . '\';';
                    JprestaUtils::dbExecuteSQL($query);
                    // Clean useless rows
                    $query = 'DELETE FROM `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` WHERE date_from=\'6666-01-01 00:00:00\' AND date_to=\'6666-01-01 00:00:00\';';
                    JprestaUtils::dbExecuteSQL($query);
                    foreach ($rows as $row) {
                        // Clear product cache and linking pages because price has changed
                        if ((int)$row['id_product']) {
                            self::clearCacheOfObject('product', $row['id_product'], true);
                        }
                    }
                }
            }
        }

        /**
         * @param $nbHourExpired Number of hour since the cache is expired, can be negative to pages that are about to expire
         * @param bool $maxRows Max number of returned rows
         * @param bool $deleted false if you want pages that have available cache, true for pages where the cache has been deleted, null if it does not matter
         * @return array cached pages
         */
        public static function getCachedPages($nbHourExpired, $maxRows = false, $deleted = false)
        {
            $rowsToReturn = array();
            $query = 'SELECT * FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE ';
            $whereClauses = array();
            $v_css = Configuration::get('PS_CCCCSS_VERSION');
            $v_js = Configuration::get('PS_CCCJS_VERSION');
            if ($v_css) {
                $query .= 'v_css<>' . (int)$v_css . ' OR ';
            }
            if ($v_js) {
                $query .= 'v_js<>' . (int)$v_js . ' OR ';
            }

            foreach (PageCache::$managed_controllers as $controller) {
                $configuredMaxAge = 60 * ((int)Configuration::get('pagecache_' . $controller . '_timeout'));
                if ($configuredMaxAge < 0) {
                    // Never expire
                    $minAgeToReturn = PHP_INT_MAX;
                }
                elseif ($configuredMaxAge === 0) {
                    // Cache is disabled
                    $minAgeToReturn = 0;
                } else {
                    $minAgeToReturn = max(0, $configuredMaxAge + ((int)$nbHourExpired * 60 * 60));
                }
                $whereClauses[] = '(id_controller = ' . JprestaUtils::dbToInt(self::getControllerId($controller)) . ' AND last_gen < (NOW() - INTERVAL ' . (int)$minAgeToReturn . ' SECOND))';
            }
            $query .= implode(' OR ', $whereClauses);
            if ($deleted !== null) {
                $query .= ' AND `deleted`=' . ($deleted ? 1 : 0);
            }
            $query .= ' ORDER BY last_gen ASC';
            if ($maxRows !== false) {
                $query .= ' LIMIT ' . ((int)$maxRows - count($rowsToReturn));
            }
            return Db::getInstance()->executeS($query);
        }

        /**
         * @param $rows array Rows returned by self::getCachedPages()
         * @param bool $deleteStats
         */
        public static function deleteCachedPages($rows, $deleteStats = false)
        {
            if (JprestaUtils::isIterable($rows) && count($rows) > 0) {
                $cacheIdsToDelete = array();
                foreach ($rows as $row) {
                    PageCache::getCache()->delete(JprestaCacheKey::intToString($row['cache_key']));
                    $cacheIdsToDelete[] = (int) $row['id'];
                }
                if ($deleteStats) {
                    // Delete all rows
                    $query = 'DELETE pc, bl, mods FROM `' . _DB_PREFIX_ . self::TABLE . '` pc
                        INNER JOIN `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` bl ON pc.id=bl.id  
                        INNER JOIN `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` mods ON pc.id=mods.id  
                        WHERE pc.id IN (' . pSQL(implode(',', $cacheIdsToDelete)) . ')';
                } else {
                    // Mark deleted cache contents as deleted in DB
                    $query = 'UPDATE `' . _DB_PREFIX_ . self::TABLE . '` SET `deleted`=1 WHERE `id` IN (' . pSQL(implode(',', $cacheIdsToDelete)) . ')';
                }
                JprestaUtils::dbExecuteSQL($query);
            }
        }

        public static function hasTriggerIn2H()
        {
            $now = date('Y-m-d H:i:00');
            $now_plus_2h = new DateTime();
            $now_plus_2h->modify('+2 hour');
            $now_plus_2h = $now_plus_2h->format('Y-m-d H:i:00');
            $query = 'SELECT * FROM `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '` WHERE (date_from >= \'' . pSQL($now) . '\' AND date_from <= \'' . pSQL($now_plus_2h) . '\') OR (date_to >= \'' . pSQL($now) . '\'   AND date_to <= \'' . pSQL($now_plus_2h) . '\');';
            $rows = Db::getInstance()->executeS($query);
            if (JprestaUtils::isIterable($rows)) {
                return (count($rows) > 0);
            } else {
                return false;
            }
        }

        public static function dropTables()
        {
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_MODULE . '`;');
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '`;');
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE . '`;');
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_DETAILS . '`;');
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_SPECIFIC_PRICES . '`;');
            JprestaUtils::dbExecuteSQL('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . self::TABLE_PROFILING . '`;');
        }

        /**
         * @param $jprestaCacheKey JprestaCacheKey
         */
        public static function incrementCountHit($jprestaCacheKey)
        {
            JprestaUtils::dbExecuteSQL('UPDATE `' . _DB_PREFIX_ . self::TABLE . '` SET count_hit=count_hit+1 WHERE `cache_key`=' . JprestaUtils::dbToInt($jprestaCacheKey->toInt()) . ';');
        }

        public static function getMostUsedTaxManager($limit = 4)
        {
            $query = 'SELECT cd.`id` as id_tax_manager, `details` as `tax_manager`, sum(1) AS `count`
          FROM `' . _DB_PREFIX_ . self::TABLE . '` AS cc
          INNER JOIN `' . _DB_PREFIX_ . self::TABLE_DETAILS . '` AS cd
          ON cc.id_tax_csz = cd.id
          GROUP BY cd.`id`
          ORDER BY 2 DESC
          LIMIT ' . (int)$limit;
            return Db::getInstance()->executeS($query);
        }

        public static function getMostUsedSpecifics($limit = 4)
        {
            $query = 'SELECT cd.`id` as id_specifics, cd.`details` as `specifics`, sum(1) AS `count`
          FROM `' . _DB_PREFIX_ . self::TABLE . '` AS cc
          INNER JOIN `' . _DB_PREFIX_ . self::TABLE_DETAILS . '` AS cd
          ON cc.id_specifics = cd.id
          GROUP BY cd.`id`
          ORDER BY 2 DESC
          LIMIT ' . (int)$limit;
            return Db::getInstance()->executeS($query);
        }

        public static function getMostUsedCountries($limit = 4)
        {
            $query = 'SELECT `id_country`, sum(1) AS `count`
          FROM `' . _DB_PREFIX_ . self::TABLE . '` AS cc
          GROUP BY cc.`id_country`
          HAVING cc.id_country <> ' . (int)Configuration::get('PS_COUNTRY_DEFAULT') . '
          ORDER BY 2 DESC
          LIMIT ' . (int)$limit;
            return Db::getInstance()->executeS($query);
        }

        /**
         * @param $id_details
         * @return string|
         */
        public static function getDetailsById($id_details)
        {
            $query = 'SELECT `details` FROM `' . _DB_PREFIX_ . self::TABLE_DETAILS . '` AS cd WHERE cd.id = ' . (int)$id_details;
            return JprestaUtils::dbGetValue($query);
        }

        /**
         * @param $jprestaCacheKey JprestaCacheKey
         * @return array Array with 3 keys: 'hit', 'missed' and 'age' in seconds
         */
        public static function getStats($jprestaCacheKey)
        {
            $db = Db::getInstance();
            $query = 'SELECT count_hit, count_missed, TIMESTAMPDIFF(SECOND, last_gen, NOW()) as age FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE `cache_key`=' . JprestaUtils::dbToInt($jprestaCacheKey->toInt()) . ';';
            $result = $db->executeS($query);
            if (JprestaUtils::isIterable($result) && count($result) == 1) {
                return array(
                    'hit' => (int)$result[0]['count_hit'],
                    'missed' => (int)$result[0]['count_missed'],
                    'age' => (int)$result[0]['age']
                );
            }
            return array('hit' => 0, 'missed' => 0, 'age' => 0);
        }

        /**
         * @param $jprestaCacheKey JprestaCacheKey Cache key
         * @param $cache_ttl integer configured timeout in minutes
         * @return integer Number of minutes the page will leave in cache
         */
        public static function getTtl($jprestaCacheKey, $cache_ttl_minutes)
        {
            $db = Db::getInstance();
            $query = 'SELECT `deleted`, TIMESTAMPDIFF(MINUTE, last_gen, NOW()) as age  FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE `cache_key`=' . JprestaUtils::dbToInt($jprestaCacheKey->toInt()) . ';';
            $result = $db->executeS($query);
            if (JprestaUtils::isIterable($result) && count($result) == 1) {
                if ($result[0]['deleted']) {
                    return 0;
                } else {
                    return max(0, $cache_ttl_minutes - $result[0]['age']);
                }
            }
            return 0;
        }

        public static function getStatsByUrl($url)
        {
            $db = Db::getInstance();
            $query = 'SELECT sum(1) as `count`, sum(count_hit) as sum_hit, sum(count_missed) as sum_missed, max(TIMESTAMPDIFF(MINUTE, last_gen, NOW())) as max_age_minutes, max(deleted) as deleted FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE url =' . JprestaUtils::dbToString($db,
                    $url) . ' GROUP BY url;';
            $result = $db->executeS($query);
            if (JprestaUtils::isIterable($result) && count($result) == 1) {
                return $result[0];
            }
            return array(
                'count' => 0,
                'sum_hit' => 0,
                'sum_missed' => 0,
                'max_age_minutes' => PHP_INT_MAX,
                'deleted' => 0
            );
        }

        public static function getStatsByContext(
            $url,
            $id_currency,
            $id_device,
            $id_country,
            $id_fake_customer,
            $id_tax_manager,
            $id_specifics
        ) {
            static $v_css = null;
            static $v_js = null;
            if (Configuration::get('pagecache_depend_on_css_js')) {
                if ($v_css === null) {
                    $v_css = Configuration::get('PS_CCCCSS_VERSION');
                }
                if ($v_js === null) {
                    $v_js = Configuration::get('PS_CCCJS_VERSION');
                }
            }
            $db = Db::getInstance();
            /**
             * ATTENTION: this code is called a lot of times by cache-warmer, it has been optimized, don't change anything
             */
            $query = 'SELECT sum(count_hit) as sum_hit, sum(count_missed) as sum_missed, TIMESTAMPDIFF(MINUTE, min(last_gen), NOW()) as max_age_minutes, max(deleted) as deleted FROM `' . _DB_PREFIX_ . self::TABLE . '` 
            WHERE url =\'' . $url . '\'
                AND id_currency ' . ($id_currency ? '=' . (int)$id_currency : 'IS NULL') . '
                AND id_device ' . ($id_device ? '=' . (int)$id_device : 'IS NULL') . '
                AND id_country ' . ($id_country ? '=' . (int)$id_country : 'IS NULL') . '
                AND id_fake_customer ' . ($id_fake_customer ? '=' . (int)$id_fake_customer : 'IS NULL') .
                ($id_tax_manager ? ' AND id_tax_csz=' . (int)$id_tax_manager : '') . '
                AND id_specifics ' . ($id_specifics ? '=' . (int)$id_specifics : 'IS NULL') . '
                AND v_css ' . ($v_css ? '=' . (int)$v_css : 'IS NULL') . '
                AND v_js ' . ($v_js ? '=' . (int)$v_js : 'IS NULL') . '
            ';
            $result = $db->getRow($query);
            if ($result && $result['sum_hit'] !== null) {
                return $result;
            }
            return array(
                'count' => 0,
                'sum_hit' => 0,
                'sum_missed' => 0,
                'max_age_minutes' => PHP_INT_MAX,
                'deleted' => 0
            );
        }

        public static function getPerformances($ids_shop = null)
        {
            $db = Db::getInstance();
            if (empty($ids_shop)) {
                $query = 'SELECT sum(1) as `count`, sum(count_hit) as sum_hit, sum(count_missed) as sum_missed
            FROM `' . _DB_PREFIX_ . self::TABLE . '`';
            } else {
                $query = 'SELECT sum(1) as `count`, sum(count_hit) as sum_hit, sum(count_missed) as sum_missed
            FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE id_shop IN (' . pSQL(implode(',', $ids_shop)) . ')';
            }
            $result = $db->executeS($query)[0];
            return $result;
        }

        /**
         * @param $details string
         * @return int|null
         */
        public static function getOrCreateDetailsId($details, $donotcreate = false)
        {
            $id_details = null;
            if ($details) {
                $db = Db::getInstance();
                $query = 'SELECT id FROM `' . _DB_PREFIX_ . self::TABLE_DETAILS . '` WHERE `details_md5`=MD5(' . JprestaUtils::dbToString($db,
                        $details) . ');';
                $id_details = JprestaUtils::dbGetValue($query);
                if (!$donotcreate && !$id_details) {
                    $query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_DETAILS . '` (`details`,`details_md5`) VALUES (' . JprestaUtils::dbToString($db,
                            $details) . ',MD5(' . JprestaUtils::dbToString($db, $details) . '));';
                    $db->execute($query);
                    $id_details = $db->Insert_ID();
                }
            }
            return (int)$id_details;
        }

        /**
         * @param $jprestaCacheKey JprestaCacheKey Cache key with informations
         * @param $controller string Controller that manage the URL
         * @param $id_shop integer
         * @param $id_object integer ID of the object (product, category, supplier, etc.) if any
         * @param $module_ids array IDs of called module on this page
         * @param $backlinks_cache_keys int[] List of cache keys present in this page
         * @param int $log_level
         * @param bool $stats_it
         * @throws PrestaShopDatabaseException
         */
        public static function insert(
            $jprestaCacheKey,
            $controller,
            $id_shop,
            $id_object = null,
            $module_ids,
            $backlinks_cache_keys,
            $log_level = 0,
            $stats_it = true
        ) {
            $startTime1 = microtime(true);

            $db = Db::getInstance();

            //
            // Insert a new row or update stats if it exists
            //
            if ($stats_it) {
                $onDuplicateQuery = '`count_missed`=`count_missed` + 1, last_gen = CURRENT_TIMESTAMP, `deleted` = 0';
            } else {
                $onDuplicateQuery = 'last_gen = CURRENT_TIMESTAMP, `deleted` = 0';
            }
            $id_specifics = self::getOrCreateDetailsId($jprestaCacheKey->get('specifics'));
            $query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE . '` (`cache_key`, `url`, `id_controller`, `id_shop`, `id_object`, `id_currency`, `id_lang`, `id_fake_customer`, `id_device`, `id_country`, `id_tax_csz`, `id_specifics`, `v_css`, `v_js`, `count_missed`, `count_hit`)
                VALUES (
                ' . JprestaUtils::dbToInt($jprestaCacheKey->toInt()) . ',
                ' . JprestaUtils::dbToString($db, $jprestaCacheKey->get('url')) . ',
                ' . JprestaUtils::dbToInt(self::getControllerId($controller)) . ',
                ' . JprestaUtils::dbToInt($id_shop) . ',
                ' . JprestaUtils::dbToInt($id_object) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_currency')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_lang')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_fake_customer')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_device')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_country')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('id_tax_manager')) . ',
                ' . JprestaUtils::dbToInt($id_specifics) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('css_version')) . ',
                ' . JprestaUtils::dbToInt($jprestaCacheKey->get('js_version')) . ',
                ' . ($stats_it ? '1' : '0') . ', 
                0) ON DUPLICATE KEY UPDATE ' . $onDuplicateQuery . ';';

            JprestaUtils::dbExecuteSQL($query);

            // Get the ID of the inserted or updated row
            $query = 'SELECT id FROM `' . _DB_PREFIX_ . self::TABLE . '` WHERE `cache_key`=' . JprestaUtils::dbToInt($jprestaCacheKey->toInt()) . ';';
            $id_pagecache = JprestaUtils::dbGetValue($query, false);

            //
            // MODULES
            //
            $startTime3 = microtime(true);
            $startTime4 = microtime(true);
            JprestaUtils::dbExecuteSQL('DELETE FROM `' . _DB_PREFIX_ . self::TABLE_MODULE . '` WHERE `id`=' . (int)$id_pagecache . ';');
            if (count($module_ids) > 0) {
                $module_query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_MODULE . '` (`id`, `id_module`) VALUES ';
                $idx = 0;
                foreach ($module_ids as $id_module) {
                    $module_query .= '(' . $id_pagecache . ',' . $id_module . ')';
                    $idx++;
                    if ($idx < count($module_ids)) {
                        $module_query .= ',';
                    }
                }
                $startTime4 = microtime(true);
                JprestaUtils::dbExecuteSQL($module_query . ' ON DUPLICATE KEY UPDATE id=id');
            }

            //
            // BACKLINKS
            //
            $startTime5 = microtime(true);
            $startTime6 = microtime(true);
            JprestaUtils::dbExecuteSQL('DELETE FROM `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` WHERE `id`=' . (int)$id_pagecache . ';');
            if (count($backlinks_cache_keys) > 0) {
                $backlink_query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` (`id`, `backlink_key`) VALUES ';
                $idx = 0;
                foreach ($backlinks_cache_keys as $backlink_cache_key) {
                    $backlink_query .= '(' . (int)$id_pagecache . ',' . JprestaUtils::dbToInt($backlink_cache_key) . ')';
                    $idx++;
                    if ($idx < count($backlinks_cache_keys)) {
                        $backlink_query .= ',';
                    }
                }
                $startTime6 = microtime(true);
                JprestaUtils::dbExecuteSQL($backlink_query . ' ON DUPLICATE KEY UPDATE id=id');
            }

            if (((int)$log_level) > 0) {
                JprestaUtils::addLog("PageCache | insert | added cache for $controller#$id_object during "
                    . number_format($startTime3 - $startTime1, 3) . '+'
                    . number_format($startTime4 - $startTime3, 3) . '+'
                    . number_format($startTime5 - $startTime4, 3) . '+'
                    . number_format($startTime6 - $startTime5, 3) . '+'
                    . number_format(microtime(true) - $startTime6, 3) . '='
                    . number_format(microtime(true) - $startTime1, 3)
                    . " second(s) with " . count($backlinks_cache_keys) . " backlinks", 1, null, null, null, true);
            }
        }

        public static function clearCacheOfObject(
            $controller,
            $id_object,
            $delete_linking_pages,
            $action_origin = '',
            $log_level = 0
        ) {
            // Some code to avoid calling this method multiple times (can happen when saving a product for exemple)
            static $already_done = array();
            $key = $controller . '|' . $id_object . '|' . ($delete_linking_pages ? '1' : '0');
            if (array_key_exists($key, $already_done)) {
                return;
            }
            $already_done[$key] = true;
            if ($delete_linking_pages) {
                // When called with option $delete_linking_pages we can skip call without the option...
                $already_done[$controller . '|' . $id_object . '|0'] = true;
            }

            $startTime1 = microtime(true);
            $db = Db::getInstance();
            if ($id_object != null) {
                $query = 'SELECT id, id_shop, cache_key FROM `' . _DB_PREFIX_ . self::TABLE . '`
                WHERE id_controller=' . JprestaUtils::dbToInt(self::getControllerId($controller)) . ' AND id_object=' . ((int)$id_object) . ';';
            } else {
                $query = 'SELECT id, id_shop, cache_key FROM `' . _DB_PREFIX_ . self::TABLE . '`
                WHERE id_controller=' . JprestaUtils::dbToInt(self::getControllerId($controller)) . ' AND id_object IS NULL;';
            }
            $results = $db->executeS($query);
            $startTime2 = microtime(true);

            $keys = [];
            $cacheIdsToDelete = [];
            $deletedCount = 0;
            if (JprestaUtils::isIterable($results)) {
                foreach ($results as $result) {
                    if (PageCache::getCache($result['id_shop'])->delete(JprestaCacheKey::intToString($result['cache_key']))) {
                        $deletedCount++;
                    }
                    $keys[] = $result['cache_key'];
                    $cacheIdsToDelete[] = $result['id'];
                }
            }
            if (((int)$log_level) > 0) {
                JprestaUtils::addLog("PageCache | $action_origin | reffreshed $deletedCount pages from $controller#$id_object during "
                    . number_format($startTime2 - $startTime1, 3) . '+'
                    . number_format(microtime(true) - $startTime2, 3) . '='
                    . number_format(microtime(true) - $startTime1, 3)
                    . " second(s)", 1, null, null, null, true);
                $startTime1 = microtime(true);
            }
            if ($delete_linking_pages) {
                // Also add the default link of the object in case that the page has never been cached
                $default_links = self::_getDefaultLinks($controller, $id_object);
                if (count($default_links) > 0) {
                    $remainingLinks = count($default_links);
                    foreach ($default_links as $default_link) {
                        $keys[] = JprestaUtils::dbToInt(PageCache::getCacheKeyForBacklink($default_link));
                        $remainingLinks--;
                    }
                }
                $startTime2 = microtime(true);
                $startTime3 = microtime(true);
                // Delete pages that link to these pages
                $deletedCount = 0;
                if (count($keys) > 0) {
                    $query = 'SELECT DISTINCT pc.id, pc.cache_key FROM `' . _DB_PREFIX_ . self::TABLE . '` AS pc
                    LEFT JOIN `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` AS bl ON (bl.id = pc.id)
                    WHERE pc.deleted=0 AND `backlink_key` IN (' . implode(',', $keys) . ')';
                    $results = $db->executeS($query);
                    $startTime3 = microtime(true);
                    if (JprestaUtils::isIterable($results)) {
                        $cache = PageCache::getCache();
                        foreach ($results as $result) {
                            if ($cache->delete(JprestaCacheKey::intToString($result['cache_key']))) {
                                $deletedCount++;
                            }
                            $cacheIdsToDelete[] = $result['id'];
                        }
                    }
                }
                if (((int)$log_level) > 0) {
                    JprestaUtils::addLog("PageCache | $action_origin | reffreshed $deletedCount pages that were linking to $controller#$id_object during "
                        . number_format($startTime2 - $startTime1, 3) . '+'
                        . number_format($startTime3 - $startTime2, 3) . '+'
                        . number_format(microtime(true) - $startTime3, 3) . '='
                        . number_format(microtime(true) - $startTime1, 3)
                        . " second(s)", 1, null, null, null, true);
                }
            }
            if (count($cacheIdsToDelete) > 0) {
                // Mark deleted cache contents as deleted in DB
                $query_deleted = 'UPDATE `' . _DB_PREFIX_ . self::TABLE . '` SET `deleted`=1 WHERE `id` IN (' . implode(',', $cacheIdsToDelete) . ')';
                JprestaUtils::dbExecuteSQL($query_deleted);
            }
        }

        private static function _getDefaultLinks($controller, $id_object)
        {
            $links = array();
            if ($id_object != null) {
                $context = Context::getContext();
                if (!isset($context->link)) {
                    /* Link should be initialized in the context but sometimes it is not */
                    $https_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
                    $context->link = new Link($https_link, $https_link);
                }
                switch ($controller) {
                    case 'cms':
                        $links[] = $context->link->getCMSLink((int)($id_object), null, null, null, null, true);
                        break;
                    case 'product':
                        $idLang = $context->language->id;
                        $idShop = Shop::getContextShopID();
                        if (!is_object($context->cart)) {
                            $context->cart = new Cart();
                        }
                        $product = new Product((int)$id_object, false, $idLang, $idShop);
                        $ipass = Product::getProductAttributesIds((int)$id_object);
                        if (is_array($ipass)) {
                            foreach ($ipass as $ipas) {
                                foreach ($ipas as $ipa) {
                                    $links[] = $context->link->getProductLink($product, null, null, null, $idLang,
                                        $idShop, $ipa, false, true);
                                }
                            }
                        }
                        $links[] = $context->link->getProductLink((int)($id_object), null, null, null, null, null, 0,
                            false, true);
                        break;
                    case 'category':
                        $links[] = $context->link->getCategoryLink((int)($id_object), null, null, null, null, true);
                        break;
                    case 'manufacturer':
                        $links[] = $context->link->getManufacturerLink((int)($id_object), null, null, null, true);
                        break;
                    case 'supplier':
                        $links[] = $context->link->getSupplierLink((int)($id_object), null, null, null, true);
                        break;
                }
            }
            return $links;
        }

        public static function clearCacheOfModule($module_name, $action_origin = '', $log_level = 0)
        {
            $startTime1 = microtime(true);
            $module = Module::getInstanceByName($module_name);
            if ($module instanceof Module) {
                $id_module = $module->id;
                if (!empty($id_module)) {
                    $query = 'SELECT pc.id, pc.cache_key FROM `' . _DB_PREFIX_ . self::TABLE . '` AS pc
                    LEFT JOIN `' . _DB_PREFIX_ . self::TABLE_MODULE . '` AS mods ON (mods.id = pc.id)
                    WHERE pc.deleted=0 AND `id_module`=' . ((int)$id_module);
                    $db = Db::getInstance();
                    $results = $db->executeS($query);

                    $startTime2 = microtime(true);
                    $deletedCount = 0;
                    $cacheIdsToDelete = array();
                    if (JprestaUtils::isIterable($results)) {
                        foreach ($results as $result) {
                            if (PageCache::getCache()->delete(JprestaCacheKey::intToString($result['cache_key']))) {
                                $deletedCount++;
                            }
                            $cacheIdsToDelete[] = $result['id'];
                        }
                    }
                    if (((int)$log_level) > 0) {
                        JprestaUtils::addLog("PageCache | $action_origin | reffreshed $deletedCount pages containing module $module_name during "
                            . number_format($startTime2 - $startTime1, 3) . '+'
                            . number_format(microtime(true) - $startTime2, 3) . '='
                            . number_format(microtime(true) - $startTime1, 3)
                            . " second(s)", 1, null, null, null, true);
                    }

                    if (count($cacheIdsToDelete) > 0) {
                        // Mark deleted cache contents as deleted in DB
                        $query_deleted = 'UPDATE `' . _DB_PREFIX_ . self::TABLE . '` SET `deleted`=1 WHERE `id` IN (' . pSQL(implode(',', $cacheIdsToDelete)) . ')';
                        JprestaUtils::dbExecuteSQL($query_deleted);
                    }
                }
            }
        }

        public static function resetCache($ids_shop = null)
        {
            if (empty($ids_shop)) {
                JprestaUtils::dbExecuteSQL('
                    TRUNCATE `' . _DB_PREFIX_ . self::TABLE . '`; 
                    TRUNCATE `' . _DB_PREFIX_ . self::TABLE_DETAILS . '`; 
                    TRUNCATE `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '`; 
                    TRUNCATE `' . _DB_PREFIX_ . self::TABLE_MODULE . '`;');
            } else {
                JprestaUtils::dbExecuteSQL('DELETE bl, mods, pc FROM `' . _DB_PREFIX_ . self::TABLE . '` AS pc
                LEFT JOIN `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '` AS bl ON pc.id=bl.id
                LEFT JOIN `' . _DB_PREFIX_ . self::TABLE_MODULE . '` AS mods ON pc.id=mods.id
                WHERE pc.id_shop IN (' . pSQL(implode(',', $ids_shop)) . ');');
            }
        }

        /**
         * Request to MySQL to refresh the number of rows
         */
        public static function analyzeTables()
        {
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE . '`;', false, false);
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE_BACKLINK . '`;', false,
                false);
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE_MODULE . '`;', false,
                false);
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE_DETAILS . '`;', false,
                false);
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE_PROFILING . '`;', false,
                false);
            JprestaUtils::dbExecuteSQL('ANALYZE TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE_SPECIFIC_PRICES . '`;',
                false, false);
        }

        public static function clearAllCache()
        {
            try {
                JprestaUtils::dbExecuteSQL('UPDATE `' . _DB_PREFIX_ . self::TABLE . '` SET `deleted`=1;');
                JprestaUtils::dbExecuteSQL('DELETE FROM `' . _DB_PREFIX_ . self::TABLE_BACKLINK . '`;');
                JprestaUtils::dbExecuteSQL('DELETE FROM `' . _DB_PREFIX_ . self::TABLE_MODULE . '`;');
            } catch (Exception $e) {
                error_log('Warning, cannot delete cache backlinks ' . $e->getMessage());
            }
        }

        /**
         * @param $id_module
         * @param $description
         * @param $duration
         * @param integer $max_records Maximum number of records
         * @return bool true if the number of records is less than $max_records
         */
        public static function addProfiling($id_module, $description, $duration, $max_records = 1000)
        {
            try {
                $db = Db::getInstance();
                $query = 'INSERT INTO `' . _DB_PREFIX_ . self::TABLE_PROFILING . '` (`id_module`, `description`, `duration_ms`) VALUES (' . (int)$id_module . ', \'' . $db->escape($description) . '\', ' . (int)$duration . ');';
                JprestaUtils::dbExecuteSQL($query);
                return JprestaUtils::dbGetValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . self::TABLE_PROFILING) < $max_records;
            } catch (Exception $e) {
                error_log('Warning, cannot insert profiling datas ' . $e->getMessage());
            }
            return true;
        }

        public static function clearProfiling($minMs = 0)
        {
            try {
                if ($minMs === 0) {
                    JprestaUtils::dbExecuteSQL('TRUNCATE TABLE `' . _DB_PREFIX_ . self::TABLE_PROFILING . '`;');
                } else {
                    JprestaUtils::dbExecuteSQL('DELETE FROM `' . _DB_PREFIX_ . self::TABLE_PROFILING . '` WHERE `duration_ms` < ' . (int)$minMs . ';');
                }
            } catch (Exception $e) {
                error_log('Warning, cannot clear profiling datas ' . $e->getMessage());
            }
        }

        /**
         * @param $controller string name
         * @return int|null
         */
        private static function getControllerId($controller)
        {
            if (array_key_exists($controller, self::$controllersToId)) {
                return self::$controllersToId[$controller];
            }
            return null;
        }

        /**
         * @param $controllerId int Controller ID
         * @return string|null
         */
        public static function getControllerName($controllerId)
        {
            static $controllersName = null;
            if ($controllersName === null) {
                $controllersName = array_flip(self::$controllersToId);
            }
            if (array_key_exists($controllerId, $controllersName)) {
                return $controllersName[$controllerId];
            }
            return null;
        }

    }
}