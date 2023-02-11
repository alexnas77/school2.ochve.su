<?PHP

//require_once('Widget.class.php');
//require_once('placeholder.php');

class Basket extends Widget
{
  var $pays = array(0=>'Выберите метод оплаты',4=>'Оплата наличными при доставке курьером',1=>'Безналичный расчёт',/*2=>'WebMoney',*/3=>'Сберегательный Банк');
  var $delivery = array(0=>'Выберите метод доставки',1=>'Самовывоз',2=>'Доставка до ТК');
  var $wmpays = array('RUR'=>'WMR','USD'=>'WMZ','EUR'=>'WME');
  var $hundreds = array('','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот');
  var $decimals = array('','десять','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто');
  var $units = array('','один','два','три','четыре','пять','шесть','семь','восемь','девять');
  var $fix_in = array('десять один','десять одна','десять два','десять две','десять три','десять четыре','десять пять','десять шесть','десять семь','десять восемь','десять девять');
  var $fix_out = array('одинадцать','одинадцать','двенадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
  var $mon_in = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
  var $mon_out = array('Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря');
  var $knows = array('Затрудняюсь ответить','Совет знакомых','Уличная реклама','Печатные издания','Реклама в СМИ','Яndex','Rambler','Google','Другие поисковики','Каталоги','Баннеры','Другое');
  var $places = array('Не посещал','м. Китай-город, ул. Маросейка, 4/2','м. Аэропорт, ул. Красноармейская 29','м. Марксисткая, ул. Марксисткая 5','м. Павелецкая, ул. Кожевническая, д. 7','м. Щёлковская, ул.9-ая Парковая, д. 62','ул.Проспект Вернадского, д.39','м. Ботанический сад со стороны Серебрякова проезда');
  var $order_id;
  var $bill_id;
  var $pay_id;
  //var $nds = 18; %
  var $maxcount = 4;
  var $clientaddress = "";


  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->section_id = 7;
    $this->order_success = 0;
    $this->basket_products = array();
    $this->basket_price = 0;
    $this->wmconfigs = array('RUR'=>$this->settings->wm_shop_purse_wmr,'USD'=>$this->settings->wm_shop_purse_wmz,'EUR'=>$this->settings->wm_shop_purse_wme);
    $this->prepare();
  }

  function add_product($product_id)
  {
      if(is_array($_SESSION['basket_products']))
        $products = $_SESSION['basket_products'];
      else
        $products = array();
      if(! array_key_exists($product_id, $products))
       $products[$product_id] = 1;

      $_SESSION['basket_products'] = $products;
  }

  function delete_product($product_id)
  {
      if(is_array($_SESSION['basket_products']))
        $products = $_SESSION['basket_products'];
      else
        $products = array();
      if(array_key_exists($product_id, $products))
      {
        unset($products[$product_id]);
      }
      $_SESSION['basket_products'] = $products;
  }

  function change_quantities($quantities)
  {
      if(is_array($_SESSION['basket_products']))
        $products = $_SESSION['basket_products'];
      else
        $products = array();

      foreach($products as $k=>$product)
      {
        $products[$k] = abs(intval($quantities[$k]));
      }

      $_SESSION['basket_products'] = $products;
  }

  function hundred($num)
  {
    return $this->hundreds[$num];
  }

  function decimal($num)
  {
    return $this->decimals[$num];
  }

  function unit($num)
  {
    return $this->units[$num];
  }

  function str_split2($text)
  {
    $output = array();
    for($i=0;$i<strlen($text);$i++)
    $output[] = $text[$i];

    return $output;
  }

