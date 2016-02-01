<?php
$request=$_SERVER['REQUEST_URI'];

function clearCache($dirname){
	$cacheArr=array();
	$newCacheArr=array();
	if(file_exists('.'.$dirname.'/cache.txt')){
		$cacheArr=file('.'.$dirname.'/cache.txt');
	}
	foreach ($cacheArr as $fileName){
		$file=trim($fileName);
		if(file_exists('.'.$dirname.'/'.$file)){
			unlink('.'.$dirname.'/'.$file);
			echo '.'.$dirname.'/'.$file."| ";
		}
	}
	if(file_exists('.'.$dirname.'/cache.txt')){
		unlink('.'.$dirname.'/cache.txt');
	}
	$dir=opendir('.'.$dirname);
	while (true==($filename=readdir($dir))) {
		if($filename!='.' && $filename!='..' && is_dir('.'.$dirname.'/'.$filename)){
			clearCache($dirname.'/'.$filename);
		}
	}
}

if(isset($_GET['cleardir'])){
	$dirname=$_GET['cleardir'];
	clearCache($dirname);
}

$request_arr=@split('/',$request);
$destImageName=array_pop ($request_arr);
$dirname=join('/',$request_arr);



if(preg_match('!([^/]+)_([a-z0-9]+)\.(jpg|jpeg|png|gif)$!i',$destImageName,$res)){
	$imgType=$res[3];
	$imgArgs=$res[2];
	$imgName=$res[1];
}else{
	$arr=split('\.',$destImageName);
	if(count($arr)>2){
		$imgType=array_pop($arr);
		$imgArgs=array_pop($arr);
		$imgName=array_pop($arr);
	}else{
		exit();
	}
}
$imgName=iconv('utf-8','cp1251',rawurldecode ($imgName));

parse_str($imgArgs,$args);


/*–егул€рные выражени€*/
preg_match('!sq([0-9]+)!i',$imgArgs,$res);
if(isset($res[1])){
	$args['sq']=$res[1];
}
preg_match('!w([0-9]+)!i',$imgArgs,$res);
if(isset($res[1])){
	$args['m_w']=$res[1];
}
preg_match('!h([0-9]+)!i',$imgArgs,$res);
if(isset($res[1])){
	$args['m_h']=$res[1];
}
preg_match('!r!i',$imgArgs,$res);
if(isset($res[0])){
	$args['r']=true;
}
$args['f']='';
preg_match('!(gif|png|jpg)!i',$imgArgs,$res);
if(isset($res[1])){
	$args['f']=$imgType;
	$imgType=$res[1];
}
$stamp=false;
if(preg_match('!st!i',$imgArgs,$res)){
	$stamp=true;
}
$thumbs=false;

if(preg_match('|.thumbs$|',$dirname)){
	$thumbs=true;
	$dirname=preg_replace('!/.thumbs!','',$dirname);
}

$image_path='.'.$dirname.'/'.$imgName.".".$imgType;
$new_image_path='.'.iconv('utf-8','cp1251',rawurldecode ($request));
if(!file_exists($image_path)){
	$image_path=iconv('cp1251','utf-8',$image_path);
}

if(!file_exists($image_path)){
echo $image_path."<br>";

$dir=dirname($image_path);
$d=opendir($dir);
while ($f=readdir($d)) {
	echo $f."<br>";;
}
closedir($d);
exit ;
}

$image_type=getimagesize($image_path) ;
if($image_type[2]==IMAGETYPE_GIF){
	$im = imagecreatefromgif($image_path);
	imagecolortransparent($im,255*255*255);
}
if($image_type[2]==IMAGETYPE_PNG){
	$im = imagecreatefrompng($image_path);
	if(!$stamp){
		imagealphablending($im,false);
		imagesavealpha($im, true);
	}
		
}
if($image_type[2]==IMAGETYPE_JPEG){
	$im = imagecreatefromjpeg($image_path);
}

$dst_im_w=$srcWidth=imagesx($im); 
$dst_im_h=$srcHeight= imagesy($im); 
    
if(isset($args["w"])){
	$dst_im_w=$args["w"];
}
if(isset($args["h"])){
    $dst_im_h=$args["h"];
}
  
if(isset($args["sq"])){
	if($dst_im_w<$dst_im_h){
		$dst_im_max_w=$args["sq"];
		//—жимаем по ширине если она больше
		if($dst_im_max_w<$dst_im_w){
			$k=$dst_im_max_w/$dst_im_w;
			$dst_im_w=$dst_im_max_w;
			$dst_im_h=round($dst_im_h*$k);
		}
	}else{
		$dst_im_max_h=$args["sq"];
		//—жимаем по высоте если она больше
		if($dst_im_max_h<$dst_im_h){
			$k=$dst_im_max_h/$dst_im_h;
			$dst_im_h=$dst_im_max_h;
			$dst_im_w=round($dst_im_w*$k);
		}
	}
	
		
}

if(isset($args["m_w"])){
	$dst_im_max_w=$args["m_w"];
	//—жимаем по ширине если она больше
	if($dst_im_max_w<$dst_im_w){
		$k=$dst_im_max_w/$dst_im_w;
		$dst_im_w=$dst_im_max_w;
		$dst_im_h=round($dst_im_h*$k);
	}
}

    
if(isset($args["m_h"])){
	$dst_im_max_h=$args["m_h"];
	//—жимаем по высоте если она больше
	if($dst_im_max_h<$dst_im_h){
		$k=$dst_im_max_h/$dst_im_h;
		$dst_im_h=$dst_im_max_h;
		$dst_im_w=round($dst_im_w*$k);
	}
}
    
