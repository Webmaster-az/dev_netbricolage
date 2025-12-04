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
{block name="script"}
var copyToClipboard_success = "{l s='Copy to clipboard successfully' mod='g_cartreminder'}";
var link_gcart_preview = "{$fields_value['url']|escape:'htmlall':'UTF-8'}";
{/block}
{block name="field"}
    {if $input.type == 'email_template'}
        <div class="col-lg-9">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Template name' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    <div class="col-lg-10">
                        <input type="text" name="template_name" id="template_name" value="{$fields_value['template_name']|escape:'htmlall':'UTF-8'}" class="" required="required">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Subject' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        {/if}
                            <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">
                                <input type="text" id="subjectlang_{$language.id_lang|escape:'html':'UTF-8'}" name="subjectlang_{$language.id_lang|escape:'html':'UTF-8'}" class="" value="{$fields_value['subjectlang'][{$language.id_lang|escape:'html':'UTF-8'}]|escape:'htmlall':'UTF-8'}" onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();">
                            </div>
                        {if $languages|count > 1}
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                    {$language.iso_code|escape:'html':'UTF-8'}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        {/if}
                        {if $languages|count > 1}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Email template editor' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        {/if}
                            <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">
                                <textarea type="text" id="email_htmllang_{$language.id_lang|escape:'html':'UTF-8'}" name="email_htmllang_{$language.id_lang|escape:'html':'UTF-8'}" class="rte autoload_rte gautoload_rte" onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();">
                                    {$fields_value['email_htmllang'][{$language.id_lang|escape:'html':'UTF-8'}]|escape:'htmlall':'UTF-8'}
                                </textarea>
                            </div>
                        {if $languages|count > 1}
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                    {$language.iso_code|escape:'html':'UTF-8'}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        {/if}
                        {if $languages|count > 1}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <div class="col-lg-12">
                        <button class="btn btn-default" type="button" id="autoconvert">
                            <span class="ladda-label">
                                <i class="icon-magic"></i>{l s='Convert email template to txt format' mod='g_cartreminder'}
                            </span>
                            <span class="ladda-spinner"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="hide">
                <input class="g_sendtest" value="{l s='Email sent successfully' mod='g_cartreminder'}"/>
                <input class="g_sendtesterror" value="{l s='There was an error sending the email' mod='g_cartreminder'}"/>
                <input class="abadonedcartvalid_converhtml" value="{l s='Convert email template to txt format successful' mod='g_cartreminder'}"/>
                <input class="abadonedcartvalid_subject" value="{l s='ERROR: Subject is empty' mod='g_cartreminder'}"/>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Email txt format' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        {/if}
                            <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">
                                <textarea type="text" id="email_txtlang_{$language.id_lang|escape:'html':'UTF-8'}" name="email_txtlang_{$language.id_lang|escape:'html':'UTF-8'}" class="autoload_rte content_form_mail"  onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();" style="height:200px">
                                    {$fields_value['email_txtlang'][{$language.id_lang|escape:'html':'UTF-8'}]|escape:'htmlall':'UTF-8'}
                                </textarea>
                            </div>
                        {if $languages|count > 1}
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                    {$language.iso_code|escape:'html':'UTF-8'}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        {/if}
                        {if $languages|count > 1}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"></label>
                <div class="col-lg-3">
                    <button class="btn btn-link gcart-btn-default gcartshow_extra_country_show" type="button" data-id="#gcartshow_extra_country">{l s='Send Test email' mod='g_cartreminder'} <i class="icon-angle-up icon-angle-down"></i></button>
                </div>
            </div>
            <div class="panel1 col-lg-offset-3 col-lg-9 " id="gcartshow_extra_country">
                <div class="form-group">
                    <label class="control-label col-lg-3" for="condition">
                        {l s='Select Id Order Preview Template Email' mod='g_cartreminder'}
                    </label>
                    <div class="col-lg-9">
                        <select name="id_orderpreview" id="id_orderpreview">
                            {foreach $allid_order key=kid item=itid}
                                <option value="{$itid["id_order"]|escape:'html':'UTF-8'}" >{$itid["id_order"]|escape:'html':'UTF-8'}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3">
                        {l s='Send a test email to' mod='g_cartreminder'}
                    </label>
                    <div class="col-lg-9">
                        <textarea name="emailtest_template" id="emailtest_template" class="textarea-autosize"></textarea>
                        <p class="help-block">
                            {l s='Emails are separated by commas.' mod='g_cartreminder'}
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3"></label>
                    <div>
                        <button type="button" class="btn btn-default" id="getidorder_preview">{l s='Send' mod='g_cartreminder'}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="emailshortcode_wp">
                <div class="publish_box emailshortcode_panel">
                    <div class="box-heading">
                        <i class="icon-code"></i>
                        {l s='Variables' mod='g_cartreminder'}
                    </div>
                    <div class="gbox_content">
                        <p class="help-block">
                            {l s='Click to copy variable' mod='g_cartreminder'}
                        </p>
                        <div class="emailshortcode">
                            <table>
                                <tbody>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Customer first name' mod='g_cartreminder'}">{l s='{customer_firstname}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Customer last name' mod='g_cartreminder'}">{l s='{customer_lastname}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add link to your store. E.g: click{shop_link_start} here {shop_link_end}' mod='g_cartreminder'}">{l s='{shop_link_start}{shop_link_end}' mod='g_cartreminder'} </span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add link to cart E.g: click{cart_link_start} here {cart_link_end} to complete your order' mod='g_cartreminder'}">{l s='{cart_link_start}{cart_link_end}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Add link to your store: click<a href="{shop_link_url}">here</a> to access our shop' mod='g_cartreminder'}">{l s='{shop_link_url}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Link to checkout page, step 3.' mod='g_cartreminder'}">{l s='{cart_url}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Link to checkout page, step 1' mod='g_cartreminder'}">{l s='{cart_url_s1}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Link to checkout page, step 2' mod='g_cartreminder'}">{l s='{cart_url_s2}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add coupon code (only if the rule creat a coupon)' mod='g_cartreminder'}">{l s='{voucher_code}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add coupon expiry date' mod='g_cartreminder'}">{l s='{voucher_expirate_date}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add custom message to email when you send reminder by manually' mod='g_cartreminder'}">{l s='{custom_message}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Total product price tax include' mod='g_cartreminder'}">{l s='{total_cart_incl}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Total product price tax exclude' mod='g_cartreminder'}">{l s='{total_cart_excl}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Click to show details' mod='g_cartreminder'}">{l s='{cart_product}' mod='g_cartreminder'}</span> 
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Display list of products in cart. ' mod='g_cartreminder'}">{l s='{cart_product_txt}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product_txt.png">
                                                <i class="icon-eye-open"></i>
                                            </a></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Click to show details' mod='g_cartreminder'}">{l s='{cart_product_1}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product_1.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Display list of products in cart. ' mod='g_cartreminder'}">{l s='{cart_product_txt_1}' mod='g_cartreminder'}</span> 
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product_txt_1.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Click to show details' mod='g_cartreminder'}">{l s='{cart_product_2}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product_2.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Display list of products in cart. ' mod='g_cartreminder'}">{l s='{cart_product_txt_2}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product_txt_2.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {$smarty.block.parent}
{/block}