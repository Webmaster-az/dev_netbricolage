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

use Motive\Prestashop\Model\Metadata;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MetadataBuilder
{
    /**
     * Returns a MotiveMetadata object based on the selected shop & lang
     *
     * @param \Context $context
     *
     * @return Metadata
     */
    public static function build(\Context $context)
    {
        $metadata = new Metadata();
        $metadata->lang = $context->language->iso_code;
        $metadata->shop = $context->shop->getBaseURL(true);
        $metadata->currency = $context->currency->iso_code;
        $metadata->created_at = (new \DateTime())->format(\DateTime::ATOM);
        $metadata->source = static::getSource();

        return $metadata;
    }

    /**
     * Returns a user-agent-like string that identifies this plugin.
     *
     * @param \Module $module
     *
     * @return string
     */
    public static function getSource($module = null)
    {
        if (!$module) {
            $module = \Module::getInstanceByName('motive');
        }

        return 'Motive/' . $module->version . '; Prestashop/' . _PS_VERSION_ . '; PHP/' . PHP_VERSION;
    }
}
