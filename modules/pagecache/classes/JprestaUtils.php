<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaUtils')) {

    require_once 'JprestaUtilsDispatcher.php';

    class JprestaUtils
    {
        /**
         * Original PHP code by Chirp Internet: www.chirp.com.au, Please acknowledge use of this code by including this header.
         *
         * @param string
         * @param string $base Base URL
         * @param $managedControllers
         * @param bool|string $tagIgnoreStart
         * @param bool|string $tagIgnoreEnd
         * @param bool|string $ignoreBeforePattern
         * @return array List of URLs
         */
        public static function parseLinks($html, $base, $managedControllers, $tagIgnoreStart = false, $tagIgnoreEnd = false, $ignoreBeforePattern = false) {
            if ($ignoreBeforePattern) {
                if (method_exists('Tools', 'strpos')) {
                    $startPos = Tools::strpos($html, $ignoreBeforePattern);
                } else {
                    $startPos = strpos($html, $ignoreBeforePattern);
                }
                if ($startPos !== false) {
                    return self::parseLinks(Tools::substr($html, $startPos), $base, $managedControllers);
                }
            }
            $startPos = false;
            if ($tagIgnoreStart !== false) {
                if (method_exists('Tools', 'strpos')) {
                    $startPos = Tools::strpos($html, $tagIgnoreStart);
                } else {
                    $startPos = strpos($html, $tagIgnoreStart);
                }
            }
            if ($startPos !== false) {
                $linksAfter = array();
                if (method_exists('Tools', 'strpos')) {
                    $endPos = Tools::strpos($html, $tagIgnoreEnd, min(Tools::strlen($html), $startPos + 4));
                } else {
                    $endPos = strpos($html, $tagIgnoreEnd, min(Tools::strlen($html), $startPos + 4));
                }
                $linksBefore = self::parseLinks(Tools::substr($html, 0, $startPos), $base, $managedControllers,
                    $tagIgnoreStart, $tagIgnoreEnd);
                if ($endPos !== false) {
                    $linksAfter = self::parseLinks(Tools::substr($html, $endPos + 4), $base, $managedControllers,
                        $tagIgnoreStart, $tagIgnoreEnd);
                }
                return array_merge($linksBefore, $linksAfter);
            } else {
                $links = array();

                $base_relative = preg_replace('/https?:\/\//', '//', $base);
                $base_exp = preg_replace('/([^a-zA-Z0-9])/', '\\\\$1', $base);
                $base_exp = preg_replace('/https?/', 'http[s]?', $base_exp);
                $regexp = '<a\s[^>]*href=(\"??)' . $base_exp . '([^\" >]*?)\\1[^>]*>(.*)<\/a>';
                $isMultiLanguageActivated = Language::isMultiLanguageActivated();

                if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {

                    // The links array will help us to remove duplicates
                    foreach ($matches as $match) {
                        // $match[2] = link address
                        // $match[3] = link text
                        // Insert backlinks that correspond to a possibily cached page into the database

                        $url = $match[2];
                        // Add leading /
                        if (strpos($url, "/") > 0 || strpos($url, "/") === false) {
                            $url = "/" . $url;
                        }

                        // Remove language part if any
                        $url_without_lang = $url;
                        if ($isMultiLanguageActivated && preg_match('#^/([a-z]{2})(?:/.*)?$#', $url, $m)) {
                            $url_without_lang = Tools::substr($url, 3);
                        }
                        $anchorPos = strpos($url_without_lang, '#');
                        if ($anchorPos !== false) {
                            $url_without_lang = Tools::substr($url_without_lang, 0, $anchorPos);
                        }

                        $bl_controller = JprestaUtilsDispatcher::getPageCacheInstance()->getControllerFromURL($url_without_lang);
                        if ($bl_controller === false) {
                            // To avoid re-installation of override we have this workaround
                            $bl_controller = JprestaUtilsDispatcher::getPageCacheInstance()->getControllerFromURL('en' . $url_without_lang);
                        }
                        if (in_array($bl_controller, $managedControllers)) {
                            $links[$match[2]] = $base_relative . $match[2];
                        }
                    }
                }
                return $links;
            }
        }

        public static function decodeConfiguration($value) {
            if ($value) {
                $value = str_replace('&lt;', '<', $value);
            }
            return $value;
        }

        public static function encodeConfiguration($value) {
            if ($value) {
                $value = str_replace('<', '&lt;', $value);
            }
            return $value;
        }

        public static function parseCSS($html, $base) {
            $links = array();
            $base_exp = preg_replace('/([^a-zA-Z0-9])/', '\\\\$1', $base);
            $regexp = '<link\s[^>]*href=(\"??)[^\" >]*' . $base_exp . '([^\" >]*?)\\1[^>]*>';
            if(preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
                foreach($matches as $match) {
                    $links[] = $match[2];
                }
            }
            return $links;
        }

        public static function parseJS($html, $base) {
            $links = array();
            $base_exp = preg_replace('/([^a-zA-Z0-9])/', '\\\\$1', $base);
            $regexp = '<script\s[^>]*src=(\"??)[^\" >]*' . $base_exp . '([^\" >]*?)\\1[^>]*>';
            if(preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
                foreach($matches as $match) {
                    $links[] = $match[2];
                }
            }
            return $links;
        }

        /**
         * Delete a file
         *
         * @param $file string The file to delete
         * @return bool true if the file has been deleted
         */
        public static function deleteFile($file) {
            if (is_file($file) && @unlink($file) === false) {
                $error = error_get_last();
                if ($error && stripos($error['message'], 'No such file or directory') === false) {
                    // Ignore error when the directory does not exist anymore
                    self::addLog('Cannot delete file ' . $file . ' : ' . $error['message'], 3);
                    return false;
                }
            }
            return true;
        }

        /**
         * @param $oldname string
         * @param $newname string
         * @return bool
         */
        public static function rename($oldname , $newname) {
            if (rename($oldname , $newname) === false) {
                $error = error_get_last();
                if ($error && stripos($error['message'], 'No such file or directory') === false) {
                    // Ignore error when the directory does not exist anymore
                    self::addLog('Cannot rename ' . $oldname . ' to ' . $newname . ': ' . $error['message'], 2);
                    return false;
                }
            }
            return true;
        }

        /**
         * Delete directory and subdirectories with their files.
         *
         * @param $dir string Directory to delete
         * @param int $timeoutSeconds
         * @return bool true if the directory has been deleted
         */
        public static function deleteDirectory($dir, $timeoutSeconds = 0)
        {
            $startTime = microtime(true);
            $errorCount = 0;
            $first_error = null;
            if (file_exists($dir)) {
                $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if (is_dir($file)) {
                        if (@rmdir($file) === false) {
                            $error = error_get_last();
                            if ($error && stripos($error['message'], 'No such file or directory') === false) {
                                // Ignore error when the directory does not exist anymore
                                $errorCount++;
                                if (!$first_error) {
                                    $first_error = $error['message'];
                                }
                            }
                        }
                    } else {
                        if (@unlink($file) === false) {
                            $error = error_get_last();
                            if ($error && stripos($error['message'], 'No such file or directory') === false) {
                                // Ignore error when the file does not exist anymore
                                $errorCount++;
                                if (!$first_error) {
                                    $first_error = $error['message'];
                                }
                            }
                        }
                    }
                    if ($timeoutSeconds > 0 && (microtime(true) - $startTime) > $timeoutSeconds) {
                        // It's too long, stopping
                        if (!$first_error) {
                            $first_error = "too long to delete everything (> $timeoutSeconds seconds)";
                        }
                        $errorCount++;
                        break;
                    }
                }
                if (!$errorCount && @rmdir($dir) === false) {
                    $error = error_get_last();
                    if ($error && stripos($error['message'], 'No such file or directory') === false) {
                        // Ignore error when the directory does not exist anymore
                        $errorCount++;
                        if (!$first_error) {
                            $first_error = $error['message'];
                        }
                    }
                }
                if ($errorCount > 0) {
                    self::addLog($errorCount . ' error(s) during deletion of ' . $dir . ' - First error: ' . $first_error, 3);
                }
                return $errorCount === 0;
            }
            return true;
        }

        /**
         * Creates a backup file, then search and replace in it
         *
         * @param $file string File to modify
         * @param mixed $search <p>
         * The value being searched for, otherwise known as the needle.
         * An array may be used to designate multiple needles.
         * </p>
         * @param mixed $replace <p>
         * The replacement value that replaces found search
         * values. An array may be used to designate multiple replacements.
         * </p>
         */
        public static function replaceInFile($file, $search, $replace) {
            if (is_file($file)) {
                $i = 1;
                $suffix = '-backup-' . date('Ymd');
                while(file_exists($file . $suffix)) {
                    $suffix = '-backup-' . date('Ymd') . '-' . $i;
                    $i++;
                }
                Tools::copy($file, $file . $suffix);
                $content = Tools::file_get_contents($file);
                $content = str_replace($search, $replace, $content);
                file_put_contents($file, $content);
            }
        }

        public static function isAjax() {
            // Usage of ajax parameter is deprecated
            $isAjax = Tools::getValue('ajax') || Tools::isSubmit('ajax');

            if (isset($_SERVER['HTTP_ACCEPT'])) {
                $isAjax = $isAjax || preg_match(
                        '#\bapplication/json\b#',
                        $_SERVER['HTTP_ACCEPT']
                    );
            }

            return $isAjax;
        }

        // Does not support flag GLOB_BRACE
        public static function glob_recursive($pattern, $flags = 0)
        {
            $files = glob($pattern, $flags);
            foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
            {
                $files = array_merge($files, self::glob_recursive($dir.'/'.basename($pattern), $flags));
            }
            return $files;
        }

        public static function startsWith($haystack, $needle)
        {
            $length = Tools::strlen($needle);
            return (Tools::substr($haystack, 0, $length) === $needle);
        }

        public static function endsWith($haystack, $needle)
        {
            $length = Tools::strlen($needle);
            if ($length == 0) {
                return true;
            }
            return (Tools::substr($haystack, -$length) === $needle);
        }

        public static function trimTo($string, $default) {
            if (!$string) {
                return $default;
            }
            $ret = trim($string);
            if (Tools::strlen($ret === 0)) {
                return $default;
            }
            return $ret;
        }

        /**
         * Determine if a variable is iterable. i.e. can be used to loop over.
         *
         * @return bool
         */
        public static function isIterable($var)
        {
            return $var !== null && (is_array($var) || $var instanceof Traversable);
        }

        public static function getDomains()
        {
            if (method_exists('Tools', 'getDomains')) {
                return Tools::getDomains();
            }
            $domains = [];
            foreach (ShopUrl::getShopUrls() as $shop_url) {
                /** @var ShopUrl $shop_url */
                if (!isset($domains[$shop_url->domain])) {
                    $domains[$shop_url->domain] = [];
                }

                $domains[$shop_url->domain][] = [
                    'physical' => $shop_url->physical_uri,
                    'virtual' => $shop_url->virtual_uri,
                    'id_shop' => $shop_url->id_shop,
                ];

                if ($shop_url->domain == $shop_url->domain_ssl) {
                    continue;
                }

                if (!isset($domains[$shop_url->domain_ssl])) {
                    $domains[$shop_url->domain_ssl] = [];
                }

                $domains[$shop_url->domain_ssl][] = [
                    'physical' => $shop_url->physical_uri,
                    'virtual' => $shop_url->virtual_uri,
                    'id_shop' => $shop_url->id_shop,
                ];
            }

            return $domains;
        }

        public static function valuesAreIdentical($v1, $v2)
        {
            $type1 = gettype($v1);
            $type2 = gettype($v2);

            switch (true) {
                case ($type1 === 'boolean'):
                    if ($type2 === 'string') {
                        if (($v1 && ((int)$v2) !== 1) || (!$v1 && ((int)$v2) !== 0)) {
                            // Can be string "1" or "0"
                            return false;
                        }
                    } // Else do strict comparison here.
                    else {
                        if ($v1 !== $v2) {
                            return false;
                        }
                    }
                    break;

                case ($type1 === 'integer'):
                    if ($type2 === 'string') {
                        if ($v1 !== (int)$v2) {
                            return false;
                        }
                    } // Else do strict comparison here.
                    else {
                        if ($v1 !== $v2) {
                            return false;
                        }
                    }
                    break;

                case ($type1 === 'double'):
                    if ($type2 === 'string') {
                        if ($v1 !== (float)$v2) {
                            return false;
                        }
                    } // Else do strict comparison here.
                    else {
                        if ($v1 !== $v2) {
                            return false;
                        }
                    }
                    break;

                case ($type1 === 'string'):
                    //Do strict comparison here.
                    if ($v1 !== $v2) {
                        return false;
                    }
                    break;

                case ($type1 === 'array'):
                    $bool = self::arraysAreIdentical($v1, $v2);
                    if ($bool === false) {
                        return false;
                    }
                    break;

                case ($type1 === 'object'):
                    $diffs = self::getObjectDifferences($v1, $v2);
                    if (count($diffs) > 0) {
                        return false;
                    }
                    break;

                case ($type1 === 'NULL'):
                    //Since both types were of type NULL, consider their "values" equal.
                    break;

                case ($type1 === 'resource'):
                    //How to compare if at all?
                    break;

                case ($type1 === 'unknown type'):
                    //How to compare if at all?
                    break;
            } //end switch

            //All tests passed.
            return true;
        }

        public static function getObjectDifferences($o1, $o2)
        {
            $differences = array();
            // Now do strict(er) comparison.
            $reflectionObject = new ReflectionObject($o1);

            $properties = $reflectionObject->getProperties(ReflectionProperty::IS_PUBLIC);

            foreach ($properties as $property) {
                if (in_array($property->name, ['date_upd', 'indexed']) || $property->isStatic()) {
                    continue;
                }
                if (!property_exists($o2, $property->name)) {
                    $differences[$property->name] = self::toString($o1->{$property->name}) . ' <> (not set)';
                }
                else {
                    $bool = self::valuesAreIdentical($o1->{$property->name}, $o2->{$property->name});
                    if ($bool === false) {
                        $differences[$property->name] = self::toString($o1->{$property->name}) . ' <> ' . self::toString($o2->{$property->name});
                    }
                }
            }

            // All tests passed.
            return $differences;
        }

        public static function arraysAreIdentical(array $arr1, array $arr2)
        {
            $count = count($arr1);

            // Require that they have the same size.
            if (count($arr2) !== $count) {
                return false;
            }

            // Require that they have the same keys.
            $arrKeysInCommon = array_intersect_key($arr1, $arr2);
            if (count($arrKeysInCommon) !== $count) {
                return false;
            }

            // Require that they have the same value for same key.
            foreach ($arr1 as $key => $val) {
                $bool = self::valuesAreIdentical($val, $arr2[$key]);
                if ($bool === false) {
                    return false;
                }
            }

            // All tests passed.
            return true;
        }

        public static function toString($val) {
            $type = gettype($val);
            switch (true) {
                case ($type === 'boolean'):
                    return $val ? 'true' : 'false';

                case ($type === 'array'):
                    return 'array[' . count($val) . ']';

                case ($type === 'NULL'):
                    return '(null)';

                case ($type === 'unknown type'):
                    return '(unknown type)';

                default:
                    return (string) $val;
            }
        }

        public static function getDatabaseName() {
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
                $configFile = dirname(__FILE__) . '/../../../app/config/parameters.php';
                if (file_exists($configFile)) {
                    $config = require $configFile;
                    return $config['parameters']['database_name'];
                }
                else {
                    return _DB_NAME_;
                }
            }
            else {
                return _DB_NAME_;
            }
        }

        /**
         * @param string $sql SQL query to execute
         * @param bool $logOnError true if you want errors to be logged
         * @param bool $throwOnError true if you want errors to throw PrestaShopDatabaseException
         * @return bool true if OK
         * @throws PrestaShopDatabaseException
         */
        public static function dbExecuteSQL($sql, $logOnError = true, $throwOnError = false) {
            $db = DB::getInstance();
            $result = false;
            try {
                $result = $db->execute($sql);
                if (!$result) {
                    $msg = 'SQL Error #' . $db->getNumberError() . ': "' . $db->getMsgError() . '" in ' . self::getCallerInfos();
                    $msg .= ' - SQL query was: "' . $sql . '"';
                    if ($logOnError) {
                        self::addLog($msg, 2);
                    }
                    if ($throwOnError) {
                        throw new PrestaShopDatabaseException($msg);
                    }
                }
            }
            catch (PrestaShopDatabaseException $e) {
                if ($logOnError) {
                    $msg = 'SQL Error #' . $db->getNumberError() . ': "' . $db->getMsgError() . '" in ' . self::getCallerInfos();
                    $msg .= ' - SQL query was: "' . $sql . '"';
                    self::addLog($msg . ' - exception: ' . $e->getMessage(), 2);
                }
                if ($throwOnError) {
                    throw $e;
                }
            }
            return $result;
        }

        /**
         * @param string $sql SQL query to execute
         * @param bool $logOnError true if you want errors to be logged
         * @param bool $throwOnError true if you want errors to throw PrestaShopDatabaseException
         * @return array
         * @throws PrestaShopDatabaseException
         */
        public static function dbSelectRows($sql, $logOnError = true, $throwOnError = false) {
            $db = DB::getInstance();
            $result = [];
            try {
                $result = $db->executeS($sql);
                if (!$result && $db->getNumberError() != 0) {
                    $msg = 'SQL Error #' . $db->getNumberError() . ': "' . $db->getMsgError() . '" in ' . self::getCallerInfos();
                    $msg .= ' - SQL query was: "' . $sql . '"';
                    if ($logOnError) {
                        self::addLog($msg, 2);
                    }
                    if ($throwOnError) {
                        throw new PrestaShopDatabaseException($msg);
                    }
                }
            }
            catch (PrestaShopDatabaseException $e) {
                if ($logOnError) {
                    $msg = 'SQL Error #' . $db->getNumberError() . ': "' . $db->getMsgError() . '" in ' . self::getCallerInfos();
                    $msg .= ' - SQL query was: "' . $sql . '"';
                    self::addLog($msg . ' - exception: ' . $e->getMessage(), 2);
                }
                if ($throwOnError) {
                    throw $e;
                }
            }
            if (!is_array($result)) {
                $result = [];
            }
            return $result;
        }

        /**
         * @param $tableName string Name of the table
         * @param $columns string[] Names of the columns
         * @return bool|string Name of the index or false if it does not exist
         * @throws PrestaShopDatabaseException
         */
        public static function dbIndexExists($tableName, $columns) {
            $cols = [];
            if (!is_array($columns)) {
                $cols[0] = $columns;
            }
            else {
                $cols = $columns;
            }
            if (count($cols) > 0) {
                $firsColumn = reset($cols);
                $db = Db::getInstance();
                $rows = self::dbSelectRows('SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema=' . self::dbToString($db,
                        self::getDatabaseName()) . ' AND table_name=' . self::dbToString($db,
                        $tableName) . ' AND column_name=' . self::dbToString($db, $firsColumn));
                foreach ($rows as $row) {
                    $indexCols = self::dbGetIndexColumns($tableName, $row['INDEX_NAME']);
                    if (count($indexCols) === count($cols) && count(array_diff($cols, $indexCols)) === 0) {
                        return $row['INDEX_NAME'];
                    }
                }
            }
            return false;
        }

        /**
         * @param $tableName string Name of the table
         * @param $indexName string Name of the index
         * @return string[] Names of the columns
         * @throws PrestaShopDatabaseException
         */
        public static function dbGetIndexColumns($tableName, $indexName) {
            $columns = [];
            if (is_string($tableName) && is_string($indexName)) {
                $db = Db::getInstance();
                $rows = self::dbSelectRows('SELECT SEQ_IN_INDEX, COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE '
                    . 'table_schema=' . self::dbToString($db, self::getDatabaseName())
                    . ' AND table_name=' . self::dbToString($db, $tableName)
                    . ' AND index_name=' . self::dbToString($db, $indexName)
                    . ' ORDER BY SEQ_IN_INDEX ASC'
                );
                foreach ($rows as $row) {
                    $columns[(int)$row['SEQ_IN_INDEX']] = $row['COLUMN_NAME'];
                }
            }
            return $columns;
        }

        /**
         * @param $tableName string Name of the table
         * @param $columns string[] Names of columns in the index
         * @return bool true if index has been created
         * @throws PrestaShopDatabaseException
         */
        public static function dbCreateIndexIfNotExists($tableName, $columns) {
            $cols = [];
            if (is_string($tableName) && (is_string($columns) || is_array($columns))) {
                if (!is_array($columns)) {
                    $cols[0] = $columns;
                } else {
                    $cols = $columns;
                }
                if (count($cols) > 0) {
                    $db = Db::getInstance();
                    if (self::dbIndexExists($tableName, $columns) === false) {
                        $colsList = '';
                        foreach ($cols as $col) {
                            if (Tools::strlen($colsList) > 0) {
                                $colsList .= ',';
                            }
                            $colsList .= '`' . $db->escape($col) . '`';
                        }
                        if (self::dbExecuteSQL('ALTER TABLE `' . $db->escape($tableName) . '` ADD INDEX (' . $colsList . ');')) {
                            JprestaUtils::addLog("Index created for table $tableName on column (" . $colsList . ')', 1);
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        /**
         * @param $tableName string Name of the table
         * @param $columns string[] Names of columns in the index
         * @return bool true if the index has been deleted
         */
        public static function dbDeleteIndexIfExists($tableName, $columns) {
            $cols = [];
            if (is_string($tableName) && (is_string($columns) || is_array($columns))) {
                if (!is_array($columns)) {
                    $cols[0] = $columns;
                } else {
                    $cols = $columns;
                }
                if (count($cols) > 0) {
                    $db = Db::getInstance();
                    $indexName = self::dbIndexExists($tableName, $columns);
                    if ($indexName !== false) {
                        $colsList = '';
                        foreach ($cols as $col) {
                            if (Tools::strlen($colsList) > 0) {
                                $colsList .= ',';
                            }
                            $colsList .= '`' . $db->escape($col) . '`';
                        }
                        if (self::dbExecuteSQL('ALTER TABLE `' . $db->escape($tableName) . '` DROP INDEX `' . $db->escape($indexName) . '`;')) {
                            JprestaUtils::addLog("Index $indexName deleted for table $tableName on column (" . $colsList . ')', 1);
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        /**
         * @param string $sql SQL query to execute
         * @param bool $logOnError true if you want errors to be logged
         * @param bool $throwOnError true if you want errors to throw PrestaShopDatabaseException
         * @return mixed First value of the first row
         * @throws PrestaShopDatabaseException
         */
        public static function dbGetValue($sql, $logOnError = true, $throwOnError = false) {
            $row = self::dbSelectRows($sql, $logOnError, $throwOnError);
            if (is_array($row) && count($row) > 0 && is_array($row[0]) && count($row[0]) > 0) {
                return array_pop($row[0]);
            }
            return null;
        }

        /**
         * @return string Caller information s a string : file:line::function()
         */
        private static function getCallerInfos()
        {
            $traces = debug_backtrace();
            if (isset($traces[2])) {
                return $traces[1]['file'] . ':' . $traces[1]['line'] . '::' . $traces[2]['function'] . '()';
            }
            return '?';
        }

        /**
         * @param string $filePathContains
         * @param string $functionName
         * @return bool
         */
        public static function isCaller($filePathContains, $functionName)
        {
            $traces = debug_backtrace();
            if (isset($traces[2])) {
                return strpos($filePathContains, $traces[2]['file']) >= 0 && strcmp($functionName, $traces[2]['function']) === 0;
            }
            return false;
        }

        /**
         * jTraceEx() - provide a Java style exception trace
         * @param Throwable $e
         * @param array $seen array passed to recursive calls to accumulate trace lines already seen leave as NULL when
         *              calling this function
         * @return string One entry per trace line
         */
        public static function jTraceEx($e, $seen = null) {
            $starter = $seen ? 'Caused by: ' : '';
            $result = array();
            if (!$seen) $seen = array();
            $trace  = $e->getTrace();
            $prev   = $e->getPrevious();
            $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
            $file = $e->getFile();
            $line = $e->getLine();
            while (true) {
                $current = "$file:$line";
                if (is_array($seen) && in_array($current, $seen)) {
                    $result[] = sprintf(' ... %d more', count($trace)+1);
                    break;
                }
                $result[] = sprintf(' at %s%s%s(%s%s%s)',
                    count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                    count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                    count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                    $line === null ? $file : basename($file),
                    $line === null ? '' : ':',
                    $line === null ? '' : $line);
                if (is_array($seen))
                    $seen[] = "$file:$line";
                if (!count($trace))
                    break;
                $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
                $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
                array_shift($trace);
            }
            $result = join("\n", $result);
            if ($prev) {
                $result .= "\n" . self::jTraceEx($prev, $seen);
            }
            return $result;
        }

        public static function getRequestHeaderValue($headerName) {
            $headerNameLower = Tools::strtolower($headerName);
            $headers = self::getAllHeaders();
            if (array_key_exists($headerName, $headers)) {
                return $headers[$headerNameLower];
            }
            return null;
        }

        public static function getAllHeaders() {
            static $headers = null;
            if ($headers === null) {
                $headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (Tools::substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-',
                            Tools::strtolower(str_replace('_', ' ', Tools::substr($name, 5))))] = $value;
                    }
                }
            }
            return $headers;
        }

        public static function dbToInt($val) {
            if ($val && !empty($val)) {
                if (is_numeric($val)) {
                    // Preserve unsigned integers for 32bit systems
                    return $val;
                }
            }
            return 'NULL';
        }

        /**
         * @param $db Db
         * @param $val
         * @return string
         */
        public static function dbToString($db, $val) {
            if ($val && !empty($val)) {
                return '\'' . $db->escape($val) . '\'';
            }
            else {
                return 'NULL';
            }
        }

        public static function getConfigurationAllShop($key, $default = false, $idLang = null) {
            if (Tools::version_compare(_PS_VERSION_,'1.7','<')) {
                if (Configuration::hasKey($key, $idLang, 0, 0)) {
                    return Configuration::get($key, $idLang, 0, 0);
                }
                return $default;
            }
            return Configuration::get($key, $idLang, 0, 0, $default);
        }

        public static function getConfigurationByShopId($key, $id_shop, $default = false, $idLang = null) {
            if (Tools::version_compare(_PS_VERSION_,'1.7','<')) {
                if ($id_shop === null || !Shop::isFeatureActive()) {
                    $id_shop = Shop::getContextShopID(true);
                }
                else {
                    $id_shop = (int)$id_shop;
                }
                if (Configuration::hasKey($key, $idLang, 0, $id_shop)) {
                    return Configuration::get($key, $idLang, 0, $id_shop);
                }
                return $default;
            }
            return Configuration::get($key, $idLang, 0, (int) $id_shop, $default);
        }

        public static function getConfigurationOfCurrentShop($key, $default = false, $idLang = null) {
            if (Tools::version_compare(_PS_VERSION_,'1.7','<')) {
                if (Configuration::hasKey($key, $idLang, null, null)) {
                    return Configuration::get($key, $idLang, null, null);
                }
                return $default;
            }
            return Configuration::get($key, $idLang, null, null, $default);
        }

        public static function saveConfigurationByShopId($key, $value, $id_shop) {
            Configuration::updateValue($key, $value, false, null, $id_shop);
        }

        public static function saveConfigurationAllShop($key, $value) {
            Configuration::updateValue($key, $value, false, 0, 0);
        }

        public static function saveConfigurationOfCurrentShop($key, $value) {
            Configuration::updateValue($key, $value, false);
        }

        public static function isModuleEnabledByShopId($id_module, $id_shop) {
            $ret = (int) JprestaUtils::dbGetValue('SELECT count(*) FROM `' . _DB_PREFIX_ . 'module_shop` WHERE `id_module` = ' . (int) $id_module . ' AND `id_shop` = ' . (int) $id_shop);
            return $ret > 0;
        }

        /**
         * Get a security token specific to JPresta modules. Create it if it does not exists
         * @param null $id_shop
         * @return string
         */
        public static function getSecurityToken($id_shop = null) {
            if ($id_shop === null) {
                $id_shop = Shop::getContextShopID();
            }
            $token = self::getConfigurationByShopId('pagecache_cron_token', $id_shop);
            if (!$token) {
                $token = self::generateRandomString();
                self::saveConfigurationByShopId('pagecache_cron_token', $token, $id_shop);
            }
            return $token;
        }

        /**
         * @return string A string that identify this Prestashop instance
         */
        public static function getPrestashopToken() {
            $token = self::getConfigurationAllShop('jpresta_ps_token');
            if (!$token) {
                $token = 'PS-' . Tools::strtoupper(self::generateRandomString(12));
                self::saveConfigurationAllShop('jpresta_ps_token', $token);
            }
            return $token;
        }

        public static function getPrestashopType() {
            return self::getConfigurationAllShop('jpresta_ps_type', null);
        }

        public static function setPrestashopType($type) {
            self::saveConfigurationAllShop('jpresta_ps_type', $type === 'test' ? 'test' : 'prod');
        }

        public static function getJPrestaAccountKey() {
            return self::getConfigurationAllShop('jpresta_account_key', null);
        }

        public static function setJPrestaAccountKey($key) {
            self::saveConfigurationAllShop('jpresta_account_key', $key);
        }

        public static function generateRandomString($length = 16) {
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
            $final_rand = '';
            for($i = 0; $i < $length; $i ++) {
                $final_rand .= $chars [rand ( 0, Tools::strlen ( $chars ) - 1 )];
            }
            return $final_rand;
        }

        /**
         * @param $message
         * @param int $severity 1 = info, 2 = warning, 3 = error, 4 = critical error
         * @param null $errorCode
         * @param null $objectType
         * @param null $objectId
         * @param bool $allowDuplicate
         * @param null $idEmployee
         */
        public static function addLog($message, $severity = 1, $errorCode = null, $objectType = null, $objectId = null, $allowDuplicate = false, $idEmployee = null)
        {
            if (class_exists('PrestaShopLogger')) {
                // Since PS 1.6.0.2
                PrestaShopLogger::addLog($message, $severity, $errorCode, $objectType, $objectId, $allowDuplicate, $idEmployee);
            }
            else {
                Logger::addLog($message, $severity, $errorCode, $objectType, $objectId, $allowDuplicate, $idEmployee);
            }
        }
    }
}
