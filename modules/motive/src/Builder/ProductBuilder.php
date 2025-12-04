<?php
/**
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Motive\Prestashop\Builder;

use Motive\Prestashop\Config;
use Motive\Prestashop\Model\Code;
use Motive\Prestashop\Model\Product;
use Motive\Prestashop\Model\Variation;
use Motive\Prestashop\MotiveStrTools;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class ProductBuilder
 */
class ProductBuilder
{
    protected $context;
    protected $batchSize;
    protected $plainVariants;
    protected $availabilityBuilder;
    protected $categoryBuilder;
    protected $imageBuilder;
    protected $attributeBuilder;
    protected $variationBuilder;
    protected $supplierReferencesBuilder;
    protected $priceBuilder;
    protected $labelBuilder;
    protected $featureValueBuilder;
    protected $linkBuilder;
    protected $tagsBuilder;
    protected $conditionLabel;

    /**
     * ProductBuilder constructor.
     *
     * @param \Context $context - current context
     */
    public function __construct(\Context $context)
    {
        $this->context = $context;
        $this->batchSize = Config::getProductBatchSize();
        $this->plainVariants = Config::getPlainVariants() === 'ALL';
        $this->availabilityBuilder = new AvailabilityBuilder($context);
        $this->categoryBuilder = new CategoryBuilder($context);
        $this->imageBuilder = new ImageBuilder($context);
        $priceBuilderClass = Config::getPerfPriceBuilder();
        $this->priceBuilder = new $priceBuilderClass($context);
        $this->supplierReferencesBuilder = new SupplierReferencesBuilder($context);
        $this->labelBuilder = new ProductLabelBuilder($context);
        $linkBuilderClass = Config::getPerfLinkBuilder();
        $this->linkBuilder = new $linkBuilderClass($context);
        $this->attributeBuilder = new AttributeValueBuilder($context);
        $this->featureValueBuilder = new FeatureValueBuilder($context);
        $this->tagsBuilder = new TagsBuilder($context);
        $this->variationBuilder = new VariationBuilder(
            $context,
            $this->imageBuilder,
            $this->priceBuilder,
            $this->linkBuilder,
            $this->attributeBuilder,
            $this->supplierReferencesBuilder,
            $this->availabilityBuilder
        );
        $this->conditionLabel = $this->getConditionLabel();
    }

    /**
     * Returns the products for the selected shop & lang
     *
     * @param int $fromIdProduct
     *
     * @return \Traversable<Product>|[] array of products
     *
     * @throws \PrestaShopException
     */
    public function fetchProductsFor($fromIdProduct = 0)
    {
        foreach ($this->queryProducts($fromIdProduct) as $row) {
            try {
                $product = $this->fromRow($row);
            } catch (\Exception $e) {
                continue;
            }

            if ($this->plainVariants && !empty($product->variation)) {
                $variants = $product->variation;
                unset($product->variation);
                foreach ($variants as $variant) {
                    yield $this->plainVariant($product, $variant);
                }
                continue;
            }

            yield $product;
        }
    }

    /**
     * @param Product $product
     * @param Variation $variant
     *
     * @return Product
     */
    protected function plainVariant(Product $product, Variation $variant)
    {
        $productVariant = clone $product;

        // Copy all properties
        foreach ($variant as $prop => $value) {
            $productVariant->$prop = $value;
        }

        // These properties have different value when plain variants
        $productVariant->id = $product->id . '-' . $variant->id;
        $productVariant->images = empty($variant->images) ? $product->images : $variant->images;

        return $productVariant;
    }

    /**
     * @param int $fromIdProduct First ID (not included)
     *
     * @return \Traversable<array> rows
     */
    public function queryProducts($fromIdProduct = 0)
    {
        do {
            $sql = $this->buildQuery($fromIdProduct, $this->batchSize);
            $productRows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            if (empty($productRows)) {
                break;
            }
            $toIdProduct = end($productRows)['p_id'];

            // Prefetch associated entities
            $this->tagsBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->priceBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->categoryBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->imageBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->featureValueBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->supplierReferencesBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->attributeBuilder->prefetch($fromIdProduct, $toIdProduct);
            $this->variationBuilder->prefetch($fromIdProduct, $toIdProduct);

            foreach ($productRows as $row) {
                yield $this->fullFillRow($row);
            }

            $fromIdProduct = $toIdProduct;
        } while (count($productRows) === $this->batchSize);
    }

