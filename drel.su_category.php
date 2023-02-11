<?php
set_time_limit(0);
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

  echo "<h1>������� ���������</h1>\n";

    function get_categories($parent=0)
    {
      global $db;
      //$brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      //$brand = 'Dewalt';
      $catlist = "155";
      //if($parent)
      //$brand_filter = strlen($brand) ? "AND categories.brands LIKE '%::".strtolower($brand)."::%'" : "";
      $brand_filter = "AND categories.category_id IN(".$catlist.")";
      $query = sql_placeholder("SELECT categories.* FROM categories WHERE categories.parent=? AND categories.enabled $brand_filter ORDER BY categories.name", $parent);
      //echo "$query\n";
      $db->query($query);
      $categories = $db->results();
      if($categories)
      foreach($categories as $k=>$category)
      {
        //$brands = explode("::",$category->brands);

        $categories[$k]->subcategories = get_categories($category->category_id);
        //if(!in_array(strtolower($brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
      }
      return $categories;
    }

   $categories = get_categories();

  /*$query = sql_placeholder("SELECT * FROM categories ORDER BY category_id");
  $db->query($query);
  $categories = $db->results();*/

  if($_GET['go']=='true')
  {
  	echo "<ol>\n";

  	foreach($categories as $id=>$category)
  	{  		$query = sql_placeholder("SELECT * FROM categories WHERE parent = ? AND name = ? AND alies = ? AND title = ? AND description = ? AND enabled = ?",
  							 $category->parent,
  							 $category->name,
  							 $category->alies,
  							 $category->title,
  							 $category->description,
  							 $category->enabled);
  		$db2->query($query);
  		//echo "<br />$query<br />\n";
  		$category2 = $db2->result();
  	   if($db2->num_rows()!=1)
  	    {  		echo "<li>����������� ��������� ID = ".$category->category_id." <b>".$category->name."</b><br />\n";
  		echo "� ������� �� \n";
  		$query = sql_placeholder("INSERT INTO categories (category_id, parent, name, alies, title, description, order_num, enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
  							 $category->category_id,
  							 $category->parent,
  							 $category->name,
  							 $category->alies,
  							 $category->title,
  							 $category->description,
  							 $category->order_num,
  							 $category->enabled);
  		$db2->query($query);
  	   	if($db2->affected_rows()==1)
  	   	echo "<span style='color:Green'> �������</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> ��������</span><br />\n";
  		//echo "<br />$query<br />\n";
  		}
  		else
  		{  		echo "<li><span style='color:Red'>��������� ID = ".$category->category_id." <b>".$category->name."</b>  � ������� �� ���������</span></li>\n";  		}
      if($category->subcategories)
  	  foreach($category->subcategories as $id=>$subcategory)
  	  {
  		$query = sql_placeholder("SELECT * FROM categories WHERE parent = ? AND name = ? AND alies = ? AND title = ? AND description = ? AND enabled = ?",
  							 $subcategory->parent,
  							 $subcategory->name,
  							 $subcategory->alies,
  							 $subcategory->title,
  							 $subcategory->description,
  							 $subcategory->enabled);
  		$db2->query($query);
  		//echo "<br />$query<br />\n";
  		$category2 = $db2->result();
  	   if($db2->num_rows()!=1)
  	    {  		echo "<li>����������� ��������� ID = ".$subcategory->category_id." <b>".$subcategory->name."</b><br />\n";
  		echo "� ������� �� \n";
  		$query = sql_placeholder("INSERT INTO categories (category_id, parent, name, alies, title, description, order_num, enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
  							 $subcategory->category_id,
  							 $subcategory->parent,
  							 $subcategory->name,
  							 $subcategory->alies,
  							 $subcategory->title,
  							 $subcategory->description,
  							 $subcategory->order_num,
  							 $subcategory->enabled);
  		$db2->query($query);
  	   	if($db2->affected_rows()==1)
  	   	echo "<span style='color:Green'> �������</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> ��������</span><br />\n";
  		//echo "<br />$query<br />\n";
  		}
  		else
  		{  		echo "<li><span style='color:Red'>��������� ID = ".$subcategory->category_id." <b>".$subcategory->name."</b>  � ������� �� ���������</span></li>\n";  		}
  	  }  	}

  	echo "</ol>\n";
  }
  else
  {
  	echo "<ol>\n";

  	foreach($categories as $id=>$category)
  	{  		$query = sql_placeholder("SELECT * FROM categories WHERE parent = ? AND name = ? AND alies = ? AND title = ? AND description = ? AND enabled = ?",
  							 $category->parent,
  							 $category->name,
  							 $category->alies,
  							 $category->title,
  							 $category->description,
  							 $category->enabled);
  		$db2->query($query);
  	   if($db2->num_rows()!=1)
  	    {
  		echo "<li>����������� ��������� ID = ".$category->category_id." <b>".$category->name."</b><br />\n";
  		echo "� ������� �� <b>$dbname2</b><br />\n";

  		}
  		else
  		{
  		echo "<li><span style='color:Red'>��������� ID = ".$category->category_id." <b>".$category->name."</b>  � ������� �� ���������</span></li>\n";
  		}
      if($category->subcategories)
   	  foreach($category->subcategories as $id=>$subcategory)
  	  {  		$query = sql_placeholder("SELECT * FROM categories WHERE parent = ? AND name = ? AND alies = ? AND title = ? AND description = ? AND enabled = ?",
  							 $subcategory->parent,
  							 $subcategory->name,
  							 $subcategory->alies,
  							 $subcategory->title,
  							 $subcategory->description,
  							 $subcategory->enabled);
  		$db2->query($query);
  	   if($db2->num_rows()!=1)
  	    {
  		echo "<li>����������� ��������� ID = ".$subcategory->category_id." <b>".$subcategory->name."</b><br />\n";
  		echo "� ������� �� <b>$dbname2</b><br />\n";

  		}
  		else
  		{
  		echo "<li><span style='color:Red'>��������� ID = ".$subcategory->category_id." <b>".$subcategory->name."</b>  � ������� �� ���������</span></li>\n";
  		}
  	  }
  	}

  	echo "</ol>\n";
   echo "<br /><br /><a href=\"?go=true\">������� ����������?</a>\n";  }
?>