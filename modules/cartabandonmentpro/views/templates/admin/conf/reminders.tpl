{*
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<input type="hidden" name="cartabandonment_conf" value="1">
<table class="table table-striped" style="border: 0; width: 100%;">
    <thead>
        <tr>
            <th></th>
            <th><center>{l s='Active' mod='cartabandonmentpro'}</center></th>
            <th><center>{l s='Days' mod='cartabandonmentpro'}</center></th>
            <th><center>{l s='Hours' mod='cartabandonmentpro'}</center></th>
            <th><center>{l s='Recommandation' mod='cartabandonmentpro'}</center></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{l s='First reminder' mod='cartabandonmentpro'}</td>
            <td>
                <center>
                    <span class="switch prestashop-switch input-group col-lg-12">
                        <input type="radio" name="1_reminder" id="1_reminder_on" {if $first_reminder_active|intval eq 1}checked="checked"{/if} value="1"/>
                        <label for="1_reminder_on" class="radioCheck" onClick="setActive(1, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 1);">
                            <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                        </label>
                        <input type="radio" name="1_reminder" id="1_reminder_off" value="0" {if $first_reminder_active|intval eq 0}checked="checked"{/if} />
                        <label for="1_reminder_off" class="radioCheck" onClick="setActive(1, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 0);">
                            <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                    <input type="hidden" id="1_reminder" name="1_reminder" value="{$first_reminder_active|intval}" />
                </center>
            </td>
            <td><input {if $first_reminder_active|intval eq 0}disabled="disabled"{/if} type="text" placeholder="" id="first_reminder_days" name="first_reminder_days" value="{$first_reminder_days|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setDays(1, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><input {if $first_reminder_active|intval eq 0}disabled="disabled"{/if} type="text" placeholder="" id="first_reminder_hours" name="first_reminder_hours" value="{$first_reminder_hours|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setHours(1, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><center><b>2 {l s='Hours' mod='cartabandonmentpro'}</b></center></td>
        </tr>
        <tr>
            <td>{l s='Second reminder' mod='cartabandonmentpro'}</td>
            <td>
                <center>
                    <span class="switch prestashop-switch input-group col-lg-12">
                        <input type="radio" name="2_reminder" id="2_reminder_on" {if $second_reminder_active|intval eq 1}checked="checked"{/if} value="1"/>
                        <label for="2_reminder_on" class="radioCheck" onClick="setActive(2, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 1);">
                            <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                        </label>
                        <input type="radio" name="2_reminder" id="2_reminder_off" {if $second_reminder_active|intval eq 0}checked="checked"{/if} value="0" />
                        <label for="2_reminder_off" class="radioCheck" onClick="setActive(2, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 0);">
                            <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                    <input type="hidden" id="2_reminder" name="2_reminder" value="{$second_reminder_active|intval}" />
                </center>
            </td>
            <td><input {if $second_reminder_active|intval eq 0}disabled="disabled"{/if} type="text" placeholder="" id="second_reminder_days" name="second_reminder_days" value="{$second_reminder_days|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setDays(2, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><input {if $second_reminder_active|intval eq 0}disabled="disabled"{/if}type="text" placeholder="" id="second_reminder_hours" name="second_reminder_hours" value="{$second_reminder_hours|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setHours(2, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><center><b>2 {l s='Days' mod='cartabandonmentpro'}</b></center></td>
        </tr>
        <tr>
            <td>{l s='Third reminder' mod='cartabandonmentpro'}</td>
            <td>
                <center>
                    <span class="switch prestashop-switch input-group col-lg-12">
                        <input type="radio" name="3_reminder" id="3_reminder_on" {if $third_reminder_active|intval eq 1}checked="checked"{/if}value="1"/>
                        <label for="3_reminder_on" class="radioCheck" onClick="setActive(3, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 1);">
                            <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                        </label>
                        <input type="radio" name="3_reminder" id="3_reminder_off" value="0" {if $third_reminder_active|intval eq 0}checked="checked"{/if} />
                        <label for="3_reminder_off" class="radioCheck" onClick="setActive(3, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval}, 0);">
                            <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                    <input type="hidden" id="3_reminder" name="3_reminder" value="{$third_reminder_active|intval}" />
                </center>
            </td>
            <td><input {if $third_reminder_active|intval eq 0}disabled="disabled"{/if} type="text" placeholder="" id="third_reminder_days" name="third_reminder_days" value="{$third_reminder_days|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setDays(3, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><input {if $third_reminder_active|intval eq 0}disabled="disabled"{/if} type="text" placeholder="" id="third_reminder_hours" name="third_reminder_hours" value="{$third_reminder_hours|escape:'htmlall':'UTF-8'}" class="form-control"
            onKeyUp="setHours(3, this.value, '{$token|escape:"htmlall":"UTF-8"}', {$id_shop|intval});"></td>
            <td><center><b>5 {l s='Days' mod='cartabandonmentpro'}</b></center></td>
        </tr>
    </tbody>
</table>

<div class="row form-inline" style="margin-top: 25px;">
    <strong>{l s='When activating reminders' mod='cartabandonmentpro'}</strong>
    <br><br>
    {l s='Only send reminders to carts that are less than ' mod='cartabandonmentpro'}
    <input type="number" style="width: 40px;" name="max_reminder" id="form-field-1" value="{$max_reminder|escape:'htmlall':'UTF-8'}" class="form-control" onBlur="setMaxReminder(this.value, '{$token|escape:"htmlall":"UTF-8"}');">
    {l s='days old' mod='cartabandonmentpro'}
</div>
