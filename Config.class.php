<?php
if(is_file("ConfigLocal.class.php")) {
	require_once "ConfigLocal.class.php";
}
else {
class Config
{
    var $dbname = "alex_school2";
    var $dbhost = "localhost";
    var $dbuser = 'alex';
    var $dbpass = '@di90HG18de73IO46es#';
  var $domain = "localhost";
  var $key = "kieHWEeYutoIc";
  var $smarty_dir = "smarty/Smarty.class.php";
  var $delay=0;
}
}

?>