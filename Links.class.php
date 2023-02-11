<?PHP

//require_once('Widget.class.php');

class Links extends Widget
{
  var $section_id=25;
  var $uploaddir = 'foto/links/';
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
  var $max_image_size = 1024000;
  var $items_per_page = 5;
  var $pages_per_group = 20;
/*  var $art_cats = array(0=>'Общее',
  						1=>'Межкомнатные двери',
  						2=>'Входные двери',
  						3=>'Окна пластиковые и деревянные',
  						4=>'Межкомнатные перегородки',
  						5=>'Мебель и шкафы',
  						6=>'Строительные и ремонтные работы',
  						7=>'Стоительные материалы, полы, стены, потолок',
  						8=>'Деревянные двери',
  						9=>'Двери купе');*/
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('id');
    $this->add_param('page');
  }

  function get_categories($parent=0)
  {
      $query = sql_placeholder("SELECT * FROM links_categories WHERE parent=? AND enabled='1' ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
    	$query = "SELECT COUNT(article_id) as links
                  FROM links as l
                  WHERE l.enabled='1' AND l.art_cat='".$category->category_id."'";
    	$this->db->query($query);
    	$res = $this->db->result();

    	$categories[$k]->links = $res->links;
        $categories[$k]->subcategories = Links::get_categories($category->category_id);
      }
      return $categories;
  }

  function fetch()
  {
    $document_id = $this->param('id');
    $art_cat = $this->param('category');
    $action = $this->param('action');
    $this->art_cats = Links::get_categories();
    $this->fetch_update();
    if(!empty($action))
      $this->fetch_action($action);
    elseif(!empty($document_id))
      $this->fetch_item($document_id);
    elseif(!empty($art_cat))
      $this->fetch_list($art_cat);
    else
      $this->fetch_categories();
  }

  function fetch_categories()
  {
    //echo "<pre>\n"; print_r($this->categories); echo "</pre>\n";
    $this->db->query("SELECT * FROM links WHERE enabled=1 ORDER BY order_num2 DESC LIMIT 5");
    $links = $this->db->results();

    $section_id = $this->param('section');
    $query = "SELECT * FROM sections WHERE url = '$section_id'";

    $this->db->query($query);
    $section = $this->db->result();

    $this->title = $this->keywords = $this->description = $section->name;
    # Документы
 	$query = "SELECT `pages`.*,`sections`.`name`,`sections`.`url` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='link_text'";
    $this->db->query($query);
    $link_text = $this->db->result();

    $this->smarty->assign('Categories', $this->art_cats);
    $this->smarty->assign('Column', @ceil(@count($this->art_cats)/3));
    $this->smarty->assign('Section', $section);
    $this->smarty->assign('Links', $links);
  	$this->smarty->assign('Uploaddir', $this->uploaddir);
  	$this->smarty->assign('Link_text', $link_text);
    $this->body = $this->smarty->fetch('links_categories.tpl');
  }


  function fetch_list($art_cat0=null)
  {
    //echo "<pre>".print_r($this->art_cats,true)."</pre>";
    $current_page = intval($this->param('page'));

    if(!isset($current_page))
      $current_page=1;

    $articles = array();
    if(is_null($art_cat0))
    {
    foreach($this->art_cats as $ck=>$art_cat)
    {
    $articles[$ck]->name = $art_cat->name;
    $articles[$ck]->path = $art_cat->path;
    $this->db->query("SELECT * FROM links WHERE art_cat='".$art_cat->category_id."' AND enabled=1 ORDER BY order_num DESC LIMIT 7");
    $articles[$ck]->toparticles = $this->db->results();
    }
    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE url = '$section_id'");
    $section = $this->db->result();
    # Документы
 	$query = "SELECT `pages`.*,`sections`.`name`,`sections`.`url` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='link_text'";
    $this->db->query($query);
    $link_text = $this->db->result();
    }
    else
    {
    $articles_key = $art_cat0;
    $st=($current_page)*$this->items_per_page;
    $query = "SELECT SQL_CALC_FOUND_ROWS links.*,links_categories.name FROM links LEFT JOIN links_categories ON links.art_cat=links_categories.category_id WHERE links_categories.path='$articles_key' AND links.enabled=1 ORDER BY links.order_num LIMIT $st,".$this->items_per_page."";
    //echo "$query<br />\n";
    $this->db->query($query);
    $articles[$articles_key]->toparticles = $this->db->results();
    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num0 = $this->db->result();
    $pages_num = ceil($pages_num0->count/$this->items_per_page);
    //echo "$pages_num<br />\n";
    for($i=0;$i<$pages_num;$i++)
  	{
  		  $url[$i] = "resourses/".$articles_key.'_p'.$i;

  	}
    $this->db->query("SELECT links_categories.name,links_categories.description FROM links_categories WHERE links_categories.path='$articles_key'");
    $section = $this->db->result();
    }
    //echo "<pre>".print_r($articles,true)."</pre>";
    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $ifirst_page = 0;
    $first_page = "resourses/".$articles_key.'_p'.$ifirst_page;
    $ilast_page = $pages_num-1;
    $last_page = "resourses/".$articles_key.'_p'.$ilast_page;
    $iprev_page = ($current_page!=$ifirst_page) ? $current_page-1 : $ifirst_page;
    $prev_page = "resourses/".$articles_key.'_p'.$iprev_page;
    $inext_page = ($current_page!=$ilast_page) ? $current_page+1 : $ilast_page;
    $next_page = "resourses/".$articles_key.'_p'.$inext_page;

    $start_page = floor($current_page/$this->pages_per_group)*$this->pages_per_group;
    $end_page = (floor($current_page/$this->pages_per_group)+1)*$this->pages_per_group;


    $this->smarty->assign('PagesNum', $pages_num);
    $this->smarty->assign('Url', $url);
    $this->smarty->assign('CurrentPage', $current_page);
    $this->smarty->assign('IFirst_page', $ifirst_page);
    $this->smarty->assign('First_page', $first_page);
    $this->smarty->assign('Prev_page', $prev_page);
    $this->smarty->assign('Next_page', $next_page);
    $this->smarty->assign('ILast_page', $ilast_page);
    $this->smarty->assign('Last_page', $last_page);
    $this->smarty->assign('Start_page', $start_page);
    $this->smarty->assign('End_page', $end_page);
    $this->smarty->assign('Pages_group', $this->pages_per_group);
    $this->smarty->assign('Articles', $articles);
    $this->smarty->assign('Link_text', $link_text);
    $this->smarty->assign('Section', $section);
  	$this->smarty->assign('Uploaddir', $this->uploaddir);
    $this->body = $this->smarty->fetch('links.tpl');
  }

  function fetch_update($new_id=0)
  {
    $link_filter = "enabled=1";
    if($new_id)
    $link_filter = " article_id='".$new_id."'";
    $query = "SELECT * FROM links WHERE $link_filter";
    //echo "$query<br />\n";
    $this->db->query($query);
    $links = $this->db->results();
           $filename_update_brands="web/update_links.txt";

			$ftime_update_brands = intval(@filemtime($filename_update_brands));

			//echo "ftime_usd = $ftime_usd<br />\n";

			clearstatcache();

		if ($links && ($new_id || date("d") != date("d",$ftime_update_brands)))
		{
        //echo "<pre>".print_r($links,1)."</pre>";
        file_put_contents($filename_update_brands,time());
        $prcy = new PRCY;
        foreach($links as $link)
        {
        $result = $prcy->getCY($link->path);
        if(!$result['cy']) $result['cy'] = 0;
        if(!$result['topic']) $result['topic'] = '';
        if(!$result['region']) $result['region'] = 'Не определен';
        //echo "<pre>".print_r($result,1)."</pre>";
        $query = "UPDATE links SET cy='".intval($result['cy'])."',topic='".$result['topic']."',region='".$result['region']."' WHERE article_id='".$link->article_id."'";
        //echo "$query<br />\n";
        $this->db->query($query);
        }
        }
  }


