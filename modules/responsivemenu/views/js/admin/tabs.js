/*
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

"use strict";

var RmTabs = function() {
    this.tabList = [
        {
            id: 'configuration',
            target: [
                '#configuration_form'
            ]
        },
        {
            id: 'theme',
            target: [
                '#theme_form'
            ]
        },
        {
            id: 'link',
            target: [
                '#links_form',
                '#form-link'
            ]
        },
        {
            id: 'category',
            target: [
                '#category_form',
                '#form-responsivemenu_category'
            ]
        },
        {
            id: 'sublimecategories',
            target: [
                '#route_form',
                '#form-route'
            ]
        }
    ];

    this.currentTab = 'configuration';
    if (typeof localStorage != 'undefined') {
        var tab = localStorage.getItem('rmTab')
        if (tab) {
            this.currentTab = tab;
        }
    }

    this.render();

    jQuery('.js-tab').click((function(e) {
        this.currentTab = jQuery(e.target).data('target');
        this.render();
        if (typeof localStorage != 'undefined') {
            localStorage.setItem('rmTab', this.currentTab)
        }
    }).bind(this));
};

RmTabs.prototype.render = function() {
    jQuery('.js-tab').each((function(key, target) {
        var $tab = jQuery(target);
        var classList = $tab.attr('class').split(' ');
        for (var i in classList) {
            if (!classList[i].match(/--selected$/)) {
                if ($tab.data('target') == this.currentTab) {
                    $tab.addClass(classList[i] + '--selected');
                }
            } else {
                $tab.removeClass(classList[i]);
            }
        }
    }).bind(this));

    for (var i in this.tabList) {
        var tab = this.tabList[i];

        for (var j in tab.target) {
            var target = tab.target[j];

            if (tab.id == this.currentTab) {
                jQuery(target).show();
            } else {
                jQuery(target).hide();
            }
        }
    }
};
