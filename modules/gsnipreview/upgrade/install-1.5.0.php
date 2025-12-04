<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

function upgrade_module_1_5_0($module)
{
	$name_module = 'gsnipreview';

    Configuration::updateValue($name_module.'mt_left', 1);
    Configuration::updateValue($name_module.'mt_right', 1);
    Configuration::updateValue($name_module.'mt_footer', 1);
    Configuration::updateValue($name_module.'mt_home', 1);
    Configuration::updateValue($name_module.'mt_leftside', 1);
    Configuration::updateValue($name_module.'mt_rightside', 1);

    Configuration::updateValue($name_module.'st_left', 1);
    Configuration::updateValue($name_module.'st_right', 1);
    Configuration::updateValue($name_module.'st_footer', 1);
    Configuration::updateValue($name_module.'st_home', 1);
    Configuration::updateValue($name_module.'st_leftside', 1);
    Configuration::updateValue($name_module.'st_rightside', 1);


    return true;
}
?>