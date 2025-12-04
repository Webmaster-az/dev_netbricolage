<?php
/**
 * HsMaLink for Multi Accessories
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class HsMaLink extends Link
{
    /**
     * @param string $link_rewrite
     * @param int $id_image
     * @param string $default_image_type
     * @return string
     */
    public static function getProductImageLink($link_rewrite, $id_image, $default_image_type)
    {
        $small_image_type = HsMaImageType::getFormatedNameByPsVersion('small');
        $hsma_image_type = ($default_image_type == $small_image_type) ? Configuration::get('HSMA_IMAGE_TYPE') : Configuration::get('HSMA_IMAGE_SIZE_IN_FANCYBOX');
        $context = Context::getcontext();
        return $id_image ? $context->link->getImageLink($link_rewrite, $id_image, $hsma_image_type) : _THEME_PROD_DIR_.$context->language->iso_code.'-default-'.$default_image_type.'.jpg';
    }
}
