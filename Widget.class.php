<?php
/******************************************************************************
class Widget - base class for web-applications                                *
                                                                              *
                                                                              *
                                                                              *
******************************************************************************/

//require_once('Config.class.php');
//require_once('Database.class.php');
require_once('placeholder.php');


class Widget
{
	var $params = array();
    var $title = null;
    var $description = null;
    var $keywords = null;
    var $body = null;
    var $error_msg = null;
    var $msg = null;
    var $toptext = null;
    var $domain = "/";

	var $subbrands = array('/'=>'');

    var $db;
	var $smarty;
	var $config;
    var $parent;
    var $settings;
    var $dsettings = array('site_name','title','keywords','description','counters');
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
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (is_object($parent))
        {
        	$this->parent=$parent;
            $this->db=&$parent->db;
            $this->smarty=&$parent->smarty;
            $this->config=&$parent->config;
            $this->params=$parent->params;
            $this->settings=$parent->settings;
            $this->user=$parent->user;
            $this->root_dir=$parent->root_dir;
            $this->currency=$parent->currency;
            $this->subbrands=$parent->subbrands;
            $this->domain=$parent->domain;
        }
        else
        {
			//if(get_magic_quotes_gpc())
			{
			  $_POST = $this->stripslashes_recursive($_POST);
			  $_GET = $this->stripslashes_recursive($_GET);
			}

            $this->root_dir =  str_replace(basename($_SERVER["PHP_SELF"]), '', $_SERVER["PHP_SELF"]);

			$this->config=new Config();
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
//            $_SESSION['brand'] = $brand;
            /*if(preg_match('/^([a-zA-Z0-9]+)\.[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/is',$http_host,$sub) && $sub[1]!="www")
            {
              $brand = array_search($sub[1],$this->subbrands);
              $_SESSION['brand'] = $brand;
              $this->domain = $sub[1];
              //echo "brandh = $brand\n";
            }
            else*/if(isset($_POST['brand']))
            {
              $brand = $_POST['brand'];
              $_SESSION['brand'] = $brand;
              //echo "brandp = $brand\n";
            }

            $rules = file(".htaccess");
            if($rules)
            {
                $MY_REQUEST_URI = preg_replace('/^\/+/is','',$_SERVER['REQUEST_URI']);
                //echo "MY_REQUEST_URI = ".$MY_REQUEST_URI."<br />\n";
                foreach($rules as $rule)
                {
                    if(preg_match('/^RewriteRule\s+(\^[\S]+\$?)\s+([\S]+)\s+/is',$rule,$match))
                    {
                        //echo "<pre>\n"; print_r($match); echo "</pre><hr>\n";
                        $pattern = "/".str_replace('/','\/',$match[1])."/is";
                        //echo "preg_match('$pattern','".$MY_REQUEST_URI."');<br />\n";
                        //echo "preg_replace('".$pattern.",'".$match[2]."','".$MY_REQUEST_URI."');<br />\n";
                        if(preg_match($pattern,$MY_REQUEST_URI))
                        {
                            $out = preg_replace($pattern,$match[2],$MY_REQUEST_URI);
                            //echo "out = $out<br />\n";
                            //echo "query = ".preg_replace('/^index\.php\?/is','',$out)."<br />\n";
                            if(preg_match('/^index\.php\?/is',$out))
                            {
                                Header("HTTP/1.1 200 Ok");
                                parse_str(preg_replace('/^index\.php\?/is','',$out),$_GET);
                            }
                            else
                            {
                                Header("Location: http://".$_SERVER['HTTP_HOST']."/".preg_replace('/^\/+/is','',$out));
                                exit();
                            }
                            //echo "GET = <pre>\n"; print_r($_GET); echo "</pre><hr>\n";
                            break;
                        }
                    }
                }
            }
            //echo "GET = <pre>\n"; print_r($_GET); echo "</pre>\n";

                $this->settings = new stdClass();
                
    		$query = "SELECT * FROM settings WHERE domain='/'";
    		$this->db->query($query);
    		$sts = $this->db->results();
    		foreach($sts as $s)
    		{
                $name = $s->name;
      			if(!in_array($name,$this->dsettings) && !empty($s->name)){
                    $this->settings->$name = $s->value;
                }

	   		}

    		$query = "SELECT * FROM settings WHERE domain='$this->domain'";
    		$this->db->query($query);
    		$dsts = $this->db->results();

	   		if($dsts){
                foreach($dsts as $s)
                {
                    $name = $s->name;
                    if(in_array($name,$this->dsettings) && !empty($s->name)){
                        $this->settings->$name = $s->value;
                    }

                }
                $this->smarty->assign('Settings', $this->settings);
            }



            ####  User login
            $login = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : "";
            $password = isset($_SESSION['user_password']) ? $_SESSION['user_password'] : "";
    		$query = sql_placeholder("select users.*, ucategories.discount as discount, ucategories.name as category, ucategories.category_id from users left join ucategories on ucategories.category_id=users.category_id where login=? and password=? and active=1", $login, $password);
    		$this->db->query($query);
    		$this->user = $this->db->result();
            $this->smarty->assign('User', $this->user);
            ####

            # ������
            $query = "SELECT * FROM currencies ORDER BY currency_id";
            $this->db->query($query);
            $currencies = $this->db->results();

            $query = "SELECT * FROM currencies WHERE main";
            $this->db->query($query);
            $main_currency = $this->db->result();

            if(isset($_POST['currency_id']))
            {
              $currency_id = $_POST['currency_id'];
              $query = sql_placeholder("SELECT * FROM currencies WHERE currency_id=?", $currency_id);
              $this->db->query($query);
              $this->currency = $this->db->result();
              $_SESSION['currency'] = $this->currency ;
            }elseif(isset($_SESSION['currency'])){
             $this->currency = $_SESSION['currency'];
            }else{
             $this->currency = $main_currency;
            }
            ##

	   		$this->smarty->assign('RootDir', $this->root_dir);

          	$this->smarty->assign('Currencies', $currencies);
		    $this->smarty->assign('Currency', $this->currency);
		    $this->smarty->assign('MainCurrency', $main_currency);
            ####

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