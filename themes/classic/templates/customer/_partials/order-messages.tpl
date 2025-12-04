{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
 {block name='order_messages_table'}
  {if $order.messages}
    <div class="box messages">
      <h3>{l s='Messages' d='Shop.Theme.Customeraccount'}</h3>
      <div>
        {foreach from=$order.messages item=message}
          {if $message.id_employee == 1}
            <div class="message row">
              <div class="col-sm-2 hidden-xs-down align-right">
                {$message.name}
              </div>
              <div class="col-xs-10 col-sm-7 employee">
                <div class="message-text">{$message.message nofilter}</div>
                <li class="arrow-left"></li>
                <div class="message-date hidden">{$message.message_date}</div>
              </div>
              <div class="col-xs-1 col-sm-3">
              </div>
            </div>
          {else}
            <div class="message row">
              <div class="col-xs-1 col-sm-3">
              </div>
              <div class="col-xs-10 col-sm-7 customer">
                <div class="message-text">{$message.message nofilter}</div>
                <li class="arrow-right"></li>
                <div class="message-date hidden align-right">{$message.message_date}</div>
              </div>
              <div class="col-sm-2 hidden-xs-down">
                {$message.name}
              </div>
            </div>
          {/if}
        {/foreach}
      </div>
    </div>
  {/if}
{/block}

{block name='order_message_form'}
  <section class="order-message-form box">
    <form action="{$urls.pages.order_detail}" method="post">
      {if !$order.messages}
      <header>
        <h3>{l s='Add a message' d='Shop.Theme.Customeraccount'}</h3>
        <p>{l s='If you would like to add a comment about your order, please write it in the field below.' d='Shop.Theme.Customeraccount'}</p>
      </header>
      {/if}

      <section class="form-fields">
        <div class="form-group row ">
          <div class="col-md-12">
            <textarea rows="3" name="msgText" class="form-control"></textarea>
          </div>
        </div>
        {*<div class="form-group row col-md-3">
          <label class="col-md-12 col-xl-3 form-control-label">{l s='Product' d='Shop.Forms.Labels'}</label>
          <div class="col-md-12 col-xl-9">
            <select name="id_product" class="form-control form-control-select">
              <option value="0">{l s='-- please choose --' d='Shop.Forms.Labels'}</option>
              {foreach from=$order.products item=product}
                <option value="{$product.id_product}">{$product.name}</option>
              {/foreach}
            </select>
          </div>
        </div>*}

      </section>

      <footer class="form-footer text-sm-center">
        <input type="hidden" name="id_order" value="{$order.details.id}">
        <button type="submit" name="submitMessage" class="btn btn-primary form-control-submit">
          {l s='Send' d='Shop.Theme.Actions'}
        </button>
      </footer>

    </form>
  </section>
{/block}
