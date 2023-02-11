<?PHP

require_once('Index.admin.php');

$index = new Index($a = 0);
$index->fetch();
print $index->body;

?>