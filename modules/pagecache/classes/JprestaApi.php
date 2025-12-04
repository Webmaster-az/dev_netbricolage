<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

class JprestaApi {

    const JPRESTA_PROTO = 'https://';
    const JPRESTA_DOMAIN_EXT = '.com';
    const JPRESTA_DOMAIN = 'jpresta';
    const JPRESTA_PATH_API_LICENSES = '/fr/module/jprestacrm/licenses';
    const JPRESTA_DOMAIN_ADMIN = 'admin.jpresta';
    const JPRESTA_PATH_URL_LICENSES = '/licenses.php';
    const JPRESTA_DOMAIN_CACHE_WARMER = 'cachewarmer.jpresta';
    const JPRESTA_PATH_URL_CACHE_WARMER = '/';
    const JPRESTA_DOMAIN_AUTOCONF = 'autoconf.jpresta';
    const JPRESTA_PATH_URL_AUTOCONF = '/autoconf.php';

    /**
     * @var string JPresta Account Key
     */
    private $jak;

    /**
     * @var string The string that identify this Prestashop instance
     */
    private $psToken;

    /**
     * JprestaApi constructor.
     * @param string $jak
     * @param string $psToken
     */
    public function __construct($jak, $psToken)
    {
        $this->jak = $jak;
        $this->psToken = $psToken;
    }

    /**
     * @return string[] All installed JPresta module names
     */
    private static function getJPrestaModules() {
        $modulesName = [];
        $rows = JprestaUtils::dbSelectRows('SELECT name FROM `'._DB_PREFIX_.'module` WHERE name LIKE \'jpresta%\' OR name IN (\'pagecache\',\'pagecachestd\')');
        foreach ($rows as $row) {
            $modulesName[] = $row['name'];
        }
        return $modulesName;
    }

    /**
     * @param $psIsTest boolean true if this is a Prestashop instance for test, not production
     * @return boolean|string true if ok, error message if not ok
     */
    public function attach($psIsTest) {

        if (function_exists('curl_init')) {
            $curl = curl_init();

            $defaultShop = new Shop((int) Configuration::get('PS_SHOP_DEFAULT'));
            $post_data = array(
                'action' => 'attach_module',
                'ajax' => 1,
                'ps_token' => $this->psToken,
                'shop_url' => $defaultShop->getBaseURL(true),
                'ps_version' => _PS_VERSION_,
                'modules' => implode(',', self::getJPrestaModules()),
                'ps_is_test' => (bool)$psIsTest
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, self::JPRESTA_PROTO . self::JPRESTA_DOMAIN . self::JPRESTA_DOMAIN_EXT . self::JPRESTA_PATH_API_LICENSES);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'x-jpresta-account-key: '.$this->jak
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

            $content = curl_exec($curl);

            if (false === $content) {
                $res = sprintf('error code %d - %s',
                    curl_errno($curl),
                    curl_error($curl)
                );
            }
            else {
                $jsonContent = json_decode($content, true);
                if (!is_array($jsonContent) || !array_key_exists('status', $jsonContent)) {
                    $res = 'JPresta server returned response in incorrect format';
                }
                else {
                    if ($jsonContent['status'] === 'ok') {
                        $res = true;
                    }
                    else {
                        if (array_key_exists('message', $jsonContent)) {
                            $res = $jsonContent['message'];
                        }
                        else {
                            $res = 'The account has not been attached for an unknown reason';
                        }
                    }
                }
            }

            curl_close($curl);
        }
        else {
            $res = 'CURL must be available';
        }

        return $res;
    }

    public function detach() {

        if (function_exists('curl_init')) {
            Tools::refreshCACertFile();
            $curl = curl_init();

            $post_data = array(
                'action' => 'detach',
                'ajax' => 1,
                'ps_token' => $this->psToken
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, self::JPRESTA_PROTO . self::JPRESTA_DOMAIN . self::JPRESTA_DOMAIN_EXT . self::JPRESTA_PATH_API_LICENSES);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'x-jpresta-account-key: '.$this->jak
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

            $content = curl_exec($curl);

            if (false === $content) {
                $res = sprintf('error code %d - %s',
                    curl_errno($curl),
                    curl_error($curl)
                );
                JprestaUtils::addLog('Detach JAK - ' . $res, 2);
            }
            else {
                $jsonContent = json_decode($content, true);
                if (!is_array($jsonContent) || !array_key_exists('status', $jsonContent)) {
                    $res = 'JPresta server returned response in incorrect format';
                    JprestaUtils::addLog('Detach JAK - ' . $res, 2);
                }
                else {
                    if ($jsonContent['status'] === 'ok') {
                        $res = true;
                    }
                    elseif ($jsonContent['status'] === 'jak_invalid') {
                        if (array_key_exists('message', $jsonContent)) {
                            JprestaUtils::addLog('Ignored error: cannot detach JAK ' . $this->jak . ' - ' . $jsonContent['message'], 2);
                        }
                        else {
                            JprestaUtils::addLog('Ignored error: cannot detach JAK ' . $this->jak, 2);
                        }
                        $res = true;
                    }
                    else {
                        if (array_key_exists('message', $jsonContent)) {
                            $res = $jsonContent['message'];
                        }
                        else {
                            $res = 'The account has not been detached for an unknown reason';
                        }
                        JprestaUtils::addLog('Detach JAK - ' . $res, 2);
                    }
                }
            }

            curl_close($curl);
        }
        else {
            $res = 'CURL must be available';
        }

        return $res;
    }

    public static function getLicensesURL() {
        return self::JPRESTA_PROTO . self::JPRESTA_DOMAIN_ADMIN . self::JPRESTA_DOMAIN_EXT . self::JPRESTA_PATH_URL_LICENSES;
    }

    public static function getCacheWarmerDashboardURL() {
        return self::JPRESTA_PROTO . self::JPRESTA_DOMAIN_CACHE_WARMER . self::JPRESTA_DOMAIN_EXT . self::JPRESTA_PATH_URL_CACHE_WARMER;
    }

    public static function getAutoconfURL() {
        return self::JPRESTA_PROTO . self::JPRESTA_DOMAIN_AUTOCONF . self::JPRESTA_DOMAIN_EXT . self::JPRESTA_PATH_URL_AUTOCONF;
    }

}