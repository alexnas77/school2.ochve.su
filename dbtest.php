<?php 
  $dblocation = "127.0.0.1"; 
  $dbname = "testdrive"; 
  $dbuser = "root"; 
  $dbpasswd = "123"; 

  $dbcnx = @mysql_connect($dblocation, $dbuser, $dbpasswd); 
  if (!$dbcnx) 
  { 
    echo "<p>К сожалению, не доступен сервер mySQL</p>"; 
    exit(); 
  } 
  if (!@mysql_select_db($dbname,$dbcnx) ) 
  { 
    echo "<p>К сожалению, не доступна база данных</p>"; 
    exit(); 
  } 
  $ver = mysql_query("SELECT VERSION()"); 
  if(!$ver) 
  { 
    echo "<p>Ошибка в запросе</p>"; 
    exit(); 
  } 
  echo mysql_result($ver, 0); 
?> 