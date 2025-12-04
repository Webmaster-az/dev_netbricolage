/**
 * Multi accessories for PrestaShop
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$(document).ready(function () {
    $(document).on('click', '.order .accessorygroup a.ma_accessory_name', clickAccessoryNameHandler);
});

/**
 * 
 * Get sizes of hidden element
 * http://stackoverflow.com/questions/2345784/jquery-get-height-of-hidden-element-in-jquery/8839261#8839261
 */
$.fn.getSize = function () {
    var $wrap = $("<div />").appendTo($("body"));
    $wrap.css({
        "position": "absolute !important",
        "visibility": "hidden !important",
        "display": "block !important"
    });

    $clone = $(this).clone().appendTo($wrap);

    sizes = {
        "width": this.width(),
        "height": this.height()
    };

    $wrap.remove();

    return sizes;
};

/**
 * In this callback, we will do:<br/>
 * - Set up a simpleModal box
 * - Turn on the simpleMoal box
 * @param {Event} event
 */
function clickAccessoryNameHandler(event) {
    event.preventDefault();
    var contentSizes = $(this).parent().find('.tooltipster-content').getSize();
    var contentH = contentSizes.height > $(window).height() ? '100%' : contentSizes.height;

    $(this).parent().find('.tooltipster-content').modal({
        escClose: true,
        overlayClose: true,
        containerCss: {
            'width': '600px',
            'height': contentH,
            'minHeight': '200px',
            'text-align': 'left'
        }
    });
}