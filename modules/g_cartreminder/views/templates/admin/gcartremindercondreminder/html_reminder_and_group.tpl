{*

* Do not edit the file if you want to upgrade the module in future.

* 

* @author    Globo Jsc <contact@globosoftware.net>

* @copyright 2017 Globo., Jsc

* @link	     http://www.globosoftware.net

* @license   please read license in file license.txt

*/

*} 

{if $html_name == "groups"}

    <tr id="gcart_groups_{$number|escape:'html':'UTF-8'}" class="group-orreminder" data-count-condition="0" data-id-group-condition="{$number|escape:'html':'UTF-8'}">

        <td>

            <div class="gcart-group-{$number|escape:'html':'UTF-8'}">

                <div class="panel">

                    <input type="hidden"  value="0" class="number_group_group"/>

                    <table class="gcart-tablegroup" id="gcart-tablegroup-{$number|escape:'html':'UTF-8'}">

                        <tr class="group-andreminder">

                            <td class=''>

                                <div class="">

                                    <select class="form-control select_condition_group" data-id="{$number|escape:'html':'UTF-8'}"  data-groupid="0" name="condition_group[{$number|escape:'html':'UTF-8'}][0][type]">

                                        <option value="">{l s='--Choose--' mod='g_cartreminder'}</option>

                                        <optgroup label="{l s='Cart' mod='g_cartreminder'}">

                                            <option value="cart_products">{l s='Products' mod='g_cartreminder'}</option>

                                            <option value="cart_totalincart">{l s='Products Total In Cart' mod='g_cartreminder'}</option>

                                            <option value="cart_stockproduct">{l s='Product stock' mod='g_cartreminder'}</option>

                                            <option value="cart_stockproducts">{l s='Products stock (all in cart)' mod='g_cartreminder'}</option>

                                            <option value="cart_productcat">{l s='Product Category' mod='g_cartreminder'}</option>

                                            <option value="cart_productsupplier">{l s='Product Supplier' mod='g_cartreminder'}</option>

                                            <option value="cart_productman">{l s='Products Manufacturers' mod='g_cartreminder'}</option>

                                        </optgroup>

                                        <optgroup label="{l s='Customer' mod='g_cartreminder'}">

                                            <option value="customer_email">{l s='Email' mod='g_cartreminder'}</option>

                                            <option value="customer_language">{l s='Language' mod='g_cartreminder'}</option>

                                            <option value="customer_aeg">{l s='AGE' mod='g_cartreminder'}</option>

                                            <option value="customer_social">{l s='Social title' mod='g_cartreminder'}</option>

                                            <option value="customer_newlester">{l s='Newlester' mod='g_cartreminder'}</option>

                                            <option value="customer_register">{l s='Register Date' mod='g_cartreminder'}</option>

                                            <option value="customer_order">{l s='Order count' mod='g_cartreminder'}</option>

                                            <option value="customer_country">{l s='Country address' mod='g_cartreminder'}</option>

                                        </optgroup>

                                    </select>

                                </div>

                            </td>

                            <td class="group_select_{$number|escape:'html':'UTF-8'}_0">

                                <div class="">

                                    <input type="text" disabled="disabled"/>

                                </div>

                            </td>

                            <td class="group_selectval_{$number|escape:'html':'UTF-8'}_0">

                                <div class="">

                                    <input type="text" disabled="disabled"/>

                                </div>

                            </td>

                            <td>

                                <a class="btn btn-default gcartrmove_group pull-right"><i class="icon-times-circle text-danger"></i></a>

                            </td>

                        </tr>

                    </table>

                    <div class="action-table-group">

                        <a class="btn btn-default addgcart_group" data-id="{$number|escape:'html':'UTF-8'}" data-groupid="0"><i class="icon-plus-sign"></i> {l s='Add' mod='g_cartreminder'}</a>

                    </div>

                </div>

            </div>

        </td>

        <td>

            <a class="btn btn-default gcartrmove_groups pull-right" data-id="{$number|escape:'html':'UTF-8'}"><i class="icon-trash text-danger"></i></a>

        </td> 

    </tr>

{elseif $html_name == "group"}

