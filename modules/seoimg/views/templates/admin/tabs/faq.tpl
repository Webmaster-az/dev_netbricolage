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

{if !empty($apifaq)}
<div class="clearfix"></div>
<div class="tab-pane panel" id="faq">
	<h3>
		<i class="fa fa-question-circle"></i> {l s='Help' mod='seoimg'} <small>{$module_display_name|escape:'htmlall':'UTF-8'}</small>
	</h3>
    <div class="helpContentParent">
        <div class="helpContentRight">
            <div class="helpContentRight-sub">
                <div class="faq">
                    <div class="faq-header">
                        {l s='FAQ' mod='seoimg'}
                    </div>
                    <div class="faq-content">
					<ul class="accordion">
						{foreach from=$apifaq item=categorie}
							<li>
								<span class="toggleFaq titleFaq">{$categorie->title|escape:'htmlall':'UTF-8'}<i class="fa fa-chevron-right caretRight"></i></span>
								<ul class=innerFaq>
									{foreach from=$categorie->blocks item=QandA}
										<li>
											<span href="#" class="toggleFaq questionFaq"><i class="fa fa-caret-right caretLeft"></i>{$QandA->question|escape:'htmlall':'UTF-8'}</span>
											<div class="innerFaq answerFaq">
												<p>{$QandA->answer|replace:"\n":"<br />"|escape:'htmlall':'UTF-8'}</p>
											</div>
										</li>
									{/foreach}
								</ul>
							</li>
						{/foreach}
					</ul>
                    </div>
                </div>
                <b><a href="http://addons.prestashop.com/contact-form.php" target="_blank">{l s='Contact us on PrestaShop Addons' mod='seoimg'}</a></b>
            </div>
        </div>
    </div>
</div>
{/if}
