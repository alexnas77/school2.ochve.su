<?php
set_time_limit(0);
$dir = "foto/storefront/";
echo "<h1>Сжатие фоток</h1>\n";
$total_size = 0;
$new_total_size = 0;
if(isset($_GET['go']) && $_GET['go']=='true')
{
// Открыть заведомо существующий каталог и начать считывать его содержимое
if (is_dir($dir)) {
echo "<ol>\n";
if(!@mkdir(dirname(__FILE__)."/".$dir."copy/") && !@is_dir(dirname(__FILE__)."/".$dir."copy/"))
exit("Невозможно создать каталог ".dirname(__FILE__)."/".$dir."copy/");
@chmod(dirname(__FILE__)."/".$dir."copy/", 0777);
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	$file = trim($file);
            if(0 !== strpos($file,".") && false === strpos($file,"..") && !is_dir(dirname(__FILE__)."/".$dir.$file))
            {
            @chmod(dirname(__FILE__)."/".$dir.$file, 0666);
            if(!is_writeable(dirname(__FILE__)."/".$dir.$file))
            echo ("<li>Невозвожно записать в файл ".dirname(__FILE__)."/".$dir.$file."</li>\n");
            else
            {
            $file_info = getimagesize(dirname(__FILE__)."/".$dir.$file);
            $x = $file_info[0];
            $y = $file_info[1];
            if($file_info[2] == IMAGETYPE_JPEG && (max($x,$y)>400 || filesize(dirname(__FILE__)."/".$dir.$file)>100*1024))
            {            $total_size += filesize(dirname(__FILE__)."/".$dir.$file);
            print "<li>Файл: ".dirname(__FILE__)."/".$dir.$file." ширина: ".$x." высота: ".$y." размер: ".filesize(dirname(__FILE__)."/".$dir.$file)." байт<br />\n";
            if(@copy(dirname(__FILE__)."/".$dir.$file,dirname(__FILE__)."/".$dir."copy/".$file))
            {
            print "скопирован в : ".dirname(__FILE__)."/".$dir."copy/".$file."<br />\n";
            $src_im = imagecreatefromjpeg(dirname(__FILE__)."/".$dir."copy/".$file);
            	if(max($x,$y)==$x && max($x,$y)>400)
            	{
				$width =  400;            	$height =  intval(400*$y/$x);            	}
            	elseif(max($x,$y)==$y && max($x,$y)>400)
            	{
				$width =  intval(400*$x/$y);            	$height =  400;            	}
            	else
            	{
				$width =  $x;            	$height =  $y;            	}
            $dst_im = imagecreatetruecolor($width, $height);
            imagecopyresampled($dst_im,$src_im,0,0,0,0,$width,$height,$x,$y);
            imagejpeg($dst_im,dirname(__FILE__)."/".$dir.$file,90);
            clearstatcache();
            $new_total_size += filesize(dirname(__FILE__)."/".$dir.$file);
            print "новый файл ширина: ".$width." высота: ".$height." размер: ".filesize(dirname(__FILE__)."/".$dir.$file)." байт<br /><br />\n";
            echo "<img src=\"".$dir.$file."\" width=\"".$width."\" height=\"".$height."\" alt=\"\" border=\"0\"></li><br /><br />\n";
            imagedestroy($src_im);
            imagedestroy($dst_im);
            }
            }
            }
            }
        }
        closedir($dh);
    }
echo "</ol>\n";
echo "<br /><br />Общий размер: ".$total_size." байт\n";
echo "<br /><br />Новый общий размер: ".$new_total_size." байт\n";
}
}
else
{
// Открыть заведомо существующий каталог и начать считывать его содержимое
if (is_dir($dir)) {
echo "<ol>\n";
if(!@mkdir(dirname(__FILE__)."/".$dir."copy/") && !@is_dir(dirname(__FILE__)."/".$dir."copy/"))
exit("Невозможно создать каталог ".dirname(__FILE__)."/".$dir."copy/");
@chmod(dirname(__FILE__)."/".$dir."copy/", 0777);
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
        	$file = trim($file);
            if(0 !== strpos($file,".") && false === strpos($file,"..") && !is_dir(dirname(__FILE__)."/".$dir.$file))
            {
            @chmod(dirname(__FILE__)."/".$dir.$file, 0666);
            if(!is_writeable(dirname(__FILE__)."/".$dir.$file))
            echo ("<li>Невозвожно записать в файл ".dirname(__FILE__)."/".$dir.$file."</li>\n");
            else
            {
            $file_info = getimagesize(dirname(__FILE__)."/".$dir.$file);
            $x = $file_info[0];
            $y = $file_info[1];
            if($file_info[2] == IMAGETYPE_JPEG && (max($x,$y)>400 || filesize(dirname(__FILE__)."/".$dir.$file)>100*1024))
            {            $total_size += filesize(dirname(__FILE__)."/".$dir.$file);
            print "<li>Файл: ".dirname(__FILE__)."/".$dir.$file." ширина: ".$x." высота: ".$y." размер: ".filesize(dirname(__FILE__)."/".$dir.$file)." байт</li>\n";

            }
            }
            }
        }
        closedir($dh);
    }
echo "</ol>\n";
echo "<br /><br />Общий размер: ".$total_size." байт\n";
echo "<br /><br /><a href=\"?go=true\">Желаете продожить?</a>\n";
}
}

?>
