<?
include("../config.php");
include("../core/function.php");

file_put_contents('import.log',date('Y-m-d H:i:s')."\r\n",FILE_APPEND);
file_put_contents('import.log',"GET-".print_r($_GET,true),FILE_APPEND);
//file_put_contents('import.log',"POST-".print_r($_POST,true),FILE_APPEND);

if($_GET['type']=='catalog'){
	if($_GET['mode']=='checkauth'){
		echo "success\n";
		echo "Cookie\n";
		echo "Cookie\n";
	}
	if($_GET['mode']=='init'){
//		echo "zip=no\n";
		echo "zip=yes\n";
		echo "file_limit=".(1024*1021*16)."\n";
	}
	if($_GET['mode']=='file'){
		
		$d=dirname($_GET['filename']);
		if(!file_exists($d)){
			mkdir($d,0777,true);
		}
		$filename=basename($_GET['filename']);
		file_put_contents($filename,file_get_contents("php://input"));
		if(preg_match('/zip$/',$filename)){
			$zip = new ZipArchive;
			if ($zip->open($filename) === TRUE) {
				$zip->extractTo(".");
				$zip->close();
				unlink($filename);
			}
		}
		if( preg_match('/ost/',$filename)){
			

			set_time_limit(1000);

			include("../core/lib/SQL.class.php");
			include_once '../modules/catsrv/catsrv.properties.php';
			include("../modules/catsrv/LibCatsrv.class.php");
			
			chdir(ROOT);
			$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);
			
			$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config WHERE name LIKE 'SHOP_SRV%'");
			while ($r->next()) {
				if($r->get('value')){
					$CONFIG[$r->get('name')]=$r->get('value');
				}
			}

			$res=LibCatsrv::import('import',11);
			file_put_contents('import.log',"GET-".print_r($res,true),FILE_APPEND);
		}
		
		echo "success\n";
	}	
	if($_GET['mode']=='import'){
		echo "success\n";
	}	
	
}


?>