<tr class="group-andreminder">

    <td class=''>

        <div class="">

            <select class="form-control select_condition_group" data-id="{$number|escape:'html':'UTF-8'}" data-groupid="{$number_group|escape:'html':'UTF-8'}" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][type]">

                <option value="">{l s='--Choose--' mod='g_cartreminder'}</option>

                <optgroup label="{l s='Cart' mod='g_cartreminder'}">

                    <option value="cart_products">{l s='Products' mod='g_cartreminder'}</option>

                    <option value="cart_totalincart">{l s='Total number of products in cart' mod='g_cartreminder'}</option>

                    <option value="cart_stockproduct">{l s='Product stock' mod='g_cartreminder'}</option>

                    <option value="cart_stockproducts">{l s='Products stock (all in cart)' mod='g_cartreminder'}</option>

                    <option value="cart_productcat">{l s='Product Category' mod='g_cartreminder'}</option>

                    <option value="cart_productsupplier">{l s='Product Supplier' mod='g_cartreminder'}</option>

                    <option value="cart_productman">{l s='Products Manufacturers' mod='g_cartreminder'}</option>

                </optgroup>

                <optgroup label="{l s='Customer' mod='g_cartreminder'}">

                    <option value="customer_email">{l s='Email' mod='g_cartreminder'}</option>

                    <option value="customer_language">{l s='Language' mod='g_cartreminder'}</option>

                    <option value="customer_aeg">{l s='AGE' mod='g_cartreminder'}</option>

                    <option value="customer_social">{l s='Genders' mod='g_cartreminder'}</option>

                    <option value="customer_newlester">{l s='Newlester' mod='g_cartreminder'}</option>

                    <option value="customer_register">{l s='Register Date' mod='g_cartreminder'}</option>

                    <option value="customer_order">{l s='Order count' mod='g_cartreminder'}</option>

                    <option value="customer_country">{l s='Country address' mod='g_cartreminder'}</option>

                </optgroup>

            </select>

        </div>

    </td>

    <td class="group_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}">

        <div class="">

            <input type="text" disabled="disabled"/>

        </div>

    </td>

    <td class="group_selectval_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}">

        <div class="">

            <input type="text" disabled="disabled"/>

        </div>

    </td>

    <td>

        <a class="btn btn-default gcartrmove_group pull-right" data-id="{$number_group|escape:'html':'UTF-8'}"><i class="icon-times-circle text-danger"></i></a>

    </td>

</tr>

{elseif $html_name == "cart_products_value" || $html_name == "cart_productcat_value" || $html_name == "cart_productsupplier_value" || $html_name == "cart_productman_value" || $html_name == "customer_language_value" || $html_name == "customer_social_value" || $html_name == "customer_country_value"}

    <input type="text" disabled="disabled" id="conditionproduct_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_math"/>

