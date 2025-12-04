/**
 * Multi accessories for PrestaShop
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * 
 * Render accessories of product in product list
 * @param {json} ajaxRenderAccessoriesUrl
 * @returns {Object} {HsmaRenderAccessories}
 */

var HsmaRenderAccessories = function (ajaxRenderAccessoriesUrl)
{

    this._ajaxRenderAccessoriesUrl = ajaxRenderAccessoriesUrl ? ajaxRenderAccessoriesUrl : null;
    
    /**
     * Define all params default of this class
     */
    this._params = {
    };

    /**
     * Define array selectors
     */
    this._selectors = {
        idProduct: '#product_page_product_id', // id input hidden contain id product
        classAjaxBlockProduct: '.ajax_block_product', // define block product
        ajaxAddToCartButton: '.ajax_add_to_cart_button' // define class add to cart
    };
    
    this.synUrl = function (url)
    {
        var synUrl = '';
        if (typeof url !== 'undefined')
            synUrl = url.indexOf('https:') > -1 ? url.replace("https:", document.location.protocol) : url.replace("http:", document.location.protocol);
        return synUrl;
    };
    
    HsmaRenderAccessories.instance = this;

    this.init = function ()
    {
        //this._initListDataAccessories();
        var idProducts = this._initListDataAccessories();
        if (idProducts.length > 0) {
            HsmaRenderAccessories.instance._renderAccessories(idProducts);
        }
    };
    
    /**
     * Loop all list products
     */
    this._initListDataAccessories = function () {
        var idProducts = new Array();
        $(this._selectors.classAjaxBlockProduct).each(function () {
            var idProduct = HsmaRenderAccessories.instance._getIdProduct(this);
            if (parseInt(idProduct) > 0) {
                idProducts.push(idProduct);
            }
        });
        return idProducts;
    };

    this._renderAccessories = function (idProducts)
    {
        if (!idProducts)
            return;
        $.ajax({
            type: 'POST',
            headers: {"cache-control": "no-cache"},
            url: HsmaRenderAccessories.instance._ajaxRenderAccessoriesUrl,
            async: true,
            cache: false,
            dataType: "json",
            data: {
                'ajax' : true,
                'id_products' : idProducts,
                'action' : 'renderAccessories'
            },
            success: function (jsonData)
            {
                $(HsmaRenderAccessories.instance._selectors.classAjaxBlockProduct).each(function () {
                    var idProduct = HsmaRenderAccessories.instance._getIdProduct(this);
                    var buttonAddtoCart = this;
                    if (parseInt(idProduct) > 0) {
                        $.each(jsonData.accessories, function (idProductRender, value) {
                            if (parseInt(idProduct) === parseInt(idProductRender)) {
                                $(buttonAddtoCart).find(HsmaRenderAccessories.instance._selectors.ajaxAddToCartButton).attr('data-id-accessories',value.id_accessories);
                                $(buttonAddtoCart).find(HsmaRenderAccessories.instance._selectors.ajaxAddToCartButton).attr('data-id-accessories-combination',value.id_accessories_combination);
                                $(buttonAddtoCart).find(HsmaRenderAccessories.instance._selectors.ajaxAddToCartButton).attr('data-custom-qty',value.custom_qty);
                            }
                        });
                    }
                });
            }
        });
    };
    
    
    this._getIdProduct = function(element) {
        var links = $(element).find('a');
        var idProduct = 0;

        for (var i = 0; i < links.length; i++)
        {
            var href = decodeURIComponent($(links[i]).attr('href'));
            if (href.indexOf('id_product') > 0)
            {
                i = links.length;
                idProduct = href.substring(href.indexOf('id_product') + 11, href.length);
            }
        }
        if (isNaN(idProduct) && idProduct.indexOf('&') > 0)
            idProduct = idProduct.substring(0, idProduct.indexOf('&'));
        return idProduct;
    };
};