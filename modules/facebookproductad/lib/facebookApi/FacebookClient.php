<?php
/**
 * Dynamic Ads + Pixel
 *
 * @author    businesstech.fr <modules@businesstech.fr> - https://www.businesstech.fr/
 * @copyright Business Tech - https://www.businesstech.fr/
 * @license   see file: LICENSE.txt
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

namespace FacebookProductAd\FacebookApi;

if (!defined('_PS_VERSION_')) {
    exit;
}
use FacebookProductAd\Models\apiLog;
use FacebookProductAd\ModuleLib\moduleTools;

class FacebookClient
{
    const API_VERSION = 'v20.0';

    /**
     * The API url
     *
     * @var string
     */
    public static $api_url;

    public function __construct()
    {
    }

    /**
     * method send formatted data to Facebook api
     *
     * @param mixed $data
     *
     * @return array
     */
    public static function send($data)
    {
        try {
            // Only handle the api call if we have pixel token and if the feature is activated
            if (!empty(\FacebookProductAd::$conf['FPA_PIXEL']) && !empty(\FacebookProductAd::$conf['FPA_TOKEN_API']) && !empty(\FacebookProductAd::$conf['FPA_USE_API'])) {
                $url = 'https://graph.facebook.com/' . self::API_VERSION . '/' . (string) \FacebookProductAd::$conf['FPA_PIXEL'] . '/events?access_token=' . (string) \FacebookProductAd::$conf['FPA_TOKEN_API'];
                $curl = curl_init($url);
                $headers = [
                    'content-type: application/json',
                ];
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $response = curl_exec($curl);

                // Use case get error from Facebook API and store it in model
                if (isset($response->error)) {
                    $apiLog = new apiLog();
                    $apiLog->error_message = moduleTools::formatApiErrorMessage($response->error);
                    $apiLog->page_event = (string) moduleTools::detectCurrentPage();
                    $apiLog->id_shop = \FacebookProductAd::$iShopId;

                    $apiLog->add();
                }

                curl_close($curl);
            }
        } catch (\Exception $e) {
            \PrestaShopLogger::addLog($e->getMessage(), 1, $e->getCode(), null, null, true);
        }
    }
}