{elseif $html_name == "cart_products" || $html_name == "cart_productcat" || $html_name == "cart_productsupplier_value" || $html_name == "cart_productsupplier" || $html_name == "cart_productman" || $html_name == "customer_language" || $html_name == "customer_social" || $html_name == "customer_country"}

    <a class="btn btn-default" type="button" data-toggle="modal" id="groupselectbox_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}" data-target="#groupselect_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}"><i class="icon-list-ul"></i> {l s='Choose' mod='g_cartreminder'}</a>

    <div class="modal fade group-condition-cart" id="groupselect_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="true">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    {if $html_name == "cart_products"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Products' mod='g_cartreminder'}</h4>

                    {elseif $html_name == "cart_productcat"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Category' mod='g_cartreminder'}</h4>

                    {elseif $html_name == "cart_productsupplier"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Supplier' mod='g_cartreminder'}</h4>   

                    {elseif $html_name == "cart_productman"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Manufacturers' mod='g_cartreminder'}</h4>

                    {elseif $html_name == "customer_language"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Language' mod='g_cartreminder'}</h4>

                    {elseif $html_name == "customer_social"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Genders' mod='g_cartreminder'}</h4>

                    {elseif $html_name == "customer_country"}

                        <h4 class="modal-title" id="maillabale">{l s='Select Coutry' mod='g_cartreminder'}</h4>

                    {/if}

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">×</span>

                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group" data-id="{$number|escape:'html':'UTF-8'}" data-groupid="{$number_group|escape:'html':'UTF-8'}">

                        <div class="col-lg-6">

                            <label class="control-label">{l s='Unselected' mod='g_cartreminder'}</label>

                            <div class="input-group group-condition-extra">

                                <input type="text" class="form-control gcart_search_product" placeholder="{l s='Search by ID, name, reference' mod='g_cartreminder'}"  data-id="conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_search"/>

                                <div class="input-group-addon">

                                    <i class="icon-search"></i>

                                </div>

                            </div>

                            <select multiple size="10" class="" id="conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_1" class="conditionproduct_select_1">

                                {if $html_name == "cart_products"}

                                    {if $products}

                                        {foreach from=$products item=product}

                                            <option value="{$product['id']|escape:'html':'UTF-8'}">&nbsp;{$product['id']|escape:'html':'UTF-8'} - {$product['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "cart_productcat"}

                                    {if $cats}

                                        {foreach from=$cats item=cat}

                                            <option value="{$cat['id_category']|escape:'html':'UTF-8'}">&nbsp;{$cat['id_category']|escape:'html':'UTF-8'} - {$cat['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "cart_productsupplier"}

                                    {if $suppliers}

                                        {foreach from=$suppliers item=supplier}

                                            <option value="{$supplier['id_supplier']|escape:'html':'UTF-8'}">&nbsp;{$supplier['id_supplier']|escape:'html':'UTF-8'} - {$supplier['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "cart_productman"}

                                    {if $manufacturers}

                                        {foreach from=$manufacturers item=manufacturer}

                                            <option value="{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'}">&nbsp;{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'} - {$manufacturer['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "customer_language"}

                                    {if $languages}

                                        {foreach from=$languages item=language}

                                            <option value="{$language['id_lang']|escape:'html':'UTF-8'}">&nbsp;{$language['id_lang']|escape:'html':'UTF-8'} - {$language['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "customer_social"}

                                    {if $genders}

                                        {foreach from=$genders item=gender}

                                            <option value="{$gender['id_gender']|escape:'html':'UTF-8'}">&nbsp;{$gender['id_gender']|escape:'html':'UTF-8'} - {$gender['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {elseif $html_name == "customer_country"}

                                    {if $countrys}

                                        {foreach from=$countrys item=country}

                                            <option value="{$country['id_country']|escape:'html':'UTF-8'}">&nbsp;{$country['id_country']|escape:'html':'UTF-8'} - {$country['name']|escape:'html':'UTF-8'}</option>

                                        {/foreach}

                                    {/if}

                                {/if}

                            </select>

                            <div class="clearfix">&nbsp;</div>

                            <a class="btn btn-default btn-block" id="conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_add">

                                {l s='Add' mod='g_cartreminder'}

                                <i class="icon-arrow-right"></i>

                            </a>

                        </div>

                        <div class="col-lg-6">

                            <label class="control-label">{l s='Selected' mod='g_cartreminder'}</label>

                            <div class="input-group group-condition-extra">

                            &nbsp;

                            </div>

                            <select multiple="" size="10"  class="conditionproduct_select_2" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]" id="conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_2">

                            </select>

                            <div class="clearfix">&nbsp;</div>

                            <a class="btn btn-default btn-block" id="conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_remove">

                                <i class="icon-arrow-left"></i>

                                {l s='Remove' mod='g_cartreminder'}

                            </a>

                        </div>

                    </div>

                    <script type="text/javascript">

                        $('#conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_remove').click(function() { removeConditionGroupOption(this); updateProductConditionGroupDescription(this); });

                        $('#conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_add').click(function() { addConditionGroupOption(this); updateProductConditionGroupDescription(this); });

                        $(document).ready(function() { updateProductConditionGroupDescription($('#conditionproduct_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_add')); });

                    </script>

                </div>

            </div>

        </div>

    </div>

{elseif $html_name == "cart_totalincart" || $html_name == "cart_stockproduct" || $html_name == "cart_stockproducts" || $html_name =="customer_aeg" || $html_name =="customer_register" || $html_name =="customer_order"}

    <select class="" id="cart_totalincart_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_1" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][reminder]">

       <option value="=">&nbsp;{l s='equal to' mod='g_cartreminder'}</option>

       <option value="!=">&nbsp;{l s='Not equal to' mod='g_cartreminder'}</option>

       <option value=">">&nbsp;{l s='is greater than' mod='g_cartreminder'}</option>

       <option value=">=">&nbsp;{l s='is greater than or equal to' mod='g_cartreminder'}</option>

       <option value="<">&nbsp;{l s='is less than' mod='g_cartreminder'}</option>

       <option value="<=">&nbsp;{l s='is less than or equal to' mod='g_cartreminder'}</option>

    </select>

{elseif $html_name == "customer_email"}

    <select class="" id="cart_totalincart_select_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_1" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][reminder]">

       <option value="1">&nbsp;{l s='Is' mod='g_cartreminder'}</option>

       <option value="2">&nbsp;{l s='Is not' mod='g_cartreminder'}</option>

       <option value="3">&nbsp;{l s='contains' mod='g_cartreminder'}</option>

       <option value="4">&nbsp;{l s='does not contain' mod='g_cartreminder'}</option>

       <option value="5">&nbsp;{l s='starts with' mod='g_cartreminder'}</option>

       <option value="6">&nbsp;{l s='ends with' mod='g_cartreminder'}</option>

    </select>

{elseif $html_name == "customer_register_value"}

<div class="controls input-group">

    <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>

    <input class="form-control" type="number" min="0" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]" value="" size="40" placeholder="0" onkeypress="return isNumberKey(event)">

</div>

{elseif $html_name == "customer_email_value"}

    <input type="text" class="form-control" value=""  name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]"/>

{elseif $html_name == "cart_totalincart_value" || $html_name == "cart_stockproduct_value" || $html_name == "cart_stockproducts_value" || $html_name == "customer_aeg_value" || $html_name == "customer_order_value"}

    <input type="number" class="form-control" value=""  name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]"/>

{elseif $html_name == "customer_newlester"}

<div class="col-lg-9">

    <span class="switch prestashop-switch fixed-width-lg">

        <input type="radio" id="radio_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_on" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]" value="1">

        <label for="radio_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_on">{l s='Yes' mod='g_cartreminder'}</label>

        <input type="radio" id="radio_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_off" name="condition_group[{$number|escape:'html':'UTF-8'}][{$number_group|escape:'html':'UTF-8'}][value][]" value="0" checked="checked">

        <label for="radio_{$number|escape:'html':'UTF-8'}_{$number_group|escape:'html':'UTF-8'}_off" id="">{l s='No' mod='g_cartreminder'}</label>

        <a class="slide-button btn"></a>

    </span>

</div>

{elseif $html_name == "default"}

    <input type="text" disabled="disabled"/>

{elseif $html_name == "reminder"}

    <div class="form-group">

        <label class="control-label">

            <span class="label-text" data-toggle="tooltip" data-html="true" title="">

                {l s='Email template' mod='g_cartreminder'}

            </span>

        </label>

    </div>



    <div class="form-group">

        <select class="c-select" id="id_reminder_{$trreminder|escape:'html':'UTF-8'}_reminder" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][id_emailtemplate]">

            {if !empty($array_listemail)}

                {foreach $array_listemail item=itememailteplate}

                    <option value="{$itememailteplate["id_gaddnewemail_template"]|escape:'htmlall':'UTF-8'}">

                        {$itememailteplate["template_name"]|escape:'htmlall':'UTF-8'}

                    </option>

                {/foreach}

            {/if}

        </select>

    </div>

{elseif $html_name == "prequency"}

    <div class="form-group">

        <label class="control-label">

            <span class="label-text" data-toggle="tooltip" data-html="true" title=""> {l s='Frequency' mod='g_cartreminder'}</span>

        </label>

    </div>

    <div class="form-group">

        <div class="col-lg-3">

            <div class="row">

                <div class="controls input-group">

                <div class="input-group-addon"> {l s='Days' mod='g_cartreminder'}

                    </div>

                        <input class="form-control" id="id_reminder_{$trreminder|escape:'html':'UTF-8'}_gday" type="number" min="0" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][gday]" value="" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>

                    </div>

                </div>

            </div>

            <div class="col-lg-3">

                <div class="row">

                    <div class="controls input-group"><div class="input-group-addon">

                        {l s='Hrs' mod='g_cartreminder'}

                    </div>

                    <input class="form-control" id="id_reminder_{$trreminder|escape:'html':'UTF-8'}_ghrs" type="number" min="0" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][ghrs]" value="" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-2 last_number" >

        <div class="row">

            <div class="controls input-group">

                <input class="form-control" id="id_reminder_{$trreminder|escape:'html':'UTF-8'}_number" type="text" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][number]" value="{$trreminder|escape:'html':'UTF-8'}"/>

            </div>

        </div>

    </div>

{elseif $html_name == "discount"}

<div class="form-group"><label class="control-label"><span class="label-text" data-toggle="tooltip" data-html="true" title="">

    {l s='Discount' mod='g_cartreminder'}

    </span></label>

</div>

<div class="form-group">

    <button type="button" class="btn btn-default" data-toggle="modal" id="discountbutton_{$trreminder|escape:'html':'UTF-8'}" data-target="#discount_{$trreminder|escape:'html':'UTF-8'}">

    {l s='Set Discount' mod='g_cartreminder'}

    </button>

    <div class="modal fade in" id="discount_{$trreminder|escape:'html':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="false">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="maillabale">

                        {l s='Discount' mod='g_cartreminder'}

                    </h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <div class="col-lg-12">

                            <div class="form-group">

                                <label class="control-label">

                                    {l s='Discount Type' mod='g_cartreminder'}

                                </label>

                                <div class="col-lg-12 gcart-nonepadding">

                                    <div class="radio col-lg-3"><label for="gpercentage_{$trreminder|escape:'html':'UTF-8'}" id="labale_on_{$trreminder|escape:'html':'UTF-8'}">

                                        {l s='Percentage' mod='g_cartreminder'}

                                        (%)<input type="radio" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][discounttype]" id="gpercentage_{$trreminder|escape:'html':'UTF-8'}" value="0" onclick="showdiscount(this.id)" data="{$trreminder|escape:'html':'UTF-8'}" checked="checked"></label>

                                    </div>

                                    <div class="radio col-lg-3"><label for="gfixed_{$trreminder|escape:'html':'UTF-8'}" id="labale_off_{$trreminder|escape:'html':'UTF-8'}"><input type="radio" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][discounttype]" id="gfixed_{$trreminder|escape:'html':'UTF-8'}" onclick="showdiscount(this.id)" data="{$trreminder|escape:'html':'UTF-8'}" value="1">

                                        {l s='Amount' mod='g_cartreminder'}

                                        </label>

                                    </div>

                                    <div class="radio col-lg-3"><label for="gnonedc_{$trreminder|escape:'html':'UTF-8'}" id="labale_none_{$trreminder|escape:'html':'UTF-8'}"><input type="radio" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][discounttype]" id="gnonedc_{$trreminder|escape:'html':'UTF-8'}" value="2" onclick="nonediscount(this.id)" data="{$trreminder|escape:'html':'UTF-8'}" ><i class="icon-remove color_danger"></i>

                                        {l s='None' mod='g_cartreminder'}

                                        </label>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group noneform_{$trreminder|escape:'html':'UTF-8'}">

                                <input type="hidden" value="0" class="discount_number_{$trreminder|escape:'html':'UTF-8'}">

                                <div class="col-lg-12">

                                    <div class="row">

                                        <table class="col-lg-12" data-id="{$trreminder|escape:'html':'UTF-8'}">

                                            <thead>

                                                <tr>

                                                    <th><label class="control-label">{l s='Price Range' mod='g_cartreminder'}</label></th>

                                                    <th><label class="control-label">{l s='Discount Value' mod='g_cartreminder'}</label></th>

                                                    <th></th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                <tr>

                                                    <td>

                                                        <div class="input-group gcart-input-group">

                                                            {foreach from=$Currencies item=currencie}

                                                                <div class="currencie-field curen-{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] != $currenciedefault}style="display:none"{/if}>

                                                                    <div class="col-lg-4 gcart-nonepadding">

                                                                        <input type="text" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][0][minprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="0" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                                    </div>

                                                                    <label class="control-label col-lg-1"><i class="icon-minus"></i></label>

                                                                    <div class="col-lg-4">

                                                                        <input type="text" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][0][maxprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="0" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                                    </div>

                                                                    <div class="col-lg-2">

                                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">

                                                                            {$currencie['sign']|escape:'html':'UTF-8'}

                                                                            <span class="caret"></span>

                                                                        </button>

                                                                        <ul class="dropdown-menu">

                                                                            {foreach from=$Currencies item=curren}

                                                                                <li><a href="javascript:hideOtherCurreny({$curren['id_currency']|escape:'html':'UTF-8'});" tabindex="-1">{$curren['name']|escape:'html':'UTF-8'}</a></li>

                                                                            {/foreach}

                                                                        </ul>

                                                                    </div>

                                                                </div>

                                                            {/foreach}

                                                        </div>

                                                    </td>

                                                    <td>

                                                        <div class="gcart-input-group">

                                                            <div class="controls col-lg-4 gcart-nonepadding">

                                                                <input class="form-control" id="gdiscountvalue_{$trreminder|escape:'html':'UTF-8'}" type="number" min="0" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][0][discountvalue]" value="" size="40" placeholder="0">

                                                            </div>

                                                            <div class="controls col-lg-4 nonediscounttype_price">

                                                                <select name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][0][reduction_currency]">

                                                                    {foreach from=$Currencies item=currencie}

                                                                        <option value="{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] == $currenciedefault}selected="selected"{/if}>{$currencie['name']|escape:'html':'UTF-8'}</option>

                                                                    {/foreach}

                                                                </select>

                                                            </div>

                                                            <div class="controls col-lg-4 nonediscounttype_price">

                                                                <select name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][0][reduction_tax]">

                                                                    <option value="0" selected="selected">{l s='Tax excluded' mod='g_cartreminder'}</option>

                                                                    <option value="1">{l s='Tax included' mod='g_cartreminder'}</option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </td>

                                                    <td>

                                                        <a class="btn btn-default gcartrmove_discountgroup pull-right"><i class="icon-times-circle text-danger"></i></a>

                                                    </td>

                                                </tr>

                                            </tbody>

                                        </table>

                                        <div class="action-table-group">

                                            <a class="btn btn-default addgcart_discount" data-id="{$trreminder|escape:'html':'UTF-8'}"><i class="icon-plus-sign"></i> {l s='New Discount' mod='g_cartreminder'}</a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group noneform_{$trreminder|escape:'html':'UTF-8'}">

                                <label class="control-label">

                                {l s='Coupon Validity' mod='g_cartreminder'}

                                </label>

                                <div class="col-lg-12 gcart-nonepadding">

                                    <div class="row">

                                        <div class="controls col-lg-4 input-group" style="padding-left:5px;">

                                            <div class="input-group-addon">

                                                {l s='Days' mod='g_cartreminder'}

                                            </div>

                                            <input class="form-control" id="gvalidity_{$trreminder|escape:'html':'UTF-8'}" type="number" min="0" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][counponvalidity]" value="" size="40" placeholder="0">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="form-group noneform_{$trreminder|escape:'html':'UTF-8'}">

                                <label class="control-label">

                                {l s='Free shipping' mod='g_cartreminder'}

                                </label>

                                <div class="col-lg-12 gcart-nonepadding"><span class="switch prestashop-switch fixed-width-lg"><input type="radio" id="gfreeship_on_{$trreminder|escape:'html':'UTF-8'}" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][freeshipping]" value="1"><label for="gfreeship_on_{$trreminder|escape:'html':'UTF-8'}" id="onfree_{$trreminder|escape:'html':'UTF-8'}">

                                    {l s='Yes' mod='g_cartreminder'}

                                    </label><input type="radio" id="gfreeship_off_{$trreminder|escape:'html':'UTF-8'}" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][freeshipping]" value="0" checked="checked"><label for="gfreeship_off_{$trreminder|escape:'html':'UTF-8'}" id="offfree_{$trreminder|escape:'html':'UTF-8'}">

                                    {l s='No' mod='g_cartreminder'}

                                    </label><a class="slide-button btn"></a></span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer"><button type="button" class="btn btn-default btn-lg" data-dismiss="modal">

                    {l s='Close' mod='g_cartreminder'}

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{elseif $html_name == "id_reminder"}

    <span class="badge pull-left" id="id_reminder_{$lengthcol|escape:'html':'UTF-8'}">{$lengthcol|escape:'html':'UTF-8'}</span>

