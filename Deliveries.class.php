<?PHP

//require_once('Widget.class.php');

class Deliveries extends Widget
{
  var $section_id=19;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('id');
    $this->add_param('page');
  }

  function fetch()
  {
    $delivery_id = $this->param('id');
    if(!empty($delivery_id) && is_numeric($delivery_id))
      $this->fetch_item($delivery_id);
    else
      $this->fetch_list();
  }

  function fetch_list()
  {
    # Документы
 	$query = "SELECT `pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='2' ORDER BY order_num";
    $this->db->query($query);
    $deliveries = $this->db->results();

    #echo "<pre>".print_r($deliverys,true)."</pre>";

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = ".$this->section_id."");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Deliveries', $deliveries);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('deliveries.tpl');
  }

  function fetch_item($id)
  {
    $query = "SELECT `pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='2' AND `pages`.`page_id`=$id";
    $this->db->query($query);
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('Delivery', $item);
    $this->body = $this->smarty->fetch('delivery.tpl');
  }

}