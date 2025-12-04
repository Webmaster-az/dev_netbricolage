/**
* 2007-2017 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*/

$(window).load(function() {
    $('.toggleFaq').click(function(e) {
      	e.preventDefault();

        var $this = $(this);

        if ($this.next().hasClass('showFaq')) {
            $this.next().removeClass('showFaq');
            $this.next().slideUp(350);
        } else {
            $this.parent().parent().find('li .innerFaq').removeClass('showFaq');
            $this.parent().parent().find('li .innerFaq').slideUp(350);
            $this.next().toggleClass('showFaq');
            $this.next().slideToggle(350);
        }
    });

    $('.titleFaq').click(function(e) {
        var $this = $(this);

        if ($this.next().hasClass('showFaq')) {
            $('.titleFaq').removeClass('selected');
            $this.addClass('selected');

            $('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-right');
            $('.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $this.children('.caretRight').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        } else {
            $this.removeClass('selected');

            $this.children('.caretRight').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        }
    });

    $('.questionFaq').click(function(e) {
        var $this = $(this);

        if ($this.next().hasClass('showFaq')) {
            $('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-right');
            $this.children('.caretLeft').removeClass('fa-caret-right').addClass('fa-caret-down');
        } else {
            $this.children('.caretLeft').removeClass('fa-caret-down').addClass('fa-caret-right');
        }
    });
});
