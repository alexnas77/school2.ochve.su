<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');

############################################
# Class NewsLine displays news
############################################
class Payment extends Widget
{

  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->prepare();
  }


  function prepare()
  {
  	if(isset($_POST['SUBMIT']))
  	{
        ## WM_SHOP_PURSE_WMR
  		$wm_shop_purse_wmr = $_POST['wm_shop_purse_wmr'];
  		$query = "update settings set value='$wm_shop_purse_wmr' where name='wm_shop_purse_wmr'";
  		$this->db->query($query);

        ## WM_SHOP_PURSE_WMZ
  		$wm_shop_purse_wmz = $_POST['wm_shop_purse_wmz'];
  		$query = "update settings set value='$wm_shop_purse_wmz' where name='wm_shop_purse_wmz'";
  		$this->db->query($query);

        ## WM_SHOP_PURSE_WME
  		$wm_shop_purse_wme = $_POST['wm_shop_purse_wme'];
  		$query = "update settings set value='$wm_shop_purse_wme' where name='wm_shop_purse_wme'";
  		$this->db->query($query);

  		## WM_SHOP_WMID
  		$wm_shop_wmid = $_POST['wm_shop_wmid'];
  		$query = "update settings set value='$wm_shop_wmid' where name='wm_shop_wmid'";
  		$this->db->query($query);

        ## LMI_SECRET_KEY
  		$lmi_secret_key = $_POST['lmi_secret_key'];
  		$query = "update settings set value='$lmi_secret_key' where name='lmi_secret_key'";
  		$this->db->query($query);

        ## tranfer
  		$tranfer = $_POST['tranfer'];
  		$query = sql_placeholder("update settings set value=? where name='tranfer'",$tranfer);
  		$this->db->query($query);

        ## sberbank
  		$sberbank = $_POST['sberbank'];
  		$query = sql_placeholder("update settings set value=? where name='sberbank'",$sberbank);
  		$this->db->query($query);

  		## company_name
  		$company_name = $_POST['company_name'];
  		$query = sql_placeholder("update settings set value=? where name='company_name'",$company_name);
  		$this->db->query($query);

  		## company_addres
  		$company_addres = $_POST['company_addres'];
  		$query = sql_placeholder("update settings set value=? where name='company_addres'",$company_addres);
  		$this->db->query($query);

  		## inn
  		$inn = $_POST['inn'];
  		$query = "update settings set value='$inn' where name='inn'";
  		$this->db->query($query);

  		## kpp
  		$kpp = $_POST['kpp'];
  		$query = "update settings set value='$kpp' where name='kpp'";
  		$this->db->query($query);

  		## resiver
  		$resiver = $_POST['resiver'];
  		$query = sql_placeholder("update settings set value=? where name='resiver'",$resiver);
  		$this->db->query($query);

  		## resiver_bank
  		$resiver_bank = $_POST['resiver_bank'];
  		$query = sql_placeholder("update settings set value=? where name='resiver_bank'",$resiver_bank);
  		$this->db->query($query);

  		## resiver_account
  		$resiver_account = $_POST['resiver_account'];
  		$query = "update settings set value='$resiver_account' where name='resiver_account'";
  		$this->db->query($query);

  		## resiver_bank_account
  		$resiver_bank_account = $_POST['resiver_bank_account'];
  		$query = "update settings set value='$resiver_bank_account' where name='resiver_bank_account'";
  		$this->db->query($query);

  		## bik
  		$bik = $_POST['bik'];
  		$query = "update settings set value='$bik' where name='bik'";
  		$this->db->query($query);

  		## nds
  		$nds = $_POST['nds'];
  		$query = "update settings set value='$nds' where name='nds'";
  		$this->db->query($query);

  		## director
  		$director = $_POST['director'];
  		$query = "update settings set value='$director' where name='director'";
  		$this->db->query($query);

  		## glavbuh
  		$glavbuh = $_POST['glavbuh'];
  		$query = "update settings set value='$glavbuh' where name='glavbuh'";
  		$this->db->query($query);

  		## phones
  		$phones = $_POST['phones'];
  		$query = "update settings set value='$phones' where name='phones'";
  		$this->db->query($query);

  		## fax
  		$fax = $_POST['fax'];
  		$query = "update settings set value='$fax' where name='fax'";
  		$this->db->query($query);

  		## bill_exp
  		$bill_exp = $_POST['bill_exp'];
  		$query = "update settings set value='$bill_exp' where name='bill_exp'";
  		$this->db->query($query);

  		$get = $this->form_get(array('section'=>'Payment'));
 		header("Location: index.php$get");
 	}

  }

  function fetch()
  {
    $this->title = $this->lang->PAYMENT;
    $query = 'SELECT * FROM sections WHERE menu_id is not null ORDER BY name';
    $this->db->query($query);
    $sections= $this->db->results();
  	$this->smarty->assign('Settings', $this->settings);
  	$this->smarty->assign('Sections', $sections);
  	$this->smarty->assign('Lang', $this->lang);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
 	$this->body = $this->smarty->fetch('payment.tpl');
  }
}