//сжимаем и обрезаем изображение (делаем квадратным из пр€моугольного)
$dst_im= imagecreatetruecolor($dst_im_w,$dst_im_h);

if(isset($args["small"])){
	$size=100;
	if($args["small"]!=""){
		$size=floatval($args["small"]);
	}
	
	$k = $size / $dst_im_w;
	$dst_im_w = $size;
	$dst_im_h=round($dst_im_h*$k);
		
	$dst_im_w_s = $size;
	$dst_im_h_s = 67;

	//создаЄт новое изображение true color
  	$tmp_im= imagecreatetruecolor($dst_im_w_s,$dst_im_h_s); 
  	
	//копирует и измен€ет размеры части изображени€ с пересэмплированием
	imagecopyresampled($dst_im, $im, 0, 0, 0, 0, $dst_im_w, $dst_im_h, $srcWidth, $srcHeight);
	
	imagecopy($tmp_im, $dst_im, 0, 0, 41, 0, $dst_im_w_s, $dst_im_h_s); 
	$dst_im=$tmp_im;
}
if($image_type[2]==IMAGETYPE_PNG){
	if(!$stamp){
		imagealphablending($dst_im,false);
		imagesavealpha($dst_im, true);
	}
		
}

if($image_type[2]==IMAGETYPE_GIF){
	imagecolortransparent($im,0);
}
imagecopyresampled($dst_im, $im, 0, 0, 0, 0, $dst_im_w, $dst_im_h, $srcWidth, $srcHeight);
$fullPath=substr($new_image_path,1,strrpos ($new_image_path, '/')-1 );


function cutRound($im,$type='png'){
	$r=min(imagesx($im),imagesy($im))/2;
	
	$cx=imagesx($im)/2;
	$cy=imagesy($im)/2;
	
	
	$transparencyIndex = imagecolortransparent($im);
	$transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 0);

    if ($transparencyIndex >= 0) {
         $transparencyColor    = imagecolorsforindex($im, $transparencyIndex);   
    }
	$transparencyIndex    = imagecolorallocate($im, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
    if($type=='png'){
    	imagealphablending($im, false);
		imagesavealpha($im, true);
		$transparencyIndex = imagecolorallocatealpha($im, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue'], 127);
    }
    
	
            
	for($x=0;$x<imagesx($im);$x++){
		for($y=0;$y<imagesy($im);$y++){
			
			if(sqrt(pow(($cx-$x),2)+pow(($cy-$y),2))>$r){
				imagesetpixel($im,$x,$y,$transparencyIndex);
			}
		}
	}
	imagecolortransparent($im, $transparencyIndex);
	
	$dst_im=imagecreatetruecolor($r*2,$r*2);
	imagealphablending($dst_im, false);
	imagesavealpha($dst_im, true);
	
	imagecopy($dst_im,$im,  0, 0, $cx-$r, $cy-$r, $r*2, $r*2);
	imagecolortransparent($dst_im,$transparencyIndex);
//	return $im;

	


	return $dst_im;
}

if(isset($args["r"])){
	$dst_im=cutRound($dst_im,$args['f']);	
}

if($stamp){
	$znak = imagecreatefrompng  ("img/stamp/stamp.png");//177x22
		
	$w=$dst_im_w*0.9;
	$h=round($dst_im_w/imagesx($znak)*imagesy($znak))*0.9;
	
	$dst_znak= imagecreatetruecolor($w,$h);
	
	imagealphablending($dst_znak,false);
	imagesavealpha($dst_znak, true);
	
	imagecopyresampled($dst_znak, $znak, 0, 0, 0, 0, 
		$w,
		$h,		
		imagesx($znak), imagesy($znak));
	
	imagecopy ($dst_im,$dst_znak,

	(imagesx($dst_im)-imagesx($dst_znak))/2,
	(imagesy($dst_im)-imagesy($dst_znak))/2,
	0,
	0,
	imagesx($dst_znak),
	imagesy($dst_znak));
}

if(!file_exists(dirname($new_image_path))){
	mkdir(dirname($new_image_path));
}
if($image_type[2]==IMAGETYPE_GIF ||$args['f']=='gif'){
//imagegif($dst_im);
	imagegif($dst_im,$new_image_path);
	Header("Content-type: image/gif");
}
elseif($image_type[2]==IMAGETYPE_PNG||$args['f']=='png'){
	imagealphablending($dst_im,false);
	imagesavealpha($dst_im, true);
	imagepng($dst_im,$new_image_path);
	Header("Content-type: image/png");
}
elseif($image_type[2]==IMAGETYPE_JPEG){
	imagejpeg($dst_im,$new_image_path,"100");
	Header("Content-type: image/jpg");
}

file_put_contents(dirname($new_image_path).'/cache.txt',$destImageName."\r\n",FILE_APPEND);

ImageDestroy($dst_im);
ImageDestroy($im);
echo file_get_contents($new_image_path);
if(isset($args['c'])){
	unlink($new_image_path);
}
exit;
?>