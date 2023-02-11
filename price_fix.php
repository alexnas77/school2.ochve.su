<?php
set_time_limit(0);
ini_set("default_socket_timeout","240");
require_once('Database.class.php');
//require_once('Database.remote2.php');
require_once('placeholder.php');
require_once('Config.class.php');

  $config=new Config();

  /*$tunnel = "http://drel.su/tunnel_mysql2.php";
  $dbname = "alexn_drel3";
  $dbhost = "localhost";
  $dbuser = 'alexn';
  $dbpass = 'Ytdpkjvftim';*/

  $dbname2 = $config->dbname;
  $dbhost2 = $config->dbhost;
  $dbuser2 = $config->dbuser;
  $dbpass2 = $config->dbpass;

  /*$db=new Database_remote($tunnel,$dbname,$dbhost,$dbuser,$dbpass);
  $db->connect();
  $db->query("SET NAMES cp1251");*/

  $db2=new Database($dbname2,$dbhost2,$dbuser2,$dbpass2);
  $db2->connect();
  $db2->query("SET NAMES cp1251");

  echo "<h1>Исправление цен</h1>\n";

/*  $category_id = 18;

  $query = sql_placeholder("SELECT name FROM categories WHERE category_id = ?", $category_id);
  $db->query($query);
  $category = $db->result();

  echo "Переносятся товары из БД <b>$dbname</b> категории <b>".$category->name."</b>\n";

  $category_id2 = 43;

  $query = sql_placeholder("SELECT name FROM categories WHERE category_id = ?", $category_id2);
  $db2->query($query);
  $category2 = $db2->result();

  echo "в БД <b>$dbname2</b> категорию <b>".$category2->name."</b>\n";*/

  $start = "2013-10-01";
  $end = "2014-10-01";

$query = "SELECT * FROM settings WHERE domain='/'";
$db2->query($query);
$sts = $db2->results();
foreach($sts as $s)
{
    $name = $s->name;
        $settings->$name = $s->value;
}

$breakfast = !empty($product1->new_breakfast) ? $product1->new_breakfast : $settings->breakfast;
$lunch = !empty($product1->new_lunch) ? $product1->new_lunch : $settings->lunch;
$lunch2 = !empty($product1->new_lunch2) ? $product1->new_lunch2 : $settings->lunch2;
$dinner = !empty($product1->new_dinner) ? $product1->new_dinner : $settings->dinner;

$query = sql_placeholder("SELECT stat.* FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE stat.date BETWEEN ? AND ?
    AND products.enabled = 1
    ", date("Y-m-d",strtotime($start)), date("Y-m-d",strtotime($end)));
$db2->query($query);
$products = $db2->results();
  $num_products = $db2->num_rows();
  //echo "<pre>\n";  print_r($products);  print "</pre>\n";

  if($_GET['go']=='true')
  {

  	echo "<ol>\n";

  	foreach($products as $id=>$product)
  	{
        $delta = $product->abreakfast*$breakfast+$product->alunch*$lunch+$product->alunch2*$lunch2+$product->adinner*$dinner-floatval($product->cash)-floatval($product->card);

        $qstring = sql_placeholder("(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$product->product_id,$product->date,$product->category_id,$product->breakfast,$product->lunch,$product->lunch2,$product->dinner,floatval($product->cash),floatval($product->card),floatval($breakfast),floatval($lunch),floatval($lunch2),floatval($dinner),$delta);
        $query = ("REPLACE INTO `stat` (product_id,date,category_id,breakfast,lunch,lunch2,dinner,cash,card,new_breakfast,new_lunch,new_lunch2,new_dinner,delta) VALUES ".$qstring);
        echo "<li>".$query."</li>";
        $db2->query($query);
  	}


  	echo "</ol>\n";

  }
  else
  {
   echo "Обнаружено $num_products элементов.\n";
   echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";
  }
?>