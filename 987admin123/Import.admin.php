<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Setup displays news
############################################
class Import extends Widget
{
  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
  }

  function fetch()
  {

    $this->title = $this->lang->PRODUCTS_IMPORT;

  	if(isset($_POST['engine']) && !empty($_POST['engine']) &&!empty($_FILES['f']['tmp_name']))
  	{
      $engine = $_POST['engine'];
      $fname = $_FILES['f']['tmp_name'];


      ############################### SHOP SCRIPT
      if($engine == 'shopscript')
      {
        $divider = ';';

        $lines = array_reverse(file($fname));
        array_pop($lines);
        # Собираем категорию и ее фирмы
        foreach($lines as $line)
        {
           $line = array_pop($lines);
           $cols = split($divider, $line);
           if($cols[0]>0)
           {
              if(!empty($cols[2]))
                $category_name = $cols[2];
           }elseif(substr($cols[2], 0, 1) == '!')
           {
             $firm_name = substr($cols[2], 1);
             if(!empty($firm_name) && !empty($category_name))
               $categories[$category_name]->firms[$firm_name] = $firm_name;
           }else
           {
               if(!empty($cols[2]) && !empty($category_name))
               $categories[$category_name]->products[] = $cols;
           }
        }
        foreach($categories as $k=>$category)
        {
           $category_name = $k;
           #print "Добавлаяем в базу категорию $k<br>";
           $query = sql_placeholder("SELECT * FROM categories WHERE name=?", $category_name);
           $this->db->query($query);
           $exist_cat = $this->db->result();

           if(empty($exist_cat))
           {
             $query = sql_placeholder("INSERT INTO categories(name, enabled, parent) VALUES(?, 1, 0)", $category_name);
             $this->db->query($query);
             $category_id=$this->db->insert_id();
           }else
           {
             $category_id = $exist_cat->category_id;
           }

           foreach($category->products as $p)
           {
             $bm = $p[2];
             $model='';
             foreach($category->firms as $firm)
             {
                if(strpos($bm, $firm) === 0)
                {
                   $brand = $firm;
                   $model = substr($bm, strlen($firm)+1);
                }
             }
             if(empty($model))
             {
               $brand=$firm;
               $model=$bm;
             }
             $brand = trim($brand);
             $model = trim($model);
             $body = trim($p[3], '"');
             $description = trim($p[4], '"');
             $price = floatval(trim($p[5]));
             $quantity = intval(trim($p[6]));

             #print "Добавлаяем в базу товар $brand $model<br>";
             $query = sql_placeholder("SELECT * FROM products WHERE category_id=? AND brand=? AND model=?", $category_id, $brand, $model);
             $this->db->query($query);
             $exist_prod = $this->db->result();

             if(empty($exist_prod))
             {
               $query = sql_placeholder("INSERT INTO products(category_id, brand, model, description, body, price, quantity) VALUES(?, ?, ?, ?, ?, ?, ?)", $category_id, $brand, $model, $description, $body, $price, $quantity);
               $this->db->query($query);
               $product_id=$this->db->insert_id();
               ###Фотки
               $foto_id = 0;
               for($i=18; $i<=25; $i++)
               {
                  $f = $p[$i];
                  $fa = split(',', $f);
                  if(!empty($fa))
                  {
                    $foto = trim(array_pop($fa));
                    if(!empty($foto))
                    {
                      $query = sql_placeholder("INSERT INTO products_fotos(product_id, foto_id, filename) VALUES(?, ?, ?)", $product_id, $foto_id, $foto);
                      $this->db->query($query);
                      $foto_id++;
                    }
                  }
               }


               $status=$this->lang->PRODUCT_ADDED;
             }else
             {
               $product_id = $exist_prod->product_id;
               $status=$this->lang->PRODUCT_EXISTS;
             }
             $products[$product_id]->product_id = $product_id;
             $products[$product_id]->category = $category_name;
             $products[$product_id]->category_id = $category_id;
             $products[$product_id]->brand = $brand;
             $products[$product_id]->model = $model;
             $products[$product_id]->price = $price;
             $products[$product_id]->status = $status;

           }
        }
      ############################### END SHOP SCRIPT
      }

      ############################### WEBOX CSV
      if($engine == 'webox')
      {
        $divider = ';';
        $lines = file($fname);

        # Идем по всем строкам
        foreach($lines as $line)
        {

           $cols = split($divider, $line);
           $category = $cols[0];
           $brand = $cols[1];
           $model = $cols[2];
           $price = $cols[3];
           $currency_id = $cols[4];
           $quantity = $cols[5];
           $guarantee = $cols[6];
           $short_desc = $cols[7];
           $full_desc = $cols[8];
           $active = $cols[9];
           $foto = $cols[10];

           $query = sql_placeholder("SELECT * FROM categories WHERE name=?", $category);
           $this->db->query($query);
           $cat = $this->db->result();

           if(empty($cat))
           {
             $query = sql_placeholder("INSERT INTO categories(name, enabled, parent) VALUES(?, 1, 0)", $category);
             $this->db->query($query);
             $category_id=$this->db->insert_id();
           }
           else
           {
             $category_id  = $cat->category_id;
           }

           $query = sql_placeholder("SELECT * FROM products WHERE category_id=? AND brand=? AND model=?", $category_id, $brand, $model);
           $this->db->query($query);
           $exist_prod = $this->db->result();
           if(empty($exist_prod))
           {
               $query = sql_placeholder("INSERT INTO products(category_id, brand, model, description, body, price, currency_id, guarantee, quantity) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)", $category_id, $brand, $model, $short_desc, $full_desc, $price, $currency_id, $guarantee, $quantity);
               $this->db->query($query);

               $product_id=$this->db->insert_id();
               $query = sql_placeholder("INSERT INTO products_fotos(product_id, foto_id, filename) VALUES(?, ?, ?)", $product_id, $product_id, $foto);
               $this->db->query($query);
               $status = $this->lang->PRODUCT_ADDED;
           }else
           {
               $product_id = $exist_prod->product_id;
               $status=$this->lang->PRODUCT_EXISTS;
           }


            $products[$product_id]->product_id = $product_id;
            $products[$product_id]->category = $category;
            $products[$product_id]->category_id = $category_id;
            $products[$product_id]->brand = $brand;
            $products[$product_id]->model = $model;
            $products[$product_id]->price = $price;
            $products[$currency_id]->currency_id = $currency_id;
            $products[$product_id]->status = $status;
          }

        }
        ############################### END SHOP SCRIPT



        $query = sql_placeholder("UPDATE categories SET order_num=category_id WHERE order_num IS NULL");
        $this->db->query($query);
        $query = sql_placeholder("UPDATE products SET order_num=product_id WHERE order_num IS NULL");
        $this->db->query($query);


   		$this->smarty->assign('Products', $products);
 		$this->body = $this->smarty->fetch('import_result.tpl');




 	}else
 	{
  	    $this->smarty->assign('Lang', $this->lang);
    	$this->smarty->assign('ErrorMSG', $this->error_msg);
 		$this->body = $this->smarty->fetch('import.tpl');
  	}

  }


}