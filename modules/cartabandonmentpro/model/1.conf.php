<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

$leftColumn  = false;
$rightColumn = false;
$txtsRight     = 0;
$txtsLeft     = 0;
$txtsCenter     = 2;
$colors     = 4;
$cartProducts = '<tr><td>%IMG%</td><td>%NAME%<br>%DESC%</td></tr>';
$style = 'text-decoration: none; display: block; color:#476688; font-size:30px;max-height:200px;';
$content = '<div align="center" style="background-color:#%color_1%"><center>
<table  cellpadding="0" cellspacing="0" border="0">
    <tbody>
        <tr>
            <td class="w640"  width="640" height="10"></td>
        </tr>

        <tr>
            <td align="center" class="w640"  width="640" height="20"></td>
        </tr>
        <tr>
            <td class="w640"  width="640" height="10"></td>
        </tr>


        <!-- entete -->
        <tr class="pagetoplogo">
            <td bgcolor="#%color_2%">
                <table cellpadding="0" cellspacing="0" border="0" bgcolor="#%color_2%">
                    <tbody>
                        <tr>
                            <td class="w30"  width="30"></td>
                            <td  class="w580"  width="580" valign="middle" align="center">
                                <div class="pagetoplogo-content">
                                    <center>
                                        <a href="%SHOP_LINK%" target="_blank">
                                            <img style="'.$style.'" src="%logo%" alt="%SHOP_NAME%"/>
                                        </a>
                                    </center>
                                </div>
                            </td>
                            <td class="w30"  width="30"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <!-- separateur horizontal -->
        <tr>
            <td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
        </tr>

         <!-- contenu -->
        <tr class="content">
            <td class="w640" class="w640"  width="640" bgcolor="#%color_3%">
                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr>
                            <td  class="w30"  width="30"></td>
                            <td  class="w580"  width="580">
                                <!-- une zone de contenu -->
                                <table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td class="w580"  width="580">
                                                %center_1%
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- fin zone -->
                            </td>
                            <td class="w30" class="w30"  width="30"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <!--  separateur horizontal de 15px de  haut-->
        <tr>
            <td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
        </tr>

        <!-- pied de page -->
        <tr class="pagebottom">
            <td class="w640"  width="640" bgcolor="#%color_4%">
                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#%color_4%">
                    <tbody>
                        <tr>
                            <td colspan="5" height="10"></td>
                        </tr>
                        <tr>
                            <td class="w30"  width="30"></td>
                            <td class="w580"  width="580" valign="top">
                                %center_2%
                            </td>

                            <td class="w30"  width="30"></td>
                        </tr>
                        <tr>
                            <td colspan="5" height="10"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="w640"  width="640" height="60"></td>
        </tr>
    </tbody>
</table></center></div>';

$contentEdit1 = '<div id="color_1_1_1_edit" align="center" style="background-color:#%color_1%"><center>
                <input id="color_picker_1_1_1_edit" class="color" name="color_picker_1_1_1" value="%color_1%" />
                <table  cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>

                        <tr>
                            <td align="center" class="w640"  width="640" height="20"></td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>


                        <!-- entete -->
                        <tr class="pagetoplogo">
                            <td id="color_1_2_1_edit" bgcolor="#%color_2%">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr><td colspan="3"><input id="color_picker_1_2_1_edit" class="color" name="color_picker_1_2_1" value="%color_2%" /></td></tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td  class="w580"  width="580" valign="middle" align="center">
                                                <div class="pagetoplogo-content">
                                                    <center>
                                                        <a href="%SHOP_LINK%" target="_blank">
                                                            <img style="'.$style.'" src="%logo%" alt="%SHOP_NAME%"/>
                                                        </a>
                                                    </center>
                                                </div>
                                            </td>
                                            <td class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!-- separateur horizontal -->
                        <tr>
                            <td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
                        </tr>

                         <!-- contenu -->
                        <tr class="content">
                            <td id="color_1_3_1_edit" class="w640" class="w640"  width="640" bgcolor="#%color_3%">
                                <input id="color_picker_1_3_1_edit" class="color" name="color_picker_1_3_1" value="%color_3%" />
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td  class="w30"  width="30"></td>
                                            <td  class="w580"  width="580">
                                                <!-- une zone de contenu -->
                                                <table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="w580"  width="580">
                                                                <textarea name="center_1_1_1" id="tpl1_center_1_1_edit">%center_1%</textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- fin zone -->
                                            </td>
                                            <td class="w30" class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!--  separateur horizontal de 15px de  haut-->
                        <tr>
                            <td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
                        </tr>

                        <!-- pied de page -->
                        <tr class="pagebottom">
                            <td id="color_1_4_1_edit" class="w640"  width="640" bgcolor="#%color_4%">
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" >
                                    <tbody>
                                        <tr><td colspan=3><input id="color_picker_1_4_1_edit" class="color" name="color_picker_1_4_1" value="%color_4%" /></td></tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td class="w580"  width="580" valign="top">
                                                <textarea name="center_1_2_1" id="tpl1_center_1_2_1_edit">%center_2%</textarea>
                                            </td>

                                            <td class="w30"  width="30"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="60"></td>
                        </tr>
                    </tbody>
                </table>
            </center></div>';