function  add_link($link){

	//проверка ссылки и подготовка к записи в БД

   $link=trim($link);
   $link=str_replace("\\","",$link);
   if(strpos("1".$link,"<?")) return false;
   if(preg_match("/script[ ]*>/is",$link)) return false;

   return str_replace("'","''",$link);
}

function send_mail($email,$subj,$letter){

			//отправка почты

   $admin_mail=$this->get_value("admin_mail");

              $p=mail("$email",$subj,$letter,
              "Return-path: <$admin_mail>\n".
              "From: <$admin_mail>\n".
              "Reply-To: <$admin_mail>\n".
              "MIME-Version: 1.0\n".
              "Content-Type: text/plain; charset=\"windows-1251\"\n".
              "Content-Transfer-Encoding: 8bit\n".
              "X-Priority: 3\n".
              "X-Mailer: mailer_name");

   return $p;
}


function send_mail2($admin_mail,$email,$subj,$letter){

			//отправка почты


              $p=mail("$email",$subj,$letter,
              "Return-path: <$admin_mail>\n".
              "From: <$admin_mail>\n".
              "Reply-To: <$admin_mail>\n".
              "MIME-Version: 1.0\n".
              "Content-Type: text/plain; charset=\"windows-1251\"\n".
              "Content-Transfer-Encoding: 8bit\n".
              "X-Priority: 3\n".
              "X-Mailer: mailer_name");

   return $p;
}