{elseif $html_name == "remove_reminder"}

    <a class="btn btn-default" id="remove_reminder_{$trreminder|escape:'html':'UTF-8'}" data="{$trreminder|escape:'html':'UTF-8'}" onclick="removereminder(this.id)"><i class="icon-trash text-danger"></i></a>

{elseif $html_name == "discounthtml"}

<tr>

    <td>

        <div class="input-group gcart-input-group">

            {foreach from=$Currencies item=currencie}

                <div class="currencie-field curen-{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] != $currenciedefault}style="display:none"{/if}>

                    <div class="col-lg-4 gcart-nonepadding">

                        <input type="text" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][minprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="0" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                    </div>

                    <label class="control-label col-lg-1"><i class="icon-minus"></i></label>

                    <div class="col-lg-4">

                        <input type="text" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][maxprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="0" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                    </div>

                    <div class="col-lg-2">

                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">

                            {$currencie['sign']|escape:'html':'UTF-8'}

                            <span class="caret"></span>

                        </button>

                        <ul class="dropdown-menu">

                            {foreach from=$Currencies item=curren}

                                <li><a href="javascript:hideOtherCurreny({$curren['id_currency']|escape:'html':'UTF-8'});" tabindex="-1">{$curren['name']|escape:'html':'UTF-8'}</a></li>

                            {/foreach}

                        </ul>

                    </div>

                </div>

            {/foreach}

        </div>

    </td>

    <td>

        <div class="gcart-input-group">

            <div class="controls col-lg-4 gcart-nonepadding">

                <input class="form-control" id="gdiscountvalue_{$trreminder|escape:'html':'UTF-8'}" type="number" min="0" name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][discountvalue]" value="" size="40" placeholder="0">

            </div>

            <div class="controls col-lg-4 nonediscounttype_price">

                <select name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][reduction_currency]">

                    {foreach from=$Currencies item=currencie}

                        <option value="{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] == $currenciedefault}selected="selected"{/if}>{$currencie['name']|escape:'html':'UTF-8'}</option>

                    {/foreach}

                </select>

            </div>

            <div class="controls col-lg-4 nonediscounttype_price">

                <select name="jsreminder[{$trreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][reduction_tax]">

                    <option value="0" selected="selected">{l s='Tax excluded' mod='g_cartreminder'}</option>

                    <option value="1">{l s='Tax included' mod='g_cartreminder'}</option>

                </select>

            </div>

        </div>

    </td>

    <td>

        <a class="btn btn-default gcartrmove_discountgroup pull-right"><i class="icon-times-circle text-danger"></i></a>

    </td>

