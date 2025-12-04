{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*} 
{if $name == 'PP1'}
    <div style="padding: 15px;">
        <table width="100%" style="height: auto;">
            <tbody>
                <tr>
                    <td style="text-align: center; font-size: 2.25rem; line-height: 2.75rem;padding-bottom: 10px;">
                        <h1 style="font-size: 2.25rem;">{l s='Enjoy 10% off now!' mod='g_cartreminder'}</h1>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: normal; font-size: 1.25rem; line-height: 1.75rem;">
                        <strong> {l s='Add this coupon to your cart.' mod='g_cartreminder'}
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group" style="width: 100%; text-align: center;">
                            {literal}{countdown}{/literal}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div class="form-group" style="width: 100%; text-align: center;">
                            <div style="color: rgba(97,97,97,1);font-size: 12px"> {l s='Please copy code' mod='g_cartreminder'}</div>
                            <div style="padding: 0px 3px 0px 3px; text-align: center; width: 50%; margin: 0 25% 0;">
                                <p style="font-weight: bold;font-size: 24px; margin-top: 3px;color: #0c0c0c; text-align: center; line-height: 50px; margin-bottom: 3px; background: rgb(255 255 255); border: dashed 2px rgb(198 234 238); width: 100%;">{literal}{voucher_code}{/literal}</p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div style="width: 100%; text-align: center; line-height: 59px;"><a style="padding: 10px 0px 10px 0px; background: #000000; color: #ffffff; width: 50%; margin: 0 25% 0;" href="%7Bcart_url%7D" target="_blank" class="btn btn-default">{l s='Proceed To checkout' mod='g_cartreminder'}</a></div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div style="width: 100%; text-align: center; line-height: 59px;">
                            <a style="padding: 10px 0px 10px 0px; background: transparent; width: 50%; margin: 0 25% 0;text-decoration: underline;" class="btn btn-link close_popup">{l s='No Thanks' mod='g_cartreminder'}</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
{elseif $name == 'PP2'}
    <div>
        <table width="100%" style="height: auto;">
            <tbody>
                <tr>
                    <td width="45%"  style="padding: 0;background-image: url('{literal}{urlimage_product_incart}{/literal}');background-position: center;background-repeat: no-repeat;background-size: cover;">
                    </td>
                    <td width="55%">
                        <div style="padding: 15px;">
                            <table width="100%" style="height: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; font-size: 2.25rem; line-height: 2.75rem;padding-bottom: 10px;">
                                            <strong>{l s='Get 10% OFF' mod='g_cartreminder'}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-weight: normal; font-size: 1.25rem; line-height: 1.75rem;"><strong>{l s='Add this coupon to your cart.' mod='g_cartreminder'}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group" style="width: 100%; text-align: center;">
                                                {literal}{countdown}{/literal}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div class="form-group" style="width: 100%; text-align: center;">
                                                <div style="color: rgba(97,97,97,1); font-size: 12px;">{l s='Please copy code' mod='g_cartreminder'}</div>
                                                <div style="padding: 0px 3px 0px 3px; text-align: center;">
                                                    <p style="font-weight: bold; font-size: 24px; margin-top: 3px; color: #ffffff; text-align: center; line-height: 50px; margin-bottom: 3px; background: rgb(255 235 150); border: dashed 2px rgb(127 203 204); width: 100%;">{literal}{voucher_code}{/literal}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="width: 100%; text-align: center; line-height: 59px;"><a style="padding: 10px; background: #000000; color: #ffffff; width: 100%;" href="%7Bcart_url%7D" target="_blank" class="btn btn-default">{l s='Proceed To checkout' mod='g_cartreminder'}</a></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="width: 100%; text-align: center; line-height: 59px;">
                                                <a style="padding: 10px 0px 10px 0px; background: transparent; width: auto;text-decoration: underline;" class="btn btn-link close_popup">{l s='No Thanks' mod='g_cartreminder'}</a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
{elseif $name == 'PP3'}
    <div>
        <table width="100%" style="height: auto;">
            <tbody>
                <tr>
                    <td width="55%">
                        <div style="padding: 15px;">
                            <table width="100%" style="height: 100%; color: rgb(44, 0, 58);">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; font-size: 2.25rem; line-height: 2.75rem;padding-bottom: 10px;">
                                            <strong>{l s='Get 10% OFF' mod='g_cartreminder'}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-weight: normal; font-size: 1.25rem; line-height: 1.75rem;"><strong>{l s='Add this coupon to your cart.' mod='g_cartreminder'}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group" style="width: 100%; text-align: center;">
                                                {literal}{countdown}{/literal}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div class="form-group" style="width: 100%; text-align: center;">
                                                <div style="color: rgba(97,97,97,1); font-size: 12px;">{l s='Please copy code' mod='g_cartreminder'}</div>
                                                <div style="padding: 0px 3px 0px 3px; text-align: center;">
                                                    <p style="font-weight: bold; font-size: 24px; margin-top: 3px; color: rgb(44, 0, 58); text-align: center; line-height: 50px; margin-bottom: 3px; background: rgb(255 255 255); border: dashed 2px rgb(209 203 241); width: 100%;">{literal}{voucher_code}{/literal}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="width: 100%; text-align: center; line-height: 59px;"><a style="padding: 10px; background: rgb(37, 0, 49); color: #ffffff; width: 100%;" href="%7Bcart_url%7D" target="_blank" class="btn btn-default">{l s='Proceed To checkout' mod='g_cartreminder'}</a></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">
                                            <div style="width: 100%; text-align: center; line-height: 59px;">
                                                <a style="padding: 10px 0px 10px 0px; background: transparent; width: auto;text-decoration: underline;" class="btn btn-link close_popup">{l s='No Thanks' mod='g_cartreminder'}</a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td width="45%"  style="padding: 0;background-image: url('{literal}{urlimage_product_incart}{/literal}');background-position: center;background-repeat: no-repeat;background-size: cover;">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
{/if}
