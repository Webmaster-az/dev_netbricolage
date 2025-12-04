{*
* 2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*
*  @author Areama <contact@areama.net>
*  @copyright  2018 Areama
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*}

{extends file="helpers/form/form.tpl"}
{block name="after"}
    <audio id="arlsf-sound" src=""></audio>
{/block}
{block name="script"}
    arlsfToggleFields();
    arlsfTogglePositionFields();
    arlsfToggleMPositionFields();
    
    $(document).ready(function(){
        $('.prestashop-switch').click(function() {
            arlsfToggleFields();
        });
        $('#AR_LSF_POSITION').change(function() {
            arlsfTogglePositionFields();
        });
        
        $('#AR_LSF_M_POSITION').change(function() {
            arlsfToggleMPositionFields();
        });
    });

    function arlsfToggleFields(){
        if ($('#AR_LSF_NOTIFICATION_on').is(':checked')){
            $('.ar_lsf_notification').removeClass('hidden');
        }else{
            $('.ar_lsf_notification').addClass('hidden');
        }
    }
    
    $('#AR_LSF_ORDER_SORT').change(function(){
        if ($(this).val() == 'random'){
            $('#AR_LSF_ORDER_LOOP_on').click();
        }
    });
    
    $('#AR_LSF_SOUND').change(function(){
        if ($(this).val() != 0){
            $('#arlsf-sound').attr('src', '{$path|escape:'htmlall':'UTF-8'}views/sound/' + $(this).val());
            document.getElementById('arlsf-sound').play();
        }
    });
    
    function arlsfTogglePositionFields(){
        switch($('#AR_LSF_POSITION').val()){
            case 'top_left':
                swithPositionField('#AR_LSF_TOP', false);
                swithPositionField('#AR_LSF_LEFT', false);
                swithPositionField('#AR_LSF_RIGHT', true);
                swithPositionField('#AR_LSF_BOTTOM', true);
                break;
            case 'top_right':
                swithPositionField('#AR_LSF_TOP', false);
                swithPositionField('#AR_LSF_LEFT', true);
                swithPositionField('#AR_LSF_RIGHT', false);
                swithPositionField('#AR_LSF_BOTTOM', true);
                break;
            case 'bottom_left':
                swithPositionField('#AR_LSF_TOP', true);
                swithPositionField('#AR_LSF_LEFT', false);
                swithPositionField('#AR_LSF_RIGHT', true);
                swithPositionField('#AR_LSF_BOTTOM', false);
                break;
            case 'bottom_right':
                swithPositionField('#AR_LSF_TOP', true);
                swithPositionField('#AR_LSF_LEFT', true);
                swithPositionField('#AR_LSF_RIGHT', false);
                swithPositionField('#AR_LSF_BOTTOM', false);
                break;
        }
    }
    
    function arlsfToggleMPositionFields(){
        switch($('#AR_LSF_M_POSITION').val()){
            case 'top':
                $('.field_m_top').removeClass('hidden');
                $('.field_m_bottom').addClass('hidden');
                break;
            case 'bottom':
                $('.field_m_top').addClass('hidden');
                $('.field_m_bottom').removeClass('hidden');
                break;
        }
    }
    
    function swithPositionField(id, disabled){
        if (disabled){
            $(id).addClass('disabled');
            $(id).val('auto');
            $(id).parents('.form-group').addClass('hidden');
        }else{
            $(id).removeClass('disabled');
            $(id).parents('.form-group').removeClass('hidden');
            if ($(id).val() == 'auto'){
                $(id).val('0');
            }
        }
    }
{/block}