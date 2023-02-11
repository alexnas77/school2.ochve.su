<?php
set_time_limit(0);
ini_set("default_socket_timeout","240");
require_once('Database.class.php');
require_once('placeholder.php');
require_once('Config.class.php');

$config=new Config();

  $dbname = $config->dbname;
  $dbhost = $config->dbhost;
  $dbuser = $config->dbuser;
  $dbpass = $config->dbpass;

  $db=new Database($dbname,$dbhost,$dbuser,$dbpass);
  $db->connect();
  $db->query("SET NAMES cp1251");

  echo "<h1>Обновление цен</h1>\n";


  if($_GET['go']=='true')
  {
   $handle = fopen("http://drel.su/web/prises.txt","r");
   if(!$handle)
   {
   echo "Файл http://drel.su/web/prises.txt недоступен<br />";
   echo "Используется http://93.95.98.196:8080/web/prises.txt<br />";
   $handle = fopen("http://93.95.98.196:8080/web/prises.txt","r");
   }
   $toupdate = 0;
   $num = 0;
   $currencies = array();
   $brands = array();
        $query = "SELECT * FROM currencies ORDER BY currency_id";
  		$db->query($query);
  		$results = $db->results();
  		foreach ($results as $res)
  		$currencies[$res->sign] = $res->currency_id;
  		$query = "SELECT * FROM brands ORDER BY id";
  		$db->query($query);
  		$results2 = $db->results();
  		foreach ($results2 as $res2)
  		$brands[strtolower($res2->name)] = $res2->id;
   echo "<table cellpadding=\"10\" cellspacing=\"0\" border=\"1\">\n";
   echo "<tr><td>&nbsp;</td><td><b>Модель</b></td><td><b>Производитель</b></td><td><b>Старая цена</b></td><td><b>Старая валюта</b></td><td><b>Новая цена</b></td><td><b>Новая валюта</b></td><td><b>Результат</b></td></tr>\n";
   while (!feof($handle)) {
    $buffer = fgets($handle, 4096);
    $vars = explode("\t",trim($buffer));
  		$query = sql_placeholder("SELECT products.* FROM products WHERE products.model = ? AND products.brand = ? LIMIT 1",
  							 $vars[0],
  							 $vars[1]);
  		$db->query($query);
  		$product = $db->result();
  		if($product)
  		{
  		$num++;
  		if($product->price == $vars[2] && $product->abbr == $vars[3])
  		{
  		$match = true;
  		}
  		else
  		{  		$match = false;
  		$query = sql_placeholder("UPDATE products SET price = ?, currency_id = ? WHERE model = ? AND brand = ?",
  							 $vars[2],
  							 $vars[3],
  							 $vars[0],
  							 $vars[1]);
  		$db->query($query);
  		if($db->affected_rows() == 1)
  		{
  		$toupdate++;
  		$sucsess = true;
  		}
  		else
  		{  		$sucsess = false;
  		//echo "$query<br />\n";
  		//echo $db->affected_rows()." рядов<br />\n";
  		//echo "Производитель: ".strtolower($vars[1])."<br />\n";
  		//echo "bid: ".$brands[strtolower($vars[1])]."<br />\n";  		}
  		}
  	echo "<tr ".($match?"style='background:#00FF00;'":"style='background:#FF8080;'")."><td>".$num."</td><td>".$vars[0]."</td><td>".$vars[1]."</td><td>".$product->price."</td><td>".$product->abbr."</td><td>".$vars[2]."</td><td>".$vars[3]."</td><td>&nbsp;".($sucsess?"УСПЕХ":"неудача")."</td></tr>\n";
  		}

	}
	echo "</table>\n";
   fclose($handle);
   echo "<br /><br />Обновлены цены <b>".$toupdate."</b> товаров\n";
  }
  else
  {
   $handle = fopen("http://drel.su/web/prises.txt","r");
   if(!$handle)
   {
   echo "Файл http://drel.su/web/prises.txt недоступен<br />";
   echo "Используется http://93.95.98.196:8080/web/prises.txt<br />";
   $handle = fopen("http://93.95.98.196:8080/web/prises.txt","r");
   }
   $found = 0;
   $toupdate = 0;
   $num = 0;
   echo "<a href=\"javascript:;\" onclick=\"document.getElementById('pop').style.display='';\">+ Показать подробности</a><br /><br />\n";
   echo "<table id=\"pop\" style=\"display:none;\" cellpadding=\"10\" cellspacing=\"0\" border=\"1\">\n";
   echo "<tr><td>&nbsp;</td><td><b>Модель</b></td><td><b>Производитель</b></td><td><b>Старая цена</b></td><td><b>Старая валюта</b></td><td><b>Новая цена</b></td><td><b>Новая валюта</b></td></tr>\n";
   while (!feof($handle)) {
    $buffer = fgets($handle, 4096);
    $vars = explode("\t",trim($buffer));
  		$query = sql_placeholder("SELECT products.* FROM products WHERE products.model = ? AND products.brand = ? LIMIT 1",
  							 $vars[0],
  							 $vars[1]);
  		$db->query($query);
  		$product = $db->result();
  		if($product)
  		{
  		$num++;  		$found++;
  		if($product->price == $vars[2] && $product->currency_id == $vars[3])
  		{
  		$match = true;
  		}
  		else
  		{
  		$toupdate++;  		$match = false;  		}  	echo "<tr ".($match?"style='background:#00FF00;'":"style='background:#FF8080;'")."><td>".$num."</td><td>".$vars[0]."</td><td>".$vars[1]."</td><td>".$product->price."</td><td>".$product->currency_id."</td><td>".$vars[2]."</td><td>".$vars[3]."</td></tr>\n";
  		}

	}
	echo "</table>\n";
   fclose($handle);
   echo "Обнаружено <b>".$found."</b> товаров в базе данных\n";
   echo "<br /><br />Нуждаются в обновлении цен <b>".$toupdate."</b> товаров\n";
   echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";
  }
?>