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

use Language as PsLanguage;
use Motive\Prestashop\Model\Language;

if (!defined('_PS_VERSION_')) {
    exit;
}

class LanguageBuilder
{
    /**
     * Language builder from Prestashop Language.
     *
     * @param PsLanguage $languageObj
     *
     * @return Language
     */
    public static function fromObject(PsLanguage $languageObj)
    {
        $language = new Language();
        $language->id = $languageObj->id;
        $language->name = $languageObj->name;
        $language->iso_code = $languageObj->iso_code;
        $language->locale = empty($languageObj->locale) ? $languageObj->language_code : $languageObj->locale;

        return $language;
    }

    /**
     * Language builder from array.
     *
     * @param array $languageArr
     *
     * @return Language
     */
    public static function fromArray(array $languageArr)
    {
        $language = new Language();
        $language->id = isset($languageArr['id_lang']) ? $languageArr['id_lang'] : $languageArr['id'];
        $language->name = $languageArr['name'];
        $language->iso_code = $languageArr['iso_code'];
        $language->locale = empty($languageArr['locale']) ? $languageArr['language_code'] : $languageArr['locale'];

        return $language;
    }

    /**
     * Language builder from Prestashop Language Id.
     *
     * @param int $idLanguage
     *
     * @return Language|null
     */
    public static function fromId($idLanguage)
    {
        try {
            $languageObj = new PsLanguage($idLanguage);
            if (!\Validate::isLoadedObject($languageObj)) {
                return null;
            }

            return static::fromObject($languageObj);
        } catch (\Exception $e) {
            return null;
        }
    }
}
