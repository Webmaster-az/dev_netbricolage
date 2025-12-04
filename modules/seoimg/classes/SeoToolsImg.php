<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
* -------------------------------------------------------------------
*
* Description :
*   This is a PHP class for some shortcuts of SEO module.
*/

class SeoToolsImg
{
    /**
    * Merge two or more arrays recursively
    *
    * @param array $datas
    * @return array merge array
    */
    public static function mergeRecursive($datas)
    {
        $merge = array();
        if (!empty($datas)) {
            foreach ($datas as &$data) {
                $merge['id_rule'] = $data['id_rule'];
                $merge['id_lang'] = $data['id_lang'];
                $merge['id_shop'] = $data['id_shop'];
                $merge['active'] = $data['active'];
                $merge['pattern'][] = array($data['field'] => $data['pattern']);
            }
            unset($datas, $data);
        }
        return ($merge);
    }

    /**
    * Push data in SEO Rule
    *
    * @param array $rules
    * @param array $rule
    */
    public static function pushOnSeoArray(array &$rules, array $rule)
    {
        if (!is_array($rules)) {
            $rules = array();
        }

        if (!isset($rules[$rule['id_lang']]) || !is_array($rules[$rule['id_lang']])) {
            $rules[$rule['id_lang']] = array();
        }

        if (!isset($rules[$rule['id_lang']][$rule['id_shop']])
        || !is_array($rules[$rule['id_lang']][$rule['id_shop']])) {
            $rules[$rule['id_lang']][$rule['id_shop']] = array();
        }

        $rules[$rule['id_lang']][$rule['id_shop']][$rule['field']] = $rule['pattern'];
    }

    /**
    * Merge two or more arrays recursively and distinctly
    *
    * @param array $array1
    * @param array $array2
    * @return array merge array
    */
    public static function mergeRecursiveArray(array &$array1, array &$array2)
    {
        $rules = array();
        foreach ($array1 as &$rule) {
            self::pushOnSeoArray($rules, $rule);
        }
        unset($rule, $array1);

        foreach ($array2 as &$rule) {
            self::pushOnSeoArray($rules, $rule);
        }
        unset($rule, $array2);

        return ($rules);
    }

    /**
    * truncating the length of a string
    *
    * @param string $text
    * @param int $length
    * @return string truncate string
    */
    public static function truncateString($text, $length = 120)
    {
        $options = array(
            'ellipsis' => '...', 'exact' => true, 'html' => false
        );
        if (version_compare((float)_PS_VERSION_, '1.5.6.1', '>=')) {
            return (Tools::truncateString($text, $length, $options));
        } else {
            return (self::truncateStrings($text, $length, $options));
        }
    }

    public static function truncateStrings($text, $length = 120, $options = array())
    {
        $html = $ellipsis = $exact = '';

        $default = array(
            'ellipsis' => '...', 'exact' => true, 'html' => true
        );

        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (Tools::strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }

            $total_length = Tools::strlen(strip_tags($ellipsis));
            $open_tags = array();
            $truncate = '';
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);

