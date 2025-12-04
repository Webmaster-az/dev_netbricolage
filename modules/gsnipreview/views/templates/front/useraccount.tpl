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

{literal}
<script type="text/javascript">
//<![CDATA[
    var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';
//]]>
</script>
{/literal}

{if $gsnipreviewis16 == 1 && $gsnipreviewis17 ==0}
    {capture name=path}<a href="{$gsnipreviewmy_account|escape:'htmlall':'UTF-8'}">{l s='My account' mod='gsnipreview'}</a>
        <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='User profile' mod='gsnipreview'}{/capture}
{/if}

{if $gsnipreviewis17 == 1}
    <a href="{$gsnipreviewmy_account|escape:'htmlall':'UTF-8'}">{l s='My account' mod='gsnipreview'}</a>
        <span class="navigation-pipe"> > </span>{l s='User profile' mod='gsnipreview'}
{/if}

{if $gsnipreviewis16 == 1}
    <h3 class="page-product-heading">{l s='User profile' mod='gsnipreview'}</h3>

{else}

    {l s='User profile' mod='gsnipreview'}

{/if}





<form method="post" action="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/reviews.php" enctype="multipart/form-data"
	 id="user_avatar_img" name="user_avatar_img"
	 >
    <input type="hidden" name="action" value="addavatar" />

<div class="b-info-block {if $gsnipreviewis17 == 1}block-categories{/if}">
	<div class="b-body">
		<dl>
			<dt class="check-box">
				<label class="check-box">{l s='Show my profile on the site' mod='gsnipreview'}</label>
			</dt>
			<dd class="padding-top-5">
					<input type="checkbox" name="show_my_profile" id="show_my_profile" class="check-box"
						   {if $gsnipreviewis_show == 1}checked="checked"{/if} 
					/>
			</dd>
		</dl>
	
		<dl class="b-photo-ed">
			<dt><label for="n3">{l s='Avatar:' mod='gsnipreview'}</label></dt>
				<dd>
					<div class="b-avatar">
						<img class="profile-adv-user-img" src="{$gsnipreviewavatar_thumb|escape:'htmlall':'UTF-8'}"/>
					</div>
					
					<div class="b-edit">
                        {if $gsnipreviewexist_avatar == 1}
                           <input type="radio" name="post_images" checked="" style="display: none">
                        {/if}
						   <input type="file" name="avatar-review" id="avatar-review" />
					</div>
					<div class="clr"><!-- --></div>
					<div class="b-guide">
                        {l s='Allow formats' mod='gsnipreview'} *.jpg; *.jpeg; *.png; *.gif.
					</div>
				</dd>
		</dl>
		
		<div class="b-buttons-save">
                    <button class="btn btn-success" value="Add review" type="submit">{l s='Save Changes' mod='gsnipreview'}</button>
		</div>
		
	</div>
	
</div>

</form>

{if $gsnipreviewis16 == 1}
    <br/>
    <ul class="footer_links clearfix">
        <li class="float-left margin-right-10">
            <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}" class="btn btn-default button button-small-gsnipreview">
                <span><i class="icon-chevron-left"></i> {l s='Home' mod='gsnipreview'}</span>
            </a>
        </li>
        <li class="float-left">
            <a href="{$gsnipreviewmy_account|escape:'htmlall':'UTF-8'}" class="btn btn-default button button-small-gsnipreview">
			<span>
				<i class="icon-chevron-left"></i> {l s='Back to Your Account' mod='gsnipreview'}
			</span>
            </a>
        </li>

    </ul>
{else}
<ul class="footer_links">
    <li><a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}my-account.php"><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/users/my-account.gif" alt="" class="icon" /></a>
        <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}my-account.php">{l s='Back to Your Account' mod='gsnipreview'}</a></li>
    <li><a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}"><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/users/home.gif" alt="" class="icon" /></a>
        <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}">{l s='Home' mod='gsnipreview'}</a></li>
</ul>
{/if}



{literal}
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(event) {
    $(document).ready(function (e) {
        $("#user_avatar_img").on('submit',(function(e) {



                $('#user_avatar_img').css('opacity',0.5);

                e.preventDefault();
                $.ajax({
                    url: baseDir + 'modules/gsnipreview/reviews.php',
                    type: "POST",
                    data:  new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    dataType: 'json',
                    success: function(data)
                    {

                        $('#user_avatar_img').css('opacity',1);

                        if (data.status == 'success') {

                            window.location.reload();


                        } else {

                            var error_type = data.params.error_type;

                            if(error_type == 8){
                                field_state_change('avatar-review','failed', '{/literal}{$gsnipreviewava_msg8|escape:'htmlall':'UTF-8'}{literal}');
                                return false;
                            } else if(error_type == 9){
                                field_state_change('avatar-review','failed', '{/literal}{$gsnipreviewava_msg9|escape:'htmlall':'UTF-8'}{literal}');
                                return false;
                            }

                        }

                    }
                });





        }));

    });

    });

</script>

{/literal}