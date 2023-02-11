<?PHP

//require_once('include.php');

class Page extends Widget
{
	var $main;
	var $left = array(0=>array('name'=>'Автоэкспертиза','url'=>'avtoekspertiza.html','class'=>'menu-424','subs'=>
	                     array(0=>array('name'=>'Независимая экспертиза автомобиля после ДТП','url'=>'nezavisimaya-ekspertiza-avtomobilya-posle-dtp-0.html'),
	                           1=>array('name'=>'Расчет УТС (утрата товарной стоимости)','url'=>'raschet-uts-utrata-tovarnoy-stoimosti.html'),
	                           2=>array('name'=>'Трасологическая экспертиза ДТП','url'=>'trasologicheskaya-ekspertiza-dtp.html'),
	                           3=>array('name'=>'Независимая экспертиза автомобиля для РСА','url'=>'nezavisimaya-ekspertiza-avtomobilya-dlya-rsa.html'),
	                           4=>array('name'=>'Юридическая помощь при ДТП','url'=>'yuridicheskaya-pomoshch-pri-dtp.html'))),
	                  1=>array('name'=>'Оценка ущерба после залива','url'=>'ocenka-ushcherba-posle-zaliva.html','class'=>'menu-429','subs'=>
	                     array(0=>array('name'=>'Возмещение ущерба при заливе квартиры','url'=>'vozmeshchenie-ushcherba-pri-zalive-kvartiry.html'),
	                           1=>array('name'=>'Порядок действий при затоплении квартиры','url'=>'poryadok-deystviy-pri-zatoplenii-kvartiry.html'),
	                           2=>array('name'=>'Пошаговая инструкция при затоплении квартиры','url'=>'poshagovaya-instrukciya-pri-zatoplenii-kvartiry.html'),
	                           3=>array('name'=>'Юридическая помощь при заливе квартиры','url'=>'yuridicheskaya-pomoshch-pri-zalive-kvartiry.html')
	                           )),
	                  2=>array('name'=>'Оценка имущества','url'=>'ocenka-imushchestva.html','class'=>'menu-435','subs'=>
	                     array(0=>array('name'=>'Оценка стоимости автомобиля для нотариуса','url'=>'ocenka-stoimosti-avtomobilya-dlya-notariusa.html'),
	                           1=>array('name'=>'Оценка стоимости квартиры','url'=>'ocenka-stoimosti-kvartiry.html'),
	                           2=>array('name'=>'Оценка стоимости домов и строений','url'=>'ocenka-stoimosti-domov-i-stroeniy.html'),
	                           3=>array('name'=>'Оценка стоимости гаражей','url'=>'ocenka-stoimosti-garazhey.html'),
	                           4=>array('name'=>'Оценка торговых площадей','url'=>'ocenka-torgovyh-ploshchadey.html'),
	                           5=>array('name'=>'Оценка торговой недвижимости','url'=>'ocenka-torgovoy-nedvizhimosti.html'),
	                           6=>array('name'=>'Оценка складской недвижимости','url'=>'ocenka-skladskoy-nedvizhimosti.html')
	                           )),
	                  3=>array('name'=>'Судебная экспертиза','url'=>'sudebnaya-ekspertiza.html','class'=>'menu-558','subs'=>
	                     array(0=>array('name'=>'Дорожно-транспортная экспертиза','url'=>'dorozhno-transportnaya-ekspertiza.html'),
	                           1=>array('name'=>'Судебная трасологическая экспертиза','url'=>'sudebnaya-trasologicheskaya-ekspertiza.html'),
	                           2=>array('name'=>'Экспертиза технического состояния транспортных средств','url'=>'ekspertiza-tehnicheskogo-sostoyaniya-transportnyh-sredstv.html')
	                           )),
	                  4=>array('name'=>'Строительно-техническая экспертиза','url'=>'stroitelno-tehnicheskaya-ekspertiza.html','class'=>'menu-529','subs'=>
	                     array()),
	                  5=>array('name'=>'Оценка бизнеса','url'=>'ocenka-biznesa.html','class'=>'menu-440','subs'=>
	                     array()),
	                  6=>array('name'=>'Оценка машин и оборудования','url'=>'ocenka-mashin-i-oborudovaniya.html','class'=>'menu-439','subs'=>
	                     array()),
	                  7=>array('name'=>'Оценка земли','url'=>'ocenka-zemli.html','class'=>'menu-436','subs'=>
	                     array(0=>array('name'=>'Оценка земель сельскохозяйственного назначения','url'=>'ocenka-zemel-selskohozyaystvennogo-naznacheniya.html'),
	                           1=>array('name'=>'Оценка земельных участков','url'=>'ocenka-zemelnyh-uchastkov.html'),
	                           2=>array('name'=>'Оценка стоимости природных ресурсов','url'=>'ocenka-stoimosti-prirodnyh-resursov.html'),
	                           3=>array('name'=>'Оценка участков лесов и многолетних насаждений','url'=>'ocenka-uchastkov-lesov-i-mnogoletnih-nasazhdeniy.html')
	                           )),
	                  8=>array('name'=>'Оценка акций','url'=>'ocenka-akciy.html','class'=>'menu-441','subs'=>
	                     array()),
	                  9=>array('name'=>'Экологическая экспертиза','url'=>'ekologicheskaya-ekspertiza.html','class'=>'menu-523','subs'=>
	                     array(0=>array('name'=>'Экологическая экспертиза загородного дома','url'=>'ekologicheskaya-ekspertiza-zagorodnogo-doma.html'),
	                           1=>array('name'=>'Экологическая экспертиза земельного участка ','url'=>'ekologicheskaya-ekspertiza-zemelnogo-uchastka.html'),
	                           2=>array('name'=>'Экологическая экспертиза квартиры','url'=>'ekologicheskaya-ekspertiza-kvartiry.html'),
	                           3=>array('name'=>'Экологическая экспертиза офиса','url'=>'ekologicheskaya-ekspertiza-ofisa.html'),
	                           4=>array('name'=>'Экологическая экспертиза предприятия','url'=>'ekologicheskaya-ekspertiza-predpriyatiya.html'),
	                           5=>array('name'=>'Экологический мониторинг и производственный контроль','url'=>'ekologicheskiy-monitoring-i-proizvodstvennyy-kontrol.html')
	                           )),
	                  10=>array('name'=>'Оценка ущерба после пожара','url'=>'ocenka-ushcherba-posle-pozhara.html','class'=>'menu-534','subs'=>
	                     array(0=>array('name'=>'Порядок действий при пожаре','url'=>'poryadok-deystviy-pri-pozhare.html'),
	                           1=>array('name'=>'Телеграмма виновнику при пожаре','url'=>'telegramma-vinovniku-pri-pozhare.html'),
	                           2=>array('name'=>'Юридическая помощь после пожара','url'=>'yuridicheskaya-pomoshch-posle-pozhara.html')
	                           ))
	                                                            );

