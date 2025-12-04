<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaCacheKeySpecifics')) {

    class JprestaCacheKeySpecifics
    {
        /**
         * This is public only to be able to encode in JSON, you must use functions
         * @var array All informations other than cookies
         */
        public $values = array();

        /**
         * This is public only to be able to encode in JSON, you must use functions
         * @var array Values stored in $_SESSION
         */
        public $sessionsProperties = array();

        /**
         * This is public only to be able to encode in JSON, you must use functions
         * @var array Cookies directly stored in $_COOKIES
         */
        public $cookies = array();

        /**
         * This is public only to be able to encode in JSON, you must use functions
         * @var array Cookies stored in the Prestashop cookie
         */
        public $psCookies = array();

        /**
         * This is public only to be able to encode in JSON, you must use functions
         * @var array Cookies directly stored in $_COOKIES but encrypted with Cookie class
         */
        public $otherPsCookies = array();

        /**
         * JprestaCacheKeySpecifics constructor.
         * @param $json string JSON encoded object
         */
        public function __construct($json = null)
        {
            if ($json) {
                $object = json_decode($json);
                if (isset($object->values)) {
                    $this->values = $object->values;
                }
                if (isset($object->sessionsProperties)) {
                    $this->sessionsProperties = $object->sessionsProperties;
                }
                if (isset($object->cookies)) {
                    $this->cookies = $object->cookies;
                }
                if (isset($object->psCookies)) {
                    $this->psCookies = $object->psCookies;
                }
                if (isset($object->values)) {
                    $this->otherPsCookies = $object->otherPsCookies;
                }
            }
        }

        /**
         * @return bool
         */
        public function isEmpty()
        {
            return empty($this->values) && empty($this->cookies) && empty($this->psCookies) && empty($this->otherPsCookies) && empty($this->sessionsProperties);
        }

        public function keepCookie($name)
        {
            if (array_key_exists($name, $_COOKIE)) {
                // Necessary to avoid errors in Prestashop Addons validator
                foreach ($_COOKIE as $key => $cookieValue) {
                    if ($key === $name) {
                        $this->cookies[$name] = $cookieValue;
                    }
                }
            }
        }

        public function keepSessionProperty($name)
        {
            if (array_key_exists($name, $_SESSION)) {
                // Necessary to avoid errors in Prestashop Addons validator
                foreach ($_SESSION as $key => $value) {
                    if ($key === $name) {
                        $this->sessionsProperties[$name] = $value;
                    }
                }
            }
        }

        public function keepPsCookie($name)
        {
            $cookie = Context::getContext()->cookie;
            if ($cookie->__isset($name)) {
                $this->psCookies[$name] = $cookie->__get($name);
            }
        }

        public function keepOtherPsCookie($name)
        {
            $this->otherPsCookies[$name] = new Cookie($name);
        }

        public function keepValue($name, $value)
        {
            $this->values[$name] = $value;
        }

        public function getValue($name)
        {
            if (array_key_exists($name, $this->values)) {
                return $this->values[$name];
            }
            return null;
        }

        public function restoreCookies()
        {
            foreach ($this->cookies as $cookieName => $cookieValue) {
                $_COOKIE[$cookieName] = $cookieValue;
            }
            foreach ($this->psCookies as $cookieName => $cookieValue) {
                $cookie = Context::getContext()->cookie;
                $cookie->__set($cookieName, $cookieValue);
            }
            foreach ($this->otherPsCookies as $cookieName => $cookieValue) {
                $cookie = new Cookie($cookieName);
                // TODO
            }
            foreach ($this->sessionsProperties as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        public function __toString()
        {
            return json_encode($this);
        }

        public function toPrettyString()
        {
            return json_encode($this, JSON_PRETTY_PRINT);
        }
    }
}
