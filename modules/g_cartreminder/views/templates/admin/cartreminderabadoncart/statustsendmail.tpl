{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<!-- Button trigger modal -->
{if $status_cart == 'Send Mail'}
    {assign var=configvalue value=$arraycart["datacfigsend"]|json_decode:1}
    {assign var=configcode value=$arraycart["datacfigcode"]|json_decode:1}
    <div class="hide">
        <textarea class="objectemployee">{$getemployees|escape:'quotes'}</textarea>{* $getemployees is html content, no need to escape*}
        <input class="gcartvalid_sendmailsuccessful" value="{l s='Send Mail Successful' mod='g_cartreminder'}"/>
        <input class="gcartvalid_sendmailnotsuccessful" value="{l s='ERROR: There is error while sending email.' mod='g_cartreminder'}"/>  
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
        {l s='Send Mail' mod='g_cartreminder'}
    </button>
    <!-- Modal -->
    <div class="modal fade" id="mail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="true">
        <div class="modal-dialog modal-cssemail" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="maillabale">{l s='Send Reminder Email by Manually' mod='g_cartreminder'}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-lg-6">
                            <label class="control-label">{l s='To' mod='g_cartreminder'}</label>
                            <input type="button" id="hideinputstatusemail" class="form-control hideemail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="{$arraycart["email"]|escape:'htmlall':'UTF-8'}" />
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label">{l s='From' mod='g_cartreminder'}</label>
                            <select name="employee" class="employee_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                {foreach $getemployee item=getemployees}
                                    <option value="{$getemployees["id_employee"]|escape:'htmlall':'UTF-8'}" {if $configvalue['from'] == $getemployees["id_employee"]|escape:'htmlall':'UTF-8'} selected="selected"{/if}>{$getemployees["email"]|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">{l s='Subject' mod='g_cartreminder'}</label>
                            <input type="text" class="form-control subjectemail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="{$configvalue['subject']|escape:'htmlall':'UTF-8'}"/>
                            <p class="help-block">{l s='You can insert the variables to get customer infromation:' mod='g_cartreminder'}<code>{l s='{customer_firstname}, {customer_lastname}' mod='g_cartreminder'}</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">{l s='Custom message for this customer' mod='g_cartreminder'}</label>
                            <textarea cols="15" rows="5" class="textarea-autosize custommessage_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">{$configvalue['message']|escape:'htmlall':'UTF-8'}</textarea>
                            <p class="help-block">{l s='You email template must have variable: ' mod='g_cartreminder'}<code>{l s='{custom_message}' mod='g_cartreminder'}</code>{l s=', If you want to insert the custom message to reminder email.' mod='g_cartreminder'}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">{l s='Select email template' mod='g_cartreminder'}</label>
                            <select name="emailtemplate" class="emailtemplate_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                {foreach $emailtemplates item=emailtemplate}
                                    <option value="{$emailtemplate["id_gaddnewemail_template"]|escape:'htmlall':'UTF-8'}" {if $configvalue['id_templateemail'] == $emailtemplate["id_gaddnewemail_template"]}selected="selected"{/if}>{$emailtemplate["template_name"]|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                            {foreach $emailtemplates item=itememailtemplate}
                                <input type="text" id="hidesb_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_{$itememailtemplate["id_gaddnewemail_template"]|escape:'htmlall':'UTF-8'}" class="hide_sb" value="{$itememailtemplate["subject"]|escape:'htmlall':'UTF-8'}" style="display:none;"/>
                                <textarea id="hide_inputitem_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_{$itememailtemplate["id_gaddnewemail_template"]|escape:'htmlall':'UTF-8'}" class="textarea-autosize hideinputitem" data="{$itememailtemplate["id_gaddnewemail_template"]|escape:'htmlall':'UTF-8'}" style="display:none;">{$itememailtemplate["email_htmllang"]}{*Escape is unnecessary*}</textarea>
                            {/foreach}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <div class="col-lg-2">
                                <label class="control-label">
                					{l s='Send bcc to' mod='g_cartreminder'}:
                				</label>
                            </div>
                            <div class="col-lg-9">
                                {assign var=bccs value=", "|explode:$configvalue['bcc']}
                                {foreach $getemployee item=getemployees}
            					   <div class="checkbox">
                                        <label for="sendto_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_{$getemployees["id_employee"]|escape:'htmlall':'UTF-8'}">
            							     <input type="checkbox" name="sendto_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}[]" id="sendto_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_{$getemployees["id_employee"]|escape:'htmlall':'UTF-8'}" value="{$getemployees["id_employee"]|escape:'htmlall':'UTF-8'}" {if $getemployees["id_employee"]|in_array:$bccs} checked="checked" {/if}/>
            							     {$getemployees["email"]|escape:'htmlall':'UTF-8'}
                                        </label>
            					   </div>
                                {/foreach}                          
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="control-label">
            					<b>{l s='Generate Discount Code' mod='g_cartreminder'}:</b>
            				</label>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Discount Type' mod='g_cartreminder'}</label>
                                <div class="col-lg-8">
                                    <div class="radio col-lg-4">
                                        <label for="gpercentage_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="labale_on_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                            {l s='Percentage' mod='g_cartreminder'} (%)<input type="radio" name="discounttype_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="gpercentage_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="0" {if $configcode["typediscount"] == 0 }checked="checked"{/if} onclick="showdiscount(this.id)" data="{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" />
                                        </label>
                                    </div>
                                    <div class="radio col-lg-4">
                                        <label for="gfixed_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="labale_off_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                            <input type="radio" name="discounttype_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="gfixed_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="1" {if $configcode["typediscount"] == 1}checked="checked"{/if} onclick="showdiscount(this.id)" data="{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" />
                                            {l s='Amount' mod='g_cartreminder'}
                                        </label>
                                    </div>
                                    <div class="radio col-lg-4">
                                        <label for="gnonedc_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="labale_none_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                            <input type="radio" name="discounttype_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" id="gnonedc_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="2" onclick="nonediscount(this.id)" data="{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" {if $configcode["typediscount"] == 2}checked="checked"{/if}/>
                                                <i class="icon-remove color_danger"></i>
                                             {l s='None' mod='g_cartreminder'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group noneform_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" {if $configcode["typediscount"] == '2'} style="display:none;" {/if}>
                                <label class="control-label col-lg-3">{l s='Discount Value' mod='g_cartreminder'}</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="controls col-lg-5">
                                            <input class="form-control" id="gdiscountvalue_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" type="number" min="0" name="discountvalue_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="{$configcode["valuediscount"]|escape:'htmlall':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>
                                        </div>
                                        <div class="col-lg-3 nonediscounttype_price {if $configcode["typediscount"] == '1'} show {/if}">
                                            <select name="reduction_currency_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                                {foreach from=$Currencies item=currencie}
                                                    <option value="{$currencie['id_currency']|escape:'html':'UTF-8'}" {if isset($configcode["reduction_currency"]) && $currencie['id_currency'] ==  $configcode["reduction_currency"]}selected="selected"{/if}>{$currencie['name']|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="col-lg-4 nonediscounttype_price {if $configcode["typediscount"] == '1'} show {/if}">
                                            <select name="reduction_tax_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                                                <option value="0" {if isset($configcode["reduction_tax"]) && $configcode["reduction_tax"] == 0}selected="selected"{/if}>{l s='Tax excluded' mod='g_cartreminder'}</option>
                                                <option value="1" {if isset($configcode["reduction_tax"]) && $configcode["reduction_tax"] == 1}selected="selected"{/if}>{l s='Tax included' mod='g_cartreminder'}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group noneform_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" {if $configcode["typediscount"] == '2'} style="display:none;" {/if}>
                                <label class="control-label col-lg-3">{l s='Coupon Validity' mod='g_cartreminder'}</label>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="controls col-lg-4 input-group" style="padding-left:5px;">
                                            <input class="form-control" id="gvalidity_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" type="number" min="0" name="counponvalidity_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="{$configcode["validitydiscount"]|escape:'htmlall':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKeyend(event)" />
                                            <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group noneform_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" {if $configcode["typediscount"] == '2'} style="display:none;" {/if}>
                                <label class="control-label col-lg-3">{l s='Free shipping' mod='g_cartreminder'}</label>
                                <div class="col-lg-9">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" id="gfreeship_on_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" name="freeshipping_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="1" {if $configcode["freeship"] ==1}checked="checked"{/if} />
                                        <label for="gfreeship_on_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">{l s='Yes' mod='g_cartreminder'}</label>
                                        <input type="radio" id="gfreeship_off_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" name="freeshipping_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" value="0" {if $configcode["freeship"] ==0}checked="checked"{/if} />
                                        <label for="gfreeship_off_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">{l s='No' mod='g_cartreminder'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-lg" id="reviewemail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" data="{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" onclick="reviewemail(this.id)" data-toggle="modal" data-target="#Modal_review_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" data-dismiss="modal">
                        {l s='Review email' mod='g_cartreminder'}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="Modal_review_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="Modal_review_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_Label" aria-hidden="true">
        <div class="modal-dialog modal-cssemail" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="Modal_review_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}_Label">{l s='Send a cart recovery email' mod='g_cartreminder'}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                  <td class="col-lg-2 pointer"><label class="control-label">{l s='From' mod='g_cartreminder'}:</label></td>
                                  <td class="control-label" id="showfrom_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" style="text-align: left;"></td>
                                </tr>
                                <tr>
                                  <td><label class="control-label">{l s='To' mod='g_cartreminder'}:</label></td>
                                  <td class="control-label" id="showto_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" style="text-align: left;"></td>
                                </tr>
                                <tr>
                                  <td><label class="control-label">{l s='Subject' mod='g_cartreminder'}:</label></td>
                                  <td class="control-label" id="showsubject_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" style="text-align: left;"></td>
                                </tr>
                                <tr>
                                  <td><label class="control-label">{l s='Bcc' mod='g_cartreminder'}:</label></td>
                                  <td class="control-label" id="showbcc_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" style="text-align: left;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group showtemplatemail" id="showemail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal" data-toggle="modal" data-target="#mail_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" style="float: left;">{l s='Back' mod='g_cartreminder'}</button>
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">{l s='Cancel' mod='g_cartreminder'}</button>
                    <button type="button" class="btn btn-primary btn-lg" id="send_{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" data="{$arraycart["id_cart"]|escape:'htmlall':'UTF-8'}" data-dismiss="modal" onclick="send(this.id)">{l s='Send Reminder Email' mod='g_cartreminder'}</button>
                </div>
            </div>
        </div>
    </div>
    
{/if}