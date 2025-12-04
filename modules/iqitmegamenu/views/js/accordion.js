/**
* 2007-2016 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2016 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
* 
*/

$(document).ready(function(){
           
           
$("#iqitmegamenu-shower").click(function(){
  $("#iqitmegamenu-accordion").toggleClass("showedmenu");
});

$("#iqitmegamenu-accordion > li .responsiveInykator").on("click", function(event){

  if(false == $(this).parent().next().is(':visible')) {
    
    $('#iqitmegamenu-accordion > ul').removeClass('cbpm-ul-showed');
  }
  if($(this).text()=="+")
    $(this).text("-");
  else
    $(this).text("+");
  $(this).parent().children('ul').toggleClass('cbpm-ul-showed');
});

$('#iqitmegamenu-accordion > ul:eq(0)').show();


});