<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
*
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*/

if (!class_exists('PageCacheCacheMultiStore')) {

    class PageCacheCacheMultiStore extends PageCacheCache
    {
        private $caches = array();

        public function addCache($cache)
        {
            $this->caches[] = $cache;
        }

        public function get($key, $ttl = 0)
        {
            // Should not be called
            foreach ($this->caches as $cache) {
                $value = $cache->get($key, $ttl);
                if ($value !== false) {
                    return $value;
                }
            }
            return false;
        }

        public function set($key, $value, $ttl = -1)
        {
            // Should not be called
            foreach ($this->caches as $cache) {
                $cache->set($key, $value, $ttl);
            }
        }

        public function delete($key)
        {
            $ret = true;
            foreach ($this->caches as $cache) {
                $ret = $ret && $cache->delete($key);
            }
            return $ret;
        }

        public function flush($timeoutSeconds = 0)
        {
            $ret = true;
            foreach ($this->caches as $cache) {
                $ret = $ret && $cache->flush($timeoutSeconds);
            }
            return $ret;
        }
    }
}