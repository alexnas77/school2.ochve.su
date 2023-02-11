<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Comments extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('product_id');
    $this->add_param('ip');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM products_comments
 		          WHERE comment_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->USERCOMMENTS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;

    $filter = '';
    if($this->param('product_id'))
      $filter = 'AND products_comments.product_id = '.$this->param('product_id');
    if($this->param('ip'))
      $filter = 'AND products_comments.ip = "'.$this->param('ip').'"';

    $query = "SELECT SQL_CALC_FOUND_ROWS *,
                      DATE_FORMAT(products_comments.date, '%d.%m.%Y') as date
    				  FROM products_comments, products
                      WHERE products_comments.product_id = products.product_id $filter
    				  ORDER BY products_comments.comment_id DESC
    				  LIMIT $start_item ,$this->items_per_page";
    $this->db->query($query);
  	$items = $this->db->results();
    foreach($items as $key=>$item)
    {
       $items[$key]->product_get = $this->form_get(array('product_id'=>$item->product_id, 'ip'=>'', 'page'=>''));
       $items[$key]->ip_get = $this->form_get(array('ip'=>$item->ip, 'product_id'=>'', 'page'=>''));
    }


    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('comments.tpl');
  }
}