  function dec2text($price_ftext)
  {
        $price_ftext_array = function_exists("str_split") ? str_split($price_ftext) : $this->str_split2($price_ftext);
        $price_text = "";
        $mln = false;
        $tis = false;
        foreach ($price_ftext_array as $pos=>$val)
        {
		switch ($pos) {
  		case 0:
  		if($val)
        {$price_text .= $this->hundred($val)." ";
        $mln = true;
        }
    	break;
  		case 1:
  		if($val)
        {$price_text .= $this->decimal($val)." ";
        $mln = true;
        }
    	break;
  		case 2:
  		if($val)
        {$price_text .= $this->unit($val)." ";
        $mln = true;
        }
        if($val==1 && $price_ftext_array[$pos-1]!='1')
        $price_text .="миллион ";
        elseif(($val==2||$val==3||$val==4) && $price_ftext_array[$pos-1]!='1')
        $price_text .="миллиона ";
        elseif($mln)
        $price_text .="миллионов ";
    	break;
  		case 3:
  		if($val)
        {$price_text .= $this->hundred($val)." ";
        $tis = true;
        }
    	break;
  		case 4:
  		if($val)
        {$price_text .= $this->decimal($val)." ";
        $tis = true;
        }
    	break;
  		case 5:
  		if($val==1 && $price_ftext_array[$pos-1]!='1')
        {$price_text .= "одна ";
        $tis = true;
        }
  		elseif($val==2 && $price_ftext_array[$pos-1]!='1')
        {$price_text .= "две ";
        $tis = true;
        }
        elseif($val)
        {$price_text .= $this->unit($val)." ";
        $tis = true;
        }
        if($val==1 && $price_ftext_array[$pos-1]!='1')
        $price_text .="тысяча ";
        elseif(($val==2||$val==3||$val==4) && $price_ftext_array[$pos-1]!='1')
        $price_text .="тысячи ";
        elseif($tis)
        $price_text .="тысяч ";
    	break;
  		case 6:
  		if($val)
        $price_text .= $this->hundred($val)." ";
    	break;
  		case 7:
  		if($val)
        $price_text .= $this->decimal($val)." ";
    	break;
  		case 8:
  		if($val)
        $price_text .= $this->unit($val)." ";
        if($val==1 && $price_ftext_array[$pos-1]!='1')
        $price_text .="рубль ";
        elseif(($val==2||$val==3||$val==4) && $price_ftext_array[$pos-1]!='1')
        $price_text .="рубля ";
        else
        $price_text .= "рублей ";
    	break;
  		case 9:
  		if($val)
  		preg_match('/\.(\d+)$/is',$price_ftext,$matches);
        $price_text .= $matches[1]." копеек";
    	break;
		}
        }
        //echo ucfirst($price_text)."<br />\n";
        $price_text = ucfirst(str_replace($this->fix_in,$this->fix_out,$price_text));

    return $price_text;
  }