</tr>

{elseif $html_name == "html_includeshoppingcart"}

    <tr class="odd">

        <td>{$cart_abadoned['id_cart'|escape:'html':'UTF-8']}</td>

        <td class="pointer text-center">

            <span class="badge {if $exclude}badge-danger{else}badge-warning{/if}">{$cart_abadoned['status'|escape:'html':'UTF-8']}</span>

        </td>

        <td>{$cart_abadoned['customer'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['totalproduct'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['total'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['date_add'|escape:'html':'UTF-8']}</td>

        <td class="text-right">

            {if $exclude}

                <button type="button" class="btn btn-default excluded_shoppingcart" data-id="{$cart_abadoned['id_cart'|escape:'html':'UTF-8']}"><i class="icon-times-circle"></i> {l s='Excluded' mod='g_cartreminder'}</button>

            {else}

                {l s='Does not meet Min - Max cart amount' mod='g_cartreminder'}

            {/if}

        </td>

    </tr>

{elseif $html_name == "html_excludeshoppingcart"}

    <tr class="odd">

        <td>{$cart_abadoned['id_cart'|escape:'html':'UTF-8']}</td>

        <td class="pointer text-center">

            <span class="badge {if $exclude}badge-danger{else}badge-warning{/if}">{$cart_abadoned['status'|escape:'html':'UTF-8']}</span>

        </td>

        <td>{$cart_abadoned['customer'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['totalproduct'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['total'|escape:'html':'UTF-8']}</td>

        <td>{$cart_abadoned['date_add'|escape:'html':'UTF-8']}</td>

        <td class="text-right">

            {if $exclude}

                <button type="button" class="btn btn-default included_shoppingcart" data-id="{$cart_abadoned['id_cart'|escape:'html':'UTF-8']}"><i class="icon-times-circle"></i> {l s='Excluded' mod='g_cartreminder'}</button>

            {else}

                {l s='Does not meet Min - Max cart amount' mod='g_cartreminder'}

            {/if}        

        </td>

    </tr>

{/if}