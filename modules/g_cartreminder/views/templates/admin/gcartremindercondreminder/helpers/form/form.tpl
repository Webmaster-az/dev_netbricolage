{*

* Do not edit the file if you want to upgrade the module in future.

* 

* @author    Globo Jsc <contact@globosoftware.net>

* @copyright 2017 Globo., Jsc

* @link	     http://www.globosoftware.net

* @license   please read license in file license.txt

*/

*}



{extends file="helpers/form/form.tpl"}

{block name="field"}

    {if $input.type == 'condrimindertype'}

                </div>

            </div>

        </div>

        <div class="condrimindertype">

            {assign var=custormmers value=$fields_value['custormmer']|json_decode:1}

            <div class="productTabs condrimindertype_admintab">

                <ul class="tabs-create tab nav nav-tabs">

            		<li class="tab-row active">

            			<a class="tab-page" href="#tabgeneral">{l s='General' mod='g_cartreminder'}</a>

            		</li>

            		<li class="tab-row">

            			<a class="tab-page" href="#tabcondition">{l s='Condition' mod='g_cartreminder'}</a>

            		</li>

            		<li class="tab-row">

            			<a class="tab-page" href="#tabreminder">{l s='Reminder' mod='g_cartreminder'}</a>

            		</li>

            	</ul>

            </div>

            <div id="condrimindertype_admincontent" class="panel">

                <div id="tabgeneral" class="condrimindertype_tab activetab">

                    <div class="hide">

                        <input class="abadonevalid_rulename" value="{l s='ERROR: Name is empty' mod='g_cartreminder'}"/>

                        <input class="abadonevalid_Condition" value="{l s='ERROR: Condition is empty' mod='g_cartreminder'}"/>

                        <input class="abadonevalid_Reminder" value="{l s='ERROR: Reminder is empty' mod='g_cartreminder'}"/>

                    </div>

                    <div class="form-group">

						<label class="control-label col-lg-3 required">{l s='Name' mod='g_cartreminder'}</label>

						<div class="col-lg-9">

                            <input type="text" name="rulename" id="rulename" value="{$fields_value['rulename']|escape:'htmlall':'UTF-8'}" class="" required="required">

                        </div>

                    </div>

                    <div class="form-group">

                        <label class="control-label col-lg-3">{l s='Status' mod='g_cartreminder'}</label>

						<div class="col-lg-9">

							<span class="switch prestashop-switch fixed-width-lg">

								<input type="radio" name="active" id="active_on" value="1" {if isset($fields_value['active']) && $fields_value['active'] == 1}checked="checked"{/if}>

    							<label for="active_on">{l s='Yes' mod='g_cartreminder'}</label>

   							    <input type="radio" name="active" id="active_off" value="0" {if isset($fields_value['active']) && $fields_value['active'] == 0}checked="checked"{/if}>

    							<label for="active_off">{l s='No' mod='g_cartreminder'}</label>

    							<a class="slide-button btn"></a>

							</span>

						</div>

                    </div>

                    <div class="form-group1">

                        <label class="control-label col-lg-3">

                            {l s='Active From' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-3">

                            <div class="row1">

                                <div class="input-group">

                                    <input id="gdate_from" type="text" data-hex="true" class="datetimepicker" name="datefrom" value="{$fields_value['datefrom']|escape:'html':'UTF-8'}"/>

                                    <span class="input-group-addon">

                                        <i class="icon-calendar-empty"></i>

                                    </span>

                                </div>

                            </div>

                        </div>

                        <label class="control-label col-lg-2">

                           {l s='To' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-3">

                            <div class="row1">

                                <div class="input-group">

                                    <input id="gdate_to" type="text" data-hex="true" class="datetimepicker" name="dateto" value="{$fields_value['dateto']|escape:'html':'UTF-8'}"/>

                                    <span class="input-group-addon">

                                        <i class="icon-calendar-empty"></i>

                                    </span>

                                </div>

                                <span class="help-block">{l s='Leave the field blank for unlimited active time.' mod='g_cartreminder'}</span>

                            </div>

                        </div>

                        <div class="col-lg-2"></div>

                    </div>

                </div>

                <div id="tabcondition" class="condrimindertype_tab">

                    <p>{l s='The cart must validate the following rules.' mod='g_cartreminder'}</p>

                    <div id="product_restriction_div" style="">

                        <div class="form-group">

                            <label class="control-label col-lg-3">{l s='Min Cart Amount' mod='g_cartreminder'}</label>

                            <div class="col-lg-9">

                                <div class="row">

                                    <div class="controls col-lg-12">

                                        <div class="row">

                                            <div class="input-group gcart-input-group">

                                            {foreach from=$fields_value['Currencies'] item=currencie}

                                                <div class="currencie-field curen-{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] != $fields_value['currency']->id}style="display:none"{/if}>

                                                    <div class="col-lg-4">

                                                        <input type="text" class="mincartamount_{$currencie['id_currency']|escape:'html':'UTF-8'}" name="mincartamount[{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="{if isset($fields_value['mincartamount'][$currencie['id_currency']])}{$fields_value['mincartamount'][$currencie['id_currency']]|escape:'html':'UTF-8'}{else}0{/if}" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                    </div>

                                                    <label class="control-label col-lg-2">{l s='Max Cart Amount' mod='g_cartreminder'}</label>

                                                    <div class="col-lg-4">

                                                        <input type="text" class="maxcartamount_{$currencie['id_currency']|escape:'html':'UTF-8'}" name="maxcartamount[{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="{if isset($fields_value['maxcartamount'][$currencie['id_currency']])}{$fields_value['maxcartamount'][$currencie['id_currency']]|escape:'html':'UTF-8'}{else}0{/if}" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                    </div>

                                                    <div class="col-lg-2">

                                                        <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">

                                                            {$currencie['sign']|escape:'html':'UTF-8'}

                                                            <span class="caret"></span>

                                                        </button>

                                                        <ul class="dropdown-menu">

                                                            {foreach from=$fields_value['Currencies'] item=curren}

                                                                <li><a href="javascript:hideOtherCurreny({$curren['id_currency']|escape:'html':'UTF-8'});" tabindex="-1">{$curren['name']|escape:'html':'UTF-8'}</a></li>

                                                            {/foreach}

                                                        </ul>

                                                    </div>

                                                    <span class="help-block">{l s='Leave this field blank or zero to set no limit' mod='g_cartreminder'}</span>

                                                </div>

                                            {/foreach}

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="form-group">

                            <label class="control-label col-lg-3">{l s='Customer group' mod='g_cartreminder'}</label>

                            <div class="col-lg-3">

                                <table class="table table-bordered">

                                    <thead>

                                        <tr>

                                            <th class="fixed-width-xs"><span class="title_box"><input type="checkbox" name="checkme_group" id="checkme_group" onclick="checkinput(this.id)"/></span></th>

                                            <th class="fixed-width-xs"><span class="title_box">{l s='ID' mod='g_cartreminder'}</span></th><th><span class="title_box">{l s='Group name' mod='g_cartreminder'}</span></th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        {foreach $customer_group key=keycustomer item=itemcustomer}

                                                <tr>

                                                    <td>

                                                        <input type="checkbox" name="custormmers[]" class="groupBox" id="groupBox_{$itemcustomer["id_group"]|escape:'htmlall':'UTF-8'}" value="{$itemcustomer["id_group"]|escape:'htmlall':'UTF-8'}" {if !empty($custormmers)}{if $itemcustomer["id_group"]|escape:'htmlall':'UTF-8'|in_array:$custormmers}checked="checked"{/if}{/if}/>

                                                    </td>

                                                    <td>{$itemcustomer["id_group"]|escape:'htmlall':'UTF-8'}</td>

                                                    <td>

                                                        <label for="groupBox_{$itemcustomer["id_group"]|escape:'htmlall':'UTF-8'}">{$itemcustomer["name"]|escape:'htmlall':'UTF-8'}</label>

                                                    </td>

                                                </tr>

                                        {/foreach}

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="form-group">

                            <label class="control-label col-lg-3"></label>

                            <div class="col-lg-3">

                                <button class="btn btn-link gcart-btn-default gcartshow_extra_country_show" type="button" data-id="#gcartshow_extra_country">{l s='Advanced condition' mod='g_cartreminder'} <i class="icon-angle-up"></i></button>

                            </div>

                        </div>

                        <div class="form-group active" id="gcartshow_extra_country">

                            <input id="hidenumber-groupriminder" type="hidden" data-hex="true" class="hidenumber-groupriminder" name="hidenumber-groupriminder" value='{$fields_value['reminder_group']|@count|escape:'html':'UTF-8'}'/>

                            <div id="product_restriction_div" style="">

                                <table id="country_group_table" class="table">

                                    <tbody>

                                        {if $fields_value['reminder_group']}

                                            {foreach from=$fields_value['reminder_group'] key=keyitem item=reminder_groups}

                                                <tr id="gcart_groups_{$keyitem|escape:'html':'UTF-8'}" class="group-orreminder" data-count-condition="0" data-id-group-condition="{$keyitem|escape:'html':'UTF-8'}">

                                                    <td>

                                                        <div class="gcart-group-{$keyitem|escape:'html':'UTF-8'}">

                                                            <div class="panel">

                                                                <input type="hidden"  value="{$reminder_groups|@count|escape:'html':'UTF-8'}" class="number_group_group"/>

                                                                <table class="gcart-tablegroup" id="gcart-tablegroup-{$keyitem|escape:'html':'UTF-8'}">

                                                                    {if $reminder_groups}

                                                                        {foreach from=$reminder_groups item=reminder_group key=keyitemgroup}

                                                                            <tr class="group-andreminder">

                                                                                <td class=''>

                                                                                    <div class="">

                                                                                        <select class="form-control select_condition_group" data-id="{$keyitem|escape:'html':'UTF-8'}"  data-groupid="{$keyitemgroup|escape:'html':'UTF-8'}" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][type]">

                                                                                            <option value="" {if $reminder_group['type'] == ""}selected="selected"{/if}>{l s='--Choose--' mod='g_cartreminder'}</option>

                                                                                            <optgroup label="{l s='Cart' mod='g_cartreminder'}">

                                                                                                <option value="cart_products" {if $reminder_group['type'] == "cart_products"}selected="selected"{/if}>{l s='Products' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_totalincart" {if $reminder_group['type'] == "cart_totalincart"}selected="selected"{/if}>{l s='Products Total In Cart' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_stockproduct" {if $reminder_group['type'] == "cart_stockproduct"}selected="selected"{/if}>{l s='Product stock' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_stockproducts" {if $reminder_group['type'] == "cart_stockproducts"}selected="selected"{/if}>{l s='Products stock (all in cart)' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_productcat" {if $reminder_group['type'] == "cart_productcat"}selected="selected"{/if}>{l s='Product Category' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_productsupplier" {if $reminder_group['type'] == "cart_productsupplier"}selected="selected"{/if}>{l s='Product Supplier' mod='g_cartreminder'}</option>

                                                                                                <option value="cart_productman" {if $reminder_group['type'] == "cart_productman"}selected="selected"{/if}>{l s='Products Manufacturers' mod='g_cartreminder'}</option>

                                                                                            </optgroup>

                                                                                            <optgroup label="{l s='Customer' mod='g_cartreminder'}">

                                                                                                <option value="customer_email" {if $reminder_group['type'] == "customer_email"}selected="selected"{/if}>{l s='Email' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_language" {if $reminder_group['type'] == "customer_language"}selected="selected"{/if}>{l s='Language' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_aeg" {if $reminder_group['type'] == "customer_aeg"}selected="selected"{/if}>{l s='AGE' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_social" {if $reminder_group['type'] == "customer_social"}selected="selected"{/if}>{l s='Social title' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_newlester" {if $reminder_group['type'] == "customer_newlester"}selected="selected"{/if}>{l s='Newlester' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_register" {if $reminder_group['type'] == "customer_register"}selected="selected"{/if}>{l s='Register Date' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_order" {if $reminder_group['type'] == "customer_order"}selected="selected"{/if}>{l s='Order count' mod='g_cartreminder'}</option>

                                                                                                <option value="customer_country" {if $reminder_group['type'] == "customer_country"}selected="selected"{/if}>{l s='Country address' mod='g_cartreminder'}</option>

                                                                                            </optgroup>

                                                                                        </select>

                                                                                    </div>

                                                                                </td>

                                                                                <td class="group_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}">

                                                                                    <div>

                                                                                        {if $reminder_group['type'] == "cart_products" || $reminder_group['type'] == "cart_productcat" || $reminder_group['type'] == "cart_productsupplier" || $reminder_group['type'] == "cart_productman"|| $reminder_group['type'] == "customer_language"|| $reminder_group['type'] == "customer_social"|| $reminder_group['type'] == "customer_country"}

                                                                                            <a class="btn btn-default" type="button" data-toggle="modal" id="groupselectbox_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}" data-target="#groupselect_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}"><i class="icon-list-ul"></i> {l s='Choose' mod='g_cartreminder'}</a>

                                                                                            <div class="modal fade group-condition-cart" id="groupselect_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="true">

                                                                                                <div class="modal-dialog" role="document">

                                                                                                    <div class="modal-content">

                                                                                                        <div class="modal-header">

                                                                                                            {if $reminder_group['type'] == "cart_products"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Products' mod='g_cartreminder'}</h4>

                                                                                                            {elseif $reminder_group['type'] == "cart_productcat"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Category' mod='g_cartreminder'}</h4>

                                                                                                            {elseif $reminder_group['type'] == "cart_productsupplier"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Supplier' mod='g_cartreminder'}</h4>   

                                                                                                            {elseif $reminder_group['type'] == "cart_productman"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Manufacturers' mod='g_cartreminder'}</h4>

                                                                                                            {elseif $reminder_group['type'] == "customer_language"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Language' mod='g_cartreminder'}</h4>

                                                                                                            {elseif $reminder_group['type'] == "customer_social"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Genders' mod='g_cartreminder'}</h4>

                                                                                                            {elseif $reminder_group['type'] == "customer_country"}

                                                                                                                <h4 class="modal-title" id="maillabale">{l s='Select Coutry' mod='g_cartreminder'}</h4>

                                                                                                            {/if}

                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                                                                                <span aria-hidden="true">×</span>

                                                                                                            </button>

                                                                                                        </div>

                                                                                                        <div class="modal-body">

                                                                                                            <div class="form-group" data-id="{$keyitem|escape:'html':'UTF-8'}" data-groupid="{$keyitemgroup|escape:'html':'UTF-8'}">

                                                                                                                <div class="col-lg-6">

                                                                                                                    <label class="control-label">{l s='Unselected' mod='g_cartreminder'}</label>

                                                                                                                    <div class="input-group group-condition-extra">

                                                                                                                        <input type="text" class="form-control gcart_search_product" placeholder="{l s='Search by ID, name, reference' mod='g_cartreminder'}"  data-id="conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_search"/>

                                                                                                                        <div class="input-group-addon">

                                                                                                                            <i class="icon-search"></i>

                                                                                                                        </div>

                                                                                                                    </div>

                                                                                                                    <select multiple size="10" class="conditionproduct_select_1" id="conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_1">

                                                                                                                        {if $reminder_group['type'] == "cart_products"}

                                                                                                                            {if $products}

                                                                                                                                {foreach from=$products item=product}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($product['id'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$product['id']|escape:'html':'UTF-8'}">&nbsp;{$product['id']|escape:'html':'UTF-8'} - {$product['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($product['id'], $reminder_group['value'])}

                                                                                                                                        <option value="{$product['id']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$product['id']|escape:'html':'UTF-8'} - {$product['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "cart_productcat"}

                                                                                                                            {if $cats}

                                                                                                                                {foreach from=$cats item=cat}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($cat['id_category'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$cat['id_category']|escape:'html':'UTF-8'}">&nbsp;{$cat['id_category']|escape:'html':'UTF-8'} - {$cat['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($cat['id_category'], $reminder_group['value'])}

                                                                                                                                        <option value="{$cat['id_category']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$cat['id_category']|escape:'html':'UTF-8'} - {$cat['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "cart_productsupplier"}

                                                                                                                            {if $suppliers}

                                                                                                                                {foreach from=$suppliers item=supplier}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($supplier['id_supplier'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$supplier['id_supplier']|escape:'html':'UTF-8'}">&nbsp;{$supplier['id_supplier']|escape:'html':'UTF-8'} - {$supplier['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($product['id'], $reminder_group['value'])}

                                                                                                                                        <option value="{$supplier['id_supplier']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$supplier['id_supplier']|escape:'html':'UTF-8'} - {$supplier['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "cart_productman"}

                                                                                                                            {if $manufacturers}

                                                                                                                                {foreach from=$manufacturers item=manufacturer}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($manufacturer['id_manufacturer'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'}">&nbsp;{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'} - {$manufacturer['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($manufacturer['id_manufacturer'], $reminder_group['value'])}

                                                                                                                                        <option value="{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'} - {$manufacturer['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "customer_language"}

                                                                                                                            {if $languages}

                                                                                                                                {foreach from=$languages item=language}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($language['id_lang'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$language['id_lang']|escape:'html':'UTF-8'}">&nbsp;{$language['id_lang']|escape:'html':'UTF-8'} - {$language['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($language['id_lang'], $reminder_group['value'])}

                                                                                                                                        <option value="{$language['id_lang']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$language['id_lang']|escape:'html':'UTF-8'} - {$language['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "customer_social"}

                                                                                                                            {if $genders}

                                                                                                                                {foreach from=$genders item=gender}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($gender['id_gender'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$gender['id_gender']|escape:'html':'UTF-8'}">&nbsp;{$gender['id_gender']|escape:'html':'UTF-8'} - {$gender['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($gender['id_gender'], $reminder_group['value'])}

                                                                                                                                        <option value="{$gender['id_gender']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$gender['id_gender']|escape:'html':'UTF-8'} - {$gender['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {elseif $reminder_group['type'] == "customer_country"}

                                                                                                                            {if $countrys}

                                                                                                                                {foreach from=$countrys item=country}

                                                                                                                                    {if !isset($reminder_group['value']) ||(isset($reminder_group['value']) && !in_array($country['id_country'], $reminder_group['value'])) }

                                                                                                                                        <option value="{$country['id_country']|escape:'html':'UTF-8'}">&nbsp;{$country['id_country']|escape:'html':'UTF-8'} - {$country['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {elseif isset($reminder_group['value']) && in_array($country['id_country'], $reminder_group['value'])}

                                                                                                                                        <option value="{$country['id_country']|escape:'html':'UTF-8'}" selected="selected">&nbsp;{$country['id_country']|escape:'html':'UTF-8'} - {$country['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                    {/if}

                                                                                                                                {/foreach}

                                                                                                                            {/if}

                                                                                                                        {/if}

                                                                                                                    </select>

                                                                                                                    <div class="clearfix">&nbsp;</div>

                                                                                                                    <a class="btn btn-default btn-block" id="conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_add">

                                                                                                                        {l s='Add' mod='g_cartreminder'}

                                                                                                                        <i class="icon-arrow-right"></i>

                                                                                                                    </a>

                                                                                                                </div>

                                                                                                                <div class="col-lg-6">

                                                                                                                    <label class="control-label">{l s='Selected' mod='g_cartreminder'}</label>

                                                                                                                    <div class="input-group group-condition-extra">

                                                                                                                    &nbsp;

                                                                                                                    </div>

                                                                                                                    <select multiple="" size="10"  class="conditionproduct_select_2" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]" id="conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_2">

                                                                                                                       

                                                                                                                    </select>

                                                                                                                    <div class="clearfix">&nbsp;</div>

                                                                                                                    <a class="btn btn-default btn-block" id="conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_remove">

                                                                                                                        <i class="icon-arrow-left"></i>

                                                                                                                        {l s='Remove' mod='g_cartreminder'}

                                                                                                                    </a>

                                                                                                                </div>

                                                                                                            </div>

                                                                                                            <script type="text/javascript">

                                                                                                                $('#conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_remove').click(function() { removeConditionGroupOption(this); updateProductConditionGroupDescription(this); });

                                                                                                                $('#conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_add').click(function() { addConditionGroupOption(this); updateProductConditionGroupDescription(this); });

                                                                                                                $(document).ready(function() { addConditionGroupOption($('#conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_add')); updateProductConditionGroupDescription('#conditionproduct_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_add'); });

                                                                                                            </script>

                                                                                                        </div>

                                                                                                    </div>

                                                                                                </div>

                                                                                            </div>

                                                                                        {elseif $reminder_group['type'] == "cart_totalincart" || $reminder_group['type'] == "cart_stockproduct" || $reminder_group['type'] == "cart_stockproducts" || $reminder_group['type'] == "customer_register"}

                                                                                            <select class="" id="cart_totalincart_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_1" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][reminder]">

                                                                                                <option value="="  {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "="}selected="selected"{/if}>&nbsp;{l s='equal to' mod='g_cartreminder'}</option>

                                                                                                <option value="!=" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "!="}selected="selected"{/if}>&nbsp;{l s='Not equal to' mod='g_cartreminder'}</option>

                                                                                                <option value=">"  {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == ">"}selected="selected"{/if}>&nbsp;{l s='is greater than' mod='g_cartreminder'}</option>

                                                                                                <option value=">=" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == ">="}selected="selected"{/if}>&nbsp;{l s='is greater than or equal to' mod='g_cartreminder'}</option>

                                                                                                <option value="<"  {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "<"}selected="selected"{/if}>&nbsp;{l s='is less than' mod='g_cartreminder'}</option>

                                                                                                <option value="<=" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "<="}selected="selected"{/if}>&nbsp;{l s='is less than or equal to' mod='g_cartreminder'}</option>

                                                                                            </select>

                                                                                        {elseif $reminder_group['type'] == "customer_email"}

                                                                                            <select class="" id="cart_totalincart_select_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_1" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][reminder]">

                                                                                                <option value="1" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "1"}selected="selected"{/if}>&nbsp;{l s='Is' mod='g_cartreminder'}</option>

                                                                                                <option value="2" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "2"}selected="selected"{/if}>&nbsp;{l s='Is not' mod='g_cartreminder'}</option>

                                                                                                <option value="3" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "3"}selected="selected"{/if}>&nbsp;{l s='contains' mod='g_cartreminder'}</option>

                                                                                                <option value="4" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "4"}selected="selected"{/if}>&nbsp;{l s='does not contain' mod='g_cartreminder'}</option>

                                                                                                <option value="5" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "5"}selected="selected"{/if}>&nbsp;{l s='starts with' mod='g_cartreminder'}</option>

                                                                                                <option value="6" {if isset($reminder_group['reminder']) && $reminder_group['reminder'] == "6"}selected="selected"{/if}>&nbsp;{l s='ends with' mod='g_cartreminder'}</option>

                                                                                            </select>

                                                                                        {elseif $reminder_group['type'] == "customer_newlester"}

                                                                                            <div class="col-lg-9">

                                                                                                <span class="switch prestashop-switch fixed-width-lg">

                                                                                                    <input type="radio" id="radio_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_on" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]" value="1" {if isset($reminder_group['value']) && $reminder_group['value'][0] == "1"}checked="checked"{/if}>

                                                                                                    <label for="radio_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_on">{l s='Yes' mod='g_cartreminder'}</label>

                                                                                                    <input type="radio" id="radio_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_off" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]" value="0" {if isset($reminder_group['value']) && $reminder_group['value'][0] == "0"}checked="checked"{/if}>

                                                                                                    <label for="radio_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_off" id="">{l s='No' mod='g_cartreminder'}</label>

                                                                                                    <a class="slide-button btn"></a>

                                                                                                </span>

                                                                                            </div>

                                                                                        {else}

                                                                                            <input type="text" disabled="disabled" />

                                                                                        {/if}

                                                                                    </div>

                                                                                </td>

                                                                                <td class="group_selectval_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}">

                                                                                    <div class="">

                                                                                        {if $reminder_group['type'] == "cart_products" || $reminder_group['type'] == "cart_productcat" || $reminder_group['type'] == "cart_productsupplier_value" || $reminder_group['type'] == "cart_productsupplier" || $reminder_group['type'] == "cart_productman" || $reminder_group['type'] == "customer_language" || $reminder_group['type'] == "customer_social" || $reminder_group['type'] == "customer_country"}

                                                                                            {if isset($reminder_group['value']) && $reminder_group['value']|@count == 1}

                                                                                                {if $reminder_group['type'] == "cart_products"}

                                                                                                    {if $products}

                                                                                                        {foreach from=$products item=product}

                                                                                                            {if in_array($product['id'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$product['id']|escape:'html':'UTF-8'} - {$product['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "cart_productcat"}

                                                                                                    {if $cats}

                                                                                                        {foreach from=$cats item=cat}

                                                                                                            {if in_array($cat['id_category'], $reminder_group['value']) }

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$cat['id_category']|escape:'html':'UTF-8'} - {$cat['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "cart_productsupplier"}

                                                                                                    {if $suppliers}

                                                                                                        {foreach from=$suppliers item=supplier}

                                                                                                            {if in_array($supplier['id_supplier'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$supplier['id_supplier']|escape:'html':'UTF-8'} - {$supplier['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "cart_productman"}

                                                                                                    {if $manufacturers}

                                                                                                        {foreach from=$manufacturers item=manufacturer}

                                                                                                            {if in_array($manufacturer['id_manufacturer'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$manufacturer['id_manufacturer']|escape:'html':'UTF-8'} - {$manufacturer['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "customer_language"}

                                                                                                    {if $languages}

                                                                                                        {foreach from=$languages item=language}

                                                                                                            {if in_array($language['id_lang'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$language['id_lang']|escape:'html':'UTF-8'} - {$language['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "customer_social"}

                                                                                                    {if $genders}

                                                                                                        {foreach from=$genders item=gender}

                                                                                                            {if in_array($gender['id_gender'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$gender['id_gender']|escape:'html':'UTF-8'} - {$gender['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {elseif $reminder_group['type'] == "customer_country"}

                                                                                                    {if $countrys}

                                                                                                        {foreach from=$countrys item=country}

                                                                                                            {if in_array($country['id_country'], $reminder_group['value'])}

                                                                                                                <input type="text" disabled="disabled" value="&nbsp;{$country['id_country']|escape:'html':'UTF-8'} - {$country['name']|escape:'html':'UTF-8'}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                                                {break}

                                                                                                            {/if}

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                {/if}

                                                                                            {else}

                                                                                                <input type="text" disabled="disabled" value="{if isset($reminder_group['value'])}{$reminder_group['value']|@count|escape:'html':'UTF-8'}{else}0{/if}" id="conditionproduct_{$keyitem|escape:'html':'UTF-8'}_{$keyitemgroup|escape:'html':'UTF-8'}_math"/>

                                                                                            {/if}

                                                                                        {elseif $reminder_group['type'] == "cart_totalincart" || $reminder_group['type'] == "cart_stockproduct" || $reminder_group['type'] == "cart_stockproducts"}

                                                                                            <input type="number" class="form-control" value="{if isset($reminder_group['value'])}{$reminder_group['value'][0]|escape:'html':'UTF-8'}{else}0{/if}"  name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]"/>

                                                                                        

                                                                                        {elseif $reminder_group['type'] == "customer_register"}

                                                                                            <div class="controls input-group">

                                                                                                <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>

                                                                                                <input class="form-control" type="number" min="0" name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]" value="{if isset($reminder_group['value'])}{$reminder_group['value'][0]|escape:'html':'UTF-8'}{else}0{/if}" size="40" placeholder="0" onkeypress="return isNumberKey(event)">

                                                                                            </div>

                                                                                        {elseif $reminder_group['type'] == "customer_email"}

                                                                                            <input type="text" class="form-control" value="{if isset($reminder_group['value'])}{$reminder_group['value'][0]|escape:'html':'UTF-8'}{else}0{/if}"  name="condition_group[{$keyitem|escape:'html':'UTF-8'}][{$keyitemgroup|escape:'html':'UTF-8'}][value][]"/>

                                                                                        {else}

                                                                                            <input type="text" disabled="disabled" />

                                                                                        {/if}

                                                                                    </div>

                                                                                </td>

                                                                                <td>

                                                                                    <a class="btn btn-default gcartrmove_group pull-right"><i class="icon-times-circle text-danger"></i></a>

                                                                                </td>

                                                                            </tr>

                                                                        {/foreach}

                                                                    {/if}

                                                                </table>

                                                                <div class="action-table-group">

                                                                    <a class="btn btn-default addgcart_group" data-id="{$keyitem|escape:'html':'UTF-8'}" data-groupid="0"><i class="icon-plus-sign"></i> {l s='Add' mod='g_cartreminder'}</a>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </td>

                                                    <td>

                                                        <a class="btn btn-default gcartrmove_groups pull-right" data-id="{$keyitem|escape:'html':'UTF-8'}"><i class="icon-trash text-danger"></i></a>

                                                    </td> 

                                                </tr>

                                            {/foreach}

                                        {/if}

                                    </tbody>

                                </table>

                                <button class="btn btn-default addNewreminderGroupextra" type="button" >

                                    <i class="icon-plus-sign"></i> {l s='Add Condition Group' mod='g_cartreminder'}

                                </button>

                            </div>

                        </div>

                	</div>

                </div>

                <div id="tabreminder" class="condrimindertype_tab">

                    <div id="product_restriction_div" style="">

                        <input id="hidereminder" type="text" data-hex="true" class="hidereminder" name="countreminder" value='{$fields_value['countreminder']|escape:'htmlall':'UTF-8'}'/>

                		<table id="reminder_group_table" class="table">

                    		<tbody>

                                <tr class="reminder_tr">

        						</tr>

                                {if !empty($fields_value['reminder'])}

                                    {foreach from=$fields_value['reminder'] key=keyreminder item=itemreminder}

                                        <tr class="reminder_tr_td">

                                            <td><span class="badge pull-left" id="id_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}">{$keyreminder|escape:'htmlall':'UTF-8'}</span></td>

                                            <td>

                                                <div class="form-group">

                                                    <label class="control-label">

                                                        <span class="label-text" data-toggle="tooltip" data-html="true" title="">{l s='Email template' mod='g_cartreminder'}</span>

                                                    </label>

                                                </div>

                                                <div class="form-group">

                                                    <select class="c-select" id="id_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}_reminder" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][id_emailtemplate]">

                                                        {if !empty($array_listemail)}

                                                            {foreach $array_listemail item=itememailteplate}

                                                                <option value="{$itememailteplate['id_gaddnewemail_template']|escape:'htmlall':'UTF-8'}" {if $itemreminder['id_emailtemplate'] == $itememailteplate['id_gaddnewemail_template']}selected="selected"{/if}>{$itememailteplate["template_name"]|escape:'htmlall':'UTF-8'}</option>

                                                            {/foreach}

                                                        {/if}

                                                    </select>

                                                </div>

                                            </td>

                                            <td>

                                                <div class="form-group">

                                                    <label class="control-label">

                                                        <span class="label-text" data-toggle="tooltip" data-html="true" title="">{l s='Frequency' mod='g_cartreminder'}</span>

                                                    </label>

                                                </div>

                                                <div class="form-group">

                                                    <div class="col-lg-3">

                                                        <div class="row">

                                                            <div class="controls input-group">

                                                                <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>

                                                                <input class="form-control" id="id_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}_gday" type="number" min="0" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][gday]" value="{$itemreminder['gday']|escape:'htmlall':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-lg-3">

                                                            <div class="row">

                                                                <div class="controls input-group">

                                                                    <div class="input-group-addon">{l s='Hrs' mod='g_cartreminder'}</div>

                                                                    <input class="form-control" id="id_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}_ghrs" type="number" min="0" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][ghrs]" value="{$itemreminder['ghrs']|escape:'htmlall':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-lg-2 last_number" >

                                                            <div class="row">

                                                                <div class="controls input-group">

                                                                    <input class="form-control" id="id_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}_number" type="text" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][number]" value="{$itemreminder['number']|escape:'htmlall':'UTF-8'}"/>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>

                                                <td>

                                                    <div class="form-group">

                                                        <label class="control-label">

                                                            <span class="label-text" data-toggle="tooltip" data-html="true" title="">{l s='Discount' mod='g_cartreminder'}</span>

                                                        </label>

                                                    </div>

                                                    <div class="form-group">

                                                        <button type="button" class="btn btn-default" data-toggle="modal" id="discountbutton_{$keyreminder|escape:'htmlall':'UTF-8'}" data-target="#discount_{$keyreminder|escape:'htmlall':'UTF-8'}">{l s='Set Discount' mod='g_cartreminder'}</button>

                                                        <div class="modal fade" id="discount_{$keyreminder|escape:'htmlall':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="true">

                                                            <div class="modal-dialog" role="document">

                                                                <div class="modal-content">

                                                                    <div class="modal-header">

                                                                        <div>

                                                                            <h4 class="modal-title" id="maillabale">

                                                                                {l s='Discount' mod='g_cartreminder'}

                                                                            </h4>

                                                                            <p>{l s='Discount code will be created automatically and sent to customer via email.' mod='g_cartreminder'}</p>

                                                                        </div>

                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                                            <span aria-hidden="true">×</span>

                                                                        </button>

                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="form-group">

                                                                            <div class="col-lg-12">

                                                                                <div class="form-group">

                                                                                    <label class="control-label">{l s='Discount Type' mod='g_cartreminder'}</label>

                                                                                    <div class="col-lg-12 gcart-nonepadding">

                                                                                        <div class="radio col-lg-3">

                                                                                            <label for="gpercentage_{$keyreminder|escape:'htmlall':'UTF-8'}" id="labale_on_{$keyreminder|escape:'htmlall':'UTF-8'}">

                                                                                                <input type="radio" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][discounttype]" id="gpercentage_{$keyreminder|escape:'htmlall':'UTF-8'}" value="0" {if $itemreminder['discounttype'] == '0'}checked="checked" {/if}  onclick="showdiscount(this.id)" data="{$keyreminder|escape:'htmlall':'UTF-8'}" />

                                                                                                {l s='Percentage' mod='g_cartreminder'} (%)

                                                                                            </label>

                                                                                        </div>

                                                                                        <div class="radio col-lg-3">

                                                                                            <label for="gfixed_{$keyreminder|escape:'htmlall':'UTF-8'}" id="labale_off_{$keyreminder|escape:'htmlall':'UTF-8'}">

                                                                                                <input type="radio" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][discounttype]" id="gfixed_{$keyreminder|escape:'htmlall':'UTF-8'}" value="1" {if $itemreminder['discounttype'] == '1'}checked="checked" {/if} onclick="showdiscount(this.id)" data="{$keyreminder|escape:'htmlall':'UTF-8'}" />

                                                                                            {l s='Amount' mod='g_cartreminder'}</label>

                                                                                        </div>

                                                                                        <div class="radio col-lg-3">

                                                                                            <label for="gnonedc_{$keyreminder|escape:'htmlall':'UTF-8'}" id="labale_none_{$keyreminder|escape:'htmlall':'UTF-8'}">

                                                                                                <input type="radio" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][discounttype]" id="gnonedc_{$keyreminder|escape:'htmlall':'UTF-8'}" value="2" {if $itemreminder['discounttype'] == '2'}checked="checked" {/if} onclick="nonediscount(this.id)" data="{$keyreminder|escape:'htmlall':'UTF-8'}" /><i class="icon-remove color_danger"></i> {l s='None' mod='g_cartreminder'}</label>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="form-group noneform_{$keyreminder|escape:'htmlall':'UTF-8'}" {if $itemreminder['discounttype'] == '2'} style="display:none;" {/if}>

                                                                                    <input type="hidden" value="{$itemreminder['pricerule']|@count|escape:'html':'UTF-8'}" class="discount_number_{$keyreminder|escape:'html':'UTF-8'}">

                                                                                    <div class="col-lg-12">

                                                                                        <div class="row">

                                                                                            <table class="col-lg-12" data-id="{$keyreminder|escape:'html':'UTF-8'}">

                                                                                                <thead>

                                                                                                    <tr>

                                                                                                        <th><label class="control-label">{l s='Price Range' mod='g_cartreminder'}</label></th>

                                                                                                        <th><label class="control-label">{l s='Discount Value' mod='g_cartreminder'}</label></th>

                                                                                                        <th></th>

                                                                                                    </tr>

                                                                                                </thead>

                                                                                                <tbody>

                                                                                                    {if $itemreminder['pricerule']}

                                                                                                        {foreach from=$itemreminder['pricerule'] key=id_group item=pricerule}

                                                                                                            <tr>

                                                                                                                <td>

                                                                                                                    <div class="input-group gcart-input-group">

                                                                                                                        {foreach from=$fields_value['Currencies'] item=currencie}

                                                                                                                            <div class="currencie-field curen-{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] != $fields_value['currency']->id}style="display:none"{/if}>

                                                                                                                                <div class="col-lg-4 gcart-nonepadding">

                                                                                                                                    <input type="text" name="jsreminder[{$keyreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][minprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="{$pricerule['minprice'][$currencie['id_currency']]|escape:'html':'UTF-8'}" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                                                                                                </div>

                                                                                                                                <label class="control-label col-lg-1"><i class="icon-minus"></i></label>

                                                                                                                                <div class="col-lg-4">

                                                                                                                                    <input type="text" name="jsreminder[{$keyreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][maxprice][{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="{$pricerule['maxprice'][$currencie['id_currency']]|escape:'html':'UTF-8'}" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>

                                                                                                                                </div>

                                                                                                                                <div class="col-lg-2">

                                                                                                                                    <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">

                                                                                                                                        {$currencie['sign']|escape:'html':'UTF-8'}

                                                                                                                                        <span class="caret"></span>

                                                                                                                                    </button>

                                                                                                                                    <ul class="dropdown-menu">

                                                                                                                                        {foreach from=$fields_value['Currencies'] item=curren}

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

                                                                                                                            <input class="form-control" id="gdiscountvalue_{$keyreminder|escape:'html':'UTF-8'}" type="number" min="0" name="jsreminder[{$keyreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][discountvalue]" value="{$pricerule['discountvalue']|escape:'html':'UTF-8'}" size="40" placeholder="0">

                                                                                                                        </div>

                                                                                                                        <div class="controls col-lg-4 nonediscounttype_price {if $itemreminder['discounttype'] == '1'} show {/if}">

                                                                                                                            <select name="jsreminder[{$keyreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][reduction_currency]">

                                                                                                                                {foreach from=$fields_value['Currencies'] item=currencie}

                                                                                                                                    <option value="{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $pricerule['reduction_currency'] == $currencie['id_currency']}selected="selected"{/if}>{$currencie['name']|escape:'html':'UTF-8'}</option>

                                                                                                                                {/foreach}

                                                                                                                            </select>

                                                                                                                        </div>

                                                                                                                        <div class="controls col-lg-4 nonediscounttype_price {if $itemreminder['discounttype'] == '1'} show {/if}">

                                                                                                                            <select name="jsreminder[{$keyreminder|escape:'html':'UTF-8'}][pricerule][{$id_group|escape:'html':'UTF-8'}][reduction_tax]">

                                                                                                                                <option value="0"  {if $pricerule['reduction_tax'] == "0"}selected="selected"{/if}>{l s='Tax excluded' mod='g_cartreminder'}</option>

                                                                                                                                <option value="1" {if $pricerule['reduction_tax'] == "1"}selected="selected"{/if}>{l s='Tax included' mod='g_cartreminder'}</option>

                                                                                                                            </select>

                                                                                                                        </div>

                                                                                                                    </div>

                                                                                                                </td>

                                                                                                                <td>

                                                                                                                    <a class="btn btn-default gcartrmove_discountgroup pull-right"><i class="icon-times-circle text-danger"></i></a>

                                                                                                                </td>

                                                                                                            </tr>

                                                                                                        {/foreach}

                                                                                                    {/if}

                                                                                                </tbody>

                                                                                            </table>

                                                                                            <div class="action-table-group">

                                                                                                <a class="btn btn-default addgcart_discount" data-id="{$keyreminder|escape:'html':'UTF-8'}"><i class="icon-plus-sign"></i> {l s='New Discount' mod='g_cartreminder'}</a>

                                                                                            </div>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="form-group noneform_{$keyreminder|escape:'htmlall':'UTF-8'}" {if $itemreminder['discounttype'] == '2'} style="display:none;" {/if}>

                                                                                    <label class="control-label">{l s='Coupon Validity' mod='g_cartreminder'}</label>

                                                                                    <div class="col-lg-12 gcart-nonepadding">

                                                                                        <div class="row">

                                                                                            <div class="controls col-lg-4 input-group" style="padding-left:5px;">

                                                                                                <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>

                                                                                                <input class="form-control" id="gvalidity_{$keyreminder|escape:'htmlall':'UTF-8'}" type="number" min="0" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][counponvalidity]" value="{$itemreminder['counponvalidity']|escape:'htmlall':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKeyend(event)"/>

                                                                                            </div>

                                                                                        </div>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="form-group noneform_{$keyreminder|escape:'htmlall':'UTF-8'}" {if $itemreminder['discounttype'] == '2'} style="display:none;" {/if}>

                                                                                    <label class="control-label">{l s='Free shipping' mod='g_cartreminder'}</label>

                                                                                    <div class="col-lg-12 gcart-nonepadding">

                                                                                        <span class="switch prestashop-switch fixed-width-lg">

                                                                                            <input type="radio" id="gfreeship_on_{$keyreminder|escape:'htmlall':'UTF-8'}" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][freeshipping]" value="1" {if $itemreminder['freeshipping'] == '1'}checked="checked" {/if} />

                                                                                            <label for="gfreeship_on_{$keyreminder|escape:'htmlall':'UTF-8'}" id="onfree_{$keyreminder|escape:'htmlall':'UTF-8'}">{l s='Yes' mod='g_cartreminder'}</label>

                                                                                            <input type="radio" id="gfreeship_off_{$keyreminder|escape:'htmlall':'UTF-8'}" name="jsreminder[{$keyreminder|escape:'htmlall':'UTF-8'}][freeshipping]" value="0" {if $itemreminder['freeshipping'] == '0'}checked="checked" {/if} />

                                                                                            <label for="gfreeship_off_{$keyreminder|escape:'htmlall':'UTF-8'}" id="offfree_{$keyreminder|escape:'htmlall':'UTF-8'}">{l s='No' mod='g_cartreminder'}</label>

                                                                                            <a class="slide-button btn"></a>

                                                                                        </span>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="modal-footer">

                                                                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">{l s='Close' mod='g_cartreminder'}</button>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </td>

                                                <td>

                                                    <a class="btn btn-default" id="remove_reminder_{$keyreminder|escape:'htmlall':'UTF-8'}" data="{$keyreminder|escape:'htmlall':'UTF-8'}" onclick="removereminder(this.id)">

                                                    <i class="icon-trash text-danger"></i>

                                                    </a>

                                                </td>

                                            </tr>

                                    {/foreach}

                                {/if}				

                            </tbody>

                        </table>

        				<a class="btn btn-default addNewreminderGroup">

        					<i class="icon-plus-sign"></i> {l s='New reminder' mod='g_cartreminder'}

        				</a>

                	</div>

                </div>

                <div class="panel-footer">

                    <a href="{$link->getAdminLink('AdminGcartremindercondreminder')|escape:'html':'UTF-8'}" type="submit" class="btn btn-default" onclick="window.history.back();"><i class="process-icon-cancel"></i> {l s=' Cancel' mod='g_cartreminder'}</a>

                    <button type="submit" class="btn btn-default pull-right" name="submitGcartremindercondreminder"><i class="process-icon-save"></i> {l s='Save' mod='g_cartreminder'}</button>

                </div>

            </div>

        </div>

    {/if}

    {$smarty.block.parent}

{/block}