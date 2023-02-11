<?PHP
//require_once('Widget.class.php');

class Announcement extends Widget
{
  var $uploaddir = './foto/usr/';
  var $accepted_file_types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('sale');
    $this->add_param('category');
  }

  function fetch()
  {
    $this->title = "КУПЛЮ-ПРОДАМ";
    $this->keywords = "КУПЛЮ-ПРОДАМ";
    $this->description = "КУПЛЮ-ПРОДАМ";

    if(isset($_GET['category']) && isset($_GET['sale']))
    {
    $category = $this->param('category');
  	$sale = $this->param('sale');
  		switch ($sale) {
     	case 0:
        $this->title = "Покупка ";
    	$this->keywords = "Покупка ";
    	$this->description = "Покупка ";
       	break;
     	case 1:
    	$this->title = "Продажа ";
    	$this->keywords = "Продажа ";
    	$this->description = "Продажа ";

       	break;
   		}
  		switch ($category) {
     	case 1:
    	$this->title .= "скутера";
    	$this->keywords .= "скутера";
    	$this->description .= "скутера";
       	break;
     	case 2:
        $this->title .= "мотоцикла";
    	$this->keywords .= "мотоцикла";
    	$this->description .= "мотоцикла";
       	break;
     	case 3:
        $this->title .= "запчастей";
    	$this->keywords .= "запчастей";
    	$this->description .= "запчастей";
       	break;
   		}
    $query = "SELECT * FROM `announcements` WHERE `category`='$category' AND `sale`='$sale' AND `enabled`='1' ORDER BY `order_num` DESC";
	$this->db->query($query);
	$this->announcements = $this->db->results();
    }
	elseif(isset($_POST['send']))
	{

	$email = $this->settings->admin_email;
	//echo "email = $email<br />\n";
	$sale = isset($_POST['sale']) ? $_POST['sale'] : 0;
    $category = isset($_POST['category']) ? $_POST['category'] : 1;
    $range = isset($_POST['range']) ? $_POST['range'] : 30;
    $brand = isset($_POST['brand']) ? trim($_POST['brand']) : '';
    $model = isset($_POST['model']) ? trim($_POST['model']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $volume = isset($_POST['volume']) ? trim($_POST['volume']) : 0;
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $price = isset($_POST['price']) ? trim($_POST['price']) : 0;
    $body = isset($_POST['body']) ? trim($_POST['body']) : '';

    $body = nl2br($body);

    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $mail = isset($_POST['mail']) ? trim($_POST['mail']) : '';
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
  		switch ($sale) {
     	case 0:
    	$type = "Продажа ";
       	break;
     	case 1:
        $type = "Покупка ";
       	break;
   		}
  		switch ($category) {
     	case 1:
    	$type .= "скутера";
       	break;
     	case 2:
        $type .= "мотоцикла";
       	break;
     	case 3:
        $type .= "запчастей";
       	break;
   		}
    if(isset($_FILES['foto']))
     {

        $query = "SHOW TABLE STATUS LIKE 'announcements'";
		$this->db->query($query);
		$foto_id = $this->db->result();
		$fotoid = $foto_id->Auto_increment;
        if(!empty($_FILES['foto']['name']))
        {
          if(in_array($_FILES['foto']['type'],$this->accepted_file_types))
          {
  			 switch ($_FILES['foto']['type']) {
        		case 'image/gif':
                $uploadfile = $fotoid.".gif";
          		break;
        		case 'image/png':
                $uploadfile = $fotoid.".png";
          		break;
          		case 'image/jpeg':
                $uploadfile = $fotoid.".jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = $fotoid.".jpg";
          		break;
      			}

			 if (!move_uploaded_file($_FILES['foto']['tmp_name'], $this->uploaddir.$uploadfile))
		  		 $result="<span style='color:Red'>Ошибка загрузки файла</span>";
          }
          else
          $result="<span style='color:Red'>Неверный формат файла</span>";
        }
     }

	$text="<h1>Новое объявление</h1><br />\r\nТип объявления: ".$type."<br />\r\n"."Город: ".$city."<br />\r\n"."E-mail: ".$mail."<br />\r\n"."Телефон".$phone;
	$message=empty($_POST['message'])?'':$_POST['message'];

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=windows-1251\r\n";
	$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
	$subject = "Объявление №".$fotoid;

	if(!$result)
	{
	$query = "INSERT INTO `announcements` (`id`, `sale`, `category`, `date`, `range`, `brand`, `model`, `year`, `volume`, `city`, `price`, `file`, `body`, `phone`, `mail`, `contact`, `enabled`) VALUES (NULL, '".$sale."', '".$category."', NOW(), ADDDATE(NOW(), INTERVAL ".$range." DAY), '".$brand."', '".$model."', '".$year."', '".$volume."', '".$city."', '".$price."', '".$uploadfile."', '".$body."', '".$phone."', '".$mail."', '".$contact."', '0')";
	$res1 = $this->db->query($query);

	$this->item->announcements_id = $this->db->insert_id();
	$query2 = 'UPDATE `announcements` SET `order_num`=`id` WHERE `id`=\''.$this->item->announcements_id.'\'';
	$res2 = $this->db->query($query2);
	//echo "mail($email,$subject,$text,$headers)<br />\n";
	@mail($email,$subject,$text,$headers);
	if($res1 && $res2)
	{
	$result="<span style='color:LightGreen'>Объявление успешно добавлено</span>";
	}
	else
	{
	//Header("Location:index.php?section=26");
	$result="<span style='color:Red'>Ошибка добавления объявления</span>";
	//echo "E-mail fail<br />\n";
	}
	}
	}
    //print_r($_SESSION);

    $this->smarty->assign('Title', $this->title);
    $this->smarty->assign('result', $result);
    $this->smarty->assign('Announcements', $this->announcements);
    $this->body = $this->smarty->fetch('announcement.tpl');

  }
}