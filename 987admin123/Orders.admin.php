<?PHP

require_once('Widget.admin.php');
require_once('placeholder.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Sections displays a list of sections
############################################
class Orders extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $pays = array(0=>'Выберите метод оплаты',4=>'Оплата наличными при доставке курьером',1=>'Безналичный расчёт',2=>'WebMoney',3=>'Сберегательный Банк');
  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->add_param('page');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		$items_sql = implode("', '", $items);
  		$query = "DELETE FROM orders WHERE order_id IN ('$items_sql')";
  		$this->db->query($query);
  		$query = "DELETE FROM orders_products WHERE order_id IN ('$items_sql')";
  		$this->db->query($query);
  		$query = "DELETE FROM orders_bills WHERE order_id IN ('$items_sql')";
  		$this->db->query($query);
  		$query = "DELETE FROM orders_wm WHERE order_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
  	$this->title = $this->lang->ORDERS;
  	$current_page = intval($this->param('page'));

  	$bill_id = intval($this->param('bill_id'));
  	if($bill_id > 0)
  	{
   		$this->db->query("SELECT `text` FROM orders_bills WHERE order_id = '$bill_id'");
  		$res = $this->db->result();
        $this->smarty->assign('HTML', $res->text);
        $this->smarty->display('tranfer.tpl');
        exit();
  	}
  	$bill_id2 = intval($this->param('bill_id2'));
  	if($bill_id2 > 0)
  	{
   		$this->db->query("SELECT `text` FROM orders_wm WHERE order_id = '$bill_id2'");
  		$res = $this->db->result();
        $this->smarty->assign('HTML', $res->text);
        $this->smarty->display('tranfer_html.tpl');
        exit();
  	}
  	$bill_id3 = intval($this->param('bill_id3'));
  	if($bill_id3 > 0)
  	{
   		$this->db->query("SELECT `text` FROM orders_sberbank WHERE order_id = '$bill_id3'");
  		$res = $this->db->result();
        $this->smarty->assign('HTML', $res->text);
        $this->smarty->display('tranfer.tpl');
        exit();
  	}
  	$change_status_id = intval($this->param('change_status_id'));
  	$status = intval($this->param('status'));
  	if($change_status_id > 0)
  	{
        if($status == 1) // Уменьшим количество товаров при принятии заказа
        {
          $query = "SELECT orders_products.*, products.quantity as stock
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
    				    WHERE orders_products.order_id = '$change_status_id'";
          $this->db->query($query);
  	      $products = $this->db->results();
          foreach($products as $product)
          {
            $new_quantity = $product->stock-$product->quantity;
            $query = "UPDATE products SET quantity=$new_quantity WHERE product_id = '".$product->product_id."'";
            $this->db->query($query);
          }
        }

  		$this->db->query("UPDATE orders SET status='$status' WHERE order_id = '$change_status_id'");
  		header("Location: ".$this->form_get(array()));
  	}

  	$change_pay_id = intval($this->param('change_pay_id'));
  	if($change_pay_id > 0)
  	{
   		$this->db->query("SELECT `pay` FROM orders WHERE order_id = '$change_pay_id'");
  		$res = $this->db->result();
  		$paytype = 10*intval($res->pay);
  		$this->db->query("UPDATE orders SET pay='$paytype' WHERE order_id = '$change_pay_id'");
  		header("Location: ".$this->form_get(array()));
  	}

  	$filter = '';
    if($this->param('login'))
      $filter = 'AND `orders`.`login` = "'.$this->param('login').'"';

  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS `orders`.*, DATE_FORMAT(`orders`.`date`, '%d.%m.%y %k:%i') as `date_f`,
    				  `orders_bills`.`text`,`orders_wm`.`text` as `text2`,`orders_sberbank`.`text` as `text3`
                      FROM `orders` LEFT JOIN `orders_bills` ON `orders`.`order_id`=`orders_bills`.`order_id`
                      LEFT JOIN `orders_wm` ON `orders`.`order_id`=`orders_wm`.`order_id`
                      LEFT JOIN `orders_sberbank` ON `orders`.`order_id`=`orders_sberbank`.`order_id`
                      WHERE 1 $filter
    				  ORDER BY `status` DESC, `date` DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$orders = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
 	$currencies = $this->db->results();

 	$currency_rates = array();

 	  		  foreach($currencies as $k=>$currency)
		  {
 		   $currency_rates[$currency->code] = $currency->rate;
 		   /*if ($currency->main == '1' && empty($this->currency_code))
  			  $this->currency_code = $currency->sign;*/
  		  }
  	foreach($orders as $k=>$order)
    {
  	  $orders[$k]->change_status_url = $this->form_get(array('change_status_id'=>$order->order_id, 'status'=>$order->status+1));
  	  $orders[$k]->change_pay_url = $this->form_get(array('change_pay_id'=>$order->order_id));
  	  if(!empty($order->text))
  	  $orders[$k]->bill_url = $this->form_get(array('bill_id'=>$order->order_id));
  	  if(!empty($order->text2))
  	  $orders[$k]->bill_url = $this->form_get(array('bill_id2'=>$order->order_id));
  	  if(!empty($order->text3))
  	  $orders[$k]->bill_url = $this->form_get(array('bill_id3'=>$order->order_id));
      $this->db->query("SELECT orders_products.*, products.quantity as stock, products.currency_id as currency_id
                        FROM orders_products LEFT JOIN products ON products.product_id = orders_products.product_id
    				    WHERE orders_products.order_id = '$order->order_id'");
  	  $products = $this->db->results();
      $orders[$k]->products = $products;

    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Orders', $orders);
 	$this->smarty->assign('Currency_rates', $currency_rates);
 	$this->smarty->assign('Pays', $this->pays);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('orders.tpl');
  }
}