<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <base href='http{php} if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==="on") {echo"s";} echo"://"; print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir.'/'; {/php}'>
    <TITLE>{$Title}</TITLE>
    <LINK REL='stylesheet' HREF='style.css'>
    <LINK href="baloon/css/baloon.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="baloon/css/jquery.fancybox-1.3.4.css" media="screen" />
    {*<link rel='stylesheet' type='text/css' href='calendar/calendar.css'>*}
    <link rel='stylesheet' type='text/css' href='baloon/css/jquery-ui.min.css'>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=Windows-1251'>
    <META NAME='description' content='{$Description}'>
    <META NAME='keywords' content='{$Keywords}'>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <SCRIPT src="baloon/js/default.js" type="text/javascript"></SCRIPT>
    <SCRIPT src="baloon/js/validate.js" type="text/javascript"></SCRIPT>
    <SCRIPT src="baloon/js/baloon.js" type="text/javascript"></SCRIPT>
    <SCRIPT src="baloon/js/js.js" type="text/javascript"></SCRIPT>
    <SCRIPT src="baloon/js/swfobject.js" type="text/javascript"></SCRIPT>
    {*<script src="calendar/calendar.js" type="text/javascript"></script>
    <script src="calendar/calendas.js" type="text/javascript"></script>*}
    <script type="text/javascript" src="baloon/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="baloon/js/jquery.easing-1.3.pack.js"></script>
    <script type="text/javascript" src="baloon/js/jquery.fancybox-1.3.4.js"></script>
    <script src="baloon/js/jquery-ui.js" type="text/javascript"></script>
            {*function fixheight()
            {
                document.getElementById('content').style.height = (document.documentElement.clientHeight || document.body.clientHeight)-440+'px';
            }*}
    {literal}
    	<style>
    		.warning {
    			position: sticky;
    			top: 0;
    			min-height: 1em;
    			text-align: center;
    			padding: 1.5em;
    			font-size: 16px;
    			background: red;
    			color: white;
    			z-index: 1000;
    		}
    		.warning a {
    			font-size: 16px;
    			color: white;
    			text-decoration: underline;
    		}
    		.warning a:hover {
    			text-decoration: none;
    		}
    	</style>
        <SCRIPT type="text/javascript">
        </SCRIPT>
    {/literal}
</head>

<body{* onload="fixheight();" onresize="fixheight();"*}>
{if strpos($smarty.server.HTTP_HOST,'zdorovpitanie.ru')===false}
<div class="warning">
ВНИМАНИЕ! Этот сайт предназначен ТОЛЬКО ДЛЯ ТЕСТИРОВАНИЯ. Для РАБОТЫ пожалуйста перейдите на <a href="http{if $smarty.server.HTTPS === "on"}s{/if}://{$smarty.server.HTTP_HOST|regex_replace:"/\.[^\.]+\.[^\.]+$/":".zdorovpitanie.ru"}{$smarty.server.REQUEST_URI}">http{if $smarty.server.HTTPS === "on"}s{/if}://{$smarty.server.HTTP_HOST|regex_replace:"/\.[^\.]+\.[^\.]+$/":".zdorovpitanie.ru"}{$smarty.server.REQUEST_URI}</a>
</div>	    
{/if}
<div align="center" style="font-size:16px;color: #919191;background-color: White;padding: 10px 20px;"><b>{$Settings->company_name|default:""}</b></div>
    {$BodyContent}
</body>
</html>