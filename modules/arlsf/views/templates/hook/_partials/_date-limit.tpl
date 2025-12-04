{*
* 2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*
*  @author Areama <contact@areama.net>
*  @copyright  2018 Areama
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*}

<div class="row">
    <div class="col-sm-8">
        <input name="AR_LSFO_FAKE_DATE" id="AR_LSFO_FAKE_DATE" value="{$model->fake_date|intval}" class="" placeholder="{$model->getAttributePlaceholder('fake_date')|escape:'htmlall':'UTF-8'}" type="text">
    </div>
    <div class="col-sm-4">
        <select class="form-control" name="AR_LSFO_DATE_UNIT">
            {foreach $model->dateUnitSelectOptions() as $unit}
                <option value="{$unit.id|escape:'htmlall':'UTF-8'}" {if $model->date_unit == $unit.id}selected="selected"{/if}>{$unit.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
        </select>
    </div>
</div>