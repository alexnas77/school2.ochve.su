<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Setup displays news
############################################
class PriceImport extends Widget
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

    $this->title = $this->lang->PRICELIST_IMPORT;

  	if(isset($_POST['go']))
  	{
  		  if(isset($_POST['update']))
  		  {
  		  	$products = array();

            for($i=0;$row = $_POST[$i];$i++)
            {
            $product = '';
            if(isset($_POST['MODEL_CODE']))
  		  	$product->code = trim($row[$_POST['MODEL_CODE']]);
  		  	/*if(isset($_POST['MODEL']))
  		  	$product->model = trim($row[$_POST['MODEL']]);*/
  		  	$product->currency_id = $_POST['currency_id'];
  		  	$query = "SELECT `name` FROM `currencies` WHERE `code` = '$product->currency_id'";
            $this->db->query($query);
            $name = $this->db->result();
            $product->currency_name = $name->name;
  		  	$product->price = preg_replace('/[^0-9.,]/', '', $row[$_POST['PRICE']]);
          	$product->price = str_replace(',', '.', $product->price);

            $products[] = $product;

  		  	if(!empty($product->code) && empty($product->model) && !empty($product->price))
  		  	{
  				$query = "SELECT * FROM `products` WHERE `code`='$product->code'";
  				$this->db->query($query);
  				if($this->db->num_rows()==1)
  				{
  					$old_product = $this->db->result();
  					$product->old_price = $old_product->price;
  					$product->old_currency = $old_product->currency_id;
  					$product->price = number_format($product->price,2,'.','');
  					$product->message = '<font color=green>'.$this->lang->UPDATED.'</font>';
  					$query = "UPDATE `products` SET `price`='$product->price',`currency_id`='$product->currency_id' WHERE product_id='$old_product->product_id'";
  					//echo "$query<br />\n";
  			    	$this->db->query($query);
  				}
  				else
  				{
  					$product->old_price = 0;
  					$product->message = '<font color=red>'.$this->lang->PRODUCT_NOT_FOUND.'</font>';
  				}

  		  	}
  		  	/*
  		  	elseif(empty($product->code) && !empty($product->model) && !empty($product->price))
  		  	{
  				$models = split(" ", $product->model);
  				//print_r($models);echo "<br />\n";

  				$smodel = '';

  			  foreach($models as $km=>$model)
  			  {
  				if($km!='0')
  				$smodel .= ' '.$model;
  				else
  				$smodel = $model;
  				$smodel = preg_replace('/[^0-9a-zA-Zà-ÿÀ-ß\s]+/is','',$smodel);
  				$query = "SELECT * FROM `products` WHERE `model`='$smodel'";
  				//echo "$query<br />\n";
  				$this->db->query($query);
  				if($this->db->num_rows()==1)
  				{
  					$old_product = $this->db->result();
  					$product->old_price = $old_product->price;
  					$product->old_currency = $old_product->currency_id;
  					$product->price = number_format($product->price,2,'.','');
  					$product->message = '<font color=green>'.$this->lang->UPDATED.'</font>';
  					$query = "UPDATE `products` SET `price`='$product->price',`currency_id`='$product->currency_id' WHERE `product_id`='$old_product->product_id'";
  					//echo "$query<br />\n";
  			    	$this->db->query($query);
  			    	break;
  				}
  				else
  				{
  					$product->old_price = 0;
  					$product->message = '<font color=red>'.$this->lang->PRODUCT_NOT_FOUND.'</font>';
  				}
              }
  		  	}
  		  	elseif(!empty($product->code) && !empty($product->model) && !empty($product->price))
  		  	{
  				$models = split(" ", $product->model);
  				//print_r($models);echo "<br />\n";

  				$smodel = '';

  			  foreach($models as $km=>$model)
  			  {
  				if($km!='0')
  				$smodel .= ' '.$model;
  				else
  				$smodel = $model;
  				$smodel = preg_replace('/[^0-9a-zA-Zà-ÿÀ-ß\s]+/is','',$smodel);
  				$query = "SELECT * FROM `products` WHERE `model`='$smodel'";
  				//echo "$query<br />\n";
  				$this->db->query($query);
  				if($this->db->num_rows()==1)
  				{
  					$old_product = $this->db->result();
  					$product->old_price = $old_product->price;
  					$product->old_currency = $old_product->currency_id;
  					$product->price = number_format($product->price,2,'.','');
  					$product->message = '<font color=green>'.$this->lang->UPDATED.'</font>';
  					$query = "UPDATE `products` SET `code`='$product->code',`price`='$product->price',`currency_id`='$product->currency_id' WHERE `product_id`='$old_product->product_id'";
  			    	//echo "$query<br />\n";
  			    	$this->db->query($query);
  			    	break;
  				}
  				else
  				{
  					$product->old_price = 0;
  					$product->message = '<font color=red>'.$this->lang->PRODUCT_NOT_FOUND.'</font>';
  				}
              }
  		  	}
  		  	*/



            }

            //echo "<br />$i\n";print_r($products);
          }
          else
          {
       		$item = array();
      		$radios = array();
  			$items = split("\n", $_POST['price']);
  			foreach($items as $sitem)
  			{

  		  		$str = split("\t", $sitem);
  		  		//print_r($str);echo "<br />\n";
  		  		if(count($str)>1)
  		  		{  		  		$count = count($str);
  		  		array_push($item,$str);
  		  		}


  			}

  				for($k=0;$k<$count;$k++)
  				{  		  		array_push($radios,$k);  				}
  			$query = "SELECT `code` FROM `currencies`";
           $this->db->query($query);
           $codes = $this->db->results();
  			foreach($codes as $kc=>$code)
  			{
            $codes[$kc]=$codes[$kc]->code;
  			}

         }
		if($products)
		{
  		$this->smarty->assign('Lang', $this->lang);
  		$this->smarty->assign('ErrorMSG', $this->error_msg);
  	    $this->smarty->assign('Lang', $this->lang);
  		$this->smarty->assign('Products', $products);
  		//echo "<br /><br />\n";print_r($_POST);
 		$this->body = $this->smarty->fetch('price_import_result.tpl');
 		}
 		else
 		{ 		$this->smarty->assign('Lang', $this->lang);
  		$this->smarty->assign('ErrorMSG', $this->error_msg);
  	    $this->smarty->assign('Lang', $this->lang);
  	    $this->smarty->assign('Count', $count);
  	    $this->smarty->assign('Radios', $radios);
  		$this->smarty->assign('Item', $item);
  		$this->smarty->assign('Codes', $codes);
  		//print_r($item);
  		//print_r($radios);
  		//echo "<br /><br />\n";print_r($_POST);
 		$this->body = $this->smarty->fetch('price_import_process.tpl'); 		}
 	}else
 	{

  	    $this->smarty->assign('Lang', $this->lang);
    	$this->smarty->assign('ErrorMSG', $this->error_msg);
 		$this->body = $this->smarty->fetch('price_import.tpl');
  	}

  }


}