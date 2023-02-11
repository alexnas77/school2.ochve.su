<?php
set_time_limit(0);
ini_set("default_socket_timeout","240");
require_once('Config.class.php');
require_once('Database.class.php');
require_once('placeholder.php');

  $config=new Config();

  $db=new Database($config->dbname,$config->dbhost,$config->dbuser,$config->dbpass);
  $db->connect();
  $db->query("SET NAMES cp1251");

  echo "<h1>Исправление products_fotos</h1>\n";

  $category_id = 15;

  $query = sql_placeholder("SELECT * FROM products_fotos ORDER BY product_id,foto_id");
  $db->query($query);
  $products_fotos = $db->results();

  //echo "<pre>\n";  print_r($products_fotos);  print "</pre>\n";


  if($_GET['go']=='true')
  {
  	echo "<ol>\n";

  	$last_product_id = 0;

  	foreach($products_fotos as $id=>$products_foto)
  	{
  		echo "<li>Товар ID = <b>".$products_foto->product_id."</b> Фото ID = <b>".$products_foto->foto_id."</b><br />\n";
  		if($last_product_id != $products_foto->product_id)
  		{  		$last_product_id = $products_foto->product_id;
  		$last_foto_id = 0;
  		echo "<u>Назначен новый Фото ID = <b>".$last_foto_id."</b></u></li>\n";  		}
  		else
  		{  		$last_foto_id++;
  		echo "<span style='color:green'><u>Назначен новый Фото ID = <b>".$last_foto_id."</b></u></span></li>\n";  		}
  		$query = sql_placeholder("UPDATE products_fotos SET foto_id = ? WHERE product_id = ? AND foto_id = ?",
  								$last_foto_id,
  								$products_foto->product_id,
  								$products_foto->foto_id);
  		$db->query($query);
        echo "<br />$query<br /><br />\n";  	}

  	echo "</ol>\n";
  }
  else
  {   echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";  }

  $db->disconnect();

?>