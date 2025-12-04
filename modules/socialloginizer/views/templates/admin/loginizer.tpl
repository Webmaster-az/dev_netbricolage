<script type="text/javascript">
{literal}
function submitform(val)
{
    $("#submit_form").val(val);
    document.loginizer_form.submit();
}
{/literal}
</script>
<style>
#tab_faq{ padding:10px; }
.question {  font-family: initial;  color: rgb(213, 81, 81) !important;  font-size: 17px !important; }
.answer {  font-family: initial;  font-size: 15px;  line-height: 20px;  letter-spacing: 1px; }
#sortable { list-style-type: none; margin: 0; padding: 0; }
#sortable li { cursor:move; padding: 1px; font-size: 1.2em; height: 1.5em; display:inline;line-height: 1.2em; }
.portlet-placeholder { border: 1px dashed black; margin: 0 1em 1em 0; height: 32px; width:32px; }
#module table{ border: 1px solid green; }
.icon-question-sign { color: #526D9E; }
</style>
<div style="font-size: 15px;padding-left: 4%;padding-top: 12px; display: block; padding: 10px 15px 10px 15px; background-color: #FFF8DC; text-align: center; border: 1px solid #E0DCBF;">
    Paid Version with full features.
    <a target="_blank" href="http://addons.prestashop.com/demo/BO10163.html" style="text-decoration: none;">
        <span style="color: white;background-color: #79BD3C;padding: 6px 20px;border-radius: 3px;font-size: 13px;margin-left: 10px;text-shadow: chartreuse;">
            Check Demo
        </span>
    </a>
    <a target="_blank" href="http://addons.prestashop.com/en/18220-knowband-social-login-15-in-1-statistics-mailchimp.html" style="text-decoration: none;">
        <span style="color: white;background-color: #79BD3C;padding: 6px 20px;border-radius: 3px;font-size: 13px;margin-left: 10px;text-shadow: chartreuse;">
            Buy Now
        </span>
    </a>
    <a target="_blank" href="https://www.prestashop.com/forums/topic/509386-free-module-knowband-one-page-checkout-module/" style="text-decoration: none;">
        <span style="color: white;background-color: #79BD3C;padding: 6px 20px;border-radius: 3px;font-size: 13px;margin-left: 10px;text-shadow: chartreuse;">
            Try also Superchekout free Version
        </span>
    </a>
</div>
<br>
<div id="velsof_supercheckout_container" class="content" style="width: 100%;">
    <div class="box">        
        <div class="navbar main hidden-print" style="width: 100%;">	    
            <!-- Cancel & save buttons -->
            <div class="topbuttons">                
                <a href="javascript: submitform('sub')"><span id="save_post_setting" class="btn btn-block btn-success action-btn">{l s='Save' mod='socialloginizer'}</span></a>&nbsp;&nbsp;&nbsp;<a href="{$cancel_action|escape:'htmlall':'UTF-8'}"><span class="btn btn-block btn-danger action-btn">{l s='Cancel' mod='socialloginizer'}</span></a>
            </div>
        </div>
        <div class="velsof-container" style="width: 100%;">
            <div class="widget velsof-widget-left" style="width: 100%;">
                <div class="widget-body velsof-widget-left" style="width: 100%; padding: 0px !important">                    
                        <div id="wrapper" style="width: 100%;">
                            <div id="menuVel" class="hidden-print ui-resizable"  style="position: static">
                                <div class="slimScrollDiv">
                                    <div class="slim-scroll">
                                        <ul>
                                            <li class="active"><a class="glyphicons settings" href="#tab_general_settings" data-toggle="tab"><i></i><span>{l s='General Settings' mod='socialloginizer'}</span></a></li>
					    <li class=""><a class="glyphicons stats" href="#tab_statistics" data-toggle="tab" id="stat_graph" ><i></i><span>{l s='Statistics' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social facebook" id="velsof_tab_login" href="#tab_facebook" data-toggle="tab" ><i></i><span>{l s='Facebook Settings' mod='socialloginizer'}</span></a></li>                                            
                                            <li class=""><a class="glyphicons-social google_plus" href="#tab_gplus" data-toggle="tab" ><i></i><span>{l s='Google Plus Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social windows" href="#tab_live" data-toggle="tab" ><i></i><span>{l s='Live Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social linked_in" href="#tab_linked" data-toggle="tab" ><i></i><span>{l s='LinkedIn Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social twitter" href="#tab_twitter" data-toggle="tab" ><i></i><span>{l s='Twitter Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social yahoo" href="#tab_yahoo" data-toggle="tab" ><i></i><span>{l s='Yahoo Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social instagram" id="velsof_tab_design" href="#tab_instagram" data-toggle="tab" ><i></i><span>{l s='Instagram Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social amazon" href="#tab_amazon" data-toggle="tab" ><i></i><span>{l s='Amazon Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons" style="background-image: url('../modules/socialloginizer/views/img/buttons/paypal_setting.png');background-repeat: no-repeat;background-position: 16px 10px;" href="#tab_paypal" data-toggle="tab" ><i></i><span>{l s='Paypal Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social foursquare" href="#tab_foursquare" data-toggle="tab" ><i></i><span>{l s='Foursquare Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social github" href="#tab_github" data-toggle="tab" ><i></i><span>{l s='Github Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons" style="background-image: url('../modules/socialloginizer/views/img/buttons/disqus_setting.png');background-repeat: no-repeat;background-position: 16px 10px;" href="#tab_disqus" data-toggle="tab" ><i></i><span>{l s='Disqus Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons" style="background-image: url('../modules/socialloginizer/views/img/buttons/vk_setting.png');background-repeat: no-repeat;background-position: 16px 10px;" href="#tab_vk" data-toggle="tab" ><i></i><span>{l s='Vkontakte Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social wordpress" href="#tab_wordpress" data-toggle="tab" ><i></i><span>{l s='Wordpress Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons-social dropbox" href="#tab_dropbox" data-toggle="tab" ><i></i><span>{l s='Dropbox Settings' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons circle_question_mark" href="#tab_faq" data-toggle="tab"><i></i><span>{l s='FAQs' mod='socialloginizer'}</span></a></li>                                            
                                            <li class=""><a class="glyphicons pen" href="#tab_suggest" data-toggle="tab"><i></i><span>{l s='Suggestions' mod='socialloginizer'}</span></a></li>
                                            <li class=""><a class="glyphicons bookmark" target="_blank" href="http://addons.prestashop.com/en/2_community-developer?contributor=38002" target="_blank"><i></i><span>{l s='Other Plugins' mod='socialloginizer'}</span></a></li>
                                        </ul>
                                
                                        <div class="clearfix"></div>
                                      
                                    </div>
                                </div>
                                <div class="ui-resizable-handle ui-resizable-e" style="z-index: 1000;"></div>
                            </div>
                            
                            <div id="content">
                                <div class="box">
                                    <div class="content tabs">
                                        <form action="{$action|escape:'htmlall':'UTF-8'}" name="loginizer_form" action="" method="post" enctype="multipart/form-data" id="loginizer_configuration_form">
                                            <input type='hidden' id='submit_form' name='submit_form' value=''>
                                           <div class="layout">
                                                <div class="tab-content even-height">
                                                    <!--------------- Start - General Setings -------------------->
                                                    <div id="tab_general_settings" class="tab-pane active">
                                                            <div class="block">
                                                                <h4 class='velsof-tab-heading'>{l s='General Settings' mod='socialloginizer'}</h4>
                                                                <table class="form">
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span></td>
                                                                        <td class="settings">                                                                            
                                                                            {if isset($velocity_social['enable']) && ($velocity_social['enable'] eq 1)}                                                                                
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[enable]" id="loginizer_enable" checked="checked" />
                                                                                    </div>                                                                                
                                                                            {else}                                                                                
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[enable]" id="loginizer_enable"/>
                                                                                    </div>                                                                                
                                                                            {/if}
                                                                        </td>
                                                                    </tr>
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Show Popup' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Show popup rather than using redirection when customer clicks on social login button' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">                                                                            
                                                                            
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox"  checked="checked" />
                                                                                    </div>                                                                                
                                                                            
                                                                        </td>
                                                                    </tr>
								                                                                                                                              
                                                                       <tr class="free-disabled">
                                                                            <td class="name"><span class="control-label">{l s='Button Arrangements' mod='socialloginizer'}:</span>                                                                
                                                                                <i class="icon-question-sign" data-toggle="tooltip" data-placement="top" data-original-title="{l s='Edit the position of Loginizer Buttons ' mod='socialloginizer'}"></i>
                                                                            </td>
                                                                            <td class='settings'>
                                                                                <ul style="margin-bottom:10px;" id='sortable'>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/fb_small.png' alt='Facebook'/>
                                                                                    
                                                                                        </li>                   
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/google_small.png' alt='Google'/>

                                                                                        </li>
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/linkedin_small.png' alt='LinkedIn'/>

                                                                                        </li>
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/live_small.png' alt='Live'/>

                                                                                        </li>
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/twitter_small.png' alt='Twitter'/>

                                                                                        </li>
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/yahoo_small.png' alt='Yahoo'/>
                                                                                        </li>
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/amazon_small.png' alt='Amazon'/>
                                                                                        
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/instagram_small.png' alt='instagram'/>   
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                                <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/paypal_small.png' alt='Paypal'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/foursquare_small.png' alt='foursquare'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/github_small.png' alt='github'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/disqus_small.png' alt='disqus'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/vk_small.png' alt='vk'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/wordpress_small.png' alt='wordpress'/>
                                                                                        </li>
                                                                                    
                                                                                        <li data-index=''>
                                                                                            <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/dropbox_small.png' alt='dropbox'/>
                                                                                        </li>
                    
                                                                                </ul>  
                                                                                <span style="padding:10px;">{l s='Drag and drop these buttons to change sort order' mod='socialloginizer'}.</span>
                                                                            </td>
                                                                        </tr>
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Show on' mod='socialloginizer'} <a target="_blank" style="color:#51595C !important;text-decoration:underline !important;" href="http://addons.prestashop.com/en/checkout-modules/18016-one-page-checkout-with-social-login-superfast.html">{l s='SuperCheckout' mod='socialloginizer'}</a>: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Supercheckout is our One page checkout module' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">                                                                            
										<label style=' display:block !important;font-weight: normal !important;text-align: inherit;'>
											<input type="radio" disabled="disabled" checked="checked" /><span style="margin: 6px;vertical-align: top;">{l s='Do not show' mod='socialloginizer'}</span>
										</label>
										<label style='display:block !important; font-weight: normal !important;text-align: inherit;'>
											<input type="radio"  disabled="disabled" /><span style="margin: 6px;vertical-align: top;">{l s='Small Buttons' mod='socialloginizer'}</span>
										</label>
										<label style='display:block !important; font-weight: normal !important;text-align: inherit;'>	
											<input type="radio" disabled="disabled"/><span style="margin: 6px;vertical-align: top;">{l s='Large Buttons' mod='socialloginizer'}</span>
										</label>
                                                                        </td>
                                                                    </tr>
								    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Custom Css' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Provide some CSS code for changes in the front end of Socialloginizer' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">                                                                            
										<textarea rows="5" style="resize: both;width:100%;" name="velocity_social[custom_css]">{if isset($velocity_social['custom_css'])}{$velocity_social['custom_css']|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                                                        </td>
                                                                    </tr>
								    
                                                                </table>
                                                                <br>
                                                                <table id="module" class="list form alternate pure-table pure-table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <td class="left">{l s='Page' mod='socialloginizer'}</td>
                                                                            <td class="left">{l s='Status' mod='socialloginizer'}</td>
                                                                            <td class="left">{l s='Position' mod='socialloginizer'}</td>
                                                                            <td class="left">{l s='Button Size' mod='socialloginizer'}</td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Home Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} name="">
                                                                                    <option disabled="disabled" selected >{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}> 
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option selected="selected" disabled="disabled">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>

                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Category Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option  disabled="disabled" selected >{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Product Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Login Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected="selected">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} >
                                                                                    <option disabled="disabled">{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Create Account Form' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected>{l s='Login Form' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Five Steps Checkout Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
									
									
									<tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='One-page Checkout Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select  {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='CMS Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Special Products Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='New Products Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Best Sellers Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Our Stores Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                        
                                                                        
                                                                        <tr class="free-disabled">
                                                                            <td class="left">
                                                                                {l s='Contact Us Page' mod='socialloginizer'}
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Enable' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Disable' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled" selected>{l s='Header' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Footer' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Left Column' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled">{l s='Right Column' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="left">
                                                                                <select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if}>
                                                                                    <option disabled="disabled">{l s='Large' mod='socialloginizer'}</option>
                                                                                    <option disabled="disabled" selected="selected">{l s='Small' mod='socialloginizer'}</option>
                                                                                </select>
                                                                            </td>                                                                            
                                                                        </tr>
                                                                    </tbody>                                                                                        
                                                                </table>
                                                            </div>                                               
                                                    </div>

                                                    <!--------------- End - General Settings -------------------->

                                                    <!--------------- Start - Facebook Settings -------------------->

                                                    <div id="tab_facebook" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Facebook Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://developers.facebook.com/apps/" target="_blank">{l s='Click here to get Facebook app id and app secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Facebook Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['facebook']['enable']) && ($velocity_social['facebook']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[facebook][enable]" id="fb_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[facebook][enable]" id="fb_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Facebook App Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Facebook App Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[facebook][app_id]" value="{if isset($velocity_social['facebook']['app_id'])}{$velocity_social['facebook']['app_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Facebook App Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Facebook App Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[facebook][app_secret]" value="{if isset($velocity_social['facebook']['app_secret'])}{$velocity_social['facebook']['app_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>   
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="facebook_mailchimp_key"/>
									    
									    </span>
									    <span><input type="button" disabled="disabled" value="Get List" onclick="return false" id="facebook_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="facebook_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>  
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="facebook_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'} </h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook2.jpg' />
										</div>
										<h3>{l s='Step 3' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook3.jpg' />
										</div>
										<h3>{l s='Step 4, 5' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook4.jpg' />
										</div>
										<h3>{l s='Step 6, 7' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook5.jpg' />
										</div>
										<h3>{l s='Step 8' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook6.jpg' />
										</div>
										<h3>{l s='Step 9' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook7.jpg' />
										</div>
										<h3>{l s='Step 10 , 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #9 use App Domain: ' mod='socialloginizer'}</b>{$domain|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #11 use Site Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook8.jpg' />
										</div>
										<h3>{l s='Step 13' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook9.jpg' />
										</div>
										<h3>{l s='Step 14, 15' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook10.jpg' />
										</div>
										<h3>{l s='Step 16, 17, 18 ,19' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/facebook/facebook11.jpg' />
										</div>
										
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Facebook Settings -------------------->                                                   

                                                    <!--------------- Start - Google Plus -------------------->

                                                    <div id="tab_gplus" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Google Plus Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">                                                                                                                                 
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://console.developers.google.com/project" target="_blank">{l s='Click here to get Google  client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Google Plus Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['gplus']['enable']) && ($velocity_social['gplus']['enable'] eq 1)}
                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[gplus][enable]" id="gplus_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[gplus][enable]" id="gplus_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>                                                                   

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Google Plus Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Google Plus Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[gplus][client_id]" value="{if isset($velocity_social['gplus']['client_id'])}{$velocity_social['gplus']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Google Plus Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Google Plus Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[gplus][client_secret]" value="{if isset($velocity_social['gplus']['client_secret'])}{$velocity_social['gplus']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>   
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="google_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="google_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="google_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="google_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google2.jpg' />
										</div>
										<h3>{l s='Step 3, 4' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google3.jpg' />
										</div>
										<h3>{l s='Step 5, 6, 7' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google4.jpg' />
										</div>
										<h3>{l s='Step 8' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google5.jpg' />
										</div>
										<h3>{l s='Step 9' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google6.jpg' />
										</div>
										<h3>{l s='Step 10, 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google7.jpg' />
										</div>
										<h3>{l s='Step 13, 14, 15, 16, 17' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #15 Use Authorized javascript origins: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</b></br><b>{l s='For Step #16 Use Authorized Redirect Url: ' mod='socialloginizer'}</b>{$google_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google8.jpg' />
										</div>
										<h3>{l s='Step 18, 19' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google9.jpg' />
										</div>
										<h3>{l s='Step 20, 21, 22, 23' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/google/google10.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Google Plus Settings -------------------->

                                                    <!--------------- Start - Live Settings -------------------->

                                                    <div id="tab_live" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Live Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://account.live.com/developers/applications/create?tou=1" target="_blank">{l s='Click here to get Live client id and client secret(v1)' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Live Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['live']['enable']) && ($velocity_social['live']['enable'] eq 1)}
                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[live][enable]" id="live_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[live][enable]" id="live_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Live Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Live Client ID' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[live][client_id]" value="{if isset($velocity_social['live']['client_id'])}{$velocity_social['live']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Live Client Secret(v1)' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Live Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[live][client_secret]" value="{if isset($velocity_social['live']['client_secret'])}{$velocity_social['live']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr> 
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="live_mailchimp_key"/>
									    
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="live_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="live_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="live_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/live/live1.jpg' />
										</div>
										<h3>{l s='Step 2, 3' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/live/live2.jpg' />
										</div>
										<h3>{l s='Step 4, 5, 6, 7, 8, 9' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #5 Use Target Domain: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #7 Use Root Domain: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<b>{l s='For Step #8 Use Redirect Url: ' mod='socialloginizer'}</b>{$live_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/live/live3.jpg' />
										</div>
										<h3>{l s='Step 10, 11' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/live/live4.jpg' />
										</div>
										<h3>{l s='Step 12, 13, 14, 15' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/live/live5.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Live Settings -------------------->

                                                    <!--------------- Start -Linkedin Settings-------------------->

                                                    <div id="tab_linked" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='LinkedIn Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://www.linkedin.com/secure/developer" target="_blank">{l s='Click here to get Linked in api key and  secret key' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable LinkedIn Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['linked']['enable']) && ($velocity_social['linked']['enable'] eq 1)}
                                                                              
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[linked][enable]" id="linked_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[linked][enable]" id="linked_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='LinkedIn API Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter LinkedIn API Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[linked][client_id]" value="{if isset($velocity_social['linked']['client_id'])}{$velocity_social['linked']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='LinkedIn Secret Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter LinkedIn Secret Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[linked][client_secret]" value="{if isset($velocity_social['linked']['client_secret'])}{$velocity_social['linked']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>  
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="linkedin_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="linkedin_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="linkedin_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>  
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="linkedin_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/linkedin/linkedin1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/linkedin/linkedin2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6, 7, 8, 9, 10, 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #8 Use Website Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/linkedin/linkedin3.jpg' />
										</div>
										<h3>{l s='Step 13, 14, 15, 16, 17, 18, 19' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #17 Use Authorized Redirect Url: ' mod='socialloginizer'}</b>{$linkedin_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/linkedin/linkedin4.jpg' />
										</div>
										<h3>{l s='Step 20, 21, 22, 23' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/linkedin/linkedin5.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Linkedin Settings -------------------->

                                                    <!--------------- Start - Twitter Settings -------------------->

                                                    <div id="tab_twitter" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Twitter Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://dev.twitter.com/apps" target="_blank">{l s='Click here to get Twitter api key and api secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Twitter Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['twitter']['enable']) && ($velocity_social['twitter']['enable'] eq 1)}
                                                                              
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[twitter][enable]" id="twitter_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                             
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[twitter][enable]" id="twitter_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Twitter API Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Twitter API Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[twitter][client_id]" value="{if isset($velocity_social['twitter']['client_id'])}{$velocity_social['twitter']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Twitter API Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Twitter API Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[twitter][client_secret]" value="{if isset($velocity_social['twitter']['client_secret'])}{$velocity_social['twitter']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr> 
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="twitter_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="twitter_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="twitter_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="twitter_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/twitter/twitter1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/twitter/twitter2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6, 7, 8' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #5 Use Website: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #6 Use Callback Url: ' mod='socialloginizer'}</b>{$twitter_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/twitter/twitter3.jpg' />
										</div>
										<h3>{l s='Step 9, 10' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/twitter/twitter4.jpg' />
										</div>
										<h3>{l s='Step 11, 12, 13, 14' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/twitter/twitter5.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Twitter Settings -------------------->

                                                    <!--------------- Start - Yahoo Settings -------------------->
                                                    <div id="tab_yahoo" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Yahoo Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="http://developer.apps.yahoo.com/projects" target="_blank">{l s='Click here to get Yahoo client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Yahoo Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['yahoo']['enable']) && ($velocity_social['yahoo']['enable'] eq 1)}
                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[yahoo][enable]" id="yahoo_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                             
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[yahoo][enable]" id="yahoo_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Yahoo Consumer Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Yahoo Consumer Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[yahoo][consumer_key]" value="{if isset($velocity_social['yahoo']['consumer_key'])}{$velocity_social['yahoo']['consumer_key']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Yahoo Consumer Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Yahoo Consumer Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[yahoo][consumer_secret]" value="{if isset($velocity_social['yahoo']['consumer_secret'])}{$velocity_social['yahoo']['consumer_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>   
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="yahoo_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="yahoo_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">										
										<div id="yahoo_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="yahoo_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/yahoo/yahoo1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/yahoo/yahoo2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6, 7, 8, 9, 10, 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #5 Use Callback domain: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/yahoo/yahoo3.jpg' />
										</div>
										<h3>{l s='Step 13, 14, 15, 16' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/yahoo/yahoo4.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Yahoo Settings -------------------->
                                                    

                                                    <!--------------- Start - Instagram Settings -------------------->

                                                    <div id="tab_instagram" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Instagram Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="http://instagram.com/developer/clients/manage/" target="_blank">{l s='Click here to get Instagram client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Instagram Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['insta']['enable']) && ($velocity_social['insta']['enable'] eq 1)}
                                                                                
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[insta][enable]" id="insta_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[insta][enable]" id="insta_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Instagram Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Instagram Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[insta][client_id]" value="{if isset($velocity_social['insta']['client_id'])}{$velocity_social['insta']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Instagram Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Instagram Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[insta][client_secret]" value="{if isset($velocity_social['insta']['client_secret'])}{$velocity_social['insta']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="insta_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="insta_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="insta_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="insta_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta2.jpg' />
										</div>
										<h3>{l s='Step 3' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta3.jpg' />
										</div>
										<h3>{l s='Step 4, 5, 6, 7, 8, 9' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #6 Use Website Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #7 Use Redirect Url: ' mod='socialloginizer'}</b>{$insta_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta4.jpg' />
										</div>
										<h3>{l s='Step 10, 11' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta5.jpg' />
										</div>
										<h3>{l s='Step 12, 13, 14, 15' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/insta/insta6.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Instagram Settings -------------------->
                                                    
                                                    <!--------------- Start - Amazon Settings -------------------->
                                                    <div id="tab_amazon" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Amazon Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr colspan="1">
									    <td  style='padding: 8px;color:#FF2707'>
                                                                            <b style="color:black;">{l s='Note' mod='socialloginizer'}:</b> {l s='SSL must be enabled to use this option.' mod='socialloginizer'}
                                                                        </td>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="http://login.amazon.com/manageApps" target="_blank">{l s='Click here to get Amazon client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Amazon Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['amazon']['enable']) && ($velocity_social['amazon']['enable'] eq 1)}
                                                                              
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[amazon][enable]" id="amazon_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[amazon][enable]" id="amazon_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Amazon Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Amazon Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[amazon][client_id]" value="{if isset($velocity_social['amazon']['client_id'])}{$velocity_social['amazon']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Amazon Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Amazon Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[amazon][client_secret]" value="{if isset($velocity_social['amazon']['client_secret'])}{$velocity_social['amazon']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>
                                                                    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="amazon_mailchimp_key"/>
									   
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="amazon_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="amazon_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="amazon_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon2.jpg' />
										</div>
										<h3>{l s='Step 3' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon3.jpg' />
										</div>
										<h3>{l s='Step 4' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon4.jpg' />
										</div>
										<h3>{l s='Step 5, 6' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon5.jpg' />
										</div>
										<h3>{l s='Step 7, 8, 9' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #7 Use Allowed javascript origins : ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #8 Use Allowed Return Url : ' mod='socialloginizer'}</b>{$amazon_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon6.jpg' />
										</div>
										<h3>{l s='Step 10, 11, 12, 13' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/amazon/amazon7.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Amazon Settings -------------------->
                                                    
                                                    <!--------------- Start - Paypal Settings -------------------->
                                                    <div id="tab_paypal" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Paypal Settings' mod='socialloginizer'}</h4>
                                                            <table class="form">
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://developer.paypal.com/webapps/developer/applications/myapps" target="_blank">{l s='Click here to get Paypal client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Paypal Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['pay']['enable']) && ($velocity_social['pay']['enable'] eq 1)}
                                                                              
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[pay][enable]" id="amazon_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                             
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[pay][enable]" id="amazon_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Paypal Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Paypal Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[pay][client_id]" value="{if isset($velocity_social['pay']['client_id'])}{$velocity_social['pay']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Paypal Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Paypal Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[pay][client_secret]" value="{if isset($velocity_social['pay']['client_secret'])}{$velocity_social['pay']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="pay_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="pay_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="pay_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                            </table>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="pay_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal3.jpg' />
										</div>
										<h3>{l s='Step 6' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal4.jpg' />
										</div>
										<h3>{l s='Step 7, 8, 9, 10' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #9 Use Return Url: ' mod='socialloginizer'}</b>{$paypal_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal5.jpg' />
										</div>
										<h3>{l s='Step 11, 12, 13, 14, 15' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #13 Use Privacy Policy Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #14 Use User aggrement Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal6.jpg' />
										</div>
										<h3>{l s='Step 16, 17, 18, 19' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/paypal/paypal7.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>
                                                    <!--------------- End - Paypal Settings -------------------->
                                                    
                                                    <!--------------- Start - Foursquare Settings -------------------->

                                                    <div id="tab_foursquare" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Foursquare Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://foursquare.com/developers/apps" target="_blank">{l s='Click here to get Foursquare client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Foursquare Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['foursquare']['enable']) && ($velocity_social['foursquare']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[foursquare][enable]" id="foursquare_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[foursquare][enable]" id="foursquare_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Foursquare Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Foursquare Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[foursquare][client_id]" value="{if isset($velocity_social['foursquare']['client_id'])}{$velocity_social['foursquare']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Foursquare Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Foursquare Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[foursquare][client_secret]" value="{if isset($velocity_social['foursquare']['client_secret'])}{$velocity_social['foursquare']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr> 
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="foursquare_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="foursquare_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="foursquare_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="foursquare_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/foursquare/foursquare1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/foursquare/foursquare2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #5 Use Redirect Url: ' mod='socialloginizer'}</b>{$foursquare_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/foursquare/foursquare3.jpg' />
										</div>
										<h3>{l s='Step 7, 8' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/foursquare/foursquare4.jpg' />
										</div>
										<h3>{l s='Step 9, 10, 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/foursquare/foursquare5.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Foursquare Settings -------------------->    
                                                    
                                                    <!--------------- Start - Github Settings -------------------->

                                                    <div id="tab_github" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Github Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://github.com/settings/applications/new" target="_blank">{l s='Click here to get github client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Github Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['github']['enable']) && ($velocity_social['github']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[github][enable]" id="github_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[github][enable]" id="github_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Github Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Github Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[github][client_id]" value="{if isset($velocity_social['github']['client_id'])}{$velocity_social['github']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Github Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Github Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[github][client_secret]" value="{if isset($velocity_social['github']['client_secret'])}{$velocity_social['github']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>     
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="github_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="github_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="github_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="github_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/github/github1.jpg' />
										</div>
										<h3>{l s='Step 2, 3, 4, 5' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #3 Use HomePage Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #4 Use Authorization callback Url: ' mod='socialloginizer'}</b>{$github_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/github/github2.jpg' />
										</div>
										<h3>{l s='Step 6, 7' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/github/github3.jpg' />
										</div>
										<h3>{l s='Step 8, 9, 10, 11' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/github/github4.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Github Settings -------------------->    
                                                    
                                                    <!--------------- Start - Disqus Settings -------------------->

                                                    <div id="tab_disqus" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Disqus Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://disqus.com/api/applications/" target="_blank">{l s='Click here to get disqus api key and api secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Disqus Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['disqus']['enable']) && ($velocity_social['disqus']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[disqus][enable]" id="disqus_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[disqus][enable]" id="disqus_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Disqus Api Key ' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Disqus Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[disqus][client_id]" value="{if isset($velocity_social['disqus']['client_id'])}{$velocity_social['disqus']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Disqus Api Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Disqus Api Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[disqus][client_secret]" value="{if isset($velocity_social['disqus']['client_secret'])}{$velocity_social['disqus']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr> 
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="disqus_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="disqus_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="disqus_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="disqus_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6, 7' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus3.jpg' />
										</div>
										<h3>{l s='Step 8, 9, 10' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #9 Use Callback Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus4.jpg' />
										</div>
										<h3>{l s='Step 11, 12' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus5.jpg' />
										</div>
										<h3>{l s='Step 13, 14, 15, 16' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/disqus/disqus6.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Disqus Settings -------------------->     
                                                    
                                                    <!--------------- Start - Vkontakte Settings -------------------->

                                                    <div id="tab_vk" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Vkontakte Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://vk.com/editapp?act=create" target="_blank">{l s='Click here to get vkontakte application id and secure key' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Vkontakte Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['vk']['enable']) && ($velocity_social['vk']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[vk][enable]" id="vk_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[vk][enable]" id="vk_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Vkontakte Application Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Vkontakte Application Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[vk][client_id]" value="{if isset($velocity_social['vk']['client_id'])}{$velocity_social['vk']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Vkontakte Secure Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Vkontakte Secure Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[vk][client_secret]" value="{if isset($velocity_social['vk']['client_secret'])}{$velocity_social['vk']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr> 
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="vk_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="vk_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="vk_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>   
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="vk_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/vk/vk1.jpg' alt='Facebook'/>
										</div>
										<h3>{l s='Step 2, 3, 4, 5, 6' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #4 Use Site Address: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #5 Use Base Domain: ' mod='socialloginizer'}</b>{$domain|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/vk/vk2.jpg' alt='Facebook'/>
										</div>
										<h3>{l s='Step 7, 8, 9, 10 , 11, 12, 13' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #12 Use Authorized Redirect Url: ' mod='socialloginizer'}</b>{$vk_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/vk/vk3.jpg' alt='Facebook'/>
										</div>
										<h3>{l s='Step 14, 15, 16, 17' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/vk/vk4.jpg' alt='Facebook'/>
										</div>
										
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Vkontakte Settings -------------------->    
                                                    
                                                    <!--------------- Start - Wordpress Settings -------------------->

                                                    <div id="tab_wordpress" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Wordpress Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://developer.wordpress.com/apps/" target="_blank">{l s='Click here to get wordpress client id and client secret' mod='socialloginizer'}</a></span>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Wordpress Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['wordpress']['enable']) && ($velocity_social['wordpress']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[wordpress][enable]" id="wordpress_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[wordpress][enable]" id="wordpress_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Wordpress Client Id' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Wordpress Client Id' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[wordpress][client_id]" value="{if isset($velocity_social['wordpress']['client_id'])}{$velocity_social['wordpress']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Wordpress Client Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Wordpress Client Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[wordpress][client_secret]" value="{if isset($velocity_social['wordpress']['client_secret'])}{$velocity_social['wordpress']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="wordpress_mailchimp_key"/>									    
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="wordpress_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="wordpress_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div>
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="wordpress_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6, 7, 8, 9, 10' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #5 Use Website Url: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #6 Use Redirect Url: ' mod='socialloginizer'}</b>{$wordpress_url|escape:'htmlall':'UTF-8'}<br><b>{l s='For Step #7 Use javascript origin: ' mod='socialloginizer'}</b>{$manual_dir|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress3.jpg' />
										</div>
										<h3>{l s='Step 11' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress4.jpg' />
										</div>
										<h3>{l s='Step 12, 13' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress5.jpg' />
										</div>
										<h3>{l s='Step 14, 15, 16 , 17' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/wordpress/wordpress6.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Wordpress Settings -------------------->    
                                                    
                                                    <!--------------- Start - Dropbox Settings -------------------->

                                                    <div id="tab_dropbox" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Dropbox Settings' mod='socialloginizer'}</h4>
                                                            <div class="block">
                                                                <table class="form">
                                                                    
                                                                    <tr>
									    <td colspan="1" style='padding: 7px;color:#FF2707;'>
                                                                            <b style="color:black;">{l s='Note' mod='socialloginizer'}:</b> {l s='SSL must be enabled to use this option.' mod='socialloginizer'}
                                                                        </td>
                                                                        <td colspan="2" class="name">
                                                                            <span class="pad-right"><a href="https://www.dropbox.com/developers/apps" target="_blank">{l s='Click here to get dropbox app key and app secret' mod='socialloginizer'}</a></span>
                                                                        </td>
									
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='Enable/Disable' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enable Dropbox Login' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            {if isset($velocity_social['dropbox']['enable']) && ($velocity_social['dropbox']['enable'] eq 1)}                                                                               
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[dropbox][enable]" id="dropbox_login" checked="checked" />
                                                                                    </div>
                                                                            {else}
                                                                                    <div class="make-switch" data-on="primary" data-off="default">
                                                                                        <input class="make-switch" type="checkbox" value="1" name="velocity_social[dropbox][enable]" id="dropbox_login"/>
                                                                                    </div>
                                                                            {/if}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Dropbox App Key ' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Dropbox App Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[dropbox][client_id]" value="{if isset($velocity_social['dropbox']['client_id'])}{$velocity_social['dropbox']['client_id']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="name vertical_top_align"><span class="control-label"><span class="asterisk">*</span>{l s='Dropbox App Secret' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter Dropbox App Secret' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
                                                                            <input type="text" class="text-width" name="velocity_social[dropbox][client_secret]" value="{if isset($velocity_social['dropbox']['client_secret'])}{$velocity_social['dropbox']['client_secret']|escape:'htmlall':'UTF-8'}{/if}"/>
                                                                        </td>
                                                                    </tr>
                                                                     
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp Api Key' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Enter MailChimp Api Key' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<span style="display: inline-block;width:89%;">
                                                                            <input type="text" class="text-width" readonly id="dropbox_mailchimp_key"/>
									    </span>
									    <span><input type="button" value="Get List" disabled="disabled" onclick="return false" id="dropbox_listbtn" class="btn">
										</span>
                                                                        </td>
                                                                    </tr> 
								    
								    <tr class="free-disabled">
                                                                        <td class="name vertical_top_align"><span class="control-label">{l s='MailChimp List' mod='socialloginizer'}: </span>                                                                
                                                                            <i class="icon-question-sign" data-toggle="tooltip"  data-placement="top" data-original-title="{l s='Select MailChimp List ' mod='socialloginizer'}"></i>
                                                                        </td>
                                                                        <td class="settings">
										<div id="dropbox_list"></div>
                                                                        </td>
                                                                    </tr>                                                                                                                                                                                                                                                                          
                                                                </table>
                                                            </div> 
									<br>
									 <h4 class='velsof-tab-heading'>{l s='Steps To Configure:' mod='socialloginizer'}</h4>
									<div id="dropbox_accordian" class="accordian_container">
										<h3>{l s='Step 1' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/dropbox/dropbox1.jpg' />
										</div>
										<h3>{l s='Step 2' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/dropbox/dropbox2.jpg' />
										</div>
										<h3>{l s='Step 3, 4, 5, 6' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/dropbox/dropbox3.jpg' />
										</div>
										<h3>{l s='Step 7, 8, 9, 10' mod='socialloginizer'}</h3>
										<div class="accdiv">
											<pre><b>{l s='For Step #7 Use Redirect Url: ' mod='socialloginizer'}</b>{$dropbox_url|escape:'htmlall':'UTF-8'}</pre>
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/dropbox/dropbox4.jpg' />
										</div>
										<h3>{l s='Step 11, 12, 13, 14' mod='socialloginizer'}</h3>
										<div class="accdiv">
											 <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/manual_steps/dropbox/dropbox5.jpg' />
										</div>
									</div>
                                                        </div>
                                                    </div>

                                                    <!--------------- End - Dropbox Settings -------------------->   
                                                    
                                                    <!------------------ Stert - Statistics--------------------------------->
                                                    <div id="tab_statistics" class="tab-pane">
                                                            <div class="block free-disabled" style="padding-right: 5px;">
								    <h4 class='velsof-tab-heading'>{l s='Statistics ' mod='socialloginizer'}</h4>
								    
								    <div class="widget">
                                                                    <div class="widget-head">
                                                                        <h4 class="heading">{l s='Login vs Registered Count' mod='socialloginizer'}&nbsp({l s='Dummy Stats' mod='socialloginizer'})</h4>
                                                                    </div>
                                                                    <div class="graph_container">
									    <div id="legendContainer"></div>    
                                                                        <div id="analysis_graph">
                                                                            <div class="no_chart"><span>{l s='No data found' mod='socialloginizer'}</span></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
								    
								    <div class="widget">
								<div class="widget-head" style="color: black;padding-left: 10px;"> <div style="font-size: 14px;font-family: inherit;font-weight: 600;">{l s='Total Registrations ' mod='socialloginizer'} ({$count_account["rcount"]|escape:'htmlall':'UTF-8'})-{l s='Dummy Data' mod='socialloginizer'} </div></div>
								
								<div class="widget-body">
                                                                <table class="list form alternate pure-table pure-table-bordered" style="text-align:center;padding: 5px;width:90%;margin-left: 5%;">
									<thead style="text-align:center;">
										<tr>
											<td style="width:85px;">{l s='S. No.' mod='socialloginizer'}</td>
											<td>{l s='Account Type' mod='socialloginizer'}</td>
											<td>{l s='Total Registrations' mod='socialloginizer'}</td>
											<td>{l s='Total Login' mod='socialloginizer'}</td>
											<td>{l s='Action' mod='socialloginizer'}</td>
										</tr>
									</thead>
										</tr>
                                                                                <td style="width:85px;">
                                                                                        <span>1</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/fb_large.png' alt='Facebook'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[0]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[0]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="facebook" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>2</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/google_large.png' alt='Google'/>
                                                                                </td>
                                                                                <td>
											<span>{$each_account[1]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[1]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="google" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>3</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/live_large.png' alt='Live'/>
                                                                                </td>
                                                                                <td>
                                                                                    <span>{$each_account[2]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[2]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="live" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>4</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/linkedin_large.png' alt='Linkedin'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[3]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[3]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="linkedin" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>5</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/twitter_large.png' alt='Twitter'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[4]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[4]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="twitter" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>6</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/yahoo_large.png' alt='Yahoo'/>
                                                                                </td>
                                                                                <td>
                                                                                       <span>{$each_account[6]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[6]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="yahoo" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>7</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/instagram_large.png' alt='Instagram'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[5]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[5]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="instagram" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>8</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/amazon_large.png' alt='Amazon'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[7]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[7]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="amazon" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>9</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/paypal_large.png' alt='Paypal'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[8]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[8]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="paypal" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>10</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/foursquare_large.png' alt='Foursquare'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[9]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[9]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="foursquare" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>11</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/github_large.png' alt='Github'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[10]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[10]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="github" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>12</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/disqus_large.png' alt='Disqus'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[11]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[11]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="disqus" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>13</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/vk_large.png' alt='Vkontakte'/>
                                                                                </td>
                                                                                <td>
                                                                                        <span>{$each_account[12]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[12]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="vk" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>14</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/wordpress_large.png' alt='Wordpress'/>
                                                                                </td>
                                                                                <td>
											<span>{$each_account[13]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[13]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="wordpress" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                           <tr>
                                                                                <td style="width:85px;">
                                                                                        <span>15</span>
                                                                                </td>
                                                                                <td>
                                                                                        <img src='{$module_dir|escape:'htmlall':'UTF-8'}views/img/buttons/dropbox_large.png' alt='Dropbox'/>
                                                                                </td>
                                                                                <td>
											<span>{$each_account[14]['register_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
										<td>
                                                                                        <span>{$each_account[14]['login_count']|escape:'htmlall':'UTF-8'}</span>
                                                                                </td>
                                                                                <td>
                                                                                        <span><a href="javascript://" type="dropbox" onclick="return false">{l s='View Details' mod='socialloginizer'}</a></span>
                                                                                </td>
                                                                        </tr>
                                                                  
                                                                            
                                                                </table>
								</div>
									<div class="modal fade" id="socialstatmodal"  tab-index="-1" aria-hidden="true" aria-labelledby="modal_reason">										
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">

                                                                                <div class="modal-header" style="text-align: center;">
                                                                                    <button type="button" class="close" onclick="closeModalForm()"><span aria-hidden="true">&times;</span><span class="sr-only">{l s='Close' mod='socialloginizer'}</span></button>
                                                                                    <h4 class="modal-title velsof_modal_title" id="modal-reason" style="font-size:22px;display: inline;{if $ps_version eq 15}font-size:18px;{/if}">{l s='Social Customers ' mod='socialloginizer'}</h4>
										    <h4 class="modal-title velsof_modal_title" id="account-title" style="font-size:22px;display: inline;{if $ps_version eq 15}font-size:18px;{/if}"></h4>
                                                                                </div>
                                                                                <div class="modal-body" id="socialstatsdata">
											<div id="searchbox" style="display:block;">
												<span style="float:left;{if $ps_version eq 15}padding-top: 8px;{/if}">{l s='Show :' mod='socialloginizer'} &nbsp&nbsp&nbsp</span>
										<span style="float:left;{if $ps_version eq 15}width:12%;{/if}" >
													<select {if $ps_version eq 15}class="selectpicker vss_sc_ver15"{/if} id="socialuseritem">
														<option value="10">10</option>
														<option value="20">20</option>
														<option value="50">50</option>
														<option value="100">100</option>
													</select>
												</span>
													<input type="button" value="Search"  style="float:right;width:11%;" id="social-search-btn" class="btn btn-block velsof-btn-block btn-primary"/>
													<input type="text" name="socialloginizer[search]" placeholder="Search Email" id="social-email-search" style="float:right;width:30%;margin-right:5px;{if $ps_version eq 15}width:33%;line-height: 2.0;margin-top: 1px!important;{/if}" {if $ps_version eq 15}class="input-text-vs15"{/if} />
													<input type="hidden" name="account_type" value=" " id="set-account"/>
												</div>
											<div id="socialstatsuserdata" style="max-height:520px;overflow:auto;width: 100%;border-top: 1px solid #e5e5e5;padding-top: 5px;{if $ps_version eq 15}max-height:440px;{/if}">
												
												
											</div>
											<div class="paginator-block block right" style="margin-top: 10px;">
												
											</div>
                                                                                </div>
                                                                                <div class="modal-footer" style="margin-top:20px;">
											
											<button type="button" id="close_reason" class="btn btn-warning" onclick="closeModalForm()">{l s='Close' mod='socialloginizer'}</button>
											
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
								    </div>
                                                            </div>
                                                    </div>
                                                    <!--------------- End - Statistics  -------------------->   
                                                    <!--------------- Start - Frequently Asked Questions -------------------->
                                                                                                        
                                                    <div id="tab_faq" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Frequently Asked Questions' mod='socialloginizer'}</h4>
                                                            <br>
                                                            
                                                            <div class="row {if $ps_version eq 15}faq-row{/if}">
                                                                <div class="span">
                                                                    <p style="margin-bottom: 0; margin-right: 5px">
                                                                        <span class="question" style="font-weight: bold; font-size: 15px;">1. I have setup the social API according to the manual but it is still not working. What should I do?</span><br><br>
                                                                        <span class="answer" style="color: black;">
                                                                            In such case it is recommended to review the settings and data provided by you in the developer account of the respective social platform and if you can't figure out the issue, kindly <a target="_blank" href="http://www.knowband.com/helpdesk">contact us</a> with admin and FTP login details.
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div><hr>         
                                                            <div class="row {if $ps_version eq 15}faq-row{/if}">
                                                                <div class="span">
                                                                    <p style="margin-bottom: 0; margin-right: 5px">
                                                                        <span class="question" style="font-weight: bold; font-size: 15px;">2. How can I add the Social Login buttons to a new location or to my new page?</span><br><br>
                                                                        <span class="answer" style="color: black;">
                                                                            There is a feature (i.e. Short Code Functionality) to add Social Login buttons to what ever location you want. You just need to add a line of code to the tpl file of the page where you want to add the login buttons.<br><br>
                                                                            To add small sized buttons you can use
<pre>&lt;script&gt; document.write(loginizer_small); &lt;/script&gt;</pre><br/>
                                                                            <span class="answer" style="color: black;">To add large sized buttons you can use</span>
<pre>&lt;script&gt; document.write(loginizer_large); &lt;/script&gt;</pre>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div><hr>

                                                            <div class="row {if $ps_version eq 15}faq-row{/if}">
                                                                <div class="span">
                                                                    <p style="margin-bottom: 0; margin-right: 5px">
                                                                        <span class="question" style="font-weight: bold; font-size: 15px;">3.  Can you implement any other website login feature into this module for additional cost?</span><br><br>
                                                                        <span class="answer" style="color: black;">
                                                                        Yes, we can implement it for you. You can <a target="_blank" href="http://www.knowband.com/helpdesk">contact us</a> for any kind of customization in this module.
                                                                        </span><br><br>
                                                                    </p>
                                                                </div>
                                                            </div><hr>
                                                            
                                                            <div class="row {if $ps_version eq 15}faq-row{/if}">
                                                                <div class="span">
                                                                    <p style="margin-bottom: 0; margin-right: 5px">
                                                                        <span class="question" style="font-weight: bold; font-size: 15px;">4.  I have enabled buttons for left column on login page, but left column is not coming with these buttons.</span><br><br>
                                                                        <span class="answer" style="color: black;">
                                                                        Kindly ensure that left column is enabled in your theme for Login page. To check go to, Preferences->Themes->Advance Setting->Enable left column from there for login page.
                                                                        </span><br><br>
                                                                    </p>
                                                                </div>
                                                            </div><hr>
							    
							      <div class="row {if $ps_version eq 15}faq-row{/if}">
                                                                <div class="span ">
                                                                    <p style="margin-bottom: 0; margin-right: 5px">
                                                                        <span class="question" style="font-weight: bold; font-size: 15px;">5.  I need to change background of footer buttons block.</span><br><br>
                                                                        <span class="answer" style="color: black;">
									    In custom CSS use below code:<br><br>
									<pre>.sl_footer{ background-color:green !important; }</pre>
                                                                        </span><br><br>
                                                                    </p>
                                                                </div>
                                                            </div><hr>
                                                        </div>    
                                                    </div>

                                                    <!--------------- End - Frequently Asked Questions -------------------->
                                                    
                                                    <!--------------- Start - Suggestions Tab -------------------->
                                                                                                        
                                                    <div id="tab_suggest" class="tab-pane">
                                                        <div class="block">
                                                            <h4 class='velsof-tab-heading'>{l s='Suggestions' mod='socialloginizer'}</h4>
                                                        <div style= "  text-align:center;padding: 25px; height:140px;margin: 40px;margin-bottom:0px; background: aliceblue;">
                                                        <div><span style="font-size:18px;" >Want us to include some feature in next version of this module?</span>
                                                        <br>
                                                        <br>
                                                         <a target="_blank" href="http://addons.prestashop.com/ratings.php"><span style="margin-left:30%;max-width:40% !important;font-size:18px;" class='btn btn-block btn-success action-btn'>Share your idea</span></a><div>
                                                            </div>
                                                              
                                                   </div>
                                                  </div>
                                                  <div style="margin: 40px;border: 1px solid;color: rgb(240, 29, 53);padding: 15px;padding-top: 0px;"><br>*** If you like our module, don't forget to give us 5 STAR rating on the above link. This will definitely boost our morale.
						</div>          
						<div style="margin:40px;border:1px solid;">
						<p style="font-weight:600;font-size: 18px;border-bottom: 1px solid #000;padding: 5px;text-align: center;background-color: aliceblue;{if $ps_version eq 15}margin:0px;{/if}" >Features that we have added till yet based upon our customers suggestions.</p>
						<ol style="font-size:16px;margin-left:40px;" {if $ps_version eq 15}class="sug-ol"{/if}>
							<li> Compatibility for store with multiple languages <i style="color:rgb(237, 30, 121);">- by Aharon Titus</i></li>
							<li> Option to show popup when customer click on social login button <i style="color:rgb(237, 30, 121);">- by Merwyn</i></li>
						</ol>
						<span style="font-size:16px;padding-left:40px;">Thanks to all, as you helped us improve this module by sharing your ideas and pointing out bugs.</span><br/><br/><span style="font-size:16px;padding-left:40px;">Regards,</span><br/><span style="font-size:16px;padding-left:40px;">Knowband Team<br/><br/></span>
						</div>
						    <!--------------- End - Suggestions Tab -------------------->
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                </div>
            </div>          
        </div>                                                                                
    </div>
</div>

<style type="text/css">
    {literal}
	.faq-span{max-height:10px;}
	    .faq-row{background: rgba(230, 230, 236, 0.37);
  border-radius:3px;
  margin-top:10px;
  padding: 30px;
  cursor: pointer;
  padding-left: 10px;
  padding-top: 15px;}
	    #legendContainer {
    background-color: #fff;
    padding: 2px;
    margin-bottom: 8px;
    border-radius: 3px 3px 3px 3px;
    border: 1px solid #E6E6E6;
    display: inline-block;
    margin: 0 auto;
}
	    {/literal}
</style>
<script>
	var module_path = '{$action|escape:'quotes':'UTF-8'}';
	var sno = '{l s='S.No' mod='socialloginizer'}';
	var username = '{l s='User Name' mod='socialloginizer'}';
	var useremail = '{l s='Email' mod='socialloginizer'}';
	var ps_ver = '{$ps_version|escape:'quotes':'UTF-8'}';
	var lcount = '{$count_account["lcount"]|escape:'quotes':'UTF-8'}';
	var rcount = '{$count_account["rcount"]|escape:'quotes':'UTF-8'}';
	var graphdata = {$graphdata|escape:'quotes':'UTF-8'};
	var login = '{l s='Login' mod='socialloginizer'}';
	var register = '{l s='Register' mod='socialloginizer'}';
	var account_type = '{l s='Account Type' mod='socialloginizer'}';
	var count = '{l s='Count' mod='socialloginizer'}';
	var empty_list_msg = '{l s='No data found' mod='socialloginizer'}';
	var note = '{l s='Note: ' mod='socialloginizer'}';
	var no_record_msg = '{l s='No Record Found' mod='socialloginizer'}';
	var delete_msg = '{l s='Not showing deleted customers.' mod='socialloginizer'}';
	var dummy_list_msg = '{l s='The above list contains dummy data only for visualization purpose.' mod='socialloginizer'}';
        var user_login_count = '{l s='Login Count' mod='socialloginizer'}';
</script>
<script type='text/javascript'>
    {literal}
    $(function() {
        $('#sortable > li').tsort({
            attr:'data-index'
        });
        $('#sortable').sortable({
            placeholder: "portlet-placeholder",
            distance: 5,
            opacity: 0.8,
            update: function(e, ui) {
                $('#sortable').find('li').each(function(i, el) {
                    $(this).attr('data-index', $(el).index());
                    $(this).find('input').val($(el).index());
                });
            }
        });
        $("#sortable").disableSelection();
    });
    {/literal}
</script>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Order Lookup Result Page
*}
