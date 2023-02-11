<?php
$dir = "foto/storefront/";

// Открыть заведомо существующий каталог и начать считывать его содержимое
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	$file = trim($file);
            if(0 !== strpos($file,".") && false === strpos($file,".."))
            {
            clearstatcache();
            print "Разрешения файла: ".dirname(__FILE__)."/".$dir.$file." : " . substr(sprintf('%o', fileperms(dirname(__FILE__)."/".$dir.$file)), -4). "<br />\n";
            chmod(dirname(__FILE__)."/".$dir.$file, 0644);
            clearstatcache();
            print "Разрешения файла: ".dirname(__FILE__)."/".$dir.$file." : " . substr(sprintf('%o', fileperms(dirname(__FILE__)."/".$dir.$file)), -4). "<br /><br />\n";
            }
        }
        closedir($dh);
    }
}




?>