  function prepare()
  {
    # Добавление товара в корзину
    $product_id = $this->param('product_id');
    if(!empty($product_id))
    {
      $this->add_product($product_id);
    }
    #####

    # Удаление товара из корзины
    $delete_product_id = $this->param('delete_product_id');
    if(!empty($delete_product_id))
    {
      $this->delete_product($delete_product_id);
    }
    #####

    if(isset($_POST['quantities']))
    {
      $this->change_quantities($_POST['quantities']);
    }

    ### Создаем массив товаров в корзине
    if(!empty($_SESSION['basket_products']))
    {
      $basket = $_SESSION['basket_products'];
      $products_ids = join(', ', array_keys($basket));
      $query = "SELECT *, products.*, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100*(100-products.discount)/100 as discount_price, categories.name as category FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.product_id IN ($products_ids) GROUP BY products.product_id ORDER BY products_fotos.foto_id";
      $this->db->query($query);
      $basket_products = $this->db->results();

      $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
 		   $currencies = $this->db->results();

      $this->title = 'Купить ';

      foreach($basket_products as $product)
      {

       $this->title .= strip_tags($product->category).' : '.strip_tags($product->brand).' : '.strip_tags($product->model).' / ';

  		  foreach($currencies as $k=>$currency)
		    {
 		   if ($currency->code == $product->currency_id)
  			  $product->currency_rate = $currency->rate;
  			  $this->basket_products[$product->product_id]->currency_rate = $currency->rate;
  		  }
        $this->basket_products[$product->product_id] = $product;
        $this->basket_products[$product->product_id]->urlbrand = urlencode($product->brand);
        /*$this->basket_price += $product->price/$product->currency_rate*(100-$product->discount)/100*$basket[$product->product_id];
        $this->basket_products[$product->product_id]->price = $product->price*(100-($this->user->discount?$this->user->discount:0))/100;*/
        $this->basket_price += $product->discount_price/$product->currency_rate*$basket[$product->product_id];
        $this->basket_products[$product->product_id]->price = $product->discount_price;
        $this->basket_products[$product->product_id]->currency_id = $product->currency_id;
        $this->basket_products[$product->product_id]->quantity = $basket[$product->product_id];

      }
      //$this->basket_price = 111112119;
    }
    //echo "<pre>".print_r($this->basket_products,1)."</pre>";
    ########
      $name = isset($_POST['name']) ? trim($_POST['name']) : '';
      $address = isset($_POST['address']) ? trim($_POST['address']) : '';
      $this->clientaddress = $address;
      $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
      $mail = isset($_POST['mail']) ? trim($_POST['mail']) : '';
      $pay = isset($_POST['pay']) ? $_POST['pay'] : '';
      $del = isset($_POST['del']) ? $_POST['del'] : '';
      if($del==2)
      $this->basket_price = $this->basket_price + 240*floatval($this->currency->rate);
      $tk = isset($_POST['tk']) ? $_POST['tk'] : '';
      $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
      $metro = isset($_POST['metro']) ? trim($_POST['metro']) : '';
      $know = isset($_POST['know']) ? trim($_POST['know']) : '';
      $city = isset($_POST['city']) ? trim($_POST['city']) : '';
      $place = isset($_POST['place']) ? trim($_POST['place']) : '';
      $consult = isset($_POST['consult']) ? trim($_POST['consult']) : '';
      $inn_payer = isset($_POST['inn_payer']) ? trim($_POST['inn_payer']) : '';
      $name_payer = isset($_POST['name_payer']) ? trim($_POST['name_payer']) : '';
      $ur_adress = isset($_POST['ur_adress']) ? trim($_POST['ur_adress']) : '';
      /*$glavbuh = isset($_POST['glavbuh']) ? trim($_POST['glavbuh']) : '';

      $registration = isset($_POST['registration']) ? trim($_POST['registration']) : $this->user->registration;
      $series = isset($_POST['series']) ? trim($_POST['series']) : $this->user->series;
      $number = isset($_POST['number']) ? trim($_POST['number']) : $this->user->number;
      $given = isset($_POST['given']) ? trim($_POST['given']) : $this->user->given;
      $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : $this->user->lastname;
      $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : $this->user->firstname;
      $middle = isset($_POST['middle']) ? trim($_POST['middle']) : $this->user->middle;

      $index = isset($_POST['index']) ? trim($_POST['index']) : $this->user->index;

      $code = isset($_POST['currency']) ? $_POST['currency'] : $this->currency->code;*/

      $this->smarty->assign('Name', $name);
      $this->smarty->assign('Address', $address);
      $this->smarty->assign('Phone', $phone);
      $this->smarty->assign('Mail', $mail);
      $this->smarty->assign('Pay', $pay);
      $this->smarty->assign('Del', $del);
      $this->smarty->assign('Tk', $tk);
      $this->pay_id = $pay;
      $this->smarty->assign('Comment', $comment);
      $this->smarty->assign('Metro', $metro);
      $this->smarty->assign('Know', $know);
      $this->smarty->assign('Knows', $this->knows);
      $this->smarty->assign('Place', $place);
      $this->smarty->assign('Places', $this->places);
      $this->smarty->assign('City', $city);
      $this->smarty->assign('Consult', $consult);
      $this->smarty->assign('Inn_payer', $inn_payer);
      $this->smarty->assign('Name_payer', $name_payer);
      $this->smarty->assign('Ur_adress', $ur_adress);
      /*$this->smarty->assign('Glavbuh', $glavbuh);

      $this->smarty->assign('Registration', $registration);
      $this->smarty->assign('Series', $series);
      $this->smarty->assign('Number', $number);
      $this->smarty->assign('Given', $given);
      $this->smarty->assign('Lastname', $lastname);
      $this->smarty->assign('Firstname', $firstname);
      $this->smarty->assign('Middle', $middle);

      $this->smarty->assign('Index', $index);*/

        $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
 		$currencies = $this->db->results();
        $selects = array();
        $rates = array();
        $signs = array();
  		  foreach($currencies as $k=>$currency)
		  {
		   if(array_key_exists($currency->code,$this->wmpays))
		   {
 		   $selects[$currency->code] = $currency->name;
 		   $rates[$currency->code] = $currency->rate;
 		   $signs[$currency->code] = $this->wmpays[$currency->code];
 		   }
  		  }
  		$this->smarty->assign('Selects', $selects);
  		$this->smarty->assign('Rates', $rates);
  		$this->smarty->assign('Signs', $signs);
      //echo "<pre>".print_r($_POST,1)."</pre>";
      //echo "<pre>".$code."</pre>";
      //echo "<pre>".$this->wmconfigs[$code]."</pre>";
    ####### Обрабатываем заказ
     //echo (count($this->basket_products)."<br />");
    if(isset($_POST['SUBMIT']) && $this->basket_products)
    {
     if($name && $city && $address && $phone && $mail)
     {
     if(!$pay)
     {
     $this->smarty->assign('Error', 'Выберите метод оплаты');
     }
     elseif(!$del && $pay!="4")
     {
     $this->smarty->assign('Error', 'Выберите метод доставки');
     }
     elseif($pay=="1" && count($this->basket_products)>$this->maxcount)
     {
     $this->smarty->assign('Error', 'Корзина переполнена (максимально '.$this->maxcount.'). Разделите заказ на части.');
     }
     elseif($pay=="2" && empty($this->wmconfigs[$code]))
     {
     $this->smarty->assign('Error', 'Платеж по WebMoney в выбранной валюте невозможен');
     }
     else
     {
      if($this->user)
        $login = $this->user->login;
      else
        $login='';
  	  /*$query = sql_placeholder('UPDATE `users` SET `registration`=?, `series`=?, `number`=?, `given`=?, `lastname`=?, `firstname`=?, `middle`=?, `city`=?, `index`=? WHERE `login`=?',
               $registration,
               $series,
  			   $number,
  			   $given,
  			   $lastname,
  			   $firstname,
  			   $middle,
  			   $city,
  			   $index,
  			   $login);
      $this->db->query($query);*/
  	  $query = sql_placeholder('INSERT INTO `orders` (`order_id`, `date`, `login`, `name`, `city`, `metro`, `know`, `address`, `phone`, `mail`, `comment`, `place`, `consult`, `pay`, `status`, `inn_payer`, `name_payer`, `ur_adress`) VALUES (NULL, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?)',
               $login,
               $name,
  			   $city,
  			   $metro,
  			   $know,
  			   $address,
  			   $phone,
  			   $mail,
  			   $comment,
  			   $place,
  			   $consult,
  			   $pay,
  			   $inn_payer,
  			   $del,
  			   $tk);
      //echo "$query<br />\n";
      $this->db->query($query);
      $this->order_id = $this->db->insert_id();

      if($pay==1)
      {  	  $query = sql_placeholder('INSERT INTO `orders_bills` (`bill_id`,`order_id`, `price`) VALUES (NULL, ?, ?)',
               $this->order_id,
  			   $this->basket_price);
  		//echo "$query<br />\n";
      $this->db->query($query);      }
      elseif($pay==2)
      {  	  $query = sql_placeholder('INSERT INTO `orders_wm` (`bill_id`,`order_id`,`price`) VALUES (NULL, ?, ?)',
               $this->order_id,
  			   $this->basket_price);
      $this->db->query($query);      }
      elseif($pay==3)
      {  	  $query = sql_placeholder('INSERT INTO `orders_sberbank` (`bill_id`,`order_id`, `price`) VALUES (NULL, ?, ?)',
               $this->order_id,
  			   $this->basket_price);
  		//echo "$query<br />\n";
      $this->db->query($query);      }
      $this->bill_id = $this->db->insert_id();
      foreach($this->basket_products as $i=>$product)
      {
  	    $query = sql_placeholder('INSERT INTO `orders_products` (`order_id`, `product_id`, `product_name`, `price`, `currency_id`, `quantity`) VALUES (?, ?, ?, ?, ?, ?)',
  			   $this->order_id,
  			   $product->product_id,
  			   $product->category.' : '.$product->brand.' : '.$product->model,
  			   $product->price,
  			   $product->currency_id,
               $product->quantity);
        $this->db->query($query);
        //echo "$query<br />\n";
      }


      $from = "Webox CMS <webox@".$_SERVER['HTTP_HOST'].">";
      $subject = "Заказ №".$this->order_id;
      $site_name = $this->settings->site_name;
      $this->smarty->assign('Pays', $this->pays);
      $this->smarty->assign('Delivery', $this->delivery);
      $this->smarty->assign('Products', $this->basket_products);
      $message = $this->smarty->fetch('email_order_admin.tpl');


      if(@mail($this->settings->admin_email, $subject, $message, "MIME-Version: 1.0\r\nContent-type: text/html; charset=windows-1251\r\nFrom: $from\r\n"))
      {
      //$this->body = $this->smarty->fetch('basket_order_success.tpl');
      unset($_SESSION['basket_products']);
      $this->order_success = $pay;
      }
      else
     $this->smarty->assign('Error', 'Ошибка отправки уведомления');

     }
     }
     else
     $this->smarty->assign('Error', 'Недостаточно данных');
      ####################
    }

  }

