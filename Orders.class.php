<?PHP

//require_once('Widget.class.php');

class Orders extends Widget
{

  var $pays = array('Не выбран','Безналичный расчёт','WebMoney','Сберегательный Банк');

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
  }

  function fetch()
  {
    if($this->user)
    {

  	$bill_id = intval($this->param('bill_id'));
  	if($bill_id > 0)
  	{
   		$this->db->query("SELECT `text` FROM orders_bills WHERE order_id = '$bill_id'");
  		$res = $this->db->result();
        $this->smarty->assign('HTML', $res->text);
        $this->smarty->display('tranfer.tpl');
        exit();
  	}

    $this->db->query("SELECT `orders`.*, DATE_FORMAT(`orders`.`date`, '%d.%m.%Y') as date,`orders_bills`.`text` FROM `orders` LEFT JOIN `orders_bills` ON `orders`.`order_id`=`orders_bills`.`order_id` WHERE `orders`.`login`='".$this->user->login."' ORDER BY `orders`.`order_id` DESC");
    $orders = $this->db->results();
  	foreach($orders as $k=>$order)
    {
      $this->db->query("SELECT orders_products.*, products.quantity as stock
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
    				    WHERE orders_products.order_id = '$order->order_id'");
  	  $products = $this->db->results();
      $orders[$k]->products = $products;
      if(!empty($order->text))
  	  $orders[$k]->bill_url = $this->form_get(array('bill_id'=>$order->order_id));

    }



    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Orders', $orders);
    $this->smarty->assign('Section', $section);
    $this->smarty->assign('Pays', $this->pays);
    $this->body = $this->smarty->fetch('orders.tpl');
    }
  }

}