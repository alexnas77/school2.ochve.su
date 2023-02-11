<?php
set_time_limit(0);
ini_set("default_socket_timeout","240");
require_once('Database.class.php');
require_once('placeholder.php');

  $dbname = "db_drel2";
  $dbhost = "localhost";
  $dbuser = 'root';
  $dbpass = '123';

  $dbname2 = "frezera";
  $dbhost2 = "localhost";
  $dbuser2 = 'root';
  $dbpass2 = '123';

  $db=new Database($dbname,$dbhost,$dbuser,$dbpass);
  $db->connect();
  $db->query("SET NAMES cp1251");

  $db2=new Database($dbname2,$dbhost2,$dbuser2,$dbpass2);
  $db2->connect();
  $db2->query("SET NAMES cp1251");

  echo "<h1>Перенос товаров</h1>\n";

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
	//$brand = 'Dewalt';
    $catlist = "155";
  $query = "SELECT * FROM products WHERE category_id IN(".$catlist.") ORDER BY product_id";
  //echo "<br />$query<br />\n";
  $db->query($query);
  $products = $db->results();
  $num_products = $db->num_rows();
  //echo "<pre>\n";  print_r($products);  print "</pre>\n";


  if($_GET['go']=='true')
  {
  	echo "<ol>\n";

  	foreach($products as $id=>$product)
  	{
  		$query = sql_placeholder("SELECT * FROM products WHERE brand = ? AND category_name = ? AND model = ? AND price = ? AND currency_id = ? AND guarantee = ? AND description = ? AND body = ? AND quantity = ? AND enabled = ? AND hit = ?",
  							 $product->brand,
  							 $product->category_name,
  							 $product->model,
  							 $product->price,
  							 $product->currency_id,
  							 $product->guarantee,
  							 $product->description,
  							 $product->body,
  							 $product->quantity,
  							 $product->enabled,
  							 $product->hit);
  		$db2->query($query);
  		$product2 = $db2->result();
  	   if($db2->num_rows()!=1)
  	    {
  		echo "<li>Переносится Товар ID = ".$product->product_id." <b>".$product->model."</b><br />\n";
  		$query = "SELECT MAX(product_id) as max FROM products";
  		$db2->query($query);
  		$res2 = $db2->result();
  		$product_id2 = $res2->max+1;
  		echo "в целевую БД с ID2 = ".$product_id2."<br />\n";
  		$query = sql_placeholder("INSERT INTO products (product_id, category_id, brand, category_name, model, price, currency_id, guarantee, description, body, quantity, enabled, hit, order_num) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
  							 $product_id2,
  							 $product->category_id,
  							 $product->brand,
  							 $product->category_name,
  							 $product->model,
  							 $product->price,
  							 $product->currency_id,
  							 $product->guarantee,
  							 $product->description,
  							 $product->body,
  							 $product->quantity,
  							 $product->enabled,
  							 $product->hit,
  							 $product_id2);
  		$db2->query($query);
  		$query = sql_placeholder("INSERT INTO products_properties (product_id, category_id) VALUES (?, ?)",
  							 $product_id2,
  							 $product->category_id);
  		$db2->query($query);
  		$query = sql_placeholder("SELECT * FROM products_fotos WHERE product_id = ?",$product->product_id);
  		$db->query($query);
  		$fotos = $db->results();
  			foreach($fotos as $fid=>$foto)
  			{


  			$image = file_get_contents("http://drel.su/foto/storefront/".urlencode(trim($foto->filename)));

            if($image)
            {
  				if(!is_file(dirname(__FILE__)."/foto/storefront/".$foto->filename))
  				{
  				file_put_contents(dirname(__FILE__)."/foto/storefront/".trim($foto->filename),$image);
  					if(is_file(dirname(__FILE__)."/foto/storefront/".trim($foto->filename)))
  					{echo "Фото №".$foto->foto_id." ".$foto->filename."<br />\n";
  					$query = sql_placeholder("INSERT INTO products_fotos (product_id, foto_id, filename) VALUES (?, ?, ?)",
  							 $product_id2,
  							 $foto->foto_id,
  							 $foto->filename);
  					$db2->query($query);
  					}
  					else
  					echo "<span style='color:Red'>Фото №".$foto->foto_id." не записано</span><br /><br />
            		<a href=\"http://drel.su/foto/storefront/".urlencode(trim($foto->filename))."\">".$foto->filename."</a><br />\n";
  				}
  				elseif(!is_file(dirname(__FILE__)."/foto/storefront/2_".$foto->filename))
  				{
  				file_put_contents(dirname(__FILE__)."/foto/storefront/2_".trim($foto->filename),$image);
  					if(is_file(dirname(__FILE__)."/foto/storefront/2_".trim($foto->filename)))
  					{echo "<span style='color:green'>Фото №".$foto->foto_id." 2_".$foto->filename."</span><br />\n";
  					$query = sql_placeholder("INSERT INTO products_fotos (product_id, foto_id, filename) VALUES (?, ?, ?)",
  							 $product_id2,
  							 $foto->foto_id,
  							 "2_".$foto->filename);
  					$db2->query($query);
  					}
  					else
  					echo "<span style='color:Red'>Фото №".$foto->foto_id." не записано</span><br /><br />
            		<a href=\"http://drel.su/foto/storefront/".urlencode(trim($foto->filename))."\">".$foto->filename."</a><br />\n";
  				}
  				else
  				{
  				file_put_contents(dirname(__FILE__)."/foto/storefront/3_".trim($foto->filename),$image);
  					if(is_file(dirname(__FILE__)."/foto/storefront/3_".trim($foto->filename)))
  					{echo "<span style='color:green'>Фото №".$foto->foto_id." 3_".$foto->filename."</span><br />\n";
  					$query = sql_placeholder("INSERT INTO products_fotos (product_id, foto_id, filename) VALUES (?, ?, ?)",
  							 $product_id2,
  							 $foto->foto_id,
  							 "3_".$foto->filename);
  					$db2->query($query);
  					}
  					else
  					echo "<span style='color:Red'>Фото №".$foto->foto_id." не записано</span><br /><br />
            		<a href=\"http://drel.su/foto/storefront/".urlencode(trim($foto->filename))."\">".$foto->filename."</a><br />\n";
  				}
            }
            else
            echo "<span style='color:Red'>Фото №".$foto->foto_id." не загружено</span><br /><br />
            <a target='_blank' href=\"http://drel.su/foto/storefront/".urlencode(trim($foto->filename))."\">".$foto->filename."</a><br />\n";
  			}

  		}
  		else
  		{
  		echo "<li><span style='color:Red'>Товар ID = ".$product->product_id." <b>".$product->model."</b>  в целевой БД сущетвует</span></li>\n";
  		}
  	}

  	echo "</ol>\n";
  }
  else
  {
   echo "Обнаружено $num_products товаров.\n";
   echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";
  }
?>