$contentEdit2 = '<div id="color_1_1_2_edit" align="center" style="background-color:#%color_1%;"><center>
                <input id="color_picker_1_1_2_edit" class="color" name="color_picker_1_1_2" value="%color_1%" />
                <table  cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>

                        <tr>
                            <td align="center" class="w640"  width="640" height="20"></td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>


                        <!-- entete -->
                        <tr class="pagetoplogo">
                            <td id="color_1_2_2_edit" bgcolor="#%color_2%">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr><td colspan="3"><input id="color_picker_1_2_2_edit" class="color" name="color_picker_1_2_2" value="%color_2%" /></td></tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td  class="w580"  width="580" valign="middle" align="center">
                                                <div class="pagetoplogo-content">
                                                    <center><a href="%SHOP_LINK%" target="_blank"><img  style="text-decoration: none; display: block; color:#476688; font-size:30px;max-height:200px;" src="%logo%" alt="%SHOP_NAME%"/></a></center>
                                                </div>
                                            </td>
                                            <td class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!-- separateur horizontal -->
                        <tr>
                            <td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
                        </tr>

                         <!-- contenu -->
                        <tr class="content">
                            <td id="color_1_3_2_edit" class="w640" class="w640"  width="640" bgcolor="#%color_3%">
                                <input id="color_picker_1_3_2_edit" class="color" name="color_picker_1_3_2" value="%color_3%" />
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td  class="w30"  width="30"></td>
                                            <td  class="w580"  width="580">
                                                <!-- une zone de contenu -->
                                                <table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="w580"  width="580">
                                                                <textarea name="center_1_1_2" id="tpl1_center_1_2_edit">%center_1%</textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- fin zone -->
                                            </td>
                                            <td class="w30" class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!--  separateur horizontal de 15px de  haut-->
                        <tr>
                            <td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
                        </tr>

                        <!-- pied de page -->
                        <tr class="pagebottom">
                            <td id="color_1_4_2_edit" class="w640"  width="640" bgcolor="#%color_4%">
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" >
                                    <tbody>
                                        <tr><td colspan=3><input id="color_picker_1_4_2_edit" class="color" name="color_picker_1_4_2" value="%color_4%" /></td></tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td class="w580"  width="580" valign="top">
                                                <textarea name="center_1_2_2" id="tpl1_center_2_2_edit">%center_2%</textarea>
                                            </td>

                                            <td class="w30"  width="30"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="60"></td>
                        </tr>
                    </tbody>
                </table>
            </center></div>';

$contentEdit3 = '<div id="color_1_1_3_edit" align="center" style="background-colo:#%color_1%">
                <input id="color_picker_1_1_3_edit" class="color" name="color_picker_1_1_3" value="%color_1%" />
                <table  cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>

                        <tr>
                            <td align="center" class="w640"  width="640" height="20"></td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="10"></td>
                        </tr>


                        <!-- entete -->
                        <tr class="pagetoplogo">
                            <td id="color_1_2_3_edit" bgcolor="#%color_2%">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr><td colspan="3"><input id="color_picker_1_2_3_edit" class="color" name="color_picker_1_2_3" value="%color_2%" /></td></tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td  class="w580"  width="580" valign="middle" align="center">
                                                <div class="pagetoplogo-content">
                                                    <center><a href="%SHOP_LINK%" target="_blank"><img  style="text-decoration: none; display: block; color:#476688; font-size:30px;max-height:200px;" src="%logo%" alt="%SHOP_NAME%"/></a></center>
                                                </div>
                                            </td>
                                            <td class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!-- separateur horizontal -->
                        <tr>
                            <td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
                        </tr>

                         <!-- contenu -->
                        <tr class="content">
                            <td id="color_1_3_3_edit" class="w640" class="w640"  width="640" bgcolor="#%color_3%">
                                <input id="color_picker_1_3_3_edit" class="color" name="color_picker_1_3_3" value="%color_3%" />
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td  class="w30"  width="30"></td>
                                            <td  class="w580"  width="580">
                                                <!-- une zone de contenu -->
                                                <table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="w580"  width="580">
                                                                <textarea name="center_1_1_3" id="tpl1_center_1_3_edit">%center_1%</textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- fin zone -->
                                            </td>
                                            <td class="w30" class="w30"  width="30"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <!--  separateur horizontal de 15px de  haut-->
                        <tr>
                            <td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
                        </tr>

                        <!-- pied de page -->
                        <tr class="pagebottom">
                            <td id="color_1_4_3_edit" class="w640"  width="640" bgcolor="#%color_4%">
                                <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" >
                                    <tbody>
                                        <tr><td colspan=3><input id="color_picker_1_4_3_edit" class="color" name="color_picker_1_4_3" value="%color_4%" /></td></tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                        <tr>
                                            <td class="w30"  width="30"></td>
                                            <td class="w580"  width="580" valign="top">
                                                <textarea name="center_1_2_3" id="tpl1_center_2_3_edit">%center_2%</textarea>
                                            </td>

                                            <td class="w30"  width="30"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" height="10"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="w640"  width="640" height="60"></td>
                        </tr>
                    </tbody>
                </table>
            </center></div>';
