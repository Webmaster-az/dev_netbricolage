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
 * @author Motive (www.motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Motive\Prestashop\Builder;

use Motive\Prestashop\AdditionalProductData;
use Motive\Prestashop\Builder\Link\LinkBuilderInterface;
use Motive\Prestashop\Builder\Price\PriceBuilderInterface;
use Motive\Prestashop\Model\Code;
use Motive\Prestashop\Model\Variation;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VariationBuilder extends AdditionalProductData
{
    protected $imageBuilder;
    protected $priceBuilder;
    protected $attributeBuilder;
    protected $linkBuilder;
    protected $supplierReferencesBuilder;
    protected $availabilityBuilder;

    public function __construct(
        \Context $context,
        ImageBuilder $imageBuilder,
        PriceBuilderInterface $priceBuilder,
        LinkBuilderInterface $linkBuilder,
        AttributeValueBuilder $attributeBuilder,
        SupplierReferencesBuilder $supplierReferencesBuilder,
        AvailabilityBuilder $availabilityBuilder
    ) {
        parent::__construct($context);
        $this->imageBuilder = $imageBuilder;
        $this->priceBuilder = $priceBuilder;
        $this->linkBuilder = $linkBuilder;
        $this->attributeBuilder = $attributeBuilder;
        $this->supplierReferencesBuilder = $supplierReferencesBuilder;
        $this->availabilityBuilder = $availabilityBuilder;
    }

    public function prefetch($fromIdProduct, $toIdProduct)
    {
        if (\Combination::isFeatureActive()) {
            parent::prefetch($fromIdProduct, $toIdProduct);
        }
    }

    protected function buildQuery($fromIdProduct, $toIdProduct)
    {
        $ps_ = _DB_PREFIX_;
        $mpnColumn = version_compare(_PS_VERSION_, '1.7.7.0', '>=') ? 'pa.mpn' : '""';
        $isbnColumn = version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 'pa.isbn' : '""';

        return "SELECT
                ps.id_product           AS id,
                pa.id_product_attribute AS v_id,
                pl.link_rewrite         AS p_link_rewrite,
                pa.reference            AS v_ref,
                pa.ean13                AS v_ean13,
                $isbnColumn             AS v_isbn,
                pa.upc                  AS v_upc,
                $mpnColumn              AS v_mpn,
                sa.quantity             AS v_quantity,
                IFNULL(pas.default_on, 0) AS v_is_default
            FROM {$ps_}product_attribute AS pa
                JOIN {$ps_}product_attribute_shop AS pas
                    ON pas.id_product_attribute = pa.id_product_attribute
                    AND pas.id_shop = {$this->context->shop->id}
                JOIN {$ps_}product_shop AS ps
                  ON pa.id_product = ps.id_product
                  AND ps.id_shop = {$this->context->shop->id}
                  AND ps.active = 1
                  AND ps.visibility IN ('both', 'search')
                  AND ps.id_product BETWEEN $fromIdProduct AND $toIdProduct
                {$this->availabilityBuilder->stockAvailableQuery('pa.id_product', 'pa.id_product_attribute')}
                LEFT JOIN {$ps_}product_lang AS pl
                  ON pl.id_product = pa.id_product
                  AND pl.id_lang = {$this->context->language->id}
                  AND pl.id_shop = {$this->context->shop->id}
        ";
    }

    /**
     * Converts the row data to the expected output type.
     *
     * @param array $productRow
     *
     * @return mixed
     */
    public function get($productRow)
    {
        $idProduct = (int) $productRow['p_id'];
        if (empty($this->data[$idProduct])) {
            return [];
        }

        $variations = [];
        foreach ($this->data[$idProduct] as $row) {
            $row['v_url'] = $this->linkBuilder->getProductLink($productRow, $row['v_id']);
            $row['v_images'] = $this->imageBuilder->get($productRow, $row['v_id']);
            if ($productRow['p_price'] !== null) {
                $row['v_price'] = $this->priceBuilder->get($productRow, $row['v_id']);
            }
            $row['v_availability'] = $this->availabilityBuilder->buildFrom((int) $row['v_quantity'], $productRow['p_available_for_order'], $productRow['p_out_of_stock']);
            $row['v_supplier_references'] = $this->supplierReferencesBuilder->get($productRow['p_id'], $row['v_id']);
            $row['v_attributes'] = $this->attributeBuilder->get($row['v_id']);
            $variations[] = $row;
        }

        return $variations;
    }

    /**
     * Creates Variation object from array of props.
     *
     * @param array $row
     *
     * @return Variation
     */
    public function fromRow(array $row)
    {
        $variation = new Variation();

        $variation->id = $row['v_id'];
        $variation->url = $row['v_url'];
        $variation->images = $row['v_images'];
        $variation->availability = $row['v_availability'];
        $variation->price = isset($row['v_price']) ? $row['v_price'] : null;
        $variation->code = Code::build($row['v_ref'], $row['v_ean13'], $row['v_isbn'], $row['v_upc'], $row['v_mpn']);
        $variation->is_default = (bool) $row['v_is_default'];
        $variation->supplier_references = $row['v_supplier_references'];

        // Attributes
        foreach ($row['v_attributes'] as $attribute) {
            $variation->{$attribute->key} = $attribute;
        }

        return $variation;
    }
}
