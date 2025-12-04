<?php
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

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
$obj_gsnipreviewhelp = new gsnipreviewhelp();

$_name = "gsnipreview";



if (version_compare(_PS_VERSION_, '1.5', '<')){
	require_once(_PS_MODULE_DIR_.$_name.'/backward_compatibility/backward.php');
} else{
	$cookie = Context::getContext()->cookie;
}

$id_lang = (int)$cookie->id_lang;
$data_language = $obj_gsnipreviewhelp->getfacebooklib($id_lang);
$rss_title =  Configuration::get($_name.'rssname_'.$id_lang);
$rss_description =  Configuration::get($_name.'rssdesc_'.$id_lang);

if(version_compare(_PS_VERSION_, '1.6', '>')){
$_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__; 
} else {
$_http_host = _PS_BASE_URL_.__PS_BASE_URI__;
}

// Lets build the page
$rootURL = $_http_host."feeds/";
$latestBuild = date("r");




// Lets define the the type of doc we're creating.
header('Content-Type:text/xml; charset=utf-8');
$createXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";


if(Configuration::get($_name.'rsson') == 1){

$createXML .= "<rss version=\"0.92\">\n";
$createXML .= "<channel>
	<title><![CDATA[".$rss_title."]]></title>
	<link>$rootURL</link>
	<description>".$rss_description."</description>
	<lastBuildDate>$latestBuild</lastBuildDate>
	<docs>http://backend.userland.com/rss092</docs>
	<language>".$data_language['rss_language_iso']."</language>
	<image>
			<title><![CDATA[".$rss_title."]]></title>
			<url>".$_http_host."img/logo.jpg</url>
			<link>$_http_host</link>
	</image>
";

$data_rss_items = $obj_gsnipreviewhelp->getItemsForRSS();

//echo "<pre>"; var_dump($data_rss_items); exit;

foreach($data_rss_items['items'] as $_item)
{
	$page = $_item['page']; 
	$description = $_item['seo_description'];
	$title = $_item['title'];
	$img = $_item['img'];
	
	$createXML .= $obj_gsnipreviewhelp->createRSSFile($title,$description,$page, $img);
}
$createXML .= "</channel>\n </rss>";
// Finish it up
}

echo $createXML;















?>