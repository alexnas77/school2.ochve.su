<?php
//require_once('webpage.php');

class Database_remote
{
  var $tunnel;
  var $db_name;
  var $host;
  var $user;
  var $pass;
  var $link;
  var $res_id;
  var $error_msg;
  var $file_log;
  var $query_log;
  var $total_time;
  var $opts;
  var $postdata;

  # Constructor
  function Database_remote($tunnel, $database_name, $host_name = "localhost", $user_name = "", $password = "")
  {
    $this->tunnel = $tunnel;
    $this->db_name = $database_name;
    $this->host = $host_name;
    $this->user = $user_name;
    $this->pass = $password;
    $this->link = 0;
    $this->res_id = 0;
    $this->error_msg = "";
    $this->file_log =dirname(__FILE__)."/cache/db_".$_SERVER['REMOTE_ADDR'];
    $this->query_log = false;
    //$this->query_log = true;
    $this->total_time = 0;
    $this->postdata =
    array(
        'db' => $this->db_name,
        'host' => $this->host,
        'login' => $this->user,
        'password' => $this->pass
    );
  }

  function encript($post=array())
  {
  if(!empty($post))
  $post2 = array();
  foreach($post as $k=>$v)
  {  $post2[strrev(base64_encode($k))] = strrev(base64_encode($v));  }

  return $post2;
  }

  # Connecting to the database
  function connect()
  {
       	if($this->query_log)
       	{
       	$file=fopen($this->file_log,"w");
		fclose($file);
        $this->total_time = microtime(true);
        }
	$this->postdata['actn'] = 'connect';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document) && $document == '1')
    {       	if($this->query_log)
       	{
      	$file=fopen($this->file_log,"w");
		fclose($file);
        $this->total_time = microtime(true);
        }    }
    else
    {
      $this->error_msg = "Could not connect to the database on $this->host";
      echo $this->error_msg;
      exit();
      return 0;
    }
    return 1;
  }

  # Close the database connection
  function disconnect()
  {
	$this->postdata['actn'] = 'disconnect';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {       	if($this->query_log)
       	{
        $this->total_time = microtime(true) - $this->total_time;
       	$file=fopen($this->file_log,"a");
		fwrite($file,date("H:i:s")."_Totaltime__".$this->total_time."\n");
		fclose($file);
		}
    return true;    }
    else
    {
       $this->error_msg = "Could not close the $this->db_name database";
       return false;
    }


  }

  # Execute the query or queries array
  function query($q)
  {
      if($this->query_log)
      $start = microtime(true);
	$this->postdata['actn'] = 'query';
	$this->postdata['q'] = $q;
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document) && $document == '1')
    {
      if($this->query_log)
      {
        if(is_file($this->file_log)){        $time = microtime(true)-$start;      	$file=fopen($this->file_log,"a");
		fwrite($file,date("H:i:s")."_\"".preg_replace('/[\r\n\s]+/',' ',$q)."\"_".$time."\n");
		fclose($file);
		}
      }
    }
    else
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      if($this->query_log)
      {
        if(is_file($this->file_log)){      	$file=fopen($this->file_log,"a");
		fwrite($file,"ERROR\n");
		fclose($file);
		}
      }
      return 0;
    }
    return 1;
  }

  # Returns results array of the query in array of objects
  function results()
  {
	$this->postdata['actn'] = 'results';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {
    if(($result = unserialize($document))==false)
    $this->error_msg = "Could not unserialize";    }
    else
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      return 0;
    }
    return $result;
  }

  # Returns result of the query in array of objects
  function result()
  {
	$this->postdata['actn'] = 'result';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {    $row = unserialize($document);    }
    else
    {
      $this->error_msg = "Could not execute query to $this->db_name database, wrong result id";
      return 0;
    }
    return $row;
  }

  # Returns last inserted id
  function insert_id()
  {
	$this->postdata['actn'] = 'insert_id';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {
    $result = $document;
    }
    return $result;
  }

  # Returns last inserted id
  function num_rows()
  {
	$this->postdata['actn'] = 'num_rows';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {
    $result = $document;
    }
    return $result;
  }

  # Returns last inserted id
  function affected_rows()
  {
	$this->postdata['actn'] = 'affected_rows';
    $this->opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
        'timeout'  => '300',
        'content' => http_build_query($this->encript($this->postdata))
    )
    );
	$context  = stream_context_create($this->opts);
	$document = file_get_contents($this->tunnel, false, $context);
    if(!empty($document))
    {
    $result = $document;
    }
    return $result;
  }

}

?>