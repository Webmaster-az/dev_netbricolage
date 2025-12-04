<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

include_once(dirname(__FILE__) . '/../../pagecache.php');

class AdminPageCacheDatasController extends ModuleAdminController
{
    public $php_self = null;

    public function init()
    {
        if (!isset(Context::getContext()->link)) {
            /* Link should be initialized in the context but sometimes it is not */
            $https_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
            Context::getContext()->link = new Link($https_link, $https_link);
        }
        // avoid useless treatments
    }

    public function initHeader()
    {
        // avoid useless treatments
    }

    public function setMedia($isNewTheme = false)
    {
        // avoid useless treatments
    }

    public function initContent()
    {
        if (Tools::getIsset('cache_key') && Tools::getIsset('id_shop')) {
            die(
                Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . '/pagecache/views/templates/admin/get-content-tab-datas_cache.tpl')
                . $this->module->getCache(Tools::getValue('id_shop'))->get(JprestaCacheKey::intToString(Tools::getValue('cache_key')))
            );
        }

        header('Access-Control-Allow-Origin: *');
        header('Cache-Control: max-age=300, private');
        header('Content-type: application/json');

        // DB table to use
        $table = _DB_PREFIX_.PageCacheDAO::TABLE;

        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array('db' => 'url', 'dt' => 0, 'formatter' => function ($s, $row) {
                return self::formatURL($s, $row);
            }),
            array('db' => 'id_currency', 'dt' => -1),
            array('db' => 'cache_key', 'dt' => -1),
            array('db' => 'id_shop', 'dt' => -1),
            array('db' => 'id_lang', 'dt' => -1),
            array('db' => 'id_country', 'dt' => -1),
            array('db' => 'id_device', 'dt' => -1),
            array('db' => 'id_fake_customer', 'dt' => -1),
            array('db' => 'id_tax_csz', 'dt' => -1),
            array('db' => 'id_specifics', 'dt' => -1),
            array('db' => 'v_css', 'dt' => -1),
            array('db' => 'v_js', 'dt' => -1),
            array('db' => 'id_controller', 'dt' => 1, 'formatter' => function ($id_controller) {
                return self::formatController($id_controller);
            }),
            array('db' => 'id_object', 'dt' => 2),
            array('db' => 'last_gen', 'dt' => 3, 'formatter' => function ($d, $row) {
                return self::formatLastGenerated($d, $row);
            }),
            array('db' => 'deleted', 'dt' => 4,'formatter' => function ($deleted) {
                return self::formatDeleted($deleted);
            }),
            array('db' => 'count_hit', 'dt' => 5, 'formatter' => function ($s, $row) {
                return self::formatHit($s, $row);
            }),
            array('db' => 'count_missed', 'dt' => -1),
        );
        $result = self::simple($_GET, $table, $columns);
        die(json_encode($result));
    }

    private static function formatController($id_controller) {
        return PageCacheDAO::getControllerName($id_controller);
    }

    private static function formatHit($count_hit, $row) {
        $smarty = Context::getContext()->smarty;
        $smarty->assign('count_hit', $count_hit);
        $smarty->assign('count_missed', $row['count_missed']);
        return $smarty->fetch(_PS_MODULE_DIR_ . '/pagecache/views/templates/admin/get-content-tab-datas_hit.tpl');
    }

    private static function formatDeleted($deleted) {
        $smarty = Context::getContext()->smarty;
        $smarty->assign('deleted', $deleted);
        $smarty->assign('isPs17', Tools::version_compare(_PS_VERSION_, '1.6', '>'));
        return $smarty->fetch(_PS_MODULE_DIR_ . '/pagecache/views/templates/admin/get-content-tab-datas_deleted.tpl');
    }

    private static function formatLastGenerated($d, $row) {
        $lastGen = strtotime($d);
        $cache_ttl = 60 * ((int)Configuration::get('pagecache_'.PageCacheDAO::getControllerName($row['id_controller']).'_timeout'));
        $age = time() - $lastGen;
        $ttl = $cache_ttl - $age;
        if ($cache_ttl <= 0) {
            $percent = 0;
        }
        elseif ($cache_ttl <= $age) {
            $percent = 100;
        }
        else {
            $percent = $age * 100 / $cache_ttl;
        }
        $color = '#ccc';
        if ($percent >= 100) {
            $color = 'red';
        }
        else if ($percent >= 95) {
            $color = 'orange';
        }

        $smarty = Context::getContext()->smarty;
        $smarty->assign('lastGen', strtotime($d));
        if ($ttl == -60) {
            $ttl_msg = 'forever';
        }
        elseif ($ttl <= 0) {
            $ttl_msg = 'dead';
        }
        else {
            $ttl_msg = self::getNiceDuration($ttl);
        }
        $smarty->assign('age', self::getNiceDuration($age));
        $smarty->assign('last_gen', date('Y-m-d H:i:s', strtotime($d)));
        $smarty->assign('ttl_msg', $ttl_msg);
        $smarty->assign('color', $color);
        $smarty->assign('percent', $percent);
        return $smarty->fetch(_PS_MODULE_DIR_ . '/pagecache/views/templates/admin/get-content-tab-datas_lastgen.tpl');
    }

    private static function formatURL($url, $row) {

        $smarty = Context::getContext()->smarty;
        $smarty->clearAssign('flag_currency');
        $smarty->clearAssign('flag_country');
        $smarty->clearAssign('flag_device');
        $smarty->clearAssign('flag_group');
        $smarty->clearAssign('flag_tax_manager');
        $smarty->clearAssign('flag_specifics');
        $smarty->clearAssign('flag_specifics_more');
        $smarty->clearAssign('flag_v_css');
        $smarty->clearAssign('flag_v_js');
        $smarty->clearAssign('url_cached');

        if (!empty($row['id_currency'])) {
            $currency = new Currency($row['id_currency']);
            $smarty->assign('flag_currency', $currency->sign);
        }
        if (!empty($row['id_country'])) {
            $country = new Country($row['id_country']);
            $smarty->assign('flag_country', $country->iso_code);
        }
        if (!empty($row['id_device'])) {
            if ($row['id_device'] == PageCache::DEVICE_COMPUTER) {
                $smarty->assign('flag_device', 'desktop');
            }
            elseif ($row['id_device'] == PageCache::DEVICE_TABLET) {
                $smarty->assign('flag_device', 'tablet');
            }
            elseif ($row['id_device'] == PageCache::DEVICE_MOBILE) {
                $smarty->assign('flag_device', 'mobile');
            }
        }
        if (!empty($row['id_fake_customer'])) {
            $groupList = '';
            $groupIds = Customer::getGroupsStatic($row['id_fake_customer']);
            foreach ($groupIds as $groupId) {
                $group = new Group($groupId);
                if (!empty($groupList)) {
                    $groupList .= ', ';
                }
                if (is_array($group->name)) {
                    $groupList .= $group->name[Context::getContext()->cookie->id_lang];
                }
                else {
                    $groupList .= $group->name;
                }
            }
            $smarty->assign('flag_group', $groupList);
        }
        if (!empty($row['id_tax_csz'])) {
            $tax_manager_json = PageCacheDAO::getDetailsById($row['id_tax_csz']);
            $smarty->assign('flag_tax_manager', $row['id_tax_csz']);
            $smarty->assign('flag_tax_manager_more', JprestaUtilsTaxManager::toPrettyString($tax_manager_json));
        }
        if (!empty($row['id_specifics'])) {
            $specifics = PageCacheDAO::getDetailsById($row['id_specifics']);
            $jscks = new JprestaCacheKeySpecifics($specifics);
            $smarty->assign('flag_specifics', $row['id_specifics']);
            $smarty->assign('flag_specifics_more', $jscks->toPrettyString());
        }
        if (!empty($row['v_css'])) {
            $smarty->assign('flag_v_css', $row['v_css']);
        }
        if (!empty($row['v_js'])) {
            $smarty->assign('flag_v_js', $row['v_js']);
        }

        $smarty->assign('url', $url);

        if (!$row['deleted']) {
            $cacheLink =
                Context::getContext()->link->getAdminLink(
                    'AdminPageCacheDatas',
                    true,
                    array(),
                    array('cache_key' => $row['cache_key'], 'id_shop' => $row['id_shop']));
            if (Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
                $cacheLink .= '&cache_key=' . $row['cache_key'] . '&id_shop=' . (int) $row['id_shop'];
            }
            $smarty->assign('url_cached', $cacheLink);
        }

        $smarty->assign('isPs17', Tools::version_compare(_PS_VERSION_, '1.6', '>'));
        return $smarty->fetch(_PS_MODULE_DIR_ . '/pagecache/views/templates/admin/get-content-tab-datas_url.tpl');

    }

    private static function getNiceDuration($durationInSeconds) {
        $duration = '';
        if ($durationInSeconds < 0) {
            $duration = '-';
        }
        else {
            $days = floor($durationInSeconds / 86400);
            $durationInSeconds -= $days * 86400;
            $hours = floor($durationInSeconds / 3600);
            $durationInSeconds -= $hours * 3600;
            $minutes = floor($durationInSeconds / 60);
            $seconds = $durationInSeconds - $minutes * 60;

            if ($days > 0) {
                $duration .= $days . ' days';
            }
            if ($hours > 0) {
                $duration .= ' ' . $hours . ' hours';
            }
            if ($minutes > 0) {
                $duration .= ' ' . $minutes . ' minutes';
            }
            if ($seconds > 0) {
                $duration .= ' ' . $seconds . ' seconds';
            }
        }
        return $duration;
    }

    /**
     * Create the data output array for the DataTables rows
     *
     * @param  array $columns Column information array
     * @param  array $data Data from the SQL get
     * @return array          Formatted data in a row based format
     */
    private static function data_output($columns, $data)
    {
        $out = array();
        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();
            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                } else {
                    $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                }
            }
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     * @param  array $request Data sent to server by DataTables
     * @return string SQL limit clause
     */
    private static function limit($request)
    {
        $limit = '';
        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . ((int)$request['start']) . ", " . ((int)$request['length']);
        }
        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     * @param  array $request Data sent to server by DataTables
     * @param  array $columns Column information array
     * @return string SQL order by clause
     */
    private static function order($request, $columns)
    {
        $order = '';
        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');
            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = (int)($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];
                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }
            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }
        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL where clause
     */
    private static function filter ( $request, $columns )
    {
        $columnSearch = array();
        $dtColumns = self::pluck( $columns, 'dt' );

        // Individual column filtering
        if ( isset( $request['columns'] ) ) {
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                $str = $requestColumn['search']['value'];

                if ( $requestColumn['searchable'] == 'true' && $str != '' ) {
                    if(!empty($column['db'])){
                        if ($column['db'] === 'url') {
                            $columnSearch[] = "`" . $column['db'] . "` LIKE '%" . $str . "%'";
                        }
                        else {
                            $columnSearch[] = "`" . $column['db'] . "` = '" . $str . "'";
                        }
                    }
                }
            }
        }

        // Combine the filters into a single string
        $where = 'id_shop IN (' . implode(',', Shop::getContextListShopID()) . ')';

        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }

        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }

        return $where;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     * @param  array $request Data sent to server by DataTables
     * @param  string $table SQL table to query
     * @param  array $columns Column information array
     * @return array Server-side processing response array
     */
    private static function simple($request, $table, $columns)
    {
        // Build the SQL query string from the request
        $limit = self::limit($request);
        $order = self::order($request, $columns);
        $where = self::filter( $request, $columns);

        // Main query to actually get the data
        try {
            $data = JprestaUtils::dbSelectRows("SELECT `" . implode("`, `", self::pluck($columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order
			 $limit"
            );
            // Data set length after filtering
            // Total data set length
            $recordsFiltered = $recordsTotal = JprestaUtils::dbGetValue("SELECT COUNT(*) FROM `$table` $where");
        } catch (Exception $e) {
            die($e->getMessage());
        }
        /*
         * Output
         */
        return array(
            "draw" => isset ($request['draw']) ? (int)$request['draw'] : 0,
            "recordsTotal" => (int)$recordsTotal,
            "recordsFiltered" => (int)$recordsFiltered,
            "data" => self::data_output($columns, $data)
        );
    }

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Pull a particular property from each assoc. array in a numeric array,
     * returning and array of the property values from each item.
     *
     * @param  array $a Array to get data from
     * @param  string $prop Property to read
     * @return array        Array of property values
     */
    private static function pluck($a, $prop)
    {
        $out = array();
        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }
}
