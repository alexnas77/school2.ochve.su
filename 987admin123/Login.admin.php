<?PHP

require_once('Widget.admin.php');

class Login extends Widget
{

  var $error = '';
  var $is_loged;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {

     if(isset($_GET['action']) && $_GET['action']=='logout')
     {
       unset($_SESSION['login']);
       unset($_SESSION['password']);
       unset($_SESSION['is_loged']);
 	   header("Location: index.php");
       exit();
     }
     if(isset($_POST['submit']))
     {
       $login = $_POST['login'];
       $password  = md5($_POST['password']);
       $query = "select `users`.*,`uc`.`isadmin` from `users` left join `ucategories` as `uc` on `users`.`category_id`=`uc`.`category_id` where `login`='$login' and `password`='$password'";
       $this->db->query($query);
       $user = $this->db->result();

       if(empty($user))
         $this->error = 'Неверный логин или пароль';
       elseif($user->active == 0)
         $this->error = 'Ваша учетная запись отключена. Пожалуйста, обратитесь к администратору';
       elseif(!$user->isadmin)
         $this->error = 'У Вас нет доступа в Административную часть.';

       if(!empty($this->error))
       {
          $this->smarty->assign('login_error', $this->error);
          $this->smarty->assign('login', $login);
          $this->is_loged = false;
       }else
       {
          $_SESSION['login'] = $login;
          $_SESSION['password'] = $password;
          $_SESSION['is_loged'] = true;
 		 header("Location: index.php");
       }
     }
  }


  function fetch()
  {
    $this->body = $this->smarty->fetch('login.tpl');
  }

}