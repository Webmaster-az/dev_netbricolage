<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('PageCacheCacheMemcached')) {

    class PageCacheCacheMemcached extends PageCacheCache
    {
        /**
         * @var Memcached
         */
        private $memcached;

        /**
         * @var bool Connection status
         */
        private $is_configured = false;

        public function __construct($host, $port)
        {
            $this->connect($host, $port);
        }

        /**
         * Connect to memcache server.
         */
        public function connect($host, $port)
        {
            if (class_exists('Memcached') && extension_loaded('memcached')) {
                $this->memcached = new Memcached();
                $this->is_configured = @$this->memcached->addServer($host, $port);
            }
        }

        /**
         * @return bool
         */
        public function isConnected($host, $port)
        {
            $statuses = $this->memcached->getStats();
            return isset($statuses[$host . ':' . ($port !== 0 ? $port : '11211')]);
        }

        public function getResultMessage()
        {
            if ($this->memcached) {
                return $this->memcached->getResultMessage() . ' (' . $this->memcached->getResultCode() . ')';
            }
            return '';
        }

        public function getVersion()
        {
            if (!$this->is_configured) {
                return '';
            }
            $version = $this->memcached->getVersion();
            if (is_array($version)) {
                $rev = array_reverse($version);
                $version = array_pop($rev);
            }
            return $version;
        }

        public static function isCompatible()
        {
            // Check extension
            return class_exists('CacheMemcached')
                && class_exists('Memcached')
                && extension_loaded('memcached');
        }

        public function get($key, $ttl = -1)
        {
            if (!$this->is_configured) {
                return false;
            }
            if ($ttl < -1) {
                ;
            } // Avoid Prestashop validator "Unused function parameter $ttl."
            return $this->memcached->get($key);
        }

        public function set($key, $value, $ttl = -1)
        {
            if ($this->is_configured) {
                if ($ttl < 0) {
                    $ttl = 0;
                }
                $result = $this->memcached->set($key, $value, $ttl);

                if ($result === false) {
                    // TODO Log something
                }
            }
        }

        public function delete($key)
        {
            if ($this->is_configured) {
                return (bool)$this->memcached->delete($key);
            }
            return true;
        }

        public function flush($timeoutSeconds = 0)
        {
            if ($this->is_configured) {
                return (bool)$this->memcached->flush();
            }
            return true;
        }
    }
}