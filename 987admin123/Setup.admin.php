<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Setup displays news
############################################
class Setup extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['main_section']))
  	{
        ## Site name
  		$site_name = $_POST['site_name'];
  		$query = "update settings set value='$site_name' where name='site_name' and domain='$this->domain'";
  		$this->db->query($query);

        ## Company name
  		$company_name = $_POST['company_name'];
  		$query = "update settings set value='$company_name' where name='company_name'";
  		$this->db->query($query);

        ## Admin email
  		$admin_email = $_POST['admin_email'];
  		$query = "update settings set value='$admin_email' where name='admin_email'";
  		$this->db->query($query);

  		## Main section
  		$main_section = $_POST['main_section'];
  		$query = "update settings set value='$main_section' where name='main_section'";
  		$this->db->query($query);

        ## Title
  		$title = $_POST['title'];
  		$query = "update settings set value='$title' where name='title' and domain='$this->domain'";
  		$this->db->query($query);

        ## Keywords
  		$keywords = $_POST['keywords'];
  		$query = "update settings set value='$keywords' where name='keywords' and domain='$this->domain'";
  		$this->db->query($query);

        ## Description
  		$description = $_POST['description'];
  		$query = "update settings set value='$description' where name='description' and domain='$this->domain'";
  		$this->db->query($query);

        ## ӥ즴﮻
  		$phones = $_POST['phones'];
  		$query = "update settings set value='$phones' where name='phones'";
  		$this->db->query($query);

        ## ҷ泷髨
  		$counters = mysql_real_escape_string($_POST['counters']);
  		$query = "update settings set value='$counters' where name='counters' and domain='$this->domain'";
  		$this->db->query($query);

        ## Footer text
  		$footer_text = mysql_real_escape_string($_POST['footer_text']);
  		$query = "update settings set value='$footer_text' where name='footer_text'";
  		$this->db->query($query);

  		## Admin email
  		$link_email = $_POST['link_email'];
  		$query = "update settings set value='$link_email' where name='link_email'";
  		$this->db->query($query);
                
        $backup_db = !empty($_POST['backup_db']) ? $_POST['backup_db'] : "";
        $query = "update settings set value='$backup_db' where name='backup_db'";
        $this->db->query($query);                

        $start = !empty($_POST['start']) ? $_POST['start'] : "01.09.".date("Y");
        $query = "update settings set value='$start' where name='start'";
        $this->db->query($query);

        $end = !empty($_POST['end']) ? $_POST['end'] : "31.05.".(date("Y")+1);
        $query = "update settings set value='$end' where name='end'";
        $this->db->query($query);

        $breakfast = $_POST['breakfast'];
        $query = "update settings set value='$breakfast' where name='breakfast'";
        $this->db->query($query);

        $lunch = $_POST['lunch'];
        $query = "update settings set value='$lunch' where name='lunch'";
        $this->db->query($query);

        $lunch2 = $_POST['lunch2'];
        $query = "update settings set value='$lunch2' where name='lunch2'";
        $this->db->query($query);

        $lunch3 = $_POST['lunch3'];
        $query = "update settings set value='$lunch3' where name='lunch3'";
        $this->db->query($query);

        $dinner = $_POST['dinner'];
        $query = "update settings set value='$dinner' where name='dinner'";
        $this->db->query($query);

  		//$get = $this->form_get(array('section'=>'Setup'));
 		header("Location: index.php");
 	}

  }

  function fetch()
  {
    $this->title = $this->lang->SETTINGS;
    $query = 'SELECT * FROM sections WHERE menu_id is not null ORDER BY name';
    $this->db->query($query);
    $sections= $this->db->results();
  	$this->smarty->assign('Settings', $this->settings);
  	$this->smarty->assign('Sections', $sections);
  	$this->smarty->assign('Lang', $this->lang);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
 	$this->body = $this->smarty->fetch('setup.tpl');
  }

  function upload_file($file, $name)
  {
  	if (!move_uploaded_file($file['tmp_name'], $name))
		$this->error_msg = $this->lang->UPLOAD_FILE_ERROR;
  }

}