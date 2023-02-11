<?PHP

//require_once('Widget.class.php');

class Registration extends Widget
{

  var $error = '';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
     if(isset($_POST['name']) && isset($_POST['login']) && isset($_POST['password']))
     {

       $name = $_POST['name'];
       $login = $_POST['login'];
       $password  = $_POST['password'];
       $password2  = $_POST['password2'];
       $mail  = $_POST['mail'];
       $query = "select * from users where login='$login'";
       $this->db->query($query);
       $num = $this->db->num_rows();
       if($num>0)
         $error = "Логин $login уже используется";
       if(empty($login))
         $error = "Введите логин";
       if($password != $password2)
         $error = "Подтверждение пароля неверно";

		if(!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] != $_POST['keystring'])
	    $error = "Защитный код неверен";

       if(!empty($error))
       {
          $this->smarty->assign('error', $error);
          $this->smarty->assign('name', $name);
          $this->smarty->assign('login', $login);
          $this->smarty->assign('mail', $mail);
       }else
       {
          $query = sql_placeholder("insert into users (name, login, password, mail, active, category_id) values(?, ?, ?, ?, 1, 0)", $name, $login, md5($password), $mail);
          $this->db->query($query);
          /*$_SESSION['user_login'] = $login;
          $_SESSION['user_password'] = $password;*/
          echo <<<DATA
          <html>
          <head>
          <META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.php'>
          <script type="text/javascript">
          alert('Пользователь успешно заригистрирован');
          </script>
          </head>
          </html>
DATA;
       }



    }
  }


  function fetch()
  {
		$this->title = "Регистрация";

		$s_name = session_name();

		$s_id = session_id();

		$adress = $_SERVER['SERVER_NAME'];

        $query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='agreement'";
        $this->db->query($query);
        $res = $this->db->result();
        $agreement = $res->body;
        $this->smarty->assign('S_name', $s_name);
    	$this->smarty->assign('S_id', $s_id);
    	$this->smarty->assign('Adress', $adress);
    	$this->smarty->assign('Agreement', $agreement);
    if($_GET['download'])
    {
    $this->smarty->display('agreement.tpl');
    exit();
    }
    else
    $this->body = $this->smarty->fetch('registration.tpl');
  }

}