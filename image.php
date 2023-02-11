<?php
//error_reporting(E_ALL ^E_NOTICE);

error_reporting(0);
/*echo('<br />memory_limit = '.ini_get('memory_limit'));
ini_set('memory_limit','64M');
echo('<br />memory_limit = '.ini_get('memory_limit'));*/

$src = isset($_REQUEST['src']) ? $_REQUEST['src'] : "";
$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : "../images2/water_20.png";
$width = isset($_REQUEST['w']) ? $_REQUEST['w'] : 0;
$height = isset($_REQUEST['h']) ? $_REQUEST['h'] : 0;
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'jpg';

$doc_root = dirname(__FILE__)."/foto/";       /*"/var/www/vhosts/drel.su"*/

$tmp_file = dirname(__FILE__).'/cache/'.urlencode($src.'_'.($sign?1:"").'_'.$width.'_'.$height.'.'.$type);  /*"/var/www/vhosts/drel.su"*/

$font = "verdana";
$size = 10;
$color = 'BABDC1';
$bgcolor = "FFFFFF";
$text = 'www.drel.su';
$text2 = '(495) 926-08-58';

	//echo "<br>doc_root = ".$doc_root;


	//echo "<br>filename = ".$filename;

	$ftime = intval(@filemtime($tmp_file));

	//echo "<br>ftime = ".$ftime;

	clearstatcache();

	$interval = (time()-$ftime)/3600;

	$delay = 0;

	//echo "<br>interval = ".$interval;
    //echo "<br>delay = ".$this->config->delay;

function rgbtostruct($color) {
 $color = hexdec($color);
 $rgb = array();
    // red
 $rgb['blue'] = $color & 255;
    // green
 $s = ($s << 8)-1;
 $rgb['green'] = ($color>>8) & 255;
    // blue
 $s = ($s << 8)-1;
 $rgb['red'] = ($color>>16) & 255;

 return $rgb;
}

	/*header('Cache-Control: max-age='.(3600*24*7));
	header('Expires: '.gmdate("D, d M Y H:i:s",$ftime+3600*24*7).' GMT');
	header('Last-Modified: '.gmdate("D, d M Y H:i:s",$ftime).' GMT');
	header('ETag: "'.md5_file($tmp_file)."\"");*/

  switch ($type) {

    case 'gif':
    header("Content-type: image/gif");
      break;
    case 'png':
    header("Content-type: image/png");
      break;
    default:
    header("Content-type: image/jpg");
      break;
  }