function my_send_mail($email,$subj,$letter){

			//отправка почты
     $admin_mail = "robot@".$_SERVER['SERVER_NAME'];

              $p=mail("$email",$subj,$letter,
              "From: <$admin_mail>\n".
              "MIME-Version: 1.0\n".
              "Content-Type: text/plain; charset=\"windows-1251\"\n".
              "Content-Transfer-Encoding: 8bit\n".
              "X-Priority: 3\n".
              "X-Mailer: mailer_name");

   return $p;
}

function add_email($m){

			//простая проверка синтаксиса мыла
   $m=trim($m); if(!strpos($m,"@") || !strpos($m,".")) return false;
   if(strlen($m)<6 || strlen($m)>50) return false;

   if(!strpos($m,"<")) return $m;
   $m2=$m=substr($m,strpos($m,"<")+1);
   $m=substr($m,0,strpos($m,">"));

   if(strlen($m)) return $m;
   else return $m2;
}

function get_hostname($str){
			//хост
   $info = parse_url($str);
   if($info['host']) return $info['host'];

 return false;
}

function ch_host($host){

			//есть ли в базе такой сайт

   $host=str_replace("http://","",$host);
   if($c=strpos($host,"/")) $host=substr($host,0,$c);
   $host=str_replace("www.","",$host);
   $query = "SELECT article_id FROM links AS t1 WHERE t1.host LIKE '$host%'";
      $this->db->query($query);
      $res_site = $this->db->result();
   if($r = $res_site->article_id) return $r;
   $host="www.".$host;
   $query = "SELECT article_id FROM links AS t1 WHERE t1.host LIKE '$host%'";
      $this->db->query($query);
      $res_site = $this->db->result();
   if($r = $res_site->article_id) return $r;

   return 0;
}

  function add_fotos($category)
  {
     if(isset($_FILES['fotos']))
     {
      //echo "<pre>\n"; print_r($_FILES['fotos']); echo "<pre>\n";
      //$category = $_GET[''];
          if(!empty($_FILES['fotos']['name']))
          {
            if(in_array($_FILES['fotos']['type'],$this->accepted_file_types))
            {
             if($_FILES['fotos']['size'] < $this->max_image_size)
             {
  			   	switch ($_FILES['fotos']['type']) {
        		case 'image/gif':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".gif";
          		break;
        		case 'image/png':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".png";
          		break;
        		case 'image/x-png':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".png";
          		break;
          		case 'image/pjpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";
          		break;
          		case 'image/jpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpg";
          		break;
      			}

			 if (!move_uploaded_file($_FILES['fotos']['tmp_name'],dirname(__FILE__).'/tmp/'.$uploadfile) ||
			 !$this->resize(dirname(__FILE__).'/tmp/'.$uploadfile, $this->uploaddir.$uploadfile,400,0))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." error: ".$_FILES['fotos']['error']."<br />";
		  		else
		  		{
                $query = "UPDATE links SET image = '$uploadfile' WHERE article_id = '$category'";
                //echo "$query<br />\n";
                $this->db->query($query);
                @unlink(dirname(__FILE__).'/tmp/'.$uploadfile);
                }
             }
             else
             $this->error_msg .= "Превышен максимальный размер<br />";
            }
            else
            $this->error_msg .= "Неверный тип<br />";
          }

     }
  }

