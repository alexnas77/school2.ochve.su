<?php
/******************************************************************************
class Widget - base class for web-applications                                *
                                                                              *
                                                                              *
                                                                              *
******************************************************************************/

require_once('Config.class.php');
require_once('../Database.class.php');

class Widget
{
	var $params = array();
    var $title = null;
    var $description = null;
    var $keywords = null;
    var $body = null;
    var $error_msg = null;
    var $msg = null;
    var $domain = "/";

	var $subbrands = array('/'=>'');

    var $db;
	var $smarty;
	var $config;
    var $settings;
    var $dsettings = array('site_name','title','keywords','description','counters');
	var $lang;
    var $parent;
    var $user;

    function stripslashes_recursive($var)
    {
    	if(is_array($var))
    	  foreach($var as $k=>$v)
    	    $var[$k] = $this->stripslashes_recursive($v);
    	  else
    	    $var = stripcslashes($var);
    	return $var;
    }

    function __construct(&$parent)
    {
        if (is_object($parent))
        {
        	$this->parent=$parent;
            $this->db=&$parent->db;
            $this->smarty=&$parent->smarty;
            $this->config=&$parent->config;
            $this->lang=&$parent->lang;
            $this->params=$parent->params;
            $this->settings=$parent->settings;
            $this->main_currency=$parent->main_currency;
            $this->subbrands=$parent->subbrands;
            $this->domain=$parent->domain;
        }
        else
        {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
			//if(get_magic_quotes_gpc())
			{
			  $_POST = $this->stripslashes_recursive($_POST);
			  $_GET = $this->stripslashes_recursive($_GET);
			}

			$this->config=new Config();

            require_once("Language.".$this->config->lang.".admin.php");
            $this->lang = new Language();

            require_once($this->config->smarty_dir);
            $this->smarty = new Smarty();
            $this->smarty->compile_check = true;
            $this->smarty->caching = false;
            $this->smarty->cache_lifetime = 0;
     		$this->smarty->debugging = false;
			$this->smarty->template_dir = 'templates/';
			$this->smarty->compile_dir = 'templates_c/';
			$this->smarty->config_dir = 'configs/';
			$this->smarty->cache_dir = 'cache/';

            $this->db=new Database($this->config->dbname,$this->config->dbhost,
    							$this->config->dbuser,$this->config->dbpass);
    		$this->db->connect();
    		$this->db->query("SET NAMES cp1251");

    		Header("Content-Type: text/html; charset=Windows-1251");

            $http_host = $_SERVER['HTTP_HOST'];
            //echo "http_host = $http_host\n";
            //$brand = 'Dewalt';
            //$_SESSION['brand'] = $brand;
            /*if(preg_match('/^([a-zA-Z0-9]+)\.[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/is',$http_host,$sub) && $sub[1]!="www")
            {
              $brand = array_search($sub[1],$this->subbrands);
              $_SESSION['brand'] = $brand;
              $this->domain = $sub[1];
              //echo "brandh = $brand\n";
            }*/

    		$query = "SELECT * FROM settings WHERE domain='/'";
    		$this->db->query($query);
    		$sts = $this->db->results();
    		$this->settings = new stdClass();
    		foreach($sts as $s)
    		{
      			$name = $s->name;
      			//if(!in_array($name,$this->dsettings))
      			$this->settings->$name = $s->value;
	   		}

    		$query = "SELECT * FROM settings WHERE domain='$this->domain'";
    		$this->db->query($query);
    		$dsts = $this->db->results();

	   		if($dsts)
    		foreach($dsts as $s)
    		{
      			$name = $s->name;
      			if(in_array($name,$this->dsettings))
      			$this->settings->$name = $s->value;
	   		}
	   		else
    		foreach($this->dsettings as $v)
    		{
    		$query = "INSERT INTO settings (`name`,`domain`,`value`) VALUES ('$v','".$this->domain."','".mysql_real_escape_string($this->settings->$v)."')";
    		//echo "$query<br />\n";
    		$this->db->query($query);
	   		}

			if(isset($_SESSION['login']))
			{
   		    $query = 'select `users`.*,`uc`.*,`users`.name as name,`uc`.name as catname from `users` left join `ucategories` as `uc` on `users`.`category_id`=`uc`.`category_id` where `login`=\''.$_SESSION['login'].'\'';
            //echo "$query<br />\n";
    		$this->db->query($query);
    		$this->user = $this->db->result();
    		$this->smarty->assign('User', $this->user);
    		//echo "<pre>".print_r($this->user,1)."</pre>";
                        }
                        else {
                            $_SESSION['is_loged'] = false;
                        }

   		    $query = 'select * from currencies where main=1';
    		$this->db->query($query);
    		$this->main_currency = $this->db->result();

	   		$this->smarty->assign('Settings', $this->settings);
	   		$this->smarty->assign('MainCurrency', $this->main_currency);
	   		$this->smarty->assign('Lang', $this->lang);


    	}
    }

    function fetch()
    {
    	$this->body="";
    }


    function param($name)
    {
    	if(!empty($name))
      	{
      		if(isset($this->params[$name]))
	  		  return $this->params[$name];
	  		elseif(isset($_GET[$name]))
	  		  return $_GET[$name];
    	}
	    return null;
    }

    /**
    	@param $name get variable name
		@param $value get variable value; the value from $_GET is taken

    */
    function add_param($name)
    {
    	if(!empty($name) && isset($_GET[$name]))
    	{
			$this->params[$name] = $_GET[$name];
	        return true;
    	}
	    return false;
    }

    function form_get($extra_params)
    {
    	$copy=$this->params;
      	foreach($extra_params as $key=>$value)
      	{
	    	if(!is_null($value))
    	  	{
          		$copy[$key]=$value;
	        }
    	}

	    $get='';
    	foreach($copy as $key=>$value)
		{
        	if(strval($value)!="")
	        {
    		    if(empty($get))
            	  $get .= '?';
	        	else
    	          $get .= '&';
    	        $get .= urlencode($key).'='.urlencode($value);
        	}
	    }
      	return $get;
    }

}

?>