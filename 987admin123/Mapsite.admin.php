<?PHP

require_once('Widget.admin.php');


############################################
# Class NewsLine displays news
############################################
class Mapsite extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $menu;
  var $exts = array("",".html",".php");
  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('menu');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['id']))
  	{
  	  $items = $_POST['items'];
  	  //print_r($items);echo "<br />\n";
      $ids = $_POST['id'];
      $alturl = $_POST['alturl'];
      $ext = $_POST['ext'];
      //print_r($ids);echo "<br />\n";
      foreach($ids as $ck=>$id)
      {
		if(in_array($ck,$items))
		{
		$query = "UPDATE sections SET inmap='1',alturl='".$alturl[$ck]."',ext='".$ext[$ck]."' WHERE section_id = '$ck'";
		//echo "$query<br />\n";
		$this->db->query($query);
		}
		else
		{
		$query = "UPDATE sections SET inmap='0',alturl='".$alturl[$ck]."',ext='".$ext[$ck]."'  WHERE section_id = '$ck'";
		//echo "$query<br />\n";
		$this->db->query($query);
		}
	  }
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
  	$current_page = intval($this->param('page'));
  	/*$menu_id = intval($this->param('menu'));
    $query = "SELECT * FROM menu WHERE menu_id = '$menu_id'";
    $this->db->query($query);
  	$this->menu = $this->db->results();*/
  	$this->title = $this->lang->MAPSITE;

  	$start_item = $current_page*$this->items_per_page;
  	$query = "SELECT SQL_CALC_FOUND_ROWS sections.*,
                      services.name as service
    				  FROM sections, services
    				  WHERE sections.service_id = services.service_id
    				  /*AND sections.menu_id = '$menu_id'*/
    				  ORDER BY menu_id,order_num
    				  LIMIT $start_item ,$this->items_per_page";
    $this->db->query($query);
  	$sections = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    /*foreach($sections as $key=>$section)
    {
       $sections[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$section->section_id));
       $sections[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$section->section_id));
       $sections[$key]->edit_get = $this->form_get(array('section'=>'EditSection','item_id'=>$section->section_id));
    }*/

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
  	$this->smarty->assign('Menu', $this->menu);
  	$this->smarty->assign('Exts', $this->exts);
	//$this->smarty->assign('Fixed', $this->menu->fixed);
 	$this->smarty->assign('Mapsite', $sections);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('mapsite.tpl');
  }
}