    /**
     * @param int $fromIdProduct
     * @param int $limit
     *
     * @return string
     */
    public function buildQuery($fromIdProduct, $limit)
    {
        $idShop = (int) $this->context->shop->id;
        $idLang = (int) $this->context->language->id;
        $fromIdProduct = (int) $fromIdProduct;
        $limit = (int) $limit;
        $mpnColumn = version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? 'p.mpn' : '""';
        $isbnColumn = version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 'p.isbn' : '""';
        $isNewProductQuery = "DATEDIFF('" . date('Y-m-d') . " 00:00:00', ps.`date_add`) < " . self::getNbDaysNewProduct();

        return "
            SELECT
                p.id_product                        AS p_id,
                pl.name                             AS p_name,
                pl.description                      AS p_desc,
                pl.description_short                AS p_short_desc,
                pl.link_rewrite                     AS p_link_rewrite,
                pl.meta_keywords                    AS p_meta_keywords,
                pl.meta_title                       AS p_meta_title,
                pl.meta_description                 AS p_meta_description,
                ps.price                            AS p_base_price,
                ps.ecotax                           AS p_ecotax,
                ps.id_tax_rules_group               AS p_id_tax_rules_group,
                ps.on_sale                          AS p_on_sale,
                p.is_virtual                        AS p_is_virtual,
                p.reference                         AS p_ref,
                p.ean13                             AS p_ean13,
                $isbnColumn                         AS p_isbn,
                p.upc                               AS p_upc,
                $mpnColumn                          AS p_mpn,
                ps.condition                        AS p_condition,
                p.cache_is_pack                     AS p_is_pack,
                ps.available_for_order              AS p_available_for_order,
                ps.show_price                       AS p_show_price,
                sa.quantity                         AS p_quantity,
                sa.out_of_stock                     AS p_out_of_stock,
                pl.available_now                    AS p_available_now,
                pl.available_later                  AS p_available_later,
                p.online_only                       AS p_online_only,
                p.cache_default_attribute           AS p_default_attribute,
                COALESCE(brand.name, '')            AS p_brand,
                COALESCE(supplier.name, '')         AS p_supplier,
                $isNewProductQuery                  AS p_is_new,
                p.id_category_default               AS p_id_category_default
            FROM " . _DB_PREFIX_ . 'product AS p
                JOIN ' . _DB_PREFIX_ . "product_shop AS ps
                    ON (ps.id_product = p.id_product AND ps.id_shop = $idShop)
                LEFT JOIN " . _DB_PREFIX_ . "product_lang AS pl
                    ON (pl.id_product = p.id_product AND pl.id_lang = $idLang AND pl.id_shop = $idShop)
                {$this->availabilityBuilder->stockAvailableQuery('p.id_product')}
                LEFT JOIN " . _DB_PREFIX_ . 'manufacturer AS brand
                    ON (brand.id_manufacturer = p.id_manufacturer)
                LEFT JOIN ' . _DB_PREFIX_ . "supplier AS supplier
                    ON (supplier.id_supplier = p.id_supplier)
            WHERE ps.active = 1 AND p.id_product > $fromIdProduct AND ps.visibility IN ('both', 'search')
            ORDER BY p.id_product
            LIMIT $limit
        ";
    }

    /**
     * @return int
     */
    public static function getNbDaysNewProduct()
    {
        $nbDaysNewProduct = \Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        if (!\Validate::isUnsignedInt($nbDaysNewProduct)) {
            return 20;
        }

        return $nbDaysNewProduct;
    }

