<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');

############################################
# Class NewsLine displays news
############################################
class Currency extends Widget
{

  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->prepare();
  }


  function prepare()
  {
  	if(isset($_POST['act']) && $_POST['act']=='delete' && isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;

  		$query = "DELETE  FROM currencies
 		          WHERE currency_id IN ('$items_sql')";
  		$this->db->query($query);

  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_POST['act']) && $_POST['act']=='add' && isset($_POST['name']))
  	{
        $name =  $_POST['name'];
        $rate =  $_POST['rate'];
        $sign =  $_POST['sign'];
        $code =  $_POST['code'];

  		$query = "INSERT INTO currencies (name, rate, sign, code) VALUES('$name', '$rate', '$sign', '$code')";
  		$this->db->query($query);

  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
    if($_POST['names'])
    {
      $main = $_POST['main'];
      $this->db->query("UPDATE currencies SET main=0");
      $this->db->query("UPDATE currencies SET main=1 where currency_id='$main'");
      $names = $_POST['names'];
      foreach($names as $id=>$name)
      {
        $rate =  $_POST['rates'][$id];
        $sign =  $_POST['signs'][$id];
        $code =  $_POST['codes'][$id];
        $this->db->query("UPDATE currencies SET name='$name', sign='$sign', code='$code', rate='$rate' WHERE currency_id='$id'");
      }
    }
  }

  function fetch()
  {
    $this->title = 'Валюты';

    $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
    $items = $this->db->results();

 	$this->smarty->assign('Items', $items);
    $this->smarty->assign('Lang', $this->lang);
	$this->body = $this->smarty->fetch('currencies.tpl');
  }
}