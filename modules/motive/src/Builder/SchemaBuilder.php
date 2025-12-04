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
use Motive\Prestashop\Model\Field;
use Motive\Prestashop\Model\FieldType;
use Motive\Prestashop\Model\Schema;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class SchemaBuilder
 */
class SchemaBuilder
{
    /** @var \Context */
    protected $context;

    /**
     * SchemaBuilder constructor.
     *
     * @param \Context $context
     */
    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    /**
     * Build a feed schema for the current shop, lang & currency
     *
     * @return Schema
     */
    public function build()
    {
        $schemaProductLabelBuilder = new SchemaProductLabelBuilder($this->context);

        return Schema::build(
            MetadataBuilder::build($this->context),
            $this->buildFields(),
            $schemaProductLabelBuilder->getAvailableLabels()
        );
    }

    /**
     * Build a feed schema fields array
     *
     * @return Field[]
     */
    public function buildFields()
    {
        $m = \Module::getInstanceByName('motive');

        $fields = $this->buildSharedFields();
        $fields[] = Field::build('name', FieldType::NAME)
            ->setLabel($m->l('Name', 'schemabuilder'))
            ->setSearchable();
        $fields[] = Field::build('description', FieldType::DESCRIPTION)
            ->setLabel($m->l('Description', 'schemabuilder'))
            ->setSearchable();
        $fields[] = Field::build('short_description', FieldType::TEXT)
            ->setLabel($m->l('Short description', 'schemabuilder'))
            ->setSearchable();
        $fields[] = Field::build('category', FieldType::CATEGORY)
            ->setLabel($m->l('Category', 'schemabuilder'))
            ->setSearchable()
            ->setFacetable();

        // Additional searchable fields
        $fields[] = Field::build('tags', FieldType::TEXT)
            ->setLabel($m->l('Tags', 'schemabuilder'))
            ->setSearchable()
            ->setFacetable();
        $fields[] = Field::build('meta_keywords', FieldType::TEXT)
            ->setLabel($m->l('Meta keywords', 'schemabuilder'))
            ->setSearchable();
        $fields[] = Field::build('meta_title', FieldType::TEXT)
            ->setLabel($m->l('Meta title', 'schemabuilder'))
            ->setSearchable();
        $fields[] = Field::build('meta_description', FieldType::TEXT)
            ->setLabel($m->l('Meta description', 'schemabuilder'))
            ->setSearchable();

        // Products features
        if (\Feature::isFeatureActive()) {
            $featureBuilder = new FeatureBuilder($this->context);
            $features = $featureBuilder->fetch();
            foreach ($features as $feature) {
                $fields[] = Field::build($feature->id, FieldType::ATTRIBUTE)
                    ->setLabel($feature->name)
                    ->setSearchable()
                    ->setFacetable();
            }
        }

        // Built in "features"
        $fields[] = Field::build('condition', FieldType::TEXT)
            ->setLabel($m->l('Condition', 'schemabuilder'))
            ->setFacetable();

        $fields[] = Field::build('is_bundle', FieldType::BOOLEAN)
            ->setLabel($m->l('Is Bundle', 'schemabuilder'))
            ->setFacetable();

        $fields[] = Field::build('is_virtual', FieldType::BOOLEAN)
            ->setLabel($m->l('Is Virtual', 'schemabuilder'))
            ->setFacetable();

        $fields[] = Field::build('is_new', FieldType::BOOLEAN)
            ->setLabel($m->l('Is New', 'schemabuilder'))
            ->setFacetable();

        $fields[] = Field::build('on_sale', FieldType::BOOLEAN)
            ->setLabel($m->l('On Sale', 'schemabuilder'))
            ->setFacetable();

        $fields[] = Field::build('brand', FieldType::BRAND)
            ->setLabel($m->l('Brand', 'schemabuilder'))
            ->setSearchable()
            ->setFacetable();

        $fields[] = Field::build('supplier', FieldType::TEXT)
            ->setLabel($m->l('Supplier', 'schemabuilder'))
            ->setSearchable()
            ->setFacetable();

        // Products variations/attributes
        if (\Combination::isFeatureActive()) {
            $varFields = [];
            $attributes = AttributeBuilder::fetchForShop($this->context->shop->id, $this->context->language->id);
            foreach ($attributes as $attribute) {
                $type = $attribute->isColor ? FieldType::COLOR : FieldType::ATTRIBUTE;
                $varFields[] = Field::build($attribute->id, $type)
                    ->setLabel($attribute->name)
                    ->setSearchable()
                    ->setFacetable();
            }
            $varFields[] = Field::build('is_default', FieldType::VARIATION_DEFAULT);

            if (\Configuration::get(Config::PLAIN_VARIANTS) === 'ALL') {
                $fields = array_merge($fields, $varFields);
            } else {
                $varFields = array_merge($this->buildSharedFields('v_'), $varFields);
                $fields[] = Field::build('variation', FieldType::VARIATION)->setFields($varFields);
            }
        }

        return $fields;
    }

    /**
     * Build fields array for product and variation
     *
     * @return Field[]
     */
    protected function buildSharedFields($idPrefix = '')
    {
        $m = \Module::getInstanceByName('motive');

        return [
            Field::build($idPrefix . 'id', FieldType::ID, 'id'),
            Field::build($idPrefix . 'url', FieldType::LINK, 'url'),
            Field::build($idPrefix . 'images', FieldType::IMAGE, 'images'),
            Field::build($idPrefix . 'availability', FieldType::AVAILABILITY, 'availability')
                ->setLabel($m->l('Availability', 'schemabuilder'))
                ->setFacetable(),
            Field::build($idPrefix . 'price', FieldType::PRICE, 'price')
                ->setLabel($m->l('Price', 'schemabuilder'))
                ->setFacetable()
                ->setSortable(),
            Field::build($idPrefix . 'code', FieldType::CODE, 'code')
                ->setLabel($m->l('Code', 'schemabuilder'))
                ->setSearchable(),
            Field::build($idPrefix . 'labels', FieldType::PRODUCT_LABEL, 'labels')
                ->setLabel($m->l('Product labels', 'schemabuilder'))
                ->setRetrievable(),
            Field::build($idPrefix . 'supplier_references', FieldType::TEXT, 'supplier_references')
                ->setLabel($m->l('Supplier references', 'schemabuilder'))
                ->setSearchable(),
        ];
    }
}