if($src != "" && ($width || $height) && /*!is_file($tmp_file)true*/$interval>$delay)
{
 //echo('<br />memory_get_peak_usage = '.memory_get_peak_usage(true)."<br />");
 /*if(preg_match('/\.jpg$/is',$src) || preg_match('/\.jpeg$/is',$src) )
 $src_im = imagecreatefromjpeg($doc_root.'/'.$src);
 elseif(preg_match('/\.gif$/is',$src))
 $src_im = imagecreatefromgif($doc_root.'/'.$src);
 elseif(preg_match('/\.png$/is',$src))
 $src_im = imagecreatefrompng($doc_root.'/'.$src);
 else
 exit();
 $src_x = imagesx($src_im);
 $src_y = imagesy($src_im);
 */
 $src_info = getimagesize($doc_root.'/'.$src);

 if($src_info[2] == IMAGETYPE_JPEG)
 $src_im = imagecreatefromjpeg($doc_root.'/'.$src);
 elseif($src_info[2] == IMAGETYPE_GIF)
 $src_im = imagecreatefromgif($doc_root.'/'.$src);
 elseif($src_info[2] == IMAGETYPE_PNG)
 $src_im = imagecreatefrompng($doc_root.'/'.$src);
 else
 exit();

 $src_x = $src_info[0];
 $src_y = $src_info[1];
  #echo('<br />memory_get_peak_usage = '.memory_get_peak_usage(true)."<br />");
 //echo "src_x = $src_x<br />\n";
 //echo "src_y = $src_y<br />\n";

 if($sign!="")
 {
 $sign_info = getimagesize($doc_root.'/'.$sign);

 $sign_x = $sign_info[0];
 $sign_y = $sign_info[1];
 if($sign_info[2] == IMAGETYPE_JPEG)
 $sign_im = imagecreatefromjpeg($doc_root.'/'.$sign);
 elseif($sign_info[2] == IMAGETYPE_GIF)
 {
 $sign_im = imagecreatefromgif($doc_root.'/'.$sign);
 imagealphablending($sign_im, false);
 }
 elseif($sign_info[2] == IMAGETYPE_PNG)
 {
 $sign_im = imagecreatetruecolor($src_x, $src_y);
 imagealphablending($sign_im, false);
 imagesavealpha($sign_im,true);
 $bgcolors = rgbtostruct($bgcolor);
 $bgcolorint = imagecolorallocatealpha($sign_im,$bgcolors['red'],$bgcolors['green'],$bgcolors['blue'],127);
 $sign_im = imagecreatefrompng($doc_root.'/'.$sign);
 } }



 if(preg_match('/([0-9]+)\%$/is',$width,$match))
 $width = ($match[1]/100)*$src_x;

 if(preg_match('/([0-9]+)\%$/is',$height,$match))
 $height = ($match[1]/100)*$src_y;

 if($height==0) $height =  intval($width*$src_y/$src_x);
 if($width==0) $width =  intval($height*$src_x/$src_y);

 //echo "width = $width<br />\n";
 //echo "height = $height<br />\n";
 $dst_im = imagecreatetruecolor($width, $height);
 imagesavealpha($dst_im,true);
 $bgcolors = rgbtostruct($bgcolor);
 $bgcolorint = imagecolorallocate($dst_im,$bgcolors['red'],$bgcolors['green'],$bgcolors['blue']);
 imagefilledrectangle($dst_im,0,0,$width,$height,$bgcolorint);/**/
 //imagecopy($dst_im,$src_im,0,0,0,0,$src_x,$src_y);
 imagecopyresampled($dst_im,$src_im,0,0,0,0,$width,$height,$src_x,$src_y);

 if($width > 200)
 {
 	imagecopy($dst_im,$sign_im,($width-$sign_x)/2,($height-$sign_y)/2,0,0,$sign_x,$sign_y);
 	/*$font = $doc_root.'/'.$font.".ttf";
 	$box = imagettfbbox($size, 0, $font, $text);
 	$box2 = imagettfbbox($size, 0, $font, $text2);
	$x=/10;
 	$y=($height-abs($box[5]))-$height*0.005-15;
	$x2=10;
 	$y2=($height-abs($box2[5]))-$height*0.005;
 	$textcolor = "0x".$color;
	imagettftext($dst_im, $size, 0, $x, $y, $textcolor, $font, $text);
	imagettftext($dst_im, $size, 0, $x2, $y2, $textcolor, $font, $text2);*/
 }
 /*if($sign!="")
 imagecopymerge($dst_im,$sign_im,($width-$sign_x)/2+5,($height-$sign_y)/2,0,0,$sign_x,$sign_y,100);*/


  switch ($type) {

    case 'gif':
   imagegif($dst_im,$tmp_file);
   //imagegif($dst_im);
      break;
    case 'png':
   imagepng($dst_im,$tmp_file);
   //imagepng($dst_im);
      break;
    default:
   imagejpeg($dst_im,$tmp_file,100);
   //imagejpeg($dst_im);
      break;
  }
 imagedestroy($src_im);
 imagedestroy($dst_im);
 #echo('<br />memory_get_peak_usage = '.memory_get_peak_usage(true)."<br />");
}
if(is_file($tmp_file))
{
 //echo('<br />memory_get_peak_usage = '.memory_get_peak_usage(true)."<br />");
ob_start();

$content = file_get_contents($tmp_file);

echo $content;

ob_end_flush();
 /*if(preg_match('/\.jpg$/is',$tmp_file) || preg_match('/\.jpeg$/is',$tmp_file) )
 $src = imagecreatefromjpeg($tmp_file);
 elseif(preg_match('/\.gif$/is',$tmp_file))
 $src = imagecreatefromgif($tmp_file);
 elseif(preg_match('/\.png$/is',$tmp_file))
 $src = imagecreatefrompng($tmp_file);
 else
 exit();

  switch ($type) {

    case 'gif':
   imagegif($src);
      break;
    case 'png':
   imagepng($src);
      break;
    default:
   imagejpeg($src);
      break;
  }

 imagedestroy($src_im);*/}
?>