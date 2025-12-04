<?php

class Link extends LinkCore
{
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    protected $webpSupported = false;
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function __construct($protocolLink = null, $protocolContent = null)
    {
        parent::__construct($protocolLink, $protocolContent);
        if( 
            Module::isEnabled('ultimateimagetool') &&  
            (int)Configuration::get('uit_use_webp') >= 1 &&  
            (int)Configuration::get('uit_use_picture_webp') == 0 && 
            (int)Configuration::get('uit_use_webp_termination') >= 1 
            && (isset($_SERVER['HTTP_ACCEPT']) === true) &&  (false !== strpos($_SERVER['HTTP_ACCEPT'], 'image/webp'))   )
        {
            $this->webpSupported = true;
        }
    }
   /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function getImageLink($name, $ids, $type = null)
    {
        $parent = parent::getImageLink($name, $ids, $type);
    
        if ($this->webpSupported) 
        {
            $split_ids = explode('-', $ids);
            $id_image = (isset($split_ids[1]) ? $split_ids[1] : $split_ids[0]);
            $uri_path = _PS_ROOT_DIR_._THEME_PROD_DIR_.Image::getImgFolderStatic( $id_image ). $id_image .($type ? '-'.$type : '').'.webp';
             if(file_exists(  $uri_path ) && strpos($uri_path , '/p/default-') === false)
                return str_replace('.jpg', '.webp', $parent);
         
             return $parent;
        }
        
        return $parent;
    }
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function getCatImageLink($name, $idCategory, $type = null)
    {
        $parent = parent::getCatImageLink($name, $idCategory, $type);
        if ($this->webpSupported) 
        {
            $uri_path = _PS_ROOT_DIR_._THEME_CAT_DIR_.Image::getImgFolderStatic($idCategory).$idCategory.($type ? '-'.$type : '').'.webp';
            if(file_exists(  $uri_path )&& strpos($uri_path , '/c/default-') === false)
                 return str_replace('.jpg', '.webp', $parent);
         
            return $parent;
        }
        return $parent;
    }
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function getSupplierImageLink($idSupplier, $type = null)
    {
        $parent = parent::getSupplierImageLink($idSupplier, $type);
        if ($this->webpSupported) 
        {           
            $uri_path = _PS_ROOT_DIR_._PS_SUPP_IMG_DIR_.Image::getImgFolderStatic($idSupplier).$idSupplier.($type ? '-'.$type : '').'.webp';
            if(file_exists(  $uri_path )&& strpos($uri_path , '/s/default-') === false)
                return str_replace('.jpg', '.webp', $parent);
        }
        return $parent;
    }
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function getStoreImageLink($name, $idStore, $type = null)
    {
        $parent = parent::getStoreImageLink($name, $idStore, $type);
        if ($this->webpSupported) 
        {           
            $uri_path = _PS_ROOT_DIR_._PS_STORE_IMG_DIR_.Image::getImgFolderStatic($idStore).$idStore.($type ? '-'.$type : '').'.webp';
            if(file_exists(  $uri_path )&& strpos($uri_path , '/st/default-') === false)
                return str_replace('.jpg', '.webp', $parent);
        }
        return $parent;
    }
    /*
    * module: ultimateimagetool
    * date: 2021-06-18 11:27:58
    * version: 1.5.71
    */
    public function getManufacturerImageLink($idManufacturer, $type = null)
    {
        $parent = parent::getManufacturerImageLink($idManufacturer, $type);
        if ($this->webpSupported) 
        {           
            $uri_path = _PS_ROOT_DIR_._PS_MANU_IMG_DIR_.Image::getImgFolderStatic($idManufacturer).$idManufacturer.($type ? '-'.$type : '').'.webp';
            if(file_exists(  $uri_path )&& strpos($uri_path , '/m/default-') === false)
                return str_replace('.jpg', '.webp', $parent);
            
        }
        return $parent;
    }
    
