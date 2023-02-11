<?php
//ini_set('SMTP', 'mta.xxline.net');
//ini_set('smtp_port', 25);
//error_reporting(E_ALL);
error_reporting(0);
require_once('Config.class.php');
require_once('Database.class.php');



if(isset($_POST['name'])
&& $_POST['name']!="Ваше имя"
&& $_POST['name']!=""
&& isset($_POST['email'])
&& $_POST['email']!="E-mail"
&& $_POST['email']!=""
&& isset($_POST['message'])
&& !empty($_POST['message']))
{
$config=new Config();
            $db=new Database($config->dbname,$config->dbhost,
    							$config->dbuser,$config->dbpass);
    		$db->connect();
    		$db->query("SET NAMES cp1251");

    		$query = "SELECT * FROM settings";
    		$db->query($query);
    		$sts = $db->results();
    		foreach($sts as $s)
    		{
      			$name = $s->name;
    			$settings->$name = $s->value;
	   		}
$name=$_POST['name']=="Ваше имя"?'':$_POST['name'];
//echo "name = $name<br />\n";
$phone=$_POST['phone']=="Номер телефона"?'':$_POST['phone'];
//echo "phone = $phone<br />\n";
$mail=$_POST['email']=="E-mail"?'':$_POST['email'];
//echo "mail = $mail<br />\n";
$email=$settings->admin_email;
//echo "email = $email<br />\n";
$text="Имя: ".$name."<br />\r\n"."Телефон: ".$phone."<br />\r\n"."E-mail: ".$mail."<br />\r\n<br />\r\n".$_POST['message'];
$replace_to=array("<br>","<br>","<br>");
$replace_from=array("\r\n","\n","\r");
$text=str_replace($replace_from,$replace_to,$text);
//echo "text = $text<br />\n";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=windows-1251\r\n";
$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
$subject = "Сообщение от ".$name;
//echo "mail($email,$subject,$text,$headers)<br />\n";
if(preg_match('/^[^\@\.]+\@[^\@\.]+\.[0-9a-zA-z]+$/i',$mail))
{
if(mail($email,$subject,$text,$headers))
{
Header("Location:index.php?section=24");
//echo "Ok<br />\n";
}
else
{
Header("Location:index.php?section=26");
//echo "E-mail fail<br />\n";
}
}
else
{
Header("Location:index.php?section=27");
//echo "E-mail incorrect<br />\n";
}
}
else
{
Header("Location:index.php?section=25");
//echo "Missing data<br />\n";
}
?>
