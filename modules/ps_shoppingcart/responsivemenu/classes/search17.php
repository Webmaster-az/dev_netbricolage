<?php
/**
 * 2013-2021 MADEF IT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2021 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use PrestaShop\PrestaShop\Adapter\Search\SearchProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;

class RmSearch
{
    protected $total;
    protected $products;
    protected $context;

    public function __construct()
    {
        $query = Tools::getValue('query');
        $orderBy = Tools::getValue('orderby');
        $orderWay = Tools::getValue('orderway');
        $this->context = Context::getContext();

        $productSearchQuery = new ProductSearchQuery();
        $productSearchQuery
            ->setSortOrder(new SortOrder('product', $orderBy, $orderWay))
            ->setSearchString($query)
            ->setResultsPerPage(10)
            ->setPage(1);

        $provider = $this->getProductSearchProviderFromModules($productSearchQuery);

        if (null === $provider) {
            $provider = $this->getDefaultProductSearchProvider();
        }

        $context = $this->getProductSearchContext();

        $result = $provider->runQuery(
            $context,
            $productSearchQuery
        );

        $this->context->smarty->assign(
            'rm_totals',
            $result->getTotalProductsCount()
        );

        $this->products = $result->getProducts();
        $this->total = $result->getTotalProductsCount();

        /*
        foreach ($products as &$product) {
            $idImage = false;
            $productObject = new Product($product['id_product']);
            if ($product['id_product_attribute']) {
                $productObject->id_combination = $product['id_product_attribute'];
                $images = $productObject->getCombinationImages($this->context->language->id);
                if (isset($images[$product['id_product_attribute']])
                    && count($images[$product['id_product_attribute']]) > 0
                ) {
                    $idImage = $images[$product['id_product_attribute']][0]['id_image'];
                }
            }

            if (!$idImage) {
                $cover  = Product::getCover($product['id_product']);
                $idImage = $cover['id_image'];
            }

            $product['cover'] = $idImage;
        }
         */
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getProducts()
    {
        return $this->products;
    }

    /**
     * This method is the heart of the search provider delegation
     * mechanism.
     *
     * It executes the `productSearchProvider` hook (array style),
     * and returns the first one encountered.
     *
     * This provides a well specified way for modules to execute
     * the search query instead of the core.
     *
     * The hook is called with the $query argument, which allows
     * modules to decide if they can manage the query.
     *
     * For instance, if two search modules are installed and
     * one module knows how to search by category but not by manufacturer,
     * then "ManufacturerController" will use one module to do the query while
     * "CategoryController" will use another module to do the query.
     *
     * If no module can perform the query then null is returned.
     *
     * @param ProductSearchQuery $query
     *
     * @return ProductSearchProviderInterface or null
     */
    private function getProductSearchProviderFromModules($query)
    {
        $providers = Hook::exec(
            'productSearchProvider',
            array('query' => $query),
            null,
            true
        );

        if (!is_array($providers)) {
            $providers = array();
        }

        foreach ($providers as $provider) {
            if ($provider instanceof ProductSearchProviderInterface) {
                return $provider;
            }
        }

        return;
    }

    protected function getDefaultProductSearchProvider()
    {
        return new SearchProductSearchProvider(
            Context::getContext()->getTranslator()
        );
    }

    /**
     * The ProductSearchContext is passed to search providers
     * so that they can avoid using the global id_lang and such
     * variables. This method acts as a factory for the ProductSearchContext.
     *
     * @return ProductSearchContext a search context for the queries made by this controller
     */
    protected function getProductSearchContext()
    {
        return (new ProductSearchContext())
            ->setIdShop($this->context->shop->id)
            ->setIdLang($this->context->language->id)
            ->setIdCurrency($this->context->currency->id)
            ->setIdCustomer(
                $this->context->customer ?
                    $this->context->customer->id :
                    null
            )
        ;
    }
}
