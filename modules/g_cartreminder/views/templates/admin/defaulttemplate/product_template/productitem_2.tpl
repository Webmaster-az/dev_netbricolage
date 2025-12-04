
{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}
<table  style="border-collapse:collapse;border-spacing:0px;width:100%;max-widh: 600px;" align="center">
    <tbody>
        {foreach $gproducts key=keyproduct item=product}
            <div style="width:39%;max-width: 195px;display:inline-block;text-align: center;">
                {assign var="linkpr" value=$links->getProductLink($product['id_product'], $product['link_rewrite'], $product['category'], null, $id_lang, $product['id_shop'], $product['id_product_attribute'], false, false, true)}
                <table  style="border-collapse:collapse;border-spacing:0px;">
                    <tbody>
                        <tr  style="width:100%;padding: 5px;">
                            <td>
                                <a href="{$linkpr|escape:'htmlall':'UTF-8'}" target="_blank">
                                    <img src="{$links->getImageLink($product['link_rewrite']|escape:'htmlall':'UTF-8', $product['id_image']|escape:'htmlall':'UTF-8', 'home_default')}" width="100%" style=""/>
                                </a>
                            </td>
                        </tr>
                        <tr  style="width:100%;padding: 5px;">
                            <a href="{$linkpr|escape:'htmlall':'UTF-8'}" target="_blank" style="text-decoration: none; color:#4f4f4f">
                                <span style="{if isset($name)}color:rgb(85,85,85);{/if}font-size:14px;font-weight:600;line-height:1.4">
                                    {$product['name']|escape:'htmlall':'UTF-8'}
                                </span>
                            </a>
                        </tr>
                        <tr  style="width:100%;">
                            {$product['price']|escape:'htmlall':'UTF-8'}
                        </tr>
                    </tbody>
                </table>
            </div>
        {/foreach}
    </tbody>
</table>
<div style="clear:both;"></div>