    /**
     * Add more information required by the product or variant to the query result.
     *
     * @param array $row
     *
     * @return array $row
     *
     * @throws \PrestaShopException
     */
    protected function fullFillRow(array $row)
    {
        $row['p_available_for_order'] = (bool) $row['p_available_for_order'];
        $row['p_out_of_stock'] = (int) $row['p_out_of_stock'];
        $row['p_show_price'] = (bool) $row['p_show_price'] || (bool) $row['p_available_for_order'];
        $row['p_is_new'] = (bool) $row['p_is_new'];
        $row['p_is_virtual'] = (bool) $row['p_is_virtual'];
        $row['p_is_pack'] = $row['p_is_pack'] === null ? \Pack::isPack($row['p_id']) : (bool) $row['p_is_pack'];
        $row['p_category'] = $this->categoryBuilder->get($row['p_id']);
        $row['p_tags'] = $this->tagsBuilder->get($row['p_id']);
        $row['p_supplier_references'] = $this->supplierReferencesBuilder->get($row['p_id']);
        $row['p_price'] = $this->priceBuilder->get($row);
        $row['categoryBuilderInstance'] = $this->categoryBuilder;
        $row['p_url'] = $this->linkBuilder->getProductLink($row);
        $row['p_images'] = $this->imageBuilder->get($row);
        $row['p_features'] = $this->featureValueBuilder->get($row['p_id']);
        $row['p_variants'] = $this->variationBuilder->get($row);

        // Depends on p_variants
        // quantity is not reliable because it may not be the sum of the quantity of the variants (update from the api)
        $row['p_quantity'] = empty($row['p_variants'])
            ? (int) $row['p_quantity']
            : array_sum(array_column($row['p_variants'], 'v_quantity'));

        // Depends on p_quantity
        $row['p_availability'] = $this->availabilityBuilder->buildFrom($row['p_quantity'], $row['p_available_for_order'], $row['p_out_of_stock']);
        $row['p_labels'] = $this->labelBuilder->getForProduct($row);

        return $row;
    }

    /**
     * Creates Product object from array of props.
     *
     * @param array $row
     *
     * @return Product
     *
     * @throws \PrestaShopException
     */
    protected function fromRow($row)
    {
        $product = new Product();

        $product->id = (string) $row['p_id'];
        $product->name = $row['p_name'];
        $product->description = MotiveStrTools::cleanString($row['p_desc']);
        $product->short_description = MotiveStrTools::cleanString($row['p_short_desc']);
        $product->url = $row['p_url'];
        $product->images = $row['p_images'];
        $product->availability = $row['p_availability'];
        $product->price = isset($row['p_price']) ? $row['p_price'] : null;
        $product->category = $row['p_category'];
        $product->code = Code::build($row['p_ref'], $row['p_ean13'], $row['p_isbn'], $row['p_upc'], $row['p_mpn']);

        // Additional indexable fields
        $product->meta_keywords = $row['p_meta_keywords'];
        $product->meta_title = $row['p_meta_title'];
        $product->meta_description = $row['p_meta_description'];
        $product->tags = $row['p_tags'];
        $product->supplier_references = $row['p_supplier_references'];

        // Products features
        foreach ($row['p_features'] as $feature) {
            $product->{$feature->key} = $feature->values;
        }

        // built-in "features"
        $product->condition = empty($this->conditionLabel[$row['p_condition']])
            ? $row['p_condition']
            : $this->conditionLabel[$row['p_condition']];
        $product->is_bundle = $row['p_is_pack'];
        $product->brand = $row['p_brand'];
        $product->supplier = $row['p_supplier'];
        $product->is_new = $row['p_is_new'];
        $product->is_virtual = $row['p_is_virtual'];
        $product->on_sale = $row['p_on_sale'] || !empty($product->price->on_sale);
        $product->labels = $row['p_labels'];
        $product->variation = [];
        foreach ($row['p_variants'] as $variantRow) {
            $product->variation[] = $this->variationBuilder->fromRow($variantRow);
        }

        return $product;
    }

    protected function getConditionLabel()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $m = \Module::getInstanceByName('motive');

            return [
                'new' => $m->l('New', 'productbuilder'),
                'used' => $m->l('Used', 'productbuilder'),
                'refurbished' => $m->l('Refurbished', 'productbuilder'),
            ];
        }

        $t = $this->context->getTranslator();

        return [
            'new' => $t->trans('New', [], 'Shop.Theme.Catalog'),
            'used' => $t->trans('Used', [], 'Shop.Theme.Catalog'),
            'refurbished' => $t->trans('Refurbished', [], 'Shop.Theme.Catalog'),
        ];
    }
}
