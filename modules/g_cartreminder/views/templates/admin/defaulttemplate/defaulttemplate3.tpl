{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}
<div  style="padding: 30px 0px; background: #FFFFFF; width: 100%; background-color: rgb(223, 227, 232);">
    <table class="table table-mail" style="max-width:600px;margin-top: 10px; background: #FFFFFF; " align="center">
        <tbody>
            <tr>
                <td align="center" style="padding: 7px 0; background: #FFFFFF;">
                    <table  style="max-width: 600px;" class="table content" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr>
                                <td width="100%" align="center" style="background: #FFFFFF; border-collapse: collapse;">
                                    <div style="margin: auto; background: #FFFFFF; width: 75%;">
                                        <table class="content"  border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-collapse: collapse; width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td align="center" class="logo" style="padding: 25px 20px 25px 20px; border: none; background: #FFFFFF;">
                                                        <a title="{literal}{shop_name}{/literal}" href="%7Bshop_url%7D"> <img src="{literal}{shop_logo}{/literal}" alt="{literal}{shop_name}{/literal}" /> </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" align="center" class="full_width" style="border-collapse: collapse; background: #FFFFFF;">
                                    <div style="margin: auto; width: 100%;">
                                        <table class="content" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block" style=" border-collapse: collapse; border: none; background: #FFFFFF; text-align: center;padding: 0 20px;">
                                                        <h2 style="font-size: 20px; font-weight: 700; margin: 0px 0px 10px;">{literal}{customer_firstname}{/literal} , you left something behind.</h2>
                                                        <div style="font-size: 14px; line-height: 24px; margin: 0px;">Looks like you got interrupted. We saved your cart for you. We're event got a special offer for you!</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr style="width: 100%; background: rgb(244, 246, 248); text-align: center; line-height: 1.5;">
                                <td width="100%" align="center" class="full_width" style="text-align: center; padding: 20px;">
                                    <div style="margin: auto; width: 100%;">
                                        <table class="content"  border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block" style="border-collapse: collapse; border: none;">
                                                        <div style="font-size: 24px; font-weight: 700; margin: 0px 0px 10px; text-align: center;"> 25% OFF YOUR ORDER </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="grid_block" style="border-collapse: collapse; border: none;">
                                                        <div style="font-size: 16px; ">A little gift to say thank you for subscribing today!</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="grid_block" style="border-collapse: collapse; border: none;">
                                                        <div style="font-size: 24px; line-height: 44px; border: 1px dashed rgb(69, 79, 91); background: rgb(255, 255, 255); margin: 16px 20px 5px;">{literal}{voucher_code}{/literal}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="grid_block" style="border-collapse: collapse; border: none;font-size: 16px;">
                                                        Expires on: {literal}{voucher_expirate_date}{/literal}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: auto;height: 55px;color: #ffffff;text-align: center;border-radius: 10px;width: 100%;text-align: center; ">
                                                        <a style="display: inline-block; margin-top: 16px; display: inline-block; text-align: center; font-size: 16px; padding: 12px 16px; border-radius: 3px; text-decoration: none; color: rgb(255, 255, 255); background-color: rgb(0, 0, 0);" href="{literal}{shop_url}{/literal}" target="_blank">Shop now</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="100% " align="center " class="full_width " style="border-collapse: collapse; background: #FFFFFF; ">
                                    <div style="margin: auto; width: 100%; ">
                                        <table class="content"  border="0 " cellpadding="0 " cellspacing="0 " style="border-collapse: collapse; width: 100%; ">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block " style="border-collapse: collapse; border: none; background: #FFFFFF; text-align: center; ">
                                                        <div style="font-size: 16px; ">{literal}{cart_product_2}{/literal}</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="100% " align="center " class="full_width " style="border-collapse: collapse; background: #FFFFFF; ">
                                    <div style="margin: auto; width: 100%; ">
                                        <table class="content"  border="0 " cellpadding="0 " cellspacing="0 " style="border-collapse: collapse; width: 100%; max-width: 600px;">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block " style="border-collapse: collapse; border: none; background: #FFFFFF; text-align: center; padding: 0 20px;">
                                                        <div style="font-size: 14px; line-height: 24px; margin: 0px; ">The total amount of your products is {literal}{total_cart_incl}{/literal} and it's not too late to complete your purchase! All the products are still waiting for you.</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr><td><div style="margin-bottom: 10px;"></div></td></tr>
                            <tr>
                                <td width="100% " align="center " class="full_width " style="border-collapse: collapse; background: #FFFFFF; ">
                                    <div style="margin: auto; width: 100%; ">
                                        <table class="content" border="0 " cellpadding="0 " cellspacing="0 " style="border-collapse: collapse; width: 100%; ">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block " style="border-collapse: collapse; border: none; background: #FFFFFF; ">
                                                        <table style="border-collapse: collapse;border-spacing: 0px; width: 100%; ">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center" style="width: auto;height: 55px;color: #ffffff;text-align: center;border-radius: 10px;width: 100%;text-align: center; border-radius:5px;">
                                                                        <a style="background: rgb(0, 0, 0); text-decoration: none; color: #ffffff; font-size: 16px; padding: 15px 20px; border-radius: 3px;display:inline-block; " href="{literal}{cart_url}{/literal}" target="_blank ">Checkout now</a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr><td><div style="margin-bottom: 10px;"></div></td></tr>
                            <tr>
                                <td width="100% " align="center " class="full_width " style="border-collapse: collapse; background: #FFFFFF; ">
                                    <div style="margin: auto; width: 100%; ">
                                        <table class="content"  border="0 " cellpadding="0 " cellspacing="0 " style="border-collapse: collapse; width: 100%; ">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block " style="border-collapse: collapse; border: none; background: #FFFFFF; height: 55px; ">
                                                        <table style="border-collapse: collapse;border-spacing: 0px; width: 100%; ">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="center" align="center" style="margin: 0; font-size: 14px; ">
                                                                        <span>
                                                                            <a href='#' style='color:#ededed; text-decoration: none;'>
                                                                                <img src='{$server_dir|escape:'htmlall'}modules/g_cartreminder/views/img/facebook.png' editable='true' alt='facebook' width='32' height='32' border='0' style=' display: inline-block;margin-right: 10px;'/>{*Escape is unnecessary*}
                                                                            </a>
                                                                            <a href='#' style='color:#ededed; text-decoration: none;'>
                                                                                <img src='{$server_dir|escape:'htmlall'}modules/g_cartreminder/views/img/youtube.png' editable='true' alt='youtube' width='32' height='32' border='0' style=' display: inline-block;margin-right: 10px;'/>{*Escape is unnecessary*}
                                                                            </a>
                                                                            <a href='#' style='color:#ededed; text-decoration: none;'>
                                                                                <img src='{$server_dir|escape:'htmlall'}modules/g_cartreminder/views/img/twitter.png' editable='true' alt='twitter' width='32' height='32' border='0' style='display: inline-block;margin-right: 10px;'/>{*Escape is unnecessary*}
                                                                            </a>
                                                                            <a href='#' style='color:#ededed; text-decoration: none;'>
                                                                                <img src='{$server_dir|escape:'htmlall'}modules/g_cartreminder/views/img/insagram.png' editable='true' alt='instagram' width='32' height='32' border='0' style=' display: inline-block;margin-right: 10px;'/>{*Escape is unnecessary*}
                                                                            </a>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr><td><div style="margin-bottom: 10px;"></div></td></tr>
                            <tr style="width: 100%; background-color: rgb(0, 0, 0); color: rgb(255, 255, 255); ">
                                <td width="100% " align="center " class="full_width " style="text-align: center; padding: 20px; ">
                                    <div style="margin: auto; width: 100%; ">
                                        <table class="content"  border="0 " cellpadding="0 " cellspacing="0 " style="border-collapse: collapse; width: 100%; ">
                                            <tbody>
                                                <tr>
                                                    <td class="grid_block " style=" border-collapse: collapse; border: none;color: #FFFFFF;  margin: 0px 0px 10px; background-color: rgb(0, 0, 0);">
                                                        <div style="font-size: 12px; text-align: center; line-height: 20px;">No longer want to receive these emails?</div>
                                                        <div style="font-size: 12px; line-height: 20px;"><a style="text-decoration: none; color: #FFFFFF;" href="{literal}{link_unsubscribe}{/literal}" target="_blank ">Unsubscribe</a></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="grid_block " style="border-collapse: collapse; border: none;color: #FFFFFF; background-color: rgb(0, 0, 0);">
                                                        <div style="font-size: 12px; line-height: 20px;">No. 1786 Charlington Gates, Liberty Suspendis Mettis, New York.</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>