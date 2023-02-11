{php}
error_reporting(0);
//$ctype="text/html; charset=Windows-1251";
$ctype="application/vnd.ms-excel";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: $ctype");
//header('Content-disposition: attachment; filename="bill.html"');
header('Content-disposition: attachment; filename="bill.xls"');
header("Content-Transfer-Encoding: binary");
{/php}
{eval var=$HTML}