<?php
$src = isset($_REQUEST['src']) ? $_REQUEST['src'] : "";
//$sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : "";
$text = isset($_REQUEST['t']) ? $_REQUEST['t'] : "www.molny.net";
$width = isset($_REQUEST['w']) ? $_REQUEST['w'] : 0;
$height = isset($_REQUEST['h']) ? $_REQUEST['h'] : 0;
$font = isset($_REQUEST['f']) ? $_REQUEST['f'] : "verdana";
$size = isset($_REQUEST['s']) ? $_REQUEST['s'] : 10;
$color = isset($_REQUEST['c']) ? $_REQUEST['c'] : 'FF854A';
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : jpg;

$doc_root = dirname(__FILE__);

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

if($src != "" && ($width || $height)) {
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


 if(preg_match('/\.jpg$/is',$src) || preg_match('/\.jpeg$/is',$src) )
 $src_im = imagecreatefromjpeg($doc_root.'/'.$src);
 elseif(preg_match('/\.gif$/is',$src))
 $src_im = imagecreatefromgif($doc_root.'/'.$src);
 elseif(preg_match('/\.png$/is',$src))
 $src_im = imagecreatefrompng($doc_root.'/'.$src);
 else
 exit();

 $src_x = imagesx($src_im);
 $src_y = imagesy($src_im);

 //echo "src_x = $src_x<br />\n";
 //echo "src_y = $src_y<br />\n";

 if($height==0) $height =  intval($width*$src_y/$src_x);
 if($width==0) $width =  intval($height*$src_x/$src_y);

 //echo "width = $width<br />\n";
 //echo "height = $height<br />\n";
 $dst_im = imagecreatetruecolor($width, $height);
 //imagealphablending($dst_im, false);
 //imagesavealpha($dst_im,true);

 $rgb = rgbtostruct($color);
 $textcolor = imagecolorallocate($dst_im, $rgb['red'], $rgb['green'], $rgb['blue']);

 //imagecopy($dst_im,$src_im,0,0,0,0,$src_x,$src_y);
 imagecopyresampled($dst_im,$src_im,0,0,0,0,$width,$height,$src_x,$src_y);
/*
 if($sign!="")
 imagecopymergegray($dst_im,$sign_im,($width-$sign_x)/2,($height-$sign_y)-5,0,0,$sign_x,$sign_y,100);
*/

 $font = $doc_root.'/'.$font.".ttf";
 $box = imagettfbbox($size, 0, $font, $text);
 //print_r($box);
 //$x=($width-$box[4])/2;
 $x=($width-$box[4])/2;
 $y=($height-abs($box[5]))-$height*0.005;
 imagettftext($dst_im, $size, 0, $x, $y, $textcolor, $font, $text);   //$text

  switch ($type) {

    case 'gif':
   imagegif($dst_im);
      break;
    case 'png':
   imagepng($dst_im);
      break;
    default:
   imagejpeg($dst_im);
      break;
  }

 imagedestroy($dst_im);

}
?>