            foreach ($tags as &$tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($open_tags, $tag[2]);
                    } elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $close_tag)) {
                        $pos = array_search($close_tag[1], $open_tags);
                        if ($pos !== false) {
                            array_splice($open_tags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];
                $reg_pattern = '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i';
                $content_length = Tools::strlen(preg_replace($reg_pattern, ' ', $tag[3]));

                if ($content_length + $total_length > $length) {
                    $left = $length - $total_length;
                    $entities_length = 0;

                    if (preg_match_all($reg_pattern, $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as &$entity) {
                            if ($entity[1] + 1 - $entities_length <= $left) {
                                $left--;
                                $entities_length += Tools::strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= Tools::substr($tag[3], 0, $left + $entities_length);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $total_length += $content_length;
                }

                if ($total_length >= $length) {
                    break;
                }
            }
            unset($tag, $tags);
        } else {
            if (Tools::strlen($text) <= $length) {
                return $text;
            }

            $truncate = Tools::substr($text, 0, $length - Tools::strlen($ellipsis));
        }

        if (!$exact) {
            $spacepos = Tools::strrpos($truncate, ' ');
            if ($html) {
                $truncate_check = Tools::substr($truncate, 0, $spacepos);
                $last_open_tag = Tools::strrpos($truncate_check, '<');
                $last_close_tag = Tools::strrpos($truncate_check, '>');

                if ($last_open_tag > $last_close_tag) {
                    preg_match_all('/<[\w]+[^>]*>/s', $truncate, $last_tag_matches);
                    $last_tag = array_pop($last_tag_matches[0]);
                    $spacepos = Tools::strrpos($truncate, $last_tag) + Tools::strlen($last_tag);
                }

                $bits = Tools::substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $dropped_tags, PREG_SET_ORDER);

                if (!empty($dropped_tags)) {
                    if (!empty($open_tags)) {
                        foreach ($dropped_tags as &$closing_tag) {
                            if (!in_array($closing_tag[1], $open_tags)) {
                                array_unshift($open_tags, $closing_tag[1]);
                            }
                        }
                        unset($dropped_tags, $closing_tag);
                    } else {
                        foreach ($dropped_tags as &$closing_tag) {
                            $open_tags[] = $closing_tag[1];
                        }
                        unset($dropped_tags, $closing_tag);
                    }
                }
            }

            $truncate = Tools::substr($truncate, 0, $spacepos);
        }

        $truncate .= $ellipsis;

        if ($html) {
            foreach ($open_tags as &$tag) {
                $truncate .= '</'.$tag.'>';
            }
        }

        return $truncate;
    }

    public static function strrpos($str, $find, $offset = 0, $encoding = 'utf-8')
    {
        if (function_exists('mb_strrpos')) {
            return mb_strrpos($str, $find, $offset, $encoding);
        }
        return strrpos($str, $find, $offset);
    }

    public static function displayDate($value, $id_lang)
    {
        if (version_compare((float)_PS_VERSION_, (float)'1.5.5.0', '>=')) {
            return (Tools::displayDate($value));
        } else {
            return (Tools::displayDate($value, $id_lang));
        }
    }

    public static function getProducts(
        $id_lang,
        $page = 1,
        $nb_results_page = 1000,
        $order_by = 'id_product',
        $order_way = 'ASC',
        $id_category = 0,
        Context $context = null
    ) {
        if (!$context) {
            $context = Context::getContext();
        }

        if ($order_by == 'id_product') {
            $order_by_prefix = 'p';
        }

        $sql = 'SELECT SQL_BIG_RESULT SQL_CALC_FOUND_ROWS p.id_product
		FROM `'._DB_PREFIX_.'product` p
		'.Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
      p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').'
    )
		WHERE pl.`id_lang` = '.(int)$id_lang.'
		'.(((int)$id_category !== 0) ? 'AND product_shop.id_category_default IN ('.$id_category.')': '').'
		ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').'`'.pSQL($order_by).'` '.pSQL($order_way).'
		LIMIT '.(($page > 1) ? (($page-1)*$nb_results_page).','.(int)$nb_results_page : '0,'.(int)$nb_results_page);

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql));
    }

    public static function getFrontUrl()
    {
        $ps_url = Tools::usingSecureMode() ? Tools::getShopDomainSsl(true) : Tools::getShopDomain(true);
        $ps_url .= __PS_BASE_URI__;
        return $ps_url;
    }

    public static function arrayColumn($input, $column_key, $index_key = null)
    {
        if (! function_exists('array_column')) {
            function array_column(array $input, $column_key, $index_key = null)
            {
                $array = array();
                foreach ($input as $value) {
                    if (!array_key_exists($column_key, $value)) {
                        trigger_error("[1] Key \"$column_key\" does not exist in array");
                        return false;
                    }
                    if (is_null($index_key)) {
                        $array[] = $value[$column_key];
                    } else {
                        if (!array_key_exists($index_key, $value)) {
                            trigger_error("[2] Key \"$index_key\" does not exist in array");
                            return false;
                        }
                        if (! is_scalar($value[$index_key])) {
                            trigger_error("[3] Key \"$index_key\" does not contain scalar value");
                            return false;
                        }
                        $array[$value[$index_key]] = $value[$column_key];
                    }
                }
                return $array;
            }
            return array_column($input, $column_key, $index_key);
        } else {
            return array_column($input, $column_key, $index_key);
        }
    }

    // Use SQL_CALC_FOUND_ROWS in your previous request to use this function
    public static function getMaxPages($nb_results_page)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow(
            'SELECT CEILING(IF(FOUND_ROWS() / '.(int)$nb_results_page.' = 0, 1,
             FOUND_ROWS() / '.(int)$nb_results_page.')) as count,
             FOUND_ROWS() as max_result'
        );
    }
}
