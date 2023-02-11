<?PHP
require_once('Widget.admin.php');
require_once('placeholder.php');
require_once('PagesNavigation.admin.php');

############################################
# Class Sections displays a list of sections
############################################
class Sections extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $menu;
  var $system_pages = array('topdefault','maintext','baskettext','bottomtext');
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
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		$items_sql = implode("', '", $items);
  		$query = "DELETE sections, external_links, pages
  		                FROM sections
  		                LEFT JOIN pages
  		                ON pages.page_id = sections.material_id  AND sections.service_id=1
  		                LEFT JOIN external_links
  		                ON external_links.external_link_id = sections.material_id AND sections.service_id=2
 		                WHERE (sections.section_id IN ('$items_sql'))";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$menu_id = intval($this->param('menu'));
  		$section_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.section_id
  		                  FROM sections s1, sections s2
  		                  WHERE s1.order_num>s2.order_num
  		                  AND s2.section_id = '$section_id'
  		                  AND s1.menu_id = '$menu_id'
  		                  AND s1.`domain`='$this->domain'
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE sections s1, sections s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.section_id = '$section_id'
  		                  AND s2.section_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$menu_id = intval($this->param('menu'));
  		$section_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.section_id
  		                  FROM sections s1, sections s2
  		                  WHERE s1.order_num<s2.order_num
  		                  AND s2.section_id = '$section_id'
  		                  AND s2.menu_id = '$menu_id'
  		                  AND s2.`domain`='$this->domain'
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE sections s1, sections s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.section_id = '$section_id'
  		                  AND s2.section_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
    if(intval($this->param('menu')==3))
	foreach ($this->system_pages as $system_page)
	{
		$query = "SELECT `sections`.`name`,`pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`domain`='$this->domain' AND `sections`.`url`='$system_page'";
		//echo "$query<br />\n";
        $this->db->query($query);
        $res = $this->db->result();

  		if(empty($res))
  		{
		$query = "SELECT `sections`.`name`,`pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`domain`='/' AND `sections`.`url`='$system_page'";
		//echo "$query<br />\n";
        $this->db->query($query);
        $mainres = $this->db->result();
		$query = "INSERT INTO `pages` (`domain`,`title`,`description`,`keywords`,`body`) VALUES ('$this->domain','$mainres->title','$mainres->description','$mainres->keywords','$mainres->name')";
		//echo "$query<br />\n";
        $this->db->query($query);
        $page_id = $this->db->insert_id();
		$query = "INSERT INTO `sections` (`url`,`domain`,`parent`,`name`,`menu_id`,`service_id`,`material_id`) VALUES ('$system_page','$this->domain',0,'$mainres->name',3,1,$page_id)";
		//echo "$query<br />\n";
        $this->db->query($query);
        $last_insert_id = $this->db->insert_id();
        $query = "UPDATE `sections` SET `order_num`=`section_id` WHERE `section_id`=$last_insert_id";
        //echo "$query<br />\n";
        $this->db->query($query);
  		}
  	}
  }

  function fetch()
  {
  	$current_page = intval($this->param('page'));
  	$menu_id = intval($this->param('menu'));

    $this->db->query("SELECT * FROM menu WHERE menu_id = '$menu_id'");
  	$this->menu = $this->db->result();
  	$this->title = $this->menu->name;

  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS sections.*,
                      services.name as service
    				  FROM sections, services
    				  WHERE sections.service_id = services.service_id
    				  AND sections.menu_id = '$menu_id'
    				  AND sections.domain='$this->domain'
    				  ORDER BY order_num
    				  LIMIT $start_item ,$this->items_per_page");
  	$sections = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($sections as $key=>$section)
    {
       $sections[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$section->section_id));
       $sections[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$section->section_id));
       $sections[$key]->edit_get = $this->form_get(array('section'=>'EditSection','item_id'=>$section->section_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
  	$this->smarty->assign('Menu', $this->menu);
	$this->smarty->assign('Fixed', $this->menu->fixed);
 	$this->smarty->assign('Sections', $sections);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('sections.tpl');
  }
}