  function fetch()
  {
    //print_r($_SESSION);

    $this->title = empty($this->title) ? 'Корзина' : preg_replace('/\s+\/\s+$/is','',$this->title);
    //$this->smarty->assign('NDS', ($this->nds/100));
        //setlocale(LC_TIME, "ru_RU");
      $this->smarty->assign('Now', str_replace($this->mon_in,$this->mon_out,strftime("%d %b %Y"))." г.");
      $this->smarty->assign('Pay', $this->pay_id);
    if($_GET['download'])
    {
        $order_id = intval($_GET['order_id']);


  	    $query = "SELECT * FROM orders WHERE order_id = '$order_id'";
        $this->db->query($query);
  	    $order = $this->db->result();
  	    $pay_id =$order->pay;
  	    $this->smarty->assign('Name', $order->name);
        $this->smarty->assign('Address', $order->address);
        $this->smarty->assign('Phone', $order->phone);
        $this->smarty->assign('Mail', $order->mail);
      	$this->smarty->assign('Inn_payer', $order->inn_payer);
      	$this->smarty->assign('Name_payer', $order->name_payer);
      	$this->smarty->assign('Ur_adress', $order->ur_adress);
        if($pay_id==1)
        {
        $query = "SELECT * FROM orders_bills WHERE order_id = '$order_id'";
        $this->db->query($query);
  	    $result = $this->db->result();
  	    $this->smarty->assign('Order_id', $result->order_id);
  	    $this->smarty->assign('Bill_id', $result->bill_id);

        $this->db->query("SELECT orders_products.*, products.quantity as stock, products.currency_id,	currencies.rate
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
                        LEFT JOIN currencies ON products.currency_id = currencies.code
    				    WHERE orders_products.order_id = '$order_id'");
  	    $products = $this->db->results();
	    $this->smarty->assign('Price', $result->price);
	    $price_nds = $result->price*(1+$this->settings->nds/100);
		$price_form = number_format($price_nds,2,'.','\'');
        $this->smarty->assign('Price_form', $price_form);
        $price_nds_form = number_format($price_nds,2,'.','');
        $prices = explode('.',$price_nds_form);
        $price_ftext = sprintf("%09d",$prices[0]).".".sprintf("%02d",$prices[1]);
        $price_text = $this->dec2text($price_ftext);
        $this->smarty->assign('Price_text', $price_text);
        $this->smarty->assign('Products', $products);
        $this->smarty->assign('HTML', $this->settings->tranfer);
        }
        elseif ($pay_id==3) {
        $query = "SELECT * FROM orders_sberbank WHERE order_id = '$order_id'";
        $this->db->query($query);
  	    $result = $this->db->result();
  	    $this->smarty->assign('Order_id', $result->order_id);
  	    $this->smarty->assign('Bill_id', $result->bill_id);
        $price_form = number_format($result->price,2,'.','');
        $prices = explode('.',$price_form);
	    $this->smarty->assign('PriceRUB', $prices[0]);
	    $this->smarty->assign('PriceKOP', $prices[1]);
        $this->smarty->assign('HTML', $this->settings->sberbank);
        }
        $this->smarty->display('tranfer.tpl');
        exit();
    }

    if(!$this->order_success)
    {
      $this->smarty->assign('Products', $this->basket_products);
      $this->smarty->assign('Price', $this->basket_price);
      $this->smarty->assign('Pays', $this->pays);
      $this->smarty->assign('Delivery', $this->delivery);
      $this->body = $this->smarty->fetch('basket.tpl');
    }
    else
    {
      if ($this->order_success==1 || $this->order_success==3) {
  	    $query = "SELECT * FROM orders WHERE order_id = '$this->order_id'";
        $this->db->query($query);
  	    $order = $this->db->result();
        $this->smarty->assign('Order_id', $this->order_id);
        $this->smarty->assign('Bill_id', $this->bill_id);
        $this->smarty->assign('Address', $order->address);
        if($this->order_success==1)
        {
        $this->smarty->assign('Phone', $order->phone);
        $this->smarty->assign('Mail', $order->mail);
        $this->db->query("SELECT orders_products.*, products.quantity as stock, products.currency_id,	currencies.rate
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
                         LEFT JOIN currencies ON products.currency_id = currencies.code
    				    WHERE orders_products.order_id = '$this->order_id'");
  	    $products = $this->db->results();
        $this->smarty->assign('Products', $products);

	    $this->smarty->assign('Price', $this->basket_price);

	    $price_nds = $this->basket_price*(1+$this->settings->nds/100);
		$price_form = number_format($price_nds,2,'.','\'');
        $this->smarty->assign('Price_form', $price_form);
        $price_nds_form = number_format($price_nds,2,'.','');
        $prices = explode('.',$price_nds_form);
        $price_ftext = sprintf("%09d",$prices[0]).".".sprintf("%02d",$prices[1]);

//	    $price_form = number_format($this->basket_price*(1+$this->nds/100),2,'.','\'');
//        $this->smarty->assign('Price_form', $price_form);
//        $price_ftext = sprintf("%09d",$this->basket_price*(1+$this->nds/100)).".".($this->basket_price*(1+$this->nds/100)-intval($this->basket_price*(1+$this->nds/100)));
        $price_text = $this->dec2text($price_ftext);
        $this->smarty->assign('Price_text', $price_text);
        $this->smarty->assign('HTML', $this->settings->tranfer);
        }
        elseif ($this->order_success==3) {
        $price_form = number_format($this->basket_price,2,'.','');
        $prices = explode('.',$price_form);
	    $this->smarty->assign('PriceRUB', $prices[0]);
	    $this->smarty->assign('PriceKOP', $prices[1]);
        $this->smarty->assign('HTML', $this->settings->sberbank);
        }
		# Текст при принятии заказа
		$query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='baskettext' AND `sections`.`domain`='$this->domain'";
        $this->db->query($query);
        $res = $this->db->result();
        $baskettext = $res->body;
    	$this->smarty->assign('Baskettext', $baskettext);

        $this->body = $this->smarty->fetch('basket_tranfer.tpl');
        $this->db->query("SELECT `mail` FROM `orders` WHERE `order_id`='$this->order_id'");
  	    $order = $this->db->result();
        $clientaddress = $order->mail;
       $from = "Webox CMS <webox@".$_SERVER['HTTP_HOST'].">";
       $subject = "Заказ №".$this->order_id." Счет №".$this->bill_id;
       $site_name = $this->settings->site_name;
       $message = $this->smarty->fetch('tranfer_html.tpl');
       $ctype = "application/vnd.ms-excel";
       $headers = "MIME-Version: 1.0\r\n";
       $headers .= "Pragma: public\r\n";
       $headers .= "Expires: 0\r\n";
       $headers .= "Cache-Control: must-revalidate, post-check=0, pre-check=0\r\n";
       $headers .= "Content-Type: $ctype\r\n";
       $headers .= "Content-disposition: attachment; filename=\"bill.xls\"\r\n";
       $headers .= "Content-Transfer-Encoding: binary\r\n";
       $headers .= "From: $from\r\n";
      if($this->order_success!=1)
      {
      if(!@mail($clientaddress, $subject, $message, $headers))
      $this->smarty->assign('Error', 'Ошибка отправки счета клиенту');
      if(!@mail($this->settings->admin_email, $subject, $message, $headers))
      $this->smarty->assign('Error', 'Ошибка отправки счета администратору');
      }

      if($this->order_success==1)
        {
	    $query = sql_placeholder('UPDATE `orders_bills` SET `text`=? WHERE `bill_id`=?',
               $message,
               $this->bill_id);
        $this->db->query($query);
        }
      elseif($this->order_success==3)
       {
	    $query = sql_placeholder('UPDATE `orders_sberbank` SET `text`=? WHERE `bill_id`=?',
               $message,
               $this->bill_id);
        $this->db->query($query);
        }
      }
      elseif ($this->order_success==2) {      	$order_id = $this->order_id;
      	$code = isset($_POST['currency']) ? $_POST['currency'] : $this->currency->code;
        $query = "SELECT * FROM orders_wm WHERE order_id = '$order_id'";
        $this->db->query($query);
  	    $result = $this->db->result();
        $this->db->query("SELECT `rate` FROM currencies WHERE `code`='$code'");
 		$rate = $this->db->result();
 		$this->smarty->assign('Purse', $this->wmconfigs[$code]);
 		$amount = $result->price*$rate->rate;
 		$this->smarty->assign('Amount', $amount);
 		$desc = urlencode($this->settings->company_name."\r\nЗаказ №".$order_id."\r\n");
        $url_name = "Заплатить ".number_format($amount,2,'.','')." ".$this->wmpays[$code];
 		$this->smarty->assign('Desc', $desc);
  		$this->smarty->assign('url_name', $url_name);
        $this->body = $this->smarty->fetch('basket_webmoney.tpl');
        $message = "<a href=\"wmk:payto?Purse=".$this->wmconfigs[$code]."&Amount=".number_format($amount,2,'.','')."&Desc=".$desc."&BringToFront=Y\">".$url_name."</a>";


	  $query = sql_placeholder('UPDATE `orders_wm` SET `text`=? WHERE `bill_id`=?',
               $message,
               $this->bill_id);
      $this->db->query($query);

      }
      else
      {		# Текст при принятии заказа
		$query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='baskettext' AND `sections`.`domain`='$this->domain'";
        $this->db->query($query);
        $res = $this->db->result();
        $baskettext = $res->body;
    	$this->smarty->assign('Baskettext', $baskettext);

        $this->body = $this->smarty->fetch('basket_tranfer.tpl');      }

		/*# Текст при принятии заказа
		$query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='baskettext'";
        $this->db->query($query);
        $res = $this->db->result();
        $baskettext = $res->body;
    	$this->smarty->assign('Baskettext', $baskettext);

      $this->body = $this->smarty->fetch('basket_order_success.tpl');*/
    }
  }
}