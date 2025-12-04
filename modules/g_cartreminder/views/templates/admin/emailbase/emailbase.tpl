{*
* Do not edit the file if you want to upgrade the module in future.
*
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
	<head>
        <title>email</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <style type="text/css">
            body {literal}margin: 0; padding: 0; min-width: 100%!important;{/literal}
            .content {literal}width: 100%; max-width: 600px;{/literal}
        </style>
    </head>
    <body>
        {if isset($version) && $version == 'PS16'}
            {$emailcontentup}{* $emailcontentup is html content, no need to escape*}
            {elseif $version == 'PS17'}
                {$emailcontentup nofilter}{* $emailcontentup is html content, no need to escape*}
        {/if}
        {if $check != '1'}
            <img src="https://www.google-analytics.com/collect?v=1&tid={literal}{google_tracking_id}{/literal}&cid=501&t=event&ec=email&ea=open&dp=%2Femail%2Ftracking&dt=Email%20abandoned%20cart%20reminder%205%20in%201" />
        {/if}
    </body>
</html>