############################################
# Class EditSection - Edit the section
############################################
class EditSection extends Widget
{
  var $form;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('item_id');
    $this->add_param('page');
    $this->add_param('menu');
    $this->prepare();
  }

  function prepare()
  {
    if(isset($_GET['item_id']))
  	  $this->section_id = $_GET['item_id'];

  	$this->db->query("SELECT * FROM sections WHERE section_id='$this->section_id'");
    $section = $this->db->result();

  	if($section->service_id == 1)
 	  $this->form = new EditStaticSection($this);
  	elseif(($section->service_id == 2))
 	  $this->form = new EditExternalLinkSection($this);
  	else
 	  $this->form = new EditServiceSection($this);
  }

  function fetch()
  {
    $menu_id = $this->param('menu');
    $this->db->query("SELECT * FROM menu WHERE menu_id = '$menu_id'");
  	$this->menu = $this->db->result();
    $this->smarty->assign('Menu', $this->menu);
  	$this->form->fetch();
  	$this->body = $this->form->body;
  	$this->title = $this->form->title;
  }
}

############################################
# Class EditStaticSection - edit the static section
############################################
class EditStaticSection extends Widget
{
  var $section;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
    $this->section->id = $this->param('item_id');
  	if(isset($_POST['id']))
  	{
  	  if(isset($_POST['name']))
  	    $this->section->name = $_POST['name'];
  	  if(isset($_POST['url']))
  	    $this->section->url = $_POST['url'];
      if(isset($_POST['title']))
  	    $this->section->title = $_POST['title'];
  	  if(isset($_POST['description']))
  	    $this->section->description = $_POST['description'];
  	  if(isset($_POST['keywords']))
  	    $this->section->keywords = $_POST['keywords'];
  	  if(isset($_POST['body']))
  	    $this->section->body = $_POST['body'];

      ## Не допустить одинаковые URL разделов.
  	  $query = sql_placeholder("select count(*) as count from sections where url=? and sections.section_id!=? and domain='$this->domain'",
                $this->section->url,
  	  			$this->section->id);
      $this->db->query($query);
      $res = $this->db->result();

  	  if(empty($this->section->name))
  		  $this->error_msg = $this->lang->ENTER_SECTION_NAME;
  	  elseif(empty($this->section->url))
  		  $this->error_msg = $this->lang->ENTER_PAGE_URL;
  	  elseif($res->count>0)
  		  $this->error_msg = $this->lang->SECTION_WITH_SAME_URL_ALREADY_EXISTS;
      else{

  	    $query = sql_placeholder("UPDATE sections, pages
  	                    		SET sections.name=?,
                                sections.url=?,
  	                    		pages.title = ?,
  	                    		pages.description = ?,
  	                    		pages.keywords = ?,
  	                    		pages.body = ?
  	                    		WHERE sections.section_id=?
  	                    		AND sections.material_id = pages.page_id
  	                    		AND sections.domain='$this->domain'",
  	  			$this->section->name,
                $this->section->url,
  	  			$this->section->title,
  	  			$this->section->description,
  	  			$this->section->keywords,
  	  			$this->section->body,
  	  			$this->section->id);

  	    $this->db->query($query);
  	    $get = $this->form_get(array('section'=>'Sections'));
  	    header("Location: index.php$get");
      }
  	}
    else
    {
      $query = sql_placeholder("SELECT sections.section_id as id,
  	                           	sections.name as name,
                                sections.url as url,
                                sections.menu_id as menu_id,
  	                           	pages.title as title,
  	                           	pages.description as description,
                                pages.keywords as keywords,
  	                           	pages.body as body
  	                    		FROM sections, pages
  	                    		WHERE sections.section_id=?
  	                    		AND sections.material_id = pages.page_id
  	                    		AND sections.domain='$this->domain'",
                                $this->section->id);
  	  $this->db->query($query);
  	  $this->section = $this->db->result();
    }
  }

  function fetch()
  {
  	  $this->title = $this->lang->EDIT_SECTION.' &laquo;'.$this->section->name.'&raquo';
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('Section', $this->section);
	  $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('static_section.tpl');
  }
}

