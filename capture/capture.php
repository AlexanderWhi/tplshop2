<?php 
include_once("../config.php");
include_once("../core/function.php");
header ("Content-type: image/jpg");
$capture_sess_name=IMG_SECURITY;
if(isset($_GET['t'])){
	$capture_sess_name.=$_GET['t'];
}
Cookie::set($capture_sess_name,md5($t=substr(microtime(), 2, 4)));
$n1 = $t[0];
$n2 = $t[1];
$n3 = $t[2];
$n4 = $t[3];
$im = imagecreatefromjpeg("capture.jpg");
$white = imagecolorallocate ($im, 255, 255, 255);
$font="ariblk.ttf";
imagettftext ($im, 15, 15, 6, 20, 0, $font, $n1);
imagettftext ($im, 15,-15, 21, 20, 0, $font, $n2);
imagettftext ($im, 16, 15, 36, 20, 0, $font, $n3);
imagettftext ($im, 15, -15, 51, 20, 0, $font, $n4);
//imagettftext ($im, 16, 15, 70, 20, 0, $font, $n5);
//imagettftext ($im, 15, -15, 85, 20, $white, $font, $n6);
ImageJpeg ($im);
ImageDestroy ($im);
?>