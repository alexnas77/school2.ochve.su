<?PHP

//error_reporting(E_ALL ^E_NOTICE);

//error_reporting(0);

set_time_limit(0);

//require_once('Widget.class.php');
//require_once('Page.class.php');

class Index extends Widget
{
	var $page;
	function __construct($parent)
	{
		parent::__construct($parent);
		$this->add_param('section');
		$section_id = $this->param('section');

		if(empty($section_id))
		$section_id = $this->settings->main_section;

        $this->page = new Page($this);
	}

	function fetch()
	{
		$this->page->fetch();
		$this->body = $this->page->body;
		$this->db->disconnect();
	}
}