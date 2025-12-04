{*
*
* Dynamic Ads + Pixel
*
* @author    BusinessTech.fr - https://www.businesstech.fr
* @copyright Business Tech - https://www.businesstech.fr
* @license   Commercial
*
*           ____    _______
*          |  _ \  |__   __|
*          | |_) |    | |
*          |  _ <     | |
*          | |_) |    | |
*          |____/     |_|
*
*}
<script type="text/javascript">
    // instantiate object
    var oFpa = oFpa || new Fpa('{$sModuleName|escape:'htmlall':'UTF-8'}');
</script>
<div id="fpa bt_advanced-tag" class="col-xs-12 bootstrap">
    <div class="d-flex mt-3">
        <div class="p-2">
            <a class="btn btn-secondary text-white" href="{$moduleUrl|escape:'htmlall':'UTF-8'}">{l s='Go back to module configuration' mod='facebookproductad'}</a>
        </div>
        <div class="ml-auto p-2 w-25">
            <button class="btn btn-success btn-lg w-25 pull-right" type="submit" name="save_btn">{l s='Save' mod='facebookproductad'}</button>
        </div>
    </div>

    <form class="form-horizontal" method="post" id="taxonomies" name="taxonomies">
        <h2 class="text-center mb-3">{l s='Facebook product categories for' mod='facebookproductad'}: {$isoLang|escape:'htmlall':'UTF-8'}</h2>
        <hr />

        <div class="alert alert-info">
            {l s='INSTRUCTIONS: for each category, you can do keyword search that represents the category, using as many words as you wish. Simply separate each word by a space. The field will autocomplete with possible matches that contain all the words you entered. Then simply select the best match from the list.' mod='facebookproductad'}
        </div>

        {if !empty($taxonomiesToImport.gmcTaxonomies) || !empty($taxonomiesToImport.gmcpTaxonomies) || !empty($taxonomiesToImport.tkpTaxonomies)}
            <div class="alert alert-info card mt-3 shadow">
                {l s='You have already made taxonomies mathching with on of our modules by clicking on the button below you can import data' mod='facebookproductad'}
                <div class="col-xs-12 text-center mt-3 mb-3">
                    {if !empty($taxonomiesToImport.gmcTaxonomies)}
                        <button type="submit" name="gmcTaxonomies" class="btn btn-lg btn-info">
                            {l s='Import from BusinessTech\'s Google Merchant Center module' mod='facebookproductad'}
                            <span class="badge badge-light">
                                {l s='Configured taxonomies' mod='facebookproductad'}&nbsp;{count($taxonomiesToImport.gmcTaxonomies)|escape:'htmlall':'UTF-8'}
                            </span>
                        </button>
                    {/if}
                    {if !empty($taxonomiesToImport.gmcpTaxonomies)}
                        <button type="submit" name="gmcpTaxonomies" class="btn btn-lg btn-info">
                            {l s='Import from BusinessTech\'s Google Merchant Center PRO module' mod='facebookproductad'}
                            <span class="badge badge-light">
                                {l s='Configured taxonomies' mod='facebookproductad'}&nbsp;{count($taxonomiesToImport.gmcpTaxonomies)|escape:'htmlall':'UTF-8'}
                            </span>
                        </button>
                    {/if}
                    {if !empty($taxonomiesToImport.tkpTaxonomies)}
                        <button type="submit" name="tkpTaxonomies" class="btn btn-lg btn-info">
                            {l s='Import from BusinessTech\'s TikTok Ads module' mod='facebookproductad'}
                            <span class="badge badge-light">
                                {l s='Configured taxonomies' mod='facebookproductad'}&nbsp;{count($taxonomiesToImport.tkpTaxonomies)|escape:'htmlall':'UTF-8'}
                            </span>
                        </button>
                    {/if}
                </div>
            </div>
        {/if}

        {if $maxPostVar != false && $shopCategoriesCount > $shopCategoriesCount}

            <div class="alert alert-warning col-xs-12 text-center px-5 py-5">
                {l s='Important note: Be careful, apparently your maximum post variables is limited by your server and your number of categories is higher than your max post variables' mod='facebookproductad'} :<br />
                <strong>{$shopCategoriesCount|escape:'htmlall':'UTF-8'}</strong>&nbsp;{l s='categories' mod='facebookproductad'}</strong>&nbsp;{l s='on' mod='facebookproductad'}&nbsp;<strong>{$maxPostVar|escape:'htmlall':'UTF-8'}</strong>&nbsp;{l s='max post variables possible (PHP directive => max_input_vars)' mod='facebookproductad'}<br /><br />
                <strong>{l s='It is possible that you cannot register properly all your categories, please visit our FAQ on this topic' mod='facebookproductad'}</strong>: <a class="badge badge-warning text-white px-2 py-2" target="_blank" href="{$faqLink|escape:'htmlall':'UTF-8'}/59">{$faqLink|escape:'htmlall':'UTF-8'}</a>
            </div>
        {/if}

        {foreach from=$shopCategories name=category item=categorie}
            <div class="card mt-3 shadow" style="width: 100%">
                <div class="card-body">
                    <h4 class="card-title">
                        {$categorie.path|escape:'quotes':'UTF-8'}
                    </h4>
                    <p class="card-text">
                        <input class="autocmp form-control" type="text" name="bt_facebook-cat[{$categorie.id_category|escape:'htmlall':'UTF-8'}]" category_id="{$categorie.id_category|escape:'htmlall':'UTF-8'}" id="bt_facebook-cat{$categorie.id_category|escape:'htmlall':'UTF-8'}" value="{$categorie.google_category_name|escape:'htmlall':'UTF-8'}" />
                    <div id="suggesstion-box_{$categorie.id_category|escape:'htmlall':'UTF-8'}" class="suggestion_box font-weight-bold"></div>
                    {if $smarty.foreach.category.first}
                        <p class="duplicate_category text-center col-xs-12">
                        <div class="mt-3"></div>
                        <a class="btn btn-sm btn-info text-white" href="#" onclick="return oFpa.duplicateFirstValue('input.autocmp', $('#bt_facebook-cat{$categorie.id_category|escape:'htmlall':'UTF-8'}').val());"><i class="fa fa-copy"></i>&nbsp; {l s='Click here to duplicate this value on all the following categories' mod='facebookproductad'}</a>
                        </p>
                    {/if}
                    </p>
                </div>
            </div>
        {/foreach}

        <div class="navbar navbar-default navbar-fixed-bottom shadow px-3 py-3 border border-dark">
            <p class="pull-right">
                <button class="btn btn-success btn-lg text-center" type="submit" name="save_btn">{l s='Save' mod='facebookproductad'}</button>
                <a class="btn btn-default btn-lg" href="{$moduleUrl|escape:'htmlall':'UTF-8'}">{l s='Go back to module configuration' mod='facebookproductad'}</a>
            </p>
        </div>
    </form>
</div>