	function __construct(&$parent)
	{
		parent::__construct($parent);
		$this->main = new Widget($this);

		$section_id = $this->param('section');

        $service = "";

        if(empty($section_id))
			$section_id = $this->settings->main_section;
		$query = sql_placeholder("SELECT services.* FROM sections, services WHERE sections.url=? AND sections.service_id=services.service_id AND ((sections.service_id=1 AND sections.domain='$this->domain') OR (sections.service_id!=1))", $section_id);
//echo $query."<br>";
		$this->db->query($query);
		if($this->db->num_rows() == 1)
		{
            $_GET['section'] = $section_id;
			$service = $this->db->result();
			$class = $service->class;

			if(class_exists($class))
				$this->main = new $class($this);
			else
			{
				$this->error_msg = "1Ошибка загрузки модуля ".$service->name;
			}
		}
		else
		{
			$this->error_msg = "2Ошибка загрузки модуля ".$service->name;
		}
	}

    function get_categories($parent=0)
    {
      $brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      if($parent)
      $brand_filter = strlen($brand) ? "AND categories.brands LIKE '%::".strtolower($brand)."::%'" : "";
      $query = sql_placeholder("SELECT categories.* FROM categories WHERE categories.parent=? AND categories.enabled $brand_filter ORDER BY categories.order_num", $parent);
      //echo "$query\n";
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $brands = explode("::",$category->brands);

        $categories[$k]->subcategories = $this->get_categories($category->category_id);
        if(!in_array(strtolower($brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
      }
      return $categories;
    }

    function update_brands()
    {
      $query = sql_placeholder("SELECT categories.* FROM categories");
      //echo "$query\n";
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        # Производители
        $query = "SELECT DISTINCT brand FROM products WHERE category_id = ".$category->category_id." AND enabled ORDER BY brand";
    	$this->db->query($query);
    	$bres = $this->db->results();
        $brands = array();
    	foreach($bres as $br)
    	{
    	if($br->brand)
    	$brands[] = $br->brand;
    	}
        if(!empty($brands))
    	$brands_str = strtolower("::".implode("::",$brands)."::");
    	else
    	$brands_str = "";
        $query = "UPDATE categories SET brands = '$brands_str' WHERE category_id = ".$category->category_id."";
    	$this->db->query($query);

      }
    }

	function fetch()
	{
		$this->main->fetch();

        /*if($this->main->single)
        {
           $this->body = $this->main->body;
           return;
        }*/
		if(empty($this->error_msg))
			$body = $this->main->body;
		else
		{
			$this->smarty->assign("Message", $this->error_msg);
			$body = $this->smarty->fetch('error.tpl');
		}

 	    # Текущий раздел
  	    $section_id = $this->param('section');
        $query = sql_placeholder("SELECT * FROM sections WHERE url=?", $section_id);
  	    $this->db->query($query);
 	    $section = $this->db->result();

 	    # Разделы меню
 	    $this->db->query("SELECT * FROM sections WHERE menu_id=1 ORDER BY order_num");
 	    $sections = $this->db->results();



/*        # Текущая категория товаров (для подсветки меню товаров)
        $category_id = $this->param('category');
        $query = sql_placeholder("SELECT * FROM categories WHERE category_id=?", $category_id);
        $this->db->query($query);
        $category = $this->db->result();

        # Производители
        $query = "SELECT DISTINCT brand FROM products WHERE enabled ORDER BY brand";
    	$this->db->query($query);
    	$bres = $this->db->results();

    	foreach($bres as $br)
    	{
    	if($br->brand)
    	$brands[] = $br->brand;
    	}

    	$this->smarty->assign('Subbrands', $this->subbrands);*/

        # Разделы верхнего меню
        $mode = $this->param('mode');
        $url = array();
 	    $get = $this->form_get(array('section'=>'30'));
 	    $url1 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'31'));
 	    $url2 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'22'));
 	    $url3 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'14'));
 	    $url4 = "index.php$get";

 	    array_push($url,array($url1,$url2,$url3,$url4));

        # Разделы  нижнего меню

 	    $get = $this->form_get(array('section'=>'23'));
 	    $url1 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'7'));
 	    $url2 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'7'));
 	    $url3 = "index.php$get";
 	    $get = $this->form_get(array('section'=>'15'));
 	    $url4 = "index.php$get";

 	    array_push($url,array($url1,$url2,$url3,$url4));

 	    //print_r($url);
           $filename_clear_all_cache="web/clear_all_cache.txt";

			$ftime_clear_all_cache = intval(@filemtime($filename_clear_all_cache));

			//echo "ftime_clear_all_cache = $ftime_clear_all_cache<br />\n";

			clearstatcache();

		if (date("Y m d") != date("Y m d",$ftime_clear_all_cache))
		{
        $this->smarty->clear_all_cache(7200);
        file_put_contents($filename_clear_all_cache,time());
		}

