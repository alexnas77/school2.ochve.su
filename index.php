<?PHP
//error_reporting(E_ALL);
//@ini_set("display_errors","on");
//require_once('Index.class.php');
require_once('autoloader.php');

$index = new Index($a = 0);
$index->fetch();
print $index->body;

?>