############################################
# Class EditExternalLinkSection - edit the static section
############################################
class EditExternalLinkSection extends Widget
{
  var $section;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
    $this->section->id = $this->param('item_id');
  	if(isset($_POST['id']))
  	{
      if(isset($_POST['name']))
  	    $this->section->name = $_POST['name'];
  	  if(isset($_POST['url']))
  	    $this->section->url = $_POST['url'];
  	  if(isset($_POST['href']))
  	    $this->section->href = $_POST['href'];

      ## Не допустить обдинаковые URL разделов.
  	  $query = sql_placeholder('select count(*) as count from sections where url=? and sections.section_id!=? ',
                $this->section->url,
  	  			$this->section->id);
      $this->db->query($query);
      $res = $this->db->result();


  	  if(empty($this->section->name))
  		  $this->error_msg = $this->lang->ENTER_SECTION_NAME;
  	  elseif(empty($this->section->url))
  		  $this->error_msg = $this->lang->ENTER_PAGE_URL;
  	  elseif($res->count>0)
  		  $this->error_msg = $this->lang->SECTION_WITH_SAME_URL_ALREADY_EXISTS;
      else
      {
  	    $query = sql_placeholder('UPDATE sections, external_links
  	                    SET sections.name=?,
                        sections.url = ?,
  	                    external_links.url = ?
  	                    WHERE sections.section_id=?
  	                    AND sections.material_id = external_links.external_link_id',
  	   					$this->section->name,
                        $this->section->url,
  	   					$this->section->href,
  	  					$this->section->id);
  	    $this->db->query($query);
  	    $get = $this->form_get(array('section'=>'Sections'));
  	    header("Location: index.php$get");
      }
  	}
    else
  	{
      $query = sql_placeholder('SELECT sections.section_id as id,
  	                           			sections.name as name,
                                        sections.url as url,
  	                           			external_links.url as href
								FROM sections, external_links
								WHERE sections.section_id=?
								AND sections.material_id = external_links.external_link_id',
                                $this->section->id);

  	  $this->db->query($query);
  	  $this->section = $this->db->result();
  	}
  }

  function fetch()
  {
  	  $this->title = $this->lang->EDIT_SECTION.' &laquo;'.$this->section->name.'&raquo';
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
	  $this->smarty->assign('Section', $this->section);
 	  $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('link_section.tpl');
  }
}


############################################
# Class EditServiceSection - edit the static section
############################################
class EditServiceSection extends Widget
{
  var $section;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
    $this->section->id = $this->param('item_id');
  	if(isset($_POST['id']))
  	{
      if(isset($_POST['name']))
  	    $this->section->name = $_POST['name'];
      if(isset($_POST['url']))
  	    $this->section->url = $_POST['url'];

      ## Не допустить обдинаковые URL разделов.
  	  $query = sql_placeholder('select count(*) as count from sections where url=? and sections.section_id!=? ',
                $this->section->url,
  	  			$this->section->id);
      $this->db->query($query);
      $res = $this->db->result();

  	  if(empty($this->section->name))
  		  $this->error_msg = $this->lang->ENTER_SECTION_NAME;
  	  elseif(empty($this->section->url))
  		  $this->error_msg = $this->lang->ENTER_PAGE_URL;
  	  elseif($res->count>0)
  		  $this->error_msg = $this->lang->SECTION_WITH_SAME_URL_ALREADY_EXISTS;
      else{
  	    $query = sql_placeholder('UPDATE sections
  	                    		SET name=?, url=?
  	                    		WHERE section_id=?',
  	                    		$this->section->name,
                                $this->section->url,
  	                    		$this->section->id);
  	    $this->db->query($query);
  	    $get = $this->form_get(array('section'=>'Sections'));
  	    header("Location: index.php$get");
      }
  	}
    else
  	{
      $query = sql_placeholder('SELECT section_id as id,
  	                            		name as name, url as url
	                    		FROM sections
  			                    WHERE section_id=?',
            		            $this->section->id);
 	  $this->db->query($query);
  	  $this->section = $this->db->result();
  	}
  }

  function fetch()
  {
  	  $this->title = $this->lang->EDIT_SECTION.' &laquo;'.$this->section->name.'&raquo';
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('Section', $this->section);
 	  $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('service_section.tpl');
  }
}

