<ul class="rm-nav page-head rm-nav--{$rm_ps_version}">
    <li class="rm-nav__item js-tab" data-target="configuration">
        {l s='Configuration' mod='responsivemenu'}
    </li>
    <li class="rm-nav__item js-tab" data-target="theme">
        {l s='Theme' mod='responsivemenu'}
    </li>
    <li class="rm-nav__item js-tab" data-target="link">
        {l s='Links' mod='responsivemenu'}
    </li>
    <li class="rm-nav__item js-tab" data-target="category">
        {l s='Categories' mod='responsivemenu'}
    </li>
    {if version_compare(_PS_VERSION_, '1.7', '>=')}
        <li class="rm-nav__item js-tab" data-target="sublimecategories">
            {l s='Route categories' mod='responsivemenu'}
        </li>
    {/if}
</ul>
<script type="text/javascript">jQuery(function() { new RmTabs() });</script>
