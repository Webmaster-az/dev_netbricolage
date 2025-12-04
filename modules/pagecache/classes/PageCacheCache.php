<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('PageCacheCache')) {

    abstract class PageCacheCache
    {
        abstract public function get($key, $ttl = 0);

        abstract public function set($key, $value, $ttl = 0);

        /**
         * @param $key string The cache key to delete
         * @return bool true if OK, false if the key has not been completly deleted
         */
        abstract public function delete($key);

        /**
         * @param int $timeoutSeconds Maximum number of second to spend in flush
         * @return bool true if OK, false if the cache has not been completly deleted
         */
        abstract public function flush($timeoutSeconds = 0);
    }
}