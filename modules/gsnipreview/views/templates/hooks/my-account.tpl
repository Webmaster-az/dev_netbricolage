{*

/**

 * mitrocops

 *

 * NOTICE OF LICENSE

 *

 * This source file is subject to the EULA

 * that is bundled with this package in the file LICENSE.txt.

 *

 /*

 * 

 * @author    mitrocops

 * @category seo

 * @package gsnipreview

 * @copyright Copyright mitrocops

 * @license   mitrocops

 */



*}
{if $gsnipreviewrvis_on == 1}
{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}
{if $gsnipreviewislogged !=0}
    

    <li>



	

	<a href="{$gsnipreviewaccount_url|escape:'htmlall':'UTF-8'}" title="{l s='Product Reviews' mod='gsnipreview'}">



       <span class="link-item">
            <i style="font-size: 21px;margin-right:5px;" class="fas fa-comments material-icons"></i>


        <span>
            {l s='Product Reviews' mod='gsnipreview'}
        </span>





       </span>



	</a>



    </li>





{/if}



{/if}



{/if}



{if $gsnipreviewis_uprof == 1}

{if $gsnipreviewislogged !=0}




        <li>






        <a href="{$gsnipreviewuacc_url|escape:'htmlall':'UTF-8'}" 

           title="{l s='User profile' mod='gsnipreview'}">



            <span class="link-item">




                <img class="icon" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/users/users-logo.png" />
                <span>{l s='User profile' mod='gsnipreview'}</span>



            </span>



        </a>



        </li>





{/if}

{/if}





{if $gsnipreviewis_storerev == 1}

    {if $gsnipreviewislogged !=0}




            <li>





            <a href="{$gsnipreviewmysr_url|escape:'htmlall':'UTF-8'}" title="{l s='Store Reviews' mod='gsnipreview'}">
                <span class="link-item">
                    <i style="font-size: 21px;margin-right:5px;" class="far fa-comments material-icons"></i>
                    <span>
                        {l s='Store Reviews' mod='gsnipreview'}
                    </span>
               </span>
            </a>






            </li>



    {/if}

{/if}