<script>
    var current_url = window.location.href;
    var result = current_url.search("type=live");
    if(result == -1)
    {
        var search_result=current_url.search('module/socialloginizer/live');
        if (search_result != -1)
        {
            new_url=current_url.replace('module/socialloginizer/live?code', 'index.php?fc=module&module=socialloginizer&controller=live&code');
            window.location=new_url;
        }
    } 
    
    result = current_url.search("type=vk");
    if(result == -1)
    {
        var search_result=current_url.search('module/socialloginizer/vk');
        if (search_result != -1)
        {
            new_url=current_url.replace('module/socialloginizer/vk?code', 'index.php?fc=module&module=socialloginizer&controller=vk&code');
            window.location=new_url;
        }
    }
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
* Social Loginizer Additional Script Header
*}