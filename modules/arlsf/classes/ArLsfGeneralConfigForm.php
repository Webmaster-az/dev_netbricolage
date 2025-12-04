<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__).'/ArLsfModel.php';

class ArLsfGeneralConfigForm extends ArLsfModel
{
    public $sandbox;
    public $allowed_ips;
    
    public $time_offset;
    public $pages;
    public $url_params;
    
    // Display options
    public $d_section;
    public $product_thumb;
    public $second_image;
    public $stars;
    public $delay_first;
    public $delay;
    public $delay_between;
    public $mobile;
    public $sound;
    public $sound_times;
    public $display_times;
    public $close_btn;
    public $close_lifetime;
    
    
    // Appearance
    public $a_section;
    public $font_size;
    public $line_space;
    public $background_color;
    public $border_color;
    public $border_radius;
    public $shadow_color;
    public $shadow_size;
    public $text_color;
    public $link_color;
    public $close_btn_color;
    public $close_btn_bg;
    public $close_btn_style;
    public $close_btn_position;
    public $opacity;
    public $hover_opacity;
    public $animation;
    public $out_animation;
    
    public $whole_link;
    public $link_on_image;
    public $link_on_name;
    public $extra_css;
    
    // Position and size
    public $p_section;
    public $position;
    
    public $top;
    public $bottom;
    public $left;
    public $right;
    public $d_width;
    public $d_height;
    public $m_position;
    public $m_top;
    public $m_bottom;
    public $m_left;
    public $m_right;
    public $m_width;
    public $m_height;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'sandbox',
                    'allowed_ips',
                    'url_params',
                    'time_offset',
                    'pages',
                    'font_size',
                    'd_section',
                    'a_section',
                    'p_section',
                    'product_thumb',
                    'second_image',
                    'stars',
                    'delay_first',
                    'delay',
                    'delay_between',
                    'mobile',
                    'sound',
                    'display_times',
                    'background_color',
                    'border_color',
                    'border_radius',
                    'shadow_color',
                    'shadow_size',
                    'text_color',
                    'link_color',
                    'close_btn_color',
                    'close_btn_bg',
                    'close_btn_style',
                    'close_btn_position',
                    'opacity',
                    'hover_opacity',
                    'animation',
                    'out_animation',
                    'd_width',
                    'd_height',
                    'm_width',
                    'm_height',
                    'whole_link',
                    'link_on_image',
                    'link_on_name',
                    'extra_css',
                    'position',
                    'm_position',
                    'top',
                    'bottom',
                    'left',
                    'right',
                    'm_top',
                    'm_bottom',
                    'm_left',
                    'm_right',
                    'close_btn'
                ), 'safe'
            ),
            array(
                array(
                    'd_width',
                    'm_width',
                ), 'intOrPercentOrAuto'
            ),
            array(
                array(
                    'background_color',
                    'border_color',
                    'shadow_color',
                    'text_color',
                    'link_color',
                    'close_btn_color',
                    'close_btn_bg',
                ),
                'isColor'
            ),
            array(
                array(
                    'delay_first',
                    'delay_between'
                ),
                'interval'
            ),
            array(
                array(
                    'display_times',
                    'close_lifetime',
                    'sound_times'
                ), 'integer', 'params' => array(
                    'min' => 0,
                )
            ),
            array(
                array(
                    'opacity',
                    'hover_opacity'
                ), 'integer', 'params' => array(
                    'min' => 0,
                    'max' => 100
                )
            ),
            array(
                array(
                    'border_radius',
                    'shadow_size',
                    'd_height',
                    'm_height',
                    'line_space'
                ), 'isInt'
            )
        );
    }
    
    public function getCurrentIP()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    public function fieldSuffix()
    {
        return array(
            'sandbox' => 0,
            'allowed_ips' => $this->getCurrentIP(),
            'delay_first' => $this->l('ms', 'ArLsfGeneralConfigForm'),
            'delay' => $this->l('ms', 'ArLsfGeneralConfigForm'),
            'delay_between' => $this->l('ms', 'ArLsfGeneralConfigForm'),
            'close_lifetime' => $this->l('days', 'ArLsfGeneralConfigForm'),
            'line_space' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'border_radius' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'shadow_size' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'opacity' => '%',
            'hover_opacity' => '%',
            'bottom' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'right' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'left' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'top' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'd_height' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'm_bottom' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'm_right' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'm_left' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'm_top' => $this->l('px', 'ArLsfGeneralConfigForm'),
            'm_height' => $this->l('px', 'ArLsfGeneralConfigForm'),
        );
    }
    
    public function intOrPercentOrAuto($value, $params)
    {
        return preg_match('{^(?:\d+(?:px|%)|auto)$}is', $value);
    }
    
    public function attributeTypes()
    {
        return array(
            'sandbox' => 'switch',
            'allowed_ips' => 'textarea',
            'd_section' => 'html',
            'a_section' => 'html',
            'p_section' => 'html',
            'second_image' => 'switch',
            'stars' => 'switch',
            'mobile' => 'switch',
            'time_offset' => 'select',
            'thumb_size' => 'select',
            'pages' => 'select',
            'font_size' => 'select',
            'product_thumb' => 'select',
            'sound' => 'select',
            'animation' => 'select',
            'out_animation' => 'select',
            'position' => 'select',
            'm_position' => 'select',
            'background_color' => 'color',
            'border_color' => 'color',
            'shadow_color' => 'color',
            'text_color' => 'color',
            'link_color' => 'color',
            'close_btn_color' => 'color',
            'close_btn_bg' => 'color',
            'close_btn_style' => 'select',
            'close_btn_position' => 'select',
            'whole_link' => 'switch',
            'link_on_image' => 'switch',
            'link_on_name' => 'switch',
            'extra_css' => 'textarea',
            'close_btn' => 'switch',
        );
    }
    
    public function htmlFields()
    {
        return array(
            'd_section' => '<hr/><h4>' . $this->l('Display options', 'ArLsfGeneralConfigForm') . '</h4>',
            'a_section' => '<hr/><h4>' . $this->l('Appearance options', 'ArLsfGeneralConfigForm') . '</h4>',
            'p_section' => '<hr/><h4>' . $this->l('Position and size', 'ArLsfGeneralConfigForm') . '</h4>',
        );
    }
    
    public function multipleSelects()
    {
        return array(
            'pages' => true
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'time_offset' => '0',
            'url_params' => 'utm_source=lsf&utm_medium=notification&utm_campaign=lsf',
            'pages' => array('index','category','product','cms','prices-drop','new-products','best-sales','stores','sitemap'),
            'font_size' => 13,
            'line_space' => 0,
            'sound_times' => 0,
            'product_thumb' => $this->getDefaultThumbSize(),
            'second_image' => 1,
            'stars' => 1,
            'delay_first' => '1000-2000',
            'delay' => '5000',
            'delay_between' => '5000-10000',
            'mobile' => 1,
            'sound' => 'notification-01.mp3',
            'display_times' => '0',
            'background_color' => '#3b3b3b',
            'border_color' => '',
            'border_radius' => '0',
            'shadow_color' => '#3b3b3b',
            'shadow_size' => 3,
            'text_color' => '#ffffff',
            'link_color' => '#ff0000',
            'close_btn_color' => '#ffff00',
            'close_btn_bg' => '#000000',
            'close_btn_style' => 'round',
            'close_btn_position' => 'inside',
            'opacity' => 80,
            'hover_opacity' => 100,
            'animation' => 'fadeInRight',
            'out_animation' => 'fadeOutDown',
            'd_width' => '340px',
            'd_height' => 92,
            'm_width' => 'auto',
            'm_height' => 92,
            'whole_link' => 1,
            'link_on_image' => 1,
            'link_on_name' => 1,
            'extra_css' => '',
            'position' => 'bottom_right',
            'm_position' => 'bottom',
            'top' => '0',
            'bottom' => 20,
            'left' => '0',
            'right' => 20,
            'm_top' => '0',
            'm_bottom' => 10,
            'm_left' => 10,
            'm_right' => 10,
            'close_btn' => 1,
            'close_lifetime' => 365
        );
    }
    
    public function attributeDescriptions()
    {
        return array(
            'pages' => $this->l('You can select several pages by holding down the Ctrl key', 'ArLsfGeneralConfigForm'),
            'delay_first' => $this->l('Delay first notification for x miliseconds after page is loaded. You can enter two values exploded "-" sign. Exmple: 2000-10000', 'ArLsfGeneralConfigForm'),
            'delay' => $this->l('How long a notification stays on the screen (in miliseconds) ', 'ArLsfGeneralConfigForm'),
            'delay_between' => $this->l('Delay for next notificaton will be displayed (in miliseconds). You can enter two values exploded "-" sign. Exmple: 2000-10000', 'ArLsfGeneralConfigForm'),
            'mobile' => $this->l('Popup notification is responsive but you can disable it on mobile devices', 'ArLsfGeneralConfigForm'),
            'opacity' => $this->l('Range from 0 to 100', 'ArLsfGeneralConfigForm'),
            'hover_opacity' => $this->l('Range from 0 to 100', 'ArLsfGeneralConfigForm'),
            'line_space' => $this->l('Space between content lines in px', 'ArLsfGeneralConfigForm'),
            'd_width' => $this->l('Desktop popup width in "px" or "%". Example: 300px, 100%', 'ArLsfGeneralConfigForm'),
            'd_height' => $this->l('Desktop popup height in "px". Example: 92', 'ArLsfGeneralConfigForm'),
            'm_width' => $this->l('Mobile popup width in "px" or "%" or "auto". Example: 300px, 100%', 'ArLsfGeneralConfigForm'),
            'm_height' => $this->l('Mobile popup height in "px". Example: 92', 'ArLsfGeneralConfigForm'),
            'display_times' => $this->l('How many times popups will be displayed per user session. Enter 0 to unlimited number of displays', 'ArLsfGeneralConfigForm'),
            'close_lifetime' => $this->l('Value in days or 0. If customer has closed the popup, it will not be displayed the specified number of days. 0 - popup will be displayed on next session.', 'ArLsfGeneralConfigForm'),
            'sound_times' => $this->l('Type 0 to play sound for every popup', 'ArLsfGeneralConfigForm'),
            'allowed_ips' => sprintf($this->l('One IP address per line. Your current IP %s', 'ArLsfGeneralConfigForm'), $this->getCurrentIP()),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'sandbox' => $this->l('Sandbox mode', 'ArLsfGeneralConfigForm'),
            'allowed_ips' => $this->l('Allowed IPs', 'ArLsfGeneralConfigForm'),
            'd_section' => '',
            'a_section' => '',
            'p_section' => '',
            'close_btn' => $this->l('Enable close button', 'ArLsfGeneralConfigForm'),
            'close_lifetime' => $this->l('Close cookie lifetime', 'ArLsfGeneralConfigForm'),
            'url_params' => $this->l('UTM url params', 'ArLsfGeneralConfigForm'),
            'time_offset' => $this->l('Order and cart time tune', 'ArLsfGeneralConfigForm'),
            'pages' => $this->l('Display on pages', 'ArLsfGeneralConfigForm'),
            'font_size' => $this->l('Font size', 'ArLsfGeneralConfigForm'),
            'line_space' => $this->l('Space between lines', 'ArLsfGeneralConfigForm'),
            'product_thumb' => $this->l('Product thumb size', 'ArLsfGeneralConfigForm'),
            'second_image' => $this->l('Display second product image on hover', 'ArLsfGeneralConfigForm'),
            'stars' => $this->l('Show 5 stars under product image', 'ArLsfGeneralConfigForm'),
            'delay_first' => $this->l('Delay first notification', 'ArLsfGeneralConfigForm'),
            'delay' => $this->l('Display time', 'ArLsfGeneralConfigForm'),
            'delay_between' => $this->l('Delay between notifications', 'ArLsfGeneralConfigForm'),
            'mobile' => $this->l('Display on mobile devices', 'ArLsfGeneralConfigForm'),
            'sound' => $this->l('Play sound on notification display', 'ArLsfGeneralConfigForm'),
            'display_times' => $this->l('Display X times for customer (session)', 'ArLsfGeneralConfigForm'),
            'background_color' => $this->l('Background color', 'ArLsfGeneralConfigForm'),
            'border_color' => $this->l('Border color', 'ArLsfGeneralConfigForm'),
            'border_radius' => $this->l('Border radius', 'ArLsfGeneralConfigForm'),
            'shadow_color' => $this->l('Shadow color', 'ArLsfGeneralConfigForm'),
            'shadow_size' => $this->l('Shadow size', 'ArLsfGeneralConfigForm'),
            'text_color' => $this->l('Text color', 'ArLsfGeneralConfigForm'),
            'link_color' => $this->l('Link color', 'ArLsfGeneralConfigForm'),
            'close_btn_color' => $this->l('Close button color', 'ArLsfGeneralConfigForm'),
            'close_btn_bg' => $this->l('Close button background color', 'ArLsfGeneralConfigForm'),
            'close_btn_style' => $this->l('Close button style', 'ArLsfGeneralConfigForm'),
            'close_btn_position' => $this->l('Close button position', 'ArLsfGeneralConfigForm'),
            'opacity' => $this->l('Opacity', 'ArLsfGeneralConfigForm'),
            'hover_opacity' => $this->l('Opacity on hover', 'ArLsfGeneralConfigForm'),
            'animation' => $this->l('In animation', 'ArLsfGeneralConfigForm'),
            'out_animation' => $this->l('Out animation', 'ArLsfGeneralConfigForm'),
            'd_width' => $this->l('Desktop popup width', 'ArLsfGeneralConfigForm'),
            'd_height' => $this->l('Desktop popup height', 'ArLsfGeneralConfigForm'),
            'm_width' => $this->l('Mobile popup width', 'ArLsfGeneralConfigForm'),
            'm_height' => $this->l('Mobile popup height', 'ArLsfGeneralConfigForm'),
            'whole_link' => $this->l('Whole notification is link', 'ArLsfGeneralConfigForm'),
            'link_on_image' => $this->l('Link on image', 'ArLsfGeneralConfigForm'),
            'link_on_name' => $this->l('Link on product name', 'ArLsfGeneralConfigForm'),
            'extra_css' => $this->l('Extra CSS styles', 'ArLsfGeneralConfigForm'),
            'position' => $this->l('Desktop Position', 'ArLsfGeneralConfigForm'),
            'm_position' => $this->l('Mobile position', 'ArLsfGeneralConfigForm'),
            'top' => $this->l('Desktop Top', 'ArLsfGeneralConfigForm'),
            'bottom' => $this->l('Desktop Bottom', 'ArLsfGeneralConfigForm'),
            'left' => $this->l('Desktop Left', 'ArLsfGeneralConfigForm'),
            'right' => $this->l('Desktop Right', 'ArLsfGeneralConfigForm'),
            'm_top' => $this->l('Mobile Top', 'ArLsfGeneralConfigForm'),
            'm_bottom' => $this->l('Mobile Bottom', 'ArLsfGeneralConfigForm'),
            'm_left' => $this->l('Mobile Left', 'ArLsfGeneralConfigForm'),
            'm_right' => $this->l('Mobile Right', 'ArLsfGeneralConfigForm'),
            'sound_times' => $this->l('Play sound for first X popups', 'ArLsfGeneralConfigForm'),
        );
    }
    
    public function groupedSelects()
    {
        return array(
            'animation' => true,
            'out_animation' => true
        );
    }
    
    public function closeBtnStyleSelectOptions()
    {
        return array(
            array(
                'id' => 'round',
                'name' => $this->l('Round', 'ArLsfGeneralConfigForm')
            ),
            array(
                'id' => 'square',
                'name' => $this->l('Square', 'ArLsfGeneralConfigForm')
            ),
        );
    }
    
    public function closeBtnPositionSelectOptions()
    {
        return array(
            array(
                'id' => 'inside',
                'name' => $this->l('Inside popup', 'ArLsfGeneralConfigForm')
            ),
            array(
                'id' => 'outside',
                'name' => $this->l('Outside popup', 'ArLsfGeneralConfigForm')
            ),
        );
    }
    
    public function animationSelectOptions()
    {
        return array(
            array(
                'id' => 'bounce',
                'name' => $this->l('Bounce'),
                'items' => array(
                    array(
                        'id' => 'bounceIn',
                        'name' => $this->l('bounceIn')
                    ),
                    array(
                        'id' => 'bounceInDown',
                        'name' => $this->l('bounceInDown')
                    ),
                    array(
                        'id' => 'bounceInLeft',
                        'name' => $this->l('bounceInLeft')
                    ),
                    array(
                        'id' => 'bounceInRight',
                        'name' => $this->l('bounceInRight')
                    ),
                    array(
                        'id' => 'bounceInUp',
                        'name' => $this->l('bounceInUp')
                    ),
                )
            ),
            array(
                'id' => 'fade',
                'name' => $this->l('Fade'),
                'items' => array(
                    array(
                        'id' => 'fadeIn',
                        'name' => $this->l('fadeIn')
                    ),
                    array(
                        'id' => 'fadeInDown',
                        'name' => $this->l('fadeInDown')
                    ),
                    array(
                        'id' => 'fadeInDownBig',
                        'name' => $this->l('fadeInDownBig')
                    ),
                    array(
                        'id' => 'fadeInLeft',
                        'name' => $this->l('fadeInLeft')
                    ),
                    array(
                        'id' => 'fadeInLeftBig',
                        'name' => $this->l('fadeInLeftBig')
                    ),
                    array(
                        'id' => 'fadeInRight',
                        'name' => $this->l('fadeInRight')
                    ),
                    array(
                        'id' => 'fadeInRightBig',
                        'name' => $this->l('fadeInRightBig')
                    ),
                    array(
                        'id' => 'fadeInUp',
                        'name' => $this->l('fadeInUp')
                    ),
                    array(
                        'id' => 'fadeInUpBig',
                        'name' => $this->l('fadeInUpBig')
                    ),
                )
            ),
            array(
                'id' => 'flip',
                'name' => $this->l('Flip'),
                'items' => array(
                    array(
                        'id' => 'flip',
                        'name' => $this->l('flip')
                    ),
                    array(
                        'id' => 'flipInX',
                        'name' => $this->l('flipInX')
                    ),
                    array(
                        'id' => 'flipInY',
                        'name' => $this->l('flipInY')
                    ),
                )
            ),
            array(
                'id' => 'lightSpeed',
                'name' => $this->l('LightSpeed'),
                'items' => array(
                    array(
                        'id' => 'lightSpeedIn',
                        'name' => $this->l('lightSpeedIn')
                    ),
                )
            ),
            array(
                'id' => 'rotate',
                'name' => $this->l('Rotate'),
                'items' => array(
                    array(
                        'id' => 'rotateIn',
                        'name' => $this->l('rotateIn')
                    ),
                    array(
                        'id' => 'rotateInDownLeft',
                        'name' => $this->l('rotateInDownLeft')
                    ),
                    array(
                        'id' => 'rotateInDownRight',
                        'name' => $this->l('rotateInDownRight')
                    ),
                    array(
                        'id' => 'rotateInUpLeft',
                        'name' => $this->l('rotateInUpLeft')
                    ),
                    array(
                        'id' => 'rotateInUpRight',
                        'name' => $this->l('rotateInUpRight')
                    ),
                )
            ),
            array(
                'id' => 'slide',
                'name' => $this->l('Slide'),
                'items' => array(
                    array(
                        'id' => 'slideInUp',
                        'name' => $this->l('slideInUp')
                    ),
                    array(
                        'id' => 'slideInDown',
                        'name' => $this->l('slideInDown')
                    ),
                    array(
                        'id' => 'slideInLeft',
                        'name' => $this->l('slideInLeft')
                    ),
                    array(
                        'id' => 'slideInRight',
                        'name' => $this->l('slideInRight')
                    ),
                )
            ),
            array(
                'id' => 'zoom',
                'name' => $this->l('Zoom'),
                'items' => array(
                    array(
                        'id' => 'zoomIn',
                        'name' => $this->l('zoomIn')
                    ),
                    array(
                        'id' => 'zoomInDown',
                        'name' => $this->l('zoomInDown')
                    ),
                    array(
                        'id' => 'zoomInLeft',
                        'name' => $this->l('zoomInLeft')
                    ),
                    array(
                        'id' => 'zoomInRight',
                        'name' => $this->l('zoomInRight')
                    ),
                    array(
                        'id' => 'zoomInUp',
                        'name' => $this->l('zoomInUp')
                    ),
                )
            ),
            array(
                'id' => 'roll',
                'name' => $this->l('Roll'),
                'items' => array(
                    array(
                        'id' => 'rollIn',
                        'name' => $this->l('rollIn')
                    ),
                )
            )
        );
    }
    
    public function outAnimationSelectOptions()
    {
        return array(
            array(
                'id' => 'bounce',
                'name' => $this->l('Bounce'),
                'items' => array(
                    array(
                        'id' => 'bounceOut',
                        'name' => $this->l('bounceOut')
                    ),
                    array(
                        'id' => 'bounceOutDown',
                        'name' => $this->l('bounceOutDown')
                    ),
                    array(
                        'id' => 'bounceOutLeft',
                        'name' => $this->l('bounceOutLeft')
                    ),
                    array(
                        'id' => 'bounceOutRight',
                        'name' => $this->l('bounceOutRight')
                    ),
                    array(
                        'id' => 'bounceOutUp',
                        'name' => $this->l('bounceOutUp')
                    ),
                )
            ),
            array(
                'id' => 'fade',
                'name' => $this->l('Fade'),
                'items' => array(
                    array(
                        'id' => 'fadeOut',
                        'name' => $this->l('fadeOut')
                    ),
                    array(
                        'id' => 'fadeOutDown',
                        'name' => $this->l('fadeOutDown')
                    ),
                    array(
                        'id' => 'fadeOutDownBig',
                        'name' => $this->l('fadeOutDownBig')
                    ),
                    array(
                        'id' => 'fadeOutLeft',
                        'name' => $this->l('fadeOutLeft')
                    ),
                    array(
                        'id' => 'fadeOutLeftBig',
                        'name' => $this->l('fadeOutLeftBig')
                    ),
                    array(
                        'id' => 'fadeOutRight',
                        'name' => $this->l('fadeOutRight')
                    ),
                    array(
                        'id' => 'fadeOutRightBig',
                        'name' => $this->l('fadeOutRightBig')
                    ),
                    array(
                        'id' => 'fadeOutUp',
                        'name' => $this->l('fadeOutUp')
                    ),
                    array(
                        'id' => 'fadeOutUpBig',
                        'name' => $this->l('fadeOutUpBig')
                    ),
                )
            ),
            array(
                'id' => 'flip',
                'name' => $this->l('Flip'),
                'items' => array(
                    array(
                        'id' => 'flipOutX',
                        'name' => $this->l('flipOutX')
                    ),
                    array(
                        'id' => 'flipOutY',
                        'name' => $this->l('flipOutY')
                    ),
                )
            ),
            array(
                'id' => 'lightSpeed',
                'name' => $this->l('LightSpeed'),
                'items' => array(
                    array(
                        'id' => 'lightSpeedOut',
                        'name' => $this->l('lightSpeedOut')
                    ),
                )
            ),
            array(
                'id' => 'rotate',
                'name' => $this->l('Rotate'),
                'items' => array(
                    array(
                        'id' => 'rotateOut',
                        'name' => $this->l('rotateOut')
                    ),
                    array(
                        'id' => 'rotateOutDownLeft',
                        'name' => $this->l('rotateOutDownLeft')
                    ),
                    array(
                        'id' => 'rotateOutDownRight',
                        'name' => $this->l('rotateOutDownRight')
                    ),
                    array(
                        'id' => 'rotateOutUpLeft',
                        'name' => $this->l('rotateOutUpLeft')
                    ),
                    array(
                        'id' => 'rotateOutUpRight',
                        'name' => $this->l('rotateOutUpRight')
                    ),
                )
            ),
            array(
                'id' => 'slide',
                'name' => $this->l('Slide'),
                'items' => array(
                    array(
                        'id' => 'slideOutUp',
                        'name' => $this->l('slideOutUp')
                    ),
                    array(
                        'id' => 'slideOutDown',
                        'name' => $this->l('slideOutDown')
                    ),
                    array(
                        'id' => 'slideOutLeft',
                        'name' => $this->l('slideOutLeft')
                    ),
                    array(
                        'id' => 'slideOutRight',
                        'name' => $this->l('slideOutRight')
                    ),
                )
            ),
            array(
                'id' => 'zoom',
                'name' => $this->l('Zoom'),
                'items' => array(
                    array(
                        'id' => 'zoomOut',
                        'name' => $this->l('zoomOut')
                    ),
                    array(
                        'id' => 'zoomOutDown',
                        'name' => $this->l('zoomOutDown')
                    ),
                    array(
                        'id' => 'zoomOutLeft',
                        'name' => $this->l('zoomOutLeft')
                    ),
                    array(
                        'id' => 'zoomOutRight',
                        'name' => $this->l('zoomOutRight')
                    ),
                    array(
                        'id' => 'zoomOutUp',
                        'name' => $this->l('zoomOutUp')
                    ),
                )
            ),
            array(
                'id' => 'roll',
                'name' => $this->l('Roll'),
                'items' => array(
                    array(
                        'id' => 'hinge',
                        'name' => $this->l('hinge')
                    ),
                    array(
                        'id' => 'rollOut',
                        'name' => $this->l('rollOut')
                    ),
                )
            )
        );
    }
    
    public function productThumbSelectOptions()
    {
        $types = ImageType::getImagesTypes('products');
        $result = array(
            array(
                'id' => '',
                'name' => $this->l('None')
            )
        );
        foreach ($types as $type) {
            $result[] = array(
                'id' => $type['name'],
                'name' => $type['name'] . ' (' . $type['width'] . 'x' . $type['height'] . ')'
            );
        }
        return $result;
    }
    
    public function getDefaultThumbSize()
    {
        if ($this->module->is15()) {
            return $this->getFormatedName('home');
        }
        if ($this->module->is16()) {
            return ImageType::getFormatedName('small');
        }
        if ($this->module->is17()) {
            return ImageType::getFormattedName('small');
        }
    }
    
    public function soundSelectOptions()
    {
        $result = array();
        $result[] = array(
            'id' => 0,
            'name' => $this->l('None')
        );
        $dir = _PS_ROOT_DIR_ . '/modules/arlsf/views/sound/';
        $d = opendir($dir);
        while ($file = readdir($d)) {
            $filename = $dir . $file;
            if (is_file($filename) && !is_dir($filename)) {
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                //$finfo = finfo_open(FILEINFO_MIME_TYPE);
                //$mime = finfo_file($finfo, $filename);
                //finfo_close($finfo);
                if ($ext == 'mp3') {
                    $result[] = array(
                        'id' => basename($filename),
                        'name' => basename($filename)
                    );
                }
            }
        }
        return $result;
    }
    
    protected function getFormatedName($name)
    {
        $theme_name = Context::getContext()->shop->theme_name;
        $name_without_theme_name = str_replace(array('_'.$theme_name, $theme_name.'_'), '', $name);

        //check if the theme name is already in $name if yes only return $name
        if (strstr($name, $theme_name) && ImageType::getByNameNType($name)) {
            return $name;
        } elseif (ImageType::getByNameNType($name_without_theme_name.'_'.$theme_name)) {
            return $name_without_theme_name.'_'.$theme_name;
        } elseif (ImageType::getByNameNType($theme_name.'_'.$name_without_theme_name)) {
            return $theme_name.'_'.$name_without_theme_name;
        } else {
            return $name_without_theme_name.'_default';
        }
    }
    
    public function timeOffsetSelectOptions()
    {
        return array(
            array(
                'id' => '-21600',
                'name' => $this->l('-06:00')
            ),
            array(
                'id' => '-18000',
                'name' => $this->l('-05:00')
            ),
            array(
                'id' => '-14400',
                'name' => $this->l('-04:00')
            ),
            array(
                'id' => '-10800',
                'name' => $this->l('-03:00')
            ),
            array(
                'id' => '-7200',
                'name' => $this->l('-02:00')
            ),
            array(
                'id' => '-3600',
                'name' => $this->l('-01:00')
            ),
            array(
                'id' => '0',
                'name' => $this->l('None')
            ),
            array(
                'id' => '3600',
                'name' => $this->l('+01:00')
            ),
            array(
                'id' => '7200',
                'name' => $this->l('+02:00')
            ),
            array(
                'id' => '10800',
                'name' => $this->l('+03:00')
            ),
            array(
                'id' => '14400',
                'name' => $this->l('+04:00')
            ),
            array(
                'id' => '18000',
                'name' => $this->l('+05:00')
            ),
            array(
                'id' => '21600',
                'name' => $this->l('+06:00')
            ),
        );
    }
    
    public function pagesSelectOptions()
    {
        return array(
            array(
                'id' => 'index',
                'name' => $this->l('Home')
            ),
            array(
                'id' => 'category',
                'name' => $this->l('Category')
            ),
            array(
                'id' => 'product',
                'name' => $this->l('Product')
            ),
            array(
                'id' => 'cms',
                'name' => $this->l('CMS')
            ),
            array(
                'id' => 'order',
                'name' => $this->l('Order')
            ),
            array(
                'id' => 'contact',
                'name' => $this->l('Contact')
            ),
            array(
                'id' => 'prices-drop',
                'name' => $this->l('Specials')
            ),
            array(
                'id' => 'new-products',
                'name' => $this->l('New products')
            ),
            array(
                'id' => 'best-sales',
                'name' => $this->l('Best sellers')
            ),
            array(
                'id' => 'stores',
                'name' => $this->l('Stores')
            ),
            array(
                'id' => 'sitemap',
                'name' => $this->l('Sitemap')
            ),
        );
    }
    
    public function mPositionSelectOptions()
    {
        return array(
            array(
                'id' => 'top',
                'name' => $this->l('Top')
            ),
            array(
                'id' => 'bottom',
                'name' => $this->l('Bottom')
            ),
        );
    }
    
    public function positionSelectOptions()
    {
        return array(
            array(
                'id' => 'top_left',
                'name' => $this->l('Top left')
            ),
            array(
                'id' => 'top_right',
                'name' => $this->l('Top right')
            ),
            array(
                'id' => 'bottom_left',
                'name' => $this->l('Bottom left')
            ),
            array(
                'id' => 'bottom_right',
                'name' => $this->l('Bottom right')
            ),
        );
    }
    
    protected function fontSizeSelectOptions()
    {
        return array(
            array(
                'id' => '10',
                'name' => $this->l('10px')
            ),
            array(
                'id' => '11',
                'name' => $this->l('11px')
            ),
            array(
                'id' => '12',
                'name' => $this->l('12px')
            ),
            array(
                'id' => '13',
                'name' => $this->l('13px')
            ),
            array(
                'id' => '14',
                'name' => $this->l('14px')
            ),
            array(
                'id' => '15',
                'name' => $this->l('15px')
            ),
            array(
                'id' => '16',
                'name' => $this->l('16px')
            ),
            array(
                'id' => '17',
                'name' => $this->l('17px')
            ),
            array(
                'id' => '18',
                'name' => $this->l('18px')
            ),
            array(
                'id' => '19',
                'name' => $this->l('19px')
            ),
            array(
                'id' => '20',
                'name' => $this->l('20px')
            ),
        );
    }
    
    public function getFormTitle()
    {
        return $this->l('General settings', 'ArLsfGeneralConfigForm');
    }
}
