<?php

include_once("../config.php");
include_once("../core/function.php");
header("Content-type: image/jpg");
$capture_sess_name = IMG_SECURITY;
if (isset($_GET['t'])) {
    $capture_sess_name .= $_GET['t'];
}
$t = substr(microtime(), 2, 6);
Cookie::set($capture_sess_name, md5($t . SALT));
$n1 = $t[0];
$n2 = $t[1];
$n3 = $t[2];
$n4 = $t[3];
$n5 = $t[4];
$n6 = $t[5];
$im = imagecreatefromjpeg("capture.jpg");
$white = imagecolorallocate($im, 255, 255, 255);
$font = "ariblk.ttf";
$size = 17;
$margin = 14;
imagettftext($im, $size, 15, 1, 20, 0, $font, $n1);
imagettftext($im, $size, -15, 1 + $margin - 3, 20, 0, $font, $n2);
imagettftext($im, $size, 15, 1 + $margin * 2, 20, 0, $font, $n3);
imagettftext($im, $size, -15, 1 + $margin * 3 - 3, 20, 0, $font, $n4);
imagettftext($im, $size, 15, 1 + $margin * 4, 20, 0, $font, $n5);
imagettftext($im, $size, -15, 1 + $margin * 5 - 3, 20, 0, $font, $n6);
$b_x = imagesx($im);
$b_y = imagesy($im);
for ($x = 0; $x < $b_x; $x++) {
    for ($y = 0; $y < $b_y; $y++) {
        if (rand(0, 100)<18) {
            $color = imagecolorresolve($im, 0, 0, 0);
            imagesetpixel($im, $x, $y, $color);
        }
    }
}

ImageJpeg($im);
ImageDestroy($im);
