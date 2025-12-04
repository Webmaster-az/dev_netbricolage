<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaUtilsMobileDetect')) {

    require_once 'JprestaUtils.php';

    if (!class_exists('Mobile_Detect')) {
        // For PS 1.5
        require_once(_PS_TOOL_DIR_ . 'mobile_Detect/Mobile_Detect.php');
    }

    class JprestaUtilsMobileDetect extends Mobile_Detect
    {
        private $isMobile = false;

        private $isTablet = false;

        /**
         * JprestaUtilsMobileDetect constructor.
         * @param bool $isMobile
         */
        public function __construct()
        {
            $device = JprestaUtils::getRequestHeaderValue('jpresta-device');
            if ($device) {
                if ($device === 'mobile') {
                    $this->isMobile = true;
                } elseif ($device === 'tablet') {
                    $this->isTablet = true;
                }
            }
        }

        public function isMobile($userAgent = null, $httpHeaders = null)
        {
            return $this->isMobile;
        }

        public function isTablet($userAgent = null, $httpHeaders = null)
        {
            return $this->isTablet;
        }
    }
}