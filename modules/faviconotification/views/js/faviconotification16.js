/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

$(document).ready(function() {
if (typeof favico_front_controller !== 'undefined') {
    var badge = nbProductCart;
        window.favicon = new Favico({
        animation : 'popFade',
        bgColor : BgColor,
        textColor : TxtColor,
    });

    if (badge == 0) {
        favicon.reset();
    } else {
        favicon.badge(badge);
    }
}
});

function interceptFunction (object, fnName, options) {
    var noop = function () {};
    var fnToWrap = object[fnName];
    var before = options.before || noop;
    var after = options.after || noop;

    object[fnName] = function () {
        before.apply(this, arguments);
        var result = fnToWrap.apply(this, arguments);
        after.apply(this, arguments);
        return result
    }
}

interceptFunction(ajaxCart, 'updateCartEverywhere', {
   after: function () {
if (this.nb_total_products == 0)
    favicon.reset();
else
    favicon.badge(this.nb_total_products);
}
});
