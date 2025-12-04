<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('PageCacheCacheMemcache')) {

    class PageCacheCacheMemcache extends PageCacheCache
    {
        /**
         * @var Memcache
         */
        private $memcache;

        /**
         * @var bool Connection status
         */
        private $is_connected = false;

        public function __construct($host, $port)
        {
            $this->connect($host, $port);
        }

        public function __destruct()
        {
            $this->close();
        }

        /**
         * Connect to memcache server.
         */
        public function connect($host, $port)
        {
            if (class_exists('Memcache') && extension_loaded('memcache')) {
                $this->memcache = new Memcache();
                $this->is_connected = @$this->memcache->connect($host, $port);
            }
        }

        /**
         * @return bool
         */
        public function isConnected()
        {
            return $this->is_connected;
        }

        /**
         * Close connection to memcache server.
         *
         * @return bool
         */
        protected function close()
        {
            if (!$this->is_connected) {
                return false;
            }

            return $this->memcache->close();
        }

        public function getVersion()
        {
            if (!$this->is_connected) {
                return '';
            }
            $version = $this->memcache->getVersion();
            if (is_array($version)) {
                $rev = array_reverse($version);
                $version = array_pop($rev);
            }
            return $version;
        }

        public static function isCompatible()
        {
            // Check extension
            return class_exists('CacheMemcache')
                && class_exists('Memcache')
                && extension_loaded('memcache');
        }

        public function get($key, $ttl = -1)
        {
            if (!$this->is_connected) {
                return false;
            }
            if ($ttl < -1) {
                ;
            } // Avoid Prestashop validator "Unused function parameter $ttl."
            return $this->memcache->get($key);
        }

        public function set($key, $value, $ttl = -1)
        {
            if ($this->is_connected) {
                if ($ttl < 0) {
                    $ttl = 0;
                }
                $result = $this->memcache->set($key, $value, 0, $ttl);

                if ($result === false) {
                    // TODO Log something
                }
            }
        }

        public function delete($key)
        {
            if ($this->is_connected) {
                return (bool)$this->memcache->delete($key);
            }
            return true;
        }

        public function flush($timeoutSeconds = 0)
        {
            if ($this->is_connected) {
                return (bool)$this->memcache->flush();
            }
            return true;
        }
    }
}