<?
include_once("../config.php");
include_once("../core/function.php");

file_put_contents(dirname(__FILE__).'/import.log',date('Y-m-d H:i:s')."\r\n",FILE_APPEND);



		$filename=date('Ymd_His').".xml";
		file_put_contents($filename,file_get_contents("php://input"));
		
		
			set_time_limit(1000);

			include_once("../core/lib/SQL.class.php");
			include_once '../modules/catsrv/catsrv.properties.php';
			include_once("../modules/catsrv/LibCatsrv.class.php");
			
			chdir(ROOT);
			$ST=new SQL(DB_HOST,DB_LOGIN,DB_PASSWORD,DB_BASE);
			
			$r=$ST->select("SELECT UPPER(name) AS name,value FROM sc_config");
			while ($r->next()) {
				if($r->get('value')){
					$CONFIG[$r->get('name')]=$r->get('value');
				}
			}

			$res=LibCatsrv::import('import/'.$filename,4);
			file_put_contents(dirname(__FILE__).'/import.log',print_r($res,true),FILE_APPEND);
		
		
		echo print_r($res,true);

?>