    /*
    * module: faktiva_cleanurls
    * date: 2021-07-15 10:50:41
    * version: 1.2.3
    */
    public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false)
    {
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        $url = $this->getBaseLink($id_shop, null, $relative_protocol).$this->getLangLink($id_lang, null, $id_shop);
        if (!is_object($category)) {
            $category = new Category($category, $id_lang);
        }
        $params = array();
        $params['id'] = $category->id;
        $params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
        $params['meta_keywords'] = Tools::str2url($category->getFieldByLang('meta_keywords'));
        $params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
        $selected_filters = is_null($selected_filters) ? '' : $selected_filters;
        if (empty($selected_filters)) {
            $rule = 'category_rule';
        } else {
            $rule = 'layered_rule';
            $params['selected_filters'] = $selected_filters;
        }
        $dispatcher = Dispatcher::getInstance();
        if ($dispatcher->hasKeyword('category_rule', $id_lang, 'categories')) {
            $p_cats = array();
            foreach ($category->getParentsCategories($id_lang) as $p_cat) {
                if (!in_array($p_cat['id_category'], array_merge(self::$category_disable_rewrite, array($category->id)))) {
                    $p_cats[] = $p_cat['link_rewrite'];
                }
            }
            $params['categories'] = implode('/', array_reverse($p_cats));
        }
        return $url.$dispatcher->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
    }
    
    /*
    * module: faktiva_cleanurls
    * date: 2021-07-15 10:50:41
    * version: 1.2.3
    */
    public function getPaginationLink($type, $id_object, $nb = false, $sort = false, $pagination = false, $array = false)
    {
        if (!$type && !$id_object) {
            $method_name = 'get'.Dispatcher::getInstance()->getController().'Link';
            if (method_exists($this, $method_name) && isset($_GET['id_'.Dispatcher::getInstance()->getController()])) {
                $type = Dispatcher::getInstance()->getController();
                $id_object = $_GET['id_'.$type];
            }
        }
        if ($type && $id_object) {
            $url = $this->{'get'.$type.'Link'}($id_object, null);
        } else {
            if (isset(Context::getContext()->controller->php_self)) {
                $name = Context::getContext()->controller->php_self;
            } else {
                $name = Dispatcher::getInstance()->getController();
            }
            $url = $this->getPageLink($name);
        }
        $vars = array();
        $vars_nb = array('n', 'search_query');
        $vars_sort = array('orderby', 'orderway');
        $vars_pagination = array('p');
        foreach ($_GET as $k => $value) {
            if ($k != 'id_'.$type && $k != 'controller' && $k != $type.'_rewrite' ) {
                if (Configuration::get('PS_REWRITING_SETTINGS') && ($k == 'isolang' || $k == 'id_lang')) {
                    continue;
                }
                $if_nb = (!$nb || ($nb && !in_array($k, $vars_nb)));
                $if_sort = (!$sort || ($sort && !in_array($k, $vars_sort)));
                $if_pagination = (!$pagination || ($pagination && !in_array($k, $vars_pagination)));
                if ($if_nb && $if_sort && $if_pagination) {
                    if (!is_array($value)) {
                        $vars[urlencode($k)] = $value;
                    } else {
                        foreach (explode('&', http_build_query(array($k => $value), '', '&')) as $key => $val) {
                            $data = explode('=', $val);
                            $vars[urldecode($data[0])] = $data[1];
                        }
                    }
                }
            }
        }
        if (!$array) {
            if (count($vars)) {
                return $url.(!strstr($url, '?') && ($this->allow == 1 || $url == $this->url) ? '?' : '&').http_build_query($vars, '', '&');
            } else {
                return $url;
            }
        }
        $vars['requestUrl'] = $url;
        if ($type && $id_object) {
            $vars['id_'.$type] = (is_object($id_object) ? (int) $id_object->id : (int) $id_object);
        }
        if (!$this->allow == 1) {
            $vars['controller'] = Dispatcher::getInstance()->getController();
        }
        return $vars;
    }

    public function getProductLink(
        $product,
        $alias = null,
        $category = null,
        $ean13 = null,
        $idLang = null,
        $idShop = null,
        $ipa = null,
        $force_routes = false,
        $relativeProtocol = false,
        $addAnchor = false,
        $extraParams = []
    ) {
        $dispatcher = Dispatcher::getInstance();

        if (!$idLang) {
            $idLang = Context::getContext()->language->id;
        }

        $url = $this->getBaseLink($idShop, null, $relativeProtocol) . $this->getLangLink($idLang, null, $idShop);

        // Set available keywords
        $params = [];

        if (!is_object($product)) {
            if (is_array($product) && isset($product['id_product'])) {
                $params['id'] = $product['id_product'];
            } elseif ((int) $product) {
                $params['id'] = $product;
            } else {
                throw new PrestaShopException('Invalid product vars');
            }
        } else {
            $params['id'] = $product->id;
        }

        //Attribute equal to 0 or empty is useless, so we force it to null so that it won't be inserted in query parameters
        if (empty($ipa)) {
            $ipa = null;
        }
        /* $params['id_product_attribute'] = $ipa; */
        if (!$alias) {
            $product = $this->getProductObject($product, $idLang, $idShop);
        }
        $params['rewrite'] = (!$alias) ? $product->getFieldByLang('link_rewrite') : $alias;
        if (!$ean13) {
            $product = $this->getProductObject($product, $idLang, $idShop);
        }
        $params['ean13'] = (!$ean13) ? $product->ean13 : $ean13;
        if ($dispatcher->hasKeyword('product_rule', $idLang, 'meta_keywords', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['meta_keywords'] = Tools::str2url($product->getFieldByLang('meta_keywords'));
        }
        if ($dispatcher->hasKeyword('product_rule', $idLang, 'meta_title', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['meta_title'] = Tools::str2url($product->getFieldByLang('meta_title'));
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'manufacturer', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['manufacturer'] = Tools::str2url($product->isFullyLoaded ? $product->manufacturer_name : Manufacturer::getNameById($product->id_manufacturer));
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'supplier', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['supplier'] = Tools::str2url($product->isFullyLoaded ? $product->supplier_name : Supplier::getNameById($product->id_supplier));
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'price', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['price'] = $product->isFullyLoaded ? $product->price : Product::getPriceStatic($product->id, false, null, 6, null, false, true, 1, false, null, null, null, $product->specificPrice);
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'tags', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['tags'] = Tools::str2url($product->getTags($idLang));
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'category', $idShop)) {
            if (!$category) {
                $product = $this->getProductObject($product, $idLang, $idShop);
            }
            $params['category'] = (!$category) ? $product->category : $category;
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'reference', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['reference'] = Tools::str2url($product->reference);
        }

        if ($dispatcher->hasKeyword('product_rule', $idLang, 'categories', $idShop)) {
            $product = $this->getProductObject($product, $idLang, $idShop);
            $params['category'] = (!$category) ? $product->category : $category;
            $cats = [];
            foreach ($product->getParentCategories($idLang) as $cat) {
                if (!in_array($cat['id_category'], Link::$category_disable_rewrite)) {
                    //remove root and home category from the URL
                    $cats[] = $cat['link_rewrite'];
                }
            }
            $params['categories'] = implode('/', $cats);
        }
        if ($ipa) {
            $product = $this->getProductObject($product, $idLang, $idShop);
        }
        $anchor = $ipa ? $product->getAnchor((int) $ipa, (bool) $addAnchor) : '';

        return $url . $dispatcher->createUrl('product_rule', $idLang, array_merge($params, $extraParams), $force_routes, $anchor, $idShop);
    }
}