/*        # Опрос
        $query = sql_placeholder("SELECT * FROM polls WHERE active=1");
        $this->db->query($query);
        $poll = $this->db->result();
        $query = sql_placeholder("SELECT * FROM polls_answers WHERE polls_answers.poll_id=? ORDER BY order_num DESC", $poll->poll_id);
        $this->db->query($query);
        $poll->answers = $this->db->results();

    	$this->db->query("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as dt FROM news WHERE domain='$this->domain' ORDER BY date DESC LIMIT 7");
    	$news = $this->db->results();
    	$this->smarty->assign('News', $news);

        $categories = $this->get_categories();*/

        # Полезные советы

        /*$this->db->query("SELECT * FROM sections
                     WHERE menu_id=5
                     ORDER BY order_num DESC");
        $articles = $this->db->results();
        print_r($articles);
        */

 /*       # Категории товаров
           $filename_update_brands="web/update_brands.txt";

			$ftime_update_brands = intval(@filemtime($filename_update_brands));

			//echo "ftime_usd = $ftime_usd<br />\n";

			clearstatcache();

		if (date("H") != date("H",$ftime_update_brands))
		{
        $this->update_brands();
        file_put_contents($filename_update_brands,time());
        }

        # ICQ консультант

    	$this->db->query("SELECT contacts.* FROM contacts
                     WHERE contacts.mode='ICQ'
                     ORDER BY contacts.order_num");

    	$ICQs = $this->db->results();
		$this->smarty->assign('ICQs', $ICQs);

        # ICQ консультант
        $query = "select * from settings WHERE name IN('ICQ1', 'ICQ2', 'ICQ3')";
    	$this->db->query($query);
    	$ICQs = $this->db->results();


        # Товары в корзине
        if(!empty($_SESSION['basket_products']))
        {
           $basket = $_SESSION['basket_products'];
           $products_ids = join(', ', array_keys($basket));
           $query = "SELECT *, products.*, categories.name as category FROM  categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.product_id IN ($products_ids) GROUP BY products.product_id ORDER BY products_fotos.foto_id";
           $this->db->query($query);
           $basket_products = $this->db->results();
           foreach($basket_products as $product)
           {
             $this->basket_products[$product->product_id] = $product;
             $this->basket_products[$product->product_id]->price = $product->price*(100-($this->user->discount?$this->user->discount:0))/100.0;
             $this->basket_products[$product->product_id]->quantity = $basket[$product->product_id];
             $basket_price += $product->price*$basket[$product->product_id]*$this->currency->rate;
           }

        }
        else
          $basket_products = array();
*/
/*        # Курсы валют
        $query = "SELECT `code` FROM `currencies` WHERE `code`<>'RUR'";
           $this->db->query($query);
           $res = $this->db->results();

			$update = false;

           foreach ($res as $n=>$code)
           {
           $filename_cur="web/".$code->code.".txt";

			$ftime_cur = intval(@filemtime($filename_cur));

			//echo "ftime_usd = $ftime_usd<br />\n";

			clearstatcache();

			//echo "day = ".date("d")."<br />\n";

			//echo "f_day = ".date("d",$ftime_usd)."<br />\n";

   			if (date("d") != date("d",$ftime_cur))
   			{
   			$update = true;

     		$currency = $code->code;

     		$date = date("d").'/'.date("m").'/'.date("Y");

     		//echo "date = $date<br />\n";

     		$google = new CWebPage('http://www.cbr.ru/currency_base/D_print.aspx');
     		$google->addgetvar('date_req', $date);
     		$text = '';
     		//echo($google->doQuery()."<br />\n");
     		 if(($c = $google->doQuery())==200) {
     		 $google->document = iconv("UTF-8","Windows-1251",$google->document);
       		//echo($google->document."<br />\r\n");
       		 //$RegEx='/<tr\s+bgcolor=\"[^\"]+\"><td\s+align=\"[^\"]+\">[0-9]+<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">&nbsp;&nbsp;'.$currency.'<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">([0-9]+)<\/td>[\r\n\s]+<td>[^<>]+<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">([0-9\,]+)<\/td><\/tr>/is';
       		 $RegEx='/<tr><td\s+align=\"[^\"]+\">[0-9]+<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">&nbsp;&nbsp;'.$currency.'<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">([0-9]+)<\/td>[\r\n\s]+<td>[^<>]+<\/td>[\r\n\s]+<td\s+align=\"[^\"]+\">([0-9\,]+)<\/td><\/tr>/is';
        		if(preg_match($RegEx, $google->document, $texts)) {
             	//print_r($texts);
    			$rate0 = floatval(preg_replace('/\,/','.',$texts[2]));
    			//echo "<br />\nrate0 = $rate0<br />\n";
            	if($currency=='EUR')
    			$rate = number_format(1.03*floatval($rate0/intval($texts[1])),10);
    			else
    			$rate = number_format(floatval($rate0/intval($texts[1])),10);
            	//echo "rate = $rate<br />\n";
    			file_put_contents($filename_cur,$rate);

                $query = "UPDATE `currencies` SET `rate`='".(1/$rate)."' WHERE `code`='".$currency."'";
           		$this->db->query($query);

        		}
        		elseif ($currency != "RUR")
        		{   $email = 'alexn@altrum.ru,anaschokin@rambler.ru';
                	$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=windows-1251\r\n";
					$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
					$headers .= "Importance: High\r\n";
					$headers .= "X-MSMail-Priority: High\r\n";
					$headers .= "X-Priority: 1 (Highest)\r\n";
					$subject = "Сбой обновления валюты ".$code->code." на сайте http://".$_SERVER['SERVER_NAME'];
					$text =  $subject;

					@mail($email,$subject,$text,$headers);
					//echo "mail($email,$subject,$text,$headers)\n";
     		 	}

   			}
           }

          }

		if($update)
		{
				$query = 'SELECT products.product_id,products.category_id,products.brand,products.model,products.price,products.currency_id,products.description,products_fotos.filename as filename,categories.name as category, categories.alies as alies FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.price>0  AND products.enabled AND categories.enabled GROUP BY products.product_id ORDER BY products.brand, products.model, products_fotos.foto_id';
    			//echo "$query<br />\n";
    			//file_put_contents("web/query.txt",$query);
    			$this->db->query($query);
    			$products = $this->db->results();
    			foreach ($products as $id=>$product){
                $product->urlbrand = urlencode($product->brand);
        		$pnames = array();
        		$pnames[0] = $products[$id]->category;
        		//print_r($pnames);echo "<br />\n";
        		if($products[$id]->alies)
        		{
        		$palies = explode(";;",$products[$id]->alies);
        		if(is_array($palies))
        		{
        		foreach ($palies as $akey=>$paly)
        		{
        		if($palies[$akey]=='') unset($palies[$akey]);
        		array_push($pnames,$palies[$akey]);
        		}

        		}
        		}
        		//print_r($pnames);echo "<br />\n";
        		$products[$id]->category = $pnames[$products[$id]->category_name];
                }
    			$query = 'SELECT * FROM categories WHERE categories.enabled ORDER BY categories.name';
    			$this->db->query($query);
    			$categories1 = $this->db->results();
    			$query = 'SELECT * FROM currencies ORDER BY  main DESC, currency_id';
    			$this->db->query($query);
    			$currencies = $this->db->results();
    			foreach ($currencies as $currency){
                $currency->rate = 1/$currency->rate;}
    			$this->smarty->assign('Products', $products);
    			//file_put_contents("web/products.txt",print_r($products,1));
    			$this->smarty->assign('Categories', $categories1);
    			$this->smarty->assign('Currencies', $currencies);
    			$this->smarty->assign('Settings', $this->settings);
    			$yandex = $this->smarty->fetch('price_yandex.tpl');
    			file_put_contents("yandex.xml",$yandex);

    			unset($products);
        }
        */
        /*require_once(dirname(__FILE__)."/sapa/fns.php");
        require_once(dirname(__FILE__)."/sapa/sape.php");

          UpdateYIP();
        $bShowSape = !IsYandex();

        if(IsYandex())
        $this->smarty->assign('MSNN', "<!-- MSNN Statistics --><script type=\"text/javascript\">(function (){var d=window.document;var s=d.getElementsByTagName(\"div\");for(var i=0;i<s.length;i++) for(var j=0;j<6;j++) if((new RegExp(\"a_sneg12v\")).test(s[i].className)) s[i].innerHTML=s[i].innerHTML.replace(\"hrеf\",\"href\");})();</script><!--/ MSNN Statistics -->");

        if (defined('_SAPE_USER')){
            $sape = new SAPE_client();
            $this->smarty->assign('sape1', SapeToClient($sape->return_links(2), $bShowSape));
            $this->smarty->assign('sape2', SapeToClient($sape->return_links(), $bShowSape));
        }
        */
        foreach($this->left as $lk=>$lv)
        {
            $found = false;
            if($lv['subs'])
            foreach($lv['subs'] as $sk=>$sv)
            {
                if("/".$sv['url']==$_SERVER['REQUEST_URI'])
                $found = true;
            }
            if($lk && !$found && "/".$lv['url']!=$_SERVER['REQUEST_URI'])
                $this->left[$lk]['subs']=array();
        }

        //echo "<pre>"; print_r($_SESSION); echo "</pre>";
		$this->smarty->assign("Settings", $this->settings);
		$this->smarty->assign("Title", $this->main->title);
		$this->smarty->assign("Keywords", $this->main->keywords);
		$this->smarty->assign("Description", $this->main->description);
		$this->smarty->assign("Left", $this->left);
        $this->smarty->assign('Section', $section);
        $this->smarty->assign('Sections', $sections);
		//$this->smarty->assign("Categories", $categories);
		//$this->smarty->assign("Brands", $brands);
		$this->smarty->assign("url", $url);
		$this->smarty->assign("mode", $mode);
		//$this->smarty->assign("Articles", $articles);
		//$this->smarty->assign("ICQs", $ICQs);
		$this->smarty->assign("BodyContent", $body);
        //$this->smarty->assign('BasketProducts', $basket_products);
        //$this->smarty->assign('BasketPrice', $basket_price);
        //$this->smarty->assign('Poll', $poll);
		$this->body = $this->smarty->fetch('index.tpl');
	}
}