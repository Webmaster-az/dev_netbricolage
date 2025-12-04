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



{if $gsnipreviewis_r_p != 0}

    <meta property="og:title" content="{$gsnipreviewname|escape:'htmlall':'UTF-8'}"/>

    <meta property="og:image" content="{$gsnipreviewimg|escape:'htmlall':'UTF-8'}"/>

    <meta property="og:description" content="{$gsnipreviewdescr|escape:'htmlall':'UTF-8'}" />

    <meta property="og:url" content="{$gsnipreviewreview_url|escape:'htmlall':'UTF-8'}"/>

    <meta property="og:type" content="product"/>

{/if}



{if $gsnipreviewpinvis_on == 1 && $gsnipreviewis_product_page != 0}



<meta property="og:title" content="{$product_name|escape:'htmlall':'UTF-8'}" />

<meta property="og:description" content="{$gsnipreviewpindesc|escape:'htmlall':'UTF-8'}" />

<meta property="og:type" content="product" />

<meta property="og:url" content="{if $gsnipreviewis_ssl == 1}https{else}http{/if}://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}" />

<meta property="og:site_name" content="{$shop_name|escape:'htmlall':'UTF-8'}" />

<meta property="og:price:amount" content="{$product_price_custom|escape:'htmlall':'UTF-8'}" />

<meta property="og:price:currency" content="{$currency_custom|escape:'htmlall':'UTF-8'}" />

<meta property="og:availability" content="{if $stock_string=='in_stock'}instock{else}{$stock_string|escape:'htmlall':'UTF-8'}{/if}" />



{/if}

{if $gsnipreviewrvis_on == 1}



{if $gsnipreviewrsson == 1}

<link rel="alternate" type="application/rss+xml" href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/rss.php" />

{/if}



{if $gsnipreviewis17 == 1}

{literal}

    <script type="text/javascript">

    var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';

    </script>

{/literal}

{/if}





{if $gsnipreviewis15 == 1 || $gsnipreviewis14 ==1}



    <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/font-custom.min.css" rel="stylesheet" type="text/css" media="all" />



{/if}



{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}



{if $gsnipreviewis15 == 0}

    <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/gsnipreview.css" rel="stylesheet" type="text/css" media="all" />



    <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/gsnipreview15.css" rel="stylesheet" type="text/css" media="all" />

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/gsnipreview.js"></script>

    <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/gsnipreview14.css" rel="stylesheet" type="text/css" media="all" />

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/r_stars.js"></script>



    {if $smarty.server.REQUEST_URI != "/" && $blockblogblog_h == 3}

        <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/owl.carousel.js"></script>

        <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/owl.carousel.css" rel="stylesheet" type="text/css" media="all" />

        <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/owl.theme.default.css" rel="stylesheet" type="text/css" media="all" />

    {/if}



{/if}







{literal}

<style type="text/css">

