<?
ini_set ("default_charset","windows-1251");
date_default_timezone_set("Asia/Yekaterinburg");
$crc_list=array();
$crc_dir='crc';
if(file_exists($crc_dir)){
	$d=opendir($crc_dir);
	while ($f=readdir($d)) {
		if(preg_match('/\.crc$/',$f)){
			$crc_list[]=$f;
		}
	}
}
?>
<table>
<tr>
<td><a href="?check">Проверка на вирусы</a></td>
</tr>

<tr>
<td><a href="?crc">Создать слепок</a></td>
</tr>
<?
sort($crc_list);

foreach ($crc_list as $crc) {?>
	<tr>
	<td>
	<a href="?crc=<?=$crc?>"><?=$crc?></a>
	</td>
	</tr>
<?}?>
</table>


<?
global $FILES;
function checkFile($f){
	global $FILES;
	$c=file_get_contents($f);
	$check_arr=array(
		"include_once('/data/www/",
		'pReG_rePlaCe',
		'<David Blaine>',
		'create_function',
		'SAPE_globals','MLClient','MLAClient','Linkpad_client','lTransmiter','ladsTransmiter',
	);
	foreach ($check_arr as $code){
		if(strpos($c,$code)!==false){
			$FILES[$f][]=$code;
		}
	}
}

function readDirR($path){
	$d=opendir($path);
	while ($f=readdir($d)) {
		if(!in_array($f,array('.','..'))){
			$file="{$path}/{$f}";
			if(is_dir($file)){
				readDirR($file);
			}else{
				checkFile($file);
			}
		}
	}
}
//////////////////////
function putCrc($f,$crc_file){
	if(!file_exists(dirname($crc_file))){
		mkdir(dirname($crc_file));
	}
	file_put_contents($crc_file,$f."|".md5_file($f)."\n",FILE_APPEND);
}

function mkCrc($path,$crc_file){
	$d=opendir($path);
	while ($f=readdir($d)) {
		if(!in_array($f,array('.','..'))){
			$file="{$path}/{$f}";
			if(is_dir($file)){
				mkCrc($file,$crc_file);
			}elseif(preg_match('/\.php$/',$file)){
				putCrc($file,$crc_file);
			}
		}
	}
}

function checkCrcFile($f,$crc_list){
	global $FILES;
	if(isset($crc_list[$f])){
		if($crc_list[$f]!=($cur_md5=md5_file($f))){
			$FILES[$f]=array($crc_list[$f],$cur_md5);
		}
	}else{
		$FILES[$f]=array('NEW!');
	}
	
}

function checkCrc($path,$crc_list){
	$d=opendir($path);
	while ($f=readdir($d)) {
		if(!in_array($f,array('.','..'))){
			$file="{$path}/{$f}";
			if(is_dir($file)){
				checkCrc($file,$crc_list);
			}elseif(preg_match('/\.php$/',$file)){
				checkCrcFile($file,$crc_list);
			}
		}
	}
}

if(isset($_GET['check'])){
	readDirR(dirname(__FILE__));

}

if(isset($_GET['crc'])){
	if(empty($_GET['crc'])){
		$crc_file=$crc_dir."/".date('y_m_d_H_i_s').".crc";
		mkCrc(dirname(__FILE__),$crc_file);
		header('Location: av.php');exit;
	}else{
		$crc_file=$crc_dir."/".$_GET['crc'];
		$f=fopen($crc_file,'r');
		$crc_list=array();
		while ($line=fgets($f)) {
			$line=explode('|',trim($line));
			$crc_list[$line[0]]=$line[1];
		}
		checkCrc(dirname(__FILE__),$crc_list);
	}
}

if(!empty($FILES)){
	?>
<table border="1">
<?foreach ($FILES as $file=>$codes) {?>
	<tr>
	<td><?=$file?></td>
	<?foreach ($codes as $c) {?>
		<td><?=htmlspecialchars($c)?></td>
	<?}?>
	</tr>
<?}?>
</table><?
}
?>
