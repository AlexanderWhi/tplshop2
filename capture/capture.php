<?php

include_once("../config.php");

$ip = $_SERVER['REMOTE_ADDR'];
//$ip = '146.185.223.58';
$in_black = false;

function getInBlackRemote($ip) {
    $str = file_get_contents('https://cleantalk.org/blacklists?record=' . $ip);
    if (preg_match('/h1 class="text-danger/', $str)) {
        return true;
    }
    return false;
}

if (!preg_match('/^(127.0.|192.168.)/', $ip)) {
    $blacklist = @file_get_contents('blacklist.txt');
    $whitelist = @file_get_contents('whitelist.txt');
    if (@preg_match('/' . $ip . '/', $blacklist)) {
        $in_black = true;
    } elseif (@preg_match('/' . $ip . '/', $whitelist)) {
        $in_black = false;
    } elseif (getInBlackRemote($ip)) {
        $in_black = true;
        file_put_contents('blacklist.txt', $ip . "\n", FILE_APPEND);
    } else {
        if(fileatime('whitelist.txt')+3600*24*3<time()){
            unlink('whitelist.txt');
        }
        file_put_contents('whitelist.txt', $ip . "\n", FILE_APPEND);
    }
}




include_once("../core/function.php");
header("Content-type: image/jpg");
$capture_sess_name = IMG_SECURITY;
if (isset($_GET['t'])) {
    $capture_sess_name .= $_GET['t'];
}
//$t = substr(microtime(), 2, 4);
$t = rand(10, 99);
$t .= chr(rand(ord('a'), ord('z')));
$t .= rand(10, 99);
$t .= chr(rand(ord('a'), ord('z')));
//$t.= chr(rand(ord('a'),ord('z')));
//$t.= chr(rand(ord('a'),ord('z')));
//$t.= chr(rand(ord('a'),ord('z')));
Cookie::set($capture_sess_name, md5($t . SALT));
$n1 = $t[0];
$n2 = $t[1];
$n3 = $t[2];
$n4 = $t[3];
$n5 = $t[4];
$n6 = $t[5];
if ($in_black) {
    $n1 = 's';
    $n2 = 'p';
    $n3 = 'm';
}
$im = imagecreatefromjpeg("capture.jpg");



$white = imagecolorallocate($im, 255, 255, 255);
$font = "ariblk.ttf";
//$font = "ProximaNova-Light.ttf";
$font = "ProximaNova-Regular.ttf";

//touch ( $font ,time() );
$size = 22;
$margin = 17;
$margin_top = 4;
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
        if ($y < $b_y) {
            $color = imagecolorat($im, $x, $y);
            $color = $white - $color;
            imagesetpixel($im, $x, $y, $color);
        }
    }
}
for ($x = 0; $x < $b_x; $x++) {
    for ($y = 0; $y < $b_y; $y++) {
        if (rand(0, 100) < 65 && imagecolorat($im, $x, $y) == $white) {
            $color = imagecolorresolve($im, 0, 0, 0);
            imagesetpixel($im, $x, $y, $color);
        }
    }
}
ImageJpeg($im);
ImageDestroy($im);
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
    file_put_contents('clog_' . date('Y-m-d') . '.log', date('H:i:s') . " - ($in_black) " . $t . ' ' . http_build_query($_SERVER) . "\n", FILE_APPEND);
}