function rgbtostruct($color) {
 $color = hexdec($color);
 $rgb = array();
    // red
 $rgb['blue'] = $color & 255;
    // green
 $s = ($s << 8)-1;
 $rgb['green'] = ($color>>8) & 255;
    // blue
 $s = ($s << 8)-1;
 $rgb['red'] = ($color>>16) & 255;

 return $rgb;
}

  function resize($src,$dst,$width=0,$height=0)
  {
  $doc_root = dirname(__FILE__);
  $dst = $doc_root.'/'.$dst;
  //echo "$src<br />\n";
  //echo  intval(is_file($src))."<br />";
  //echo "$dst<br />\n";
  if(is_file($src))
  {

  $src_info = getimagesize($src);

  //echo "<pre>\n"; print_r($src_info); echo "<pre>\n";

  if($src_info[2] == IMAGETYPE_JPEG)
  $src_im = imagecreatefromjpeg($src);
  elseif($src_info[2] == IMAGETYPE_GIF)
  $src_im = imagecreatefromgif($src);
  elseif($src_info[2] == IMAGETYPE_PNG)
  $src_im = imagecreatefrompng($src);
  else
  return false;

  $src_x = $src_info[0];
  $src_y = $src_info[1];

  if($height==0) $height =  intval($width*$src_y/$src_x);
  if($width==0) $width =  intval($height*$src_x/$src_y);
  if($height>$src_y)
  {
  $height = $src_y;
  $width =  intval($height*$src_x/$src_y);
  }
  if($width>$src_x)
  {
  $width = $src_x;
  $height =  intval($width*$src_y/$src_x);
  }

  $dst_im = imagecreatetruecolor($width, $height);
  imagesavealpha($dst_im,true);
  $bgcolor = 'FFFFFF';
  $bgcolors = $this->rgbtostruct($bgcolor);
  $bgcolorint = imagecolorallocate($dst_im,$bgcolors['red'],$bgcolors['green'],$bgcolors['blue']);
  imagefilledrectangle($dst_im,0,0,$width,$height,$bgcolorint);/**/

  //imagecopy($dst_im,$src_im,0,0,0,0,$src_x,$src_y);
  imagecopyresampled($dst_im,$src_im,0,0,0,0,$width,$height,$src_x,$src_y);


  switch ($src_info[2]) {

    case IMAGETYPE_GIF:
   imagegif($dst_im,$dst);
   //imagegif($dst_im);
      break;
    case IMAGETYPE_PNG:
   imagepng($dst_im,$dst);
   //imagepng($dst_im);
      break;
    case IMAGETYPE_JPEG:
   imagejpeg($dst_im,$dst,100);
   //imagejpeg($dst_im);
      break;
  }

  imagedestroy($dst_im);

  }
  else
  return false;

  return true;
  }




  function fetch_action($action)
  {

    //echo "<pre>\n"; print_r($this->categories); echo "</pre>\n";
    switch ($action) {
      case 'ajax':
    $subcat = $this->param('subcat');
	if($subcat)
	{
	$sub_cats = Links::get_categories($subcat);
    echo "<option value=\"\"></option>\n";
	foreach($sub_cats as $subcat)
	{
    echo "<option value=\"".$subcat->category_id."\">".$subcat->name."</option>\n";
	}
	}
	$this->db->disconnect();
	exit();
        break;
      case 'find':

    $section_id = $this->param('section');
    $query = "SELECT * FROM sections WHERE url = '$section_id'";

    $this->db->query($query);
    $section = $this->db->result();
    $this->title = $this->keywords = $this->description = $section->name;
    if(isset($_POST["find_link"]))
    {
    $find_link=$_POST["find_link"];

      $query = sql_placeholder("SELECT s.id FROM ".PREFIX."Site as s WHERE s.name = ?  ", "www.".str_replace("www.","",$this->settings->site_name));

      $this->db2->query($query);

      $res_site = $this->db2->result();

      $SiteId = $res_site->id;

    $host=str_replace("http://","",str_replace("www.","",trim($find_link))); if($c=strpos($host,"/")) $host=substr($host,0,$c);
    //echo "$host<br />\n";
    $ok=$this->ch_host($host); if(!strlen($host)) $ok=0;
    if($ok){
    $query = "SELECT * FROM ".PREFIX."links AS t1, ".PREFIX."catalog AS t2 WHERE t1.host IN( '$host', 'www.$host' ) AND t2.id = t1.cat AND t2.site = $SiteId";
    //echo "$query<br />\n";
      $this->db2->query($query);

      $links_h = $this->db2->results();
    }
    }
    //echo "<pre>\n"; print_r($links_h); echo "</pre>\n";
    $this->smarty->assign('Categories', $this->categories);
    $this->smarty->assign('Column', @ceil(@count($this->categories)/3));
    $this->smarty->assign('Section', $section);
    $this->smarty->assign('Links', $links_h);
    $this->body = $this->smarty->fetch('links_find.tpl');

        break;
      case 'add':

    $query = "SELECT * FROM links_our WHERE enabled = 1 ORDER BY RAND() LIMIT 1";
    //echo "$query<br />\n";
    $this->db->query($query);
    $res = $this->db->result();
    $my_link_db = $res->my_link;
    $my_link = isset($_POST['My_link']) ? $_POST['My_link'] : $my_link_db;
    //$my_banner = sql_1("SELECT link FROM ".PREFIX."mylinks WHERE site = $SiteId AND type = 2 ORDER BY RAND()");
    /*$query = "SELECT link FROM links WHERE site = $SiteId AND type = 2 ORDER BY RAND() LIMIT 1";
    //echo "$query<br />\n";
    $this->db->query($query);
    $res = $this->db->result();
    $my_banner = isset($_POST['My_banner']) ? $_POST['My_banner'] :  $res->link;*/

    if ( $_POST[ 'AddLink' ] == 1 ) {
			//добавление ссылки
	$title = strip_tags(trim($_POST["title"]));
	$annotation = strip_tags(trim($_POST["annotation"]),'<b><strong><u><i>');
	$backlink = trim($_POST["backlink"]);

    $query = "SELECT COUNT(*) as count FROM links WHERE backlink='$backlink'";
    $this->db->query($query);
    $res = $this->db->result();
    $count = $res->count;

    if(!$host = $this->get_hostname(trim($_POST["path"]))) {
    }else $ok=1;
    $link = $this->add_link(trim($_POST["path"]));
    if(($link) && $ok==1) $ok=1;
    else {$text="Неправильная ссылка<br>"; $ok=0;}

    if(!$email=$this->add_email(trim($_POST["email"]))) {$text.="Не указан e-mail<br>"; $ok=0;}
    if(!$cat = $_POST["cat"]) {$text.="Не выбран раздел<br>"; $ok=0;}
    $com = parse_url($_POST["backlink"]);
    if(str_replace("www.","",$this->get_hostname(trim($_POST["path"]))) != str_replace("www.","",$com['host'])) {$text .= "Ссылка не соответствует ответной ссылке<br>"; $ok=0;}

    $query = "SELECT COUNT(*) as count FROM links  WHERE backlink='$backlink'";
    $this->db->query($query);
    $res = $this->db->result();
    $count = $res->count;
    if(strlen($backlink) && $count>0) {$ok=0; $text.="Такой адрес ответной ссылки уже есть в каталоге $backlink<br>";}

    $query = "SELECT * FROM links_categories  WHERE category_id='$cat'";
    $this->db->query($query);
    $category = $this->db->result();

    //echo "link = ".$this->get_hostname(trim($_POST["path"]))."<br />";
    //echo "backlink = ".$com['host']."<br />";
    //echo "$backlink<br />\n";
    //echo "$ok<br />\n";
    if($ok!=0)
    {
    $google = new CWebPage($backlink);
    $c = $google->doQuery('Windows-1251');
    //echo "$c<br />\n";
    if(($c)==200)
    {
    //echo htmlspecialchars($google->document)."<br />";
    if($my_link)
    {
    $RegEx='/'.str_replace(" ","\s",preg_quote($my_link,'/')).'/i';
    //echo "$RegEx<br />\n";
    if(!preg_match($RegEx, $google->document,$texts))
    {
    $ok=0;
    $text.="Наша ссылка на указанной странице с ответной ссылкой не найдена<br>";
    }
    }
    }
    else
    {
    $ok=0;
    $text.="Страница с ответной ссылкой не найдена<br>";
    }
    /*if($my_banner)
    {
    $RegEx2='/'.str_replace(" ","\s",preg_quote($my_banner,'/')).'/i';
    echo "$RegEx2<br />\n";
    if(!preg_match($RegEx2, $google->document,$texts))
    {
    $ok=0;
    $text.="Наш банер на указанной странице с ответной ссылкой не найден<br>";
    }
    }*/
    }
    //echo "<pre>\n"; print_r($texts); echo "</pre>\n";
    //if($this->get_value("no_spam")){
   	 //if( $_SESSION['captcha_keystring'] !=  $_POST['antispam'] ) {$text.="Введено неверное число<br>"; $ok=0;}
    //}
    if(strlen($host)){
     if($this->ch_host($host)) {$ok=0; $text.="Ссылка на сайт $host уже есть в каталоге<br>";}
    }else $ok=0;
    /*$backlink = $_POST["backlink"]; $cat = $_POST["cat"];
    if ( $_POST[ 'subcat'] > 0 ) $subcat = $_POST[ 'subcat'];
    else $subcat = 0;
    $query = "SELECT COUNT(*) as count FROM ".PREFIX."links  WHERE backlink='$backlink'";
    $this->db->query($query);
    $res = $this->db->result();
    $count = $res->count;
    if(strlen($backlink) && $count>0) {$ok=0; $text.="Такой адрес ответной ссылки уже есть в каталоге $backlink<br>";}*/

    if($ok){
    $query = "INSERT INTO links (article_id,art_cat,path,title,annotation,email,backlink,my_link,enabled,main) VALUES(NULL,'$cat','$link','$title','$annotation','$email','$backlink','$my_link',0,0)";
    //echo "$query<br />\n";
    $this->db->query($query);
      $new_id = $this->db->insert_id();
      $this->add_fotos($new_id);
      $this->fetch_update($new_id);
      //mod_action("t_add_link",$new_id);
    //ссылка добавлена в БД

    $query = sql_placeholder('SELECT * FROM links WHERE art_cat=? ORDER BY order_num',
  			                          $cat);
  				$this->db->query($query);
	  			$res = $this->db->results();
	  			$count = intval(@count($res));

    { // отправляем уведомление
     $letter="Здравствуйте\n\nВ каталог добавлена ссылка <a href=\"$link\">$title</a>\n размещена по адресу http://".$_SERVER['HTTP_HOST']."/resourses/".$category->path."_p".floor($count/5)."  После успешной проверки она будет активирована.";
     $this->send_mail2($email,$this->settings->link_email.",stroiseo@mail.ru","LINKER - добавлена ссылка",$letter);
    }
    if($new_id) $text="Ссылка добавлена. После проверки Вам будет выслано уведомление.";
    else
    {
    $ok = 0;
    $text="Ошибка обращения к базе данных";
    }
    }



   //$location = get_value("catalog_url").get_value("addlink").".htm?text=".urlencode($text)."&check=".substr(md5(date("d-m-Y-h-i",time())),0,5);
   //if(!$ok) $location.="&re=1&link=".urlencode($link)."&banner=".urlencode($banner)."&cat=$cat&email=".urlencode($email)."&backlink=".urlencode($backlink)."&check=".substr(md5(date("d-m-Y-h-i",time())),0,5);
   //$location = $_POST[ 'RetUrl' ].get_value("addlink").".htm?text=".urlencode($text)."&check=".substr(md5(date("d-m-Y-h-i",time())),0,5);
   //$location = "text=".urlencode($text)."&check=".substr(md5(date("d-m-Y-h-i",time())),0,5);
   //if(!$ok) $location.="&re=1&link=".urlencode($link)."&banner=".urlencode($banner)."&cat=$cat&email=".urlencode($email)."&backlink=".urlencode($backlink)."&check=".substr(md5(date("d-m-Y-h-i",time())),0,5);
   //echo $location."&sn=".session_name()."&si=".session_id();
   //Header("Location: $location");
    }
    $this->title = $this->keywords = $this->description = "Добавление ссылки";

    $this->smarty->assign('Categories', $this->art_cats);
    //$this->smarty->assign('Column', @ceil(@count($this->categories)/3));
    //$this->smarty->assign('Section', $section);
    $this->smarty->assign('My_link', $my_link);
    $this->smarty->assign('My_banner', $my_banner);
    $this->smarty->assign('Message', $text);
    $this->smarty->assign('Ok', $ok);
    $this->body = $this->smarty->fetch('links_add.tpl');

        break;
      default:

        break;
    }
  }

  function fetch_item($id)
  {
    $this->db->query("UPDATE links SET links.body = REPLACE(body,'\"..//\"','\"http://".$_SERVER['HTTP_HOST']."/\"');");
    $query = "SELECT `pages`.*,`sections`.`name` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='1' AND `pages`.`page_id`=$id";
    $this->db->query($query);
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('Document', $item);
    $this->body = $this->smarty->fetch('document.tpl');
  }

}