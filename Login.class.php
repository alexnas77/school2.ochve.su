<?PHP

//require_once('Widget.class.php');

class Login extends Widget
{

  var $error = '';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
     //echo "SESSION<pre>"; print_r($_SESSION); echo "</pre><br>";
     //echo "COOKIE<pre>"; print_r($_COOKIE); echo "</pre><br>";     
     //echo "GET<pre>"; print_r($_GET); echo "</pre><br>";
     //echo "POST<pre>"; print_r($_POST); echo "</pre><br>";
     //echo "REQUEST<pre>"; print_r($_REQUEST); echo "</pre><br>";
     
      if(isset($_GET['action']) && $_GET['action']=='logout')
     {
       unset($_SESSION['user_login']);
       unset($_SESSION['user_password']);
 	   header("Location: ".$_SERVER['HTTP_REFERER']);
       exit();
     }
     if(isset($_REQUEST['login']) && isset($_REQUEST['password']))
     {
       $login = trim($_REQUEST['login']);
       $password  = md5(trim($_REQUEST['password']));
       $query = "select * from users where login='".mysql_real_escape_string($login)."' and password='".mysql_real_escape_string($password)."'";
       //echo $query."<br>";
       $this->db->query($query);
       $user = $this->db->result();
       //echo "<pre>"; print_r($user); echo "</pre><br>";
       if(empty($user))
         $error = 'Неверный логин или пароль';
       elseif($user->active == 0)
         $error = 'Ваша учетная запись отключена. Пожалуйста, обратитесь к администратору';

       if(!empty($error))
       {
          $this->smarty->assign('login_error', $error);
          $this->smarty->assign('login', $login);
       }else
       {
          $_SESSION['user_login'] = $login;
          $_SESSION['user_password'] = $password;
          header("Location: ".(isset($_REQUEST['referrer']) && !empty($_REQUEST['referrer']) && strpos($_REQUEST['referrer'], "/login")===false ? $_REQUEST['referrer'] : "/"));
       }
       //echo "<pre>"; print_r($_SESSION); echo "</pre>";
     }
  }


  function fetch()
  {
	$this->title = "Вход";

    $this->body = $this->smarty->fetch('login.tpl');
  }

}