############################################
# Class New Section - creates a new section
############################################
class NewSection extends Widget
{
  var $section;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('menu');
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['name']) && isset($_POST['service_id']))
  	{
  		$this->section->name = $_POST['name'];
  		$this->section->url = trim($_POST['url']);
  		$this->section->service_id = $_POST['service_id'];
  		$menu_id = $this->param('menu');

        ## Не допустить обдинаковые URL разделов.
    	$query = sql_placeholder("select count(*) as count from sections where url=? and domain='$this->domain'",
                $this->section->url,
  	  			$this->section->id);
        $this->db->query($query);
        $res = $this->db->result();

  	  if(empty($this->section->name))
  		  $this->error_msg = $this->lang->ENTER_SECTION_NAME;
  	  elseif(empty($this->section->url))
  		  $this->error_msg = $this->lang->ENTER_PAGE_URL;
  	  elseif($res->count>0)
  		  $this->error_msg = $this->lang->SECTION_WITH_SAME_URL_ALREADY_EXISTS;

  		elseif($this->section->service_id == 1)
  		{
  			$query = "INSERT INTO pages VALUES(NULL, '$this->domain', '','','','')";
  			$this->db->query($query);
  			$inserted_page_id = $this->db->insert_id();
  			$query = sql_placeholder("INSERT INTO sections (section_id, url, domain, parent, name, menu_id, order_num, service_id, material_id) VALUES(NULL, ?, '$this->domain', 0, ?, ?, 0, 1, ?)",
  			                          $this->section->url,
                                      $this->section->name,
  			                          $menu_id,
  			                          $inserted_page_id);
  			//echo "$query<br />\n";
  			$this->db->query($query);
  			$inserted_id = $this->db->insert_id();
  			$query = sql_placeholder('UPDATE sections SET order_num=section_id WHERE section_id=?',
  			                          $inserted_id);
  			$this->db->query($query);
 			$get = $this->form_get(array('section'=>'EditSection', 'item_id'=>$inserted_id));
  		    header("Location: index.php$get");
  		}elseif($this->section->service_id == 2)
  		{
  			$this->db->query("INSERT INTO external_links VALUES(NULL, '', '$this->domain')");
  			$inserted_link_id = $this->db->insert_id();
  			$query = sql_placeholder("INSERT INTO sections (section_id, url, domain, parent, name, menu_id, order_num, service_id, material_id) VALUES(NULL, ?, '$this->domain', 0, ?, ?, 0, 2, ?)",
  			                          $this->section->url,
                                      $this->section->name,
  			                          $menu_id,
  			                          $inserted_link_id);
  			$this->db->query($query);
  			$inserted_id = $this->db->insert_id();
  			$query = sql_placeholder('UPDATE sections SET order_num=section_id WHERE section_id=?',
  			                          $inserted_id);
  			$this->db->query($query);
 			$get = $this->form_get(array('section'=>'EditSection', 'item_id'=>$inserted_id));
  		    header("Location: index.php$get");
  		}else
  		{
  			$query = sql_placeholder("INSERT INTO sections (section_id, url, domain, parent, name, menu_id, order_num, service_id, material_id) VALUES(NULL, ?, '$this->domain', 0, ?, ?, 0, ?, NULL)",
                                      $this->section->url,
  			                          $this->section->name,
  			                          $menu_id,
  			                          $this->section->service_id);
  			$this->db->query($query);
  			$inserted_id = $this->db->insert_id();
  			$query = sql_placeholder('UPDATE sections SET order_num=section_id WHERE section_id=?',
  			                          $inserted_id);
  			$this->db->query($query);

 			$get = $this->form_get(array('section'=>'Sections'));
  		    header("Location: index.php$get");
  		}
  	}else
    {
    	$this->section->id = '';
    	$this->section->name = '';
    }
  }

  function fetch()
  {
  	  $this->title = $this->lang->NEW_SECTION;
 	  $this->db->query("SELECT * FROM services");
 	  $services = $this->db->results();
 	  $this->smarty->assign('Services', $services);

      $menu_id = $this->param('menu');
      $this->db->query("SELECT * FROM menu WHERE menu_id = '$menu_id'");
      $this->menu = $this->db->result();
      $this->smarty->assign('Menu', $this->menu);

 	  $this->smarty->assign('Section', $this->section);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('section.tpl');
  }
}