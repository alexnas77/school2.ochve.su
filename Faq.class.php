<?PHP
//ini_set('SMTP', 'mta.xxline.net');
//ini_set('SMTP', 'mail.altrum.ru');
//ini_set('smtp_port', 25);
//require_once('Widget.class.php');

class Faq extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }

  function fetch()
  {
      $mode = $this->param('mode');
      if($mode == "contact")
      {
          $page_title = "��������";
          $this->title = "�������� | ����� ���������� � ������";
          $this->keywords = "�������� | ����� ���������� � ������";
          $this->description = "�������� | ����� ���������� � ������";

      }
      elseif($mode == "zakazat-zvonok")
      {
          $page_title = "�������� ������";
          $this->title = "�������� ������ | ����� ���������� � ������";
          $this->keywords = "�������� ������ | ����� ���������� � ������";
          $this->description = "�������� ������ | ����� ���������� � ������";

      }
      else
      {
          $page_title = "������� � ������";
          $this->title = "������� � ������";
          $this->keywords = "������� � ������";
          $this->description = "������� � ������";

      }


	$this->smarty->assign("Page_title", $page_title);
    $this->smarty->assign("Title", $this->title);
	$this->smarty->assign("Keywords", $this->keywords);
	$this->smarty->assign("Description", $this->description);

	$s_name = session_name();

	$s_id = session_id();

	$adress = $_SERVER['SERVER_NAME'];

/*    $query = "SELECT * FROM `faq` WHERE `show`='1' ORDER BY `order_num` DESC LIMIT 10";
	$this->db->query($query);
	$this->faqs = $this->db->results();*/


if(isset($_POST['sendmessage']))
{
$name=$_POST['name']=="���� ���"?'':$_POST['name'];
$_SESSION['name']=$name;
//echo "name = $name<br />\n";
$city=$_POST['city']=="�����"?'':$_POST['city'];
$_SESSION['city']=$city;
//echo "city = $city<br />\n";
    $subject=$_POST['subject']=="����"?'':$_POST['subject'];
    $_SESSION['subject']=$subject;
//echo "city = $city<br />\n";
    $time=$_POST['time']=="������� ����� ��� �����"?'':$_POST['time'];
    $_SESSION['time']=$time;
//echo "city = $city<br />\n";
$mail=$_POST['mail']=="E-mail"?'':$_POST['mail'];
$_SESSION['mail']=$mail;
$phone=$_POST['phone']=="�������"?'':$_POST['phone'];
$_SESSION['phone']=$phone;
//echo "mail = $mail<br />\n";
$email=$this->settings->admin_email;
//echo "email = $email<br />\n";
    if($mode == "contact")
    {
    $text="���: ".$name."\r\n"."����: ".$subject."\r\n"."E-mail: ".$mail."\r\n"."\r\n".���������."\r\n".$_POST['message'];
    }
    elseif($mode == "zakazat-zvonok")
    {
    $text="���: ".$name."\r\n"."������� ����� ��� �����: ".$time."\r\n"."\r\n"."�������: ".$phone."\r\n".���������."\r\n".$_POST['message'];
    }
$message=empty($_POST['message'])?'':$_POST['message'];
$_SESSION['message']=$message;
$replace_to=array("<br>","<br>","<br>");
$replace_from=array("\r\n","\n","\r");
$text=str_replace($replace_from,$replace_to,$text);
//echo "text = $text<br />\n";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=windows-1251\r\n";
$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
$subject0 = "��������� �� ".$name;
if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring'])
{

if(isset($name)
&& $name!="���� ���"
&& $name!=""
&& (($mode == "contact" && isset($mail) && $mail!="E-mail" && $mail!="") || ($mode == "zakazat-zvonok" && isset($phone) && $phone!="�������" && $phone!=""))
&& isset($message)
&& !empty($message))
{
if($mode == "zakazat-zvonok" || ($mode == "contact" && preg_match('/^[^\@\.]+\@[^\@\.]+\.[0-9a-zA-z]+$/i',$mail)))
{
//echo "mail($email,$subject,$text,$headers)<br />\n";
if(@mail($email,$subject0,$text,$headers))
{
//Header("Location:index.php?section=24");
unset($_SESSION['name'],$_SESSION['city'],$_SESSION['mail'],$_SESSION['phone'],$_SESSION['message'],$_SESSION['subject'],$_SESSION['time']);
$result="��������� ������� ���������";
$query = "INSERT INTO `faq` (`id`, `name`, `city`, `mail`, `phone`, `subject`, `time`, `message`, `answer`, `show`) VALUES (NULL, '".$name."', '".$city."', '".$mail."', '".$phone."', '".$subject."', '".$time."', '".$message."', NULL, '0')";
$this->db->query($query);
$this->item->faq_id = $this->db->insert_id();
$query2 = 'UPDATE `faq` SET `order_num`=`id` WHERE `id`=\''.$this->item->faq_id.'\'';
$this->db->query($query2);
}
else
{
//Header("Location:index.php?section=26");
$result="������ �������� ���������";
//echo "E-mail fail<br />\n";
}
}
else
{
//Header("Location:index.php?section=27");
$result="�������� ������ E-mail";
//echo "E-mail incorrect<br />\n";
}
}
else
{
//Header("Location:index.php?section=28");
$result="������������ ������ ��� �������� ���������";
//echo "Wrong code<br />\n";
}
}
else
{
//Header("Location:index.php?section=25");
$result="�������� �������� ���";
//echo "Missing data<br />\n";
}
}
    //print_r($_SESSION);

    $this->smarty->assign('S_name', $s_name);
    $this->smarty->assign('S_id', $s_id);
    $this->smarty->assign('Adress', $adress);
    if(isset($_SESSION['name']))
    $this->smarty->assign('name', $_SESSION['name']);
    if(isset($_SESSION['phone']))
    $this->smarty->assign('phone', $_SESSION['phone']);
    if(isset($_SESSION['city']))
    $this->smarty->assign('city', $_SESSION['city']);
    if(isset($_SESSION['mail']))
    $this->smarty->assign('mail', $_SESSION['mail']);
    if(isset($_SESSION['message']))
    $this->smarty->assign('message', $_SESSION['message']);
      if(isset($_SESSION['subject']))
          $this->smarty->assign('subject', $_SESSION['subject']);
      if(isset($_SESSION['time']))
          $this->smarty->assign('time', $_SESSION['time']);
    $this->smarty->assign('result', $result);
    $this->smarty->assign('Faqs', $this->faqs);
    $this->body = $this->smarty->fetch('faq.tpl');

  }
}