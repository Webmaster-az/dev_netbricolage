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

namespace Motive\Prestashop;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotiveStrTools
{
    /**
     * Strip tags and remove invisible control characters and unused code points
     *
     * @param string $text the input string
     *
     * @return string the cleaned string
     */
    public static function cleanString($text)
    {
        $text = html_entity_decode($text, ENT_HTML5 | ENT_QUOTES | ENT_IGNORE);
        // Add space between tags
        $text = str_replace('><', '> <', $text);
        // New line to space
        $text = preg_replace('/<br(\s*)?\/?>/iu', ' ', $text);
        // Remove HTML tags
        $text = strip_tags($text);
        // Remove invisible control characters and unused code points. https://www.regular-expressions.info/unicode.html
        $text = preg_replace('/[\p{C}]+/u', ' ', $text);
        // Join multiple consecutive spaces into one
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim($text);
    }
}