.pages span.nums a:hover { background:{/literal}{$gsnipreviewstylecolor|escape:'htmlall':'UTF-8'}{literal}; color:#fff; }

.pages span.nums b { color:#fff; background:#EF5F23}

</style>

{/literal}

{/if}



{/if}



{if $gsnipreviewrsoc_on == 1}

<!-- facebook button -->

{literal}

    <script type="text/javascript" src="{/literal}{$gsnipreviewfbliburl|escape:'htmlall':'UTF-8'}{literal}"></script>

{/literal}

<!-- facebook button -->

{/if}





{if $gsnipreviewis15 == 0 && $gsnipreviewis_product_page != 0 && $gsnipreviewis_filesr == 1}

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/jquery.ui.widget.min.js"></script>

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/jquery.fileupload.js"></script>

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/jquery.fileupload-process.js"></script>

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/jquery.fileupload-validate.js"></script>

    <script type="text/javascript" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/js/main-fileupload.js"></script>

{/if}





{if $gsnipreviewstarscat == 1 && $gsnipreviewis16 ==0}





<!--  product list settings -->

{if $gsnipreviewis_category == 1 && $gsnipreviewrvis_on == 1 && $gsnipreviewis17 ==0}



{literal}

<script type="text/javascript">



function makeStarsOnCategoryPage(){

		{/literal}

		{foreach from=$gsnipreview_data_products key="id_product" item="item_product"}





		{literal}

		append_block = $('#product_list li.ajax_block_product:has(a.ajax_add_to_cart_button[rel="ajax_id_product_{/literal}{$id_product|escape:'htmlall':'UTF-8'}{literal}"]) div.right_block');



		

		if(append_block.length > 0){

			var stars;

			stars = '<div class="catalog-stars">'+	

			{/literal}

			{section name=ratid loop=5}

			{if $smarty.section.ratid.index < $item_product.avg_rating} 

			   {literal}'<img src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}views/img/{/literal}{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}{literal}" class="gsniprev-img-star-category" alt="{/literal}{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}{literal}"/>'+{/literal}

			{else}

			   {literal}'<img src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}views/img/{/literal}{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}{literal}" class="gsniprev-img-star-category" alt="{/literal}{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}{literal}"/>'+{/literal}

			{/if}

			{/section}

				'<\/div>';

			{literal}	

			append_block.append('<a class="catalog-rating" href="{/literal}{$item_product.link|escape:'htmlall':'UTF-8'}{literal}">({/literal}{$item_product.count_review|escape:'htmlall':'UTF-8'}{literal})<\/a>'+stars+'<div class="gsnipreviews-clear"><\/div>');

			//append_block.append(stars);

				

		}

		{/literal}

		{/foreach}

		{literal}

}





	$(document).ready(function(){

		makeStarsOnCategoryPage();

	});









    {/literal}{if $gsnipreviewis16 ==1}{literal}



	$(document).on('click', '#grid', function(e){

		setTimeout(function(){

			makeStarsOnCategoryPage();

		}, 100);

	});



	$(document).on('click', '#list', function(e){

		setTimeout(function(){

			makeStarsOnCategoryPage();

		}, 100);

	});





    {/literal}{/if}{literal}

	</script>	

	{/literal}

	

{/if}

<!--  product list settings -->

{/if}









{if $gsnipreviewis_uprof == 1}



    <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/users.css" rel="stylesheet" type="text/css" media="all" />



{if $gsnipreviewis_show == 1}

{literal}

<script type="text/javascript">



    {/literal}{if $gsnipreviewislogged != 0}{literal}

    document.addEventListener("DOMContentLoaded", function(event) {

        $('document').ready( function() {

            var count1 = Math.random();

            var ph =  '<img class="avatar-header-gsnipreview" '+

                    ' src="{/literal}{$gsnipreviewavatar_thumb|escape:'htmlall':'UTF-8'}?re=' + count1+'{literal}"'+

                    ' />';







            if($('#header_user_info span'))

                $('#header_user_info span:last').append(ph);



            // for PS 1.6 >

            if($('.header_user_info')){

                $('.header_user_info .account:last').append(ph);



            }



            // for ps 1.7 >

            if($('.user-info')){

                $('.user-info .account:last').append(ph);



            }





        });

    });

    {/literal}{/if}{literal}

</script>

{/literal}

{/if}

{/if}











{if $gsnipreviewis_storerev == 1}







    {if $gsnipreviewis15 == 0}

        <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/storereviews.css" rel="stylesheet" type="text/css" media="all" />

        <link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/widgets.css" rel="stylesheet" type="text/css" media="all" />

    {literal}

        <script type="text/javascript" src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/views/js/storereviews.js"></script>

    {/literal}

    {/if}



    {if $gsnipreviewrssontestim == 1}

        <link rel="alternate" type="application/rss+xml" href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/rss_testimonials.php" />

    {/if}





{literal}

    <style type="text/css">

        .ps15-color-background{background-color:{/literal}{$gsnipreviewBGCOLOR_T|escape:'htmlall':'UTF-8'}{literal};}





        {/literal}{if $gsnipreviewt_leftside == 1 || $gsnipreviewt_rightside == 1}{literal}









        /* testimonials widget */

        {/literal}{if $gsnipreviewt_leftside == 1}{literal}





        div#gsnipreview-box.left_shopreviews .belt {



            border-radius: 5px;





        {/literal}{if $gsnipreviewis_mobile == 0}{literal}

            background-color: {/literal}{$gsnipreviewBGCOLOR_TIT|escape:'htmlall':'UTF-8'}{literal};

            -webkit-transform: rotate(90deg);

            -moz-transform: rotate(90deg);

            -ms-transform: rotate(90deg);

            -o-transform: rotate(90deg);

            transform: rotate(90deg);

            color: white;

            font-size: 15px;

            padding: 5px;

            right: 59px;

            text-align: center;

            top: 58px;

            box-sizing:border-box;

            width: 151px;

            height: 33px;

        {/literal}{else}{literal}

            background: {/literal}{$gsnipreviewBGCOLOR_TIT|escape:'htmlall':'UTF-8'}{literal} url("{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/views/img/t-left{/literal}{$gsnipreviewlang|escape:'htmlall':'UTF-8'}{literal}.png") repeat scroll 0 0;

            width: 33px;

            height: 151px;

        {/literal}{/if}{literal}

        }



        table.gsnipreview-widgets td.facebook_block{

            height: 151px;

        }



        {/literal}{/if}{literal}





        {/literal}{if $gsnipreviewt_rightside == 1}{literal}

        #gsnipreview-box.right_shopreviews .belt{



            border-radius: 5px;





        {/literal}{if $gsnipreviewis_mobile == 0}{literal}



            -webkit-transform: rotate(270deg);

            -moz-transform: rotate(270deg);

            -ms-transform: rotate(270deg);

            -o-transform: rotate(270deg);

            transform: rotate(270deg);

            background-color: {/literal}{$gsnipreviewBGCOLOR_TIT|escape:'htmlall':'UTF-8'}{literal};

            color: white;

            font-size: 15px;

            padding: 5px;

            right: -59px;

            text-align: center;

            top: 58px;

            box-sizing:border-box;

            width: 151px;

            height: 33px;



        {/literal}{else}{literal}

            background: {/literal}{$gsnipreviewBGCOLOR_TIT|escape:'htmlall':'UTF-8'}{literal} url("{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/views/img/t-right{/literal}{$gsnipreviewlang|escape:'htmlall':'UTF-8'}{literal}.png") repeat scroll 0 0;

            width: 33px;

            height: 151px;

        {/literal}{/if}{literal}

        }



        table.gsnipreview-widgets td.facebook_block{

            height: 151px;

        }



        {/literal}{/if}{literal}







        {/literal}{if $gsnipreviewt_leftside == 1}{literal}

        .gsnipreview-widgets .left_shopreviews{

            right: auto;



        }

        {/literal}{/if}{if $gsnipreviewt_rightside == 1}{literal}

        .gsnipreview-widgets .right_shopreviews {

            {/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:111px;{/literal}{/if}{literal}

            right: -{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px;

        }

        {/literal}{/if}{literal}



        div#gsnipreview-box.left_shopreviews{

            {/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:111px;{/literal}{/if}{literal}

            left: -{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px;

        }

        #gsnipreview-box .outside{

        {/literal}{if $gsnipreviewt_leftside == 1}{literal}

            float: right;

        {/literal}{else}{literal}

            float: right;

        {/literal}{/if}{literal}

        }



        /* testimonials widget */



        {/literal}



        {/if}

        {literal}



    </style>

{/literal}











{literal}

    <script type="text/javascript">

        document.addEventListener("DOMContentLoaded", function(event) {

        $(document).ready(function() {





            {/literal}



            {if $gsnipreviewt_leftside == 1 || $gsnipreviewt_rightside == 1}{literal}

            /* testimonials widget */



            {/literal}{if $gsnipreviewt_leftside == 1}{literal}



            {/literal}{if ($gsnipreviewis_mobile == 1 && $gsnipreviewmt_leftside == 1) || (!$gsnipreviewis_mobile == 1 && $gsnipreviewst_leftside == 1)}{literal}



            $(".gsnipreview-widgets .left_shopreviews .outside").hover(

                    function () {

                        $(".gsnipreview-widgets .left_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'auto',{/literal}{/if}{literal}left:'0px'}, 500);

                    },

                    function () {

                        $(".gsnipreview-widgets .left_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'111px',{/literal}{/if}{literal}left:'-{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px'}, 500);



                    }

            );

            $(".gsnipreview-widgets .left_shopreviews .belt").hover(



                    function () {

                        $(".gsnipreview-widgets .left_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'auto',{/literal}{/if}{literal}left:'0px'}, 500);

                    },

                    function () {



                        $(".gsnipreview-widgets .left_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'111px',{/literal}{/if}{literal}left:'-{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px'}, 500);



                    }

            );

            {/literal}{/if}{literal}



            {/literal}{/if}{literal}



            {/literal}{if $gsnipreviewt_rightside == 1}{literal}



            {/literal}{if ($gsnipreviewis_mobile == 1 && $gsnipreviewmt_rightside == 1) || (!$gsnipreviewis_mobile == 1 && $gsnipreviewst_rightside == 1)}{literal}



            $(".gsnipreview-widgets .right_shopreviews .outside").hover(

                    function () {

                        $(".gsnipreview-widgets .right_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'auto',{/literal}{/if}{literal}right:'0px'}, 500);

                    },

                    function () {

                        $(".gsnipreview-widgets .right_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'111px',{/literal}{/if}{literal}right:'-{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px'}, 500);



                    }

            );

            $(".gsnipreview-widgets .right_shopreviews .belt").hover(

                    function () {

                        $(".gsnipreview-widgets .right_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'auto',{/literal}{/if}{literal}right:'0px'}, 500);

                    },

                    function () {

                        $(".gsnipreview-widgets .right_shopreviews").stop().animate({{/literal}{if $gsnipreviewis_mobile  == 1}{literal}height:'111px',{/literal}{/if}{literal}right:'-{/literal}{if $gsnipreviewis16 == 1 || $gsnipreviewis14 == 1}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 5}{else}{$gsnipreviewt_width|escape:'htmlall':'UTF-8' + 23}{/if}{literal}px'}, 500);



                    }

            );

            {/literal}{/if}{literal}



            {/literal}{/if}{literal}



            /* testimonials widget */

            {/literal}{/if}{literal}









        });

        });

    </script>

{/literal}



{/if}