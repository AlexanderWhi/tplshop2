<?php
class SQLResult{
	private $result=null;
	private $counter=0;
	private $row;
	private $rows=0;
	
	function SQLResult($result,$rows=0){
		$this->result=$result;
		$this->rows=$rows;
	}	
	/**
	 * @return integer
	 */
	function getNum(){
		return $this->counter-1;
	}
	/**
	 * @return integer
	 */
	function getCount(){
		return $this->rows;// mysql_num_rows($this->result);
	}
	/**
	 * @return boolean
	 */
	function hasNext(){
		return $this->counter<$this->getCount();	
	}
	/**
	 * @return boolean
	 */
	function next(){
		if($this->row=pg_fetch_assoc($this->result)){
			$this->counter++;
			return true;
		}
		return false;
	}
	/**
	 * @param string $key
	 * @return mixed
	 */
	function get($key){
		return isset($this->row[$key])?$this->row[$key]:null;
	}
	/**
	 * @param string $key
	 * @return int
	 */
	function getDate($key,$format=null){
		if($format!==null){
			return date($format,$this->row[$key]);
		}
		
		$d=date('d',$this->row[$key]);
//		$m=$this->getMonthDesc(date('m',$this->row[$key]));
		$m=date('m',$this->row[$key]);
		$y=date('Y',$this->row[$key]);
		return $d.'.'.$m.'.'.$y;
//		return $d.' '.$m.' '.$y;
	}
	function getFDate($key){
		if(is_int($this->row[$key])){
			return date('d.m.y',$this->row[$key]);
		}else{
			return preg_replace('|^(\d{4})-(\d+)-(\d+).*|','\3.\2.\1',$this->row[$key]);
		}
	}
	
//	function getMonthDesc($i){
//		$month=array("€нвар€","феврал€","марта","апрел€","ма€","июн€","июл€","августа","сент€бр€","окт€бр€","но€бр€","декабр€");
//		return $month[$i-1];
//	}
	
	/**
	 * @param string $key
	 * @return int
	 */
	function getCountDay($key){
		$nme_day=' дней';
		
		$last_num=substr($this->row[$key],-1);
		
		if($last_num==1 && substr($this->row[$key],-2,1)!=1){
			$nme_day=' день';}
		elseif(($last_num==2 || $last_num==3 || $last_num==4) && substr($this->row[$key],-2,1)!=1){
			$last_num=substr($this->row[$key],-1);
			$nme_day=' дн€';
		}

		return $this->row[$key].$nme_day;
	}
	
	/**
	 * @param string $key
	 * @return string
	 */
	function getString($key){
		return isset($this->row[$key])?$this->row[$key]:'';
	}
	/** 
	 * @return array
	 */
	function getRow(){
		return $this->row;
	}
	/**
	 * @param string $key
	 * @return int
	 */
	function getInt($key){
		return isset($this->row[$key])?intval($this->row[$key]):0;
	}
	/**
	 * @param string $key
	 * @return float
	 */
	function getFloat($key){
		return isset($this->row[$key])?(float)floatval($this->row[$key]):null;
	}
	function reset(){
		mysql_data_seek($this->result,0);
	}
	function toArray(){
		$res=array();
		while($this->next())$res[]=$this->getRow();
		return $res;
	}
}

class SQL{
	private $connect=null;
//	const MYSQL_KEY_EXISTS_ERROR_NUM=1062;
	function __construct($sqlhost=null,$login=null,$password=null,$base=null,$scheme=null){
		if($sqlhost && $login && $password!==null && $base){
			$this->connect($sqlhost,$login,$password,$base,$scheme);
		}
	}
	private $m_queryCount=0;
	private $m_queryErrorNum=null;
	private $m_queryError=null;
	private $m_insertId=0;
	public $m_showError=true;
	/**
	 * @return integer
	 */
	function getCount(){
		return $this->m_queryCount;
	}
	/**
	 * @param string $str
	 * @return string
	 */
	static function slashes ($str)
	{
		if(get_magic_quotes_gpc()){
			$str = stripslashes ( $str );
		}
		$str = pg_escape_string( $str );
		return $str;
	}
	//var $m_queryCountAll=null;
	function connect($sqlhost,$login,$password,$base=null,$scheme=null){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);
//		if(!mysql_connect($sqlhost,$login,$password)) {
//			echo "Cannot connect MySQL DB!<br>";
//			echo mysql_error();
//			exit;
//		}
		$port='5432';
		if(preg_match('/(.*):(\d+)/',$sqlhost,$res)){
			$sqlhost=$res[1];
			$port=$res[2];
		}

		$r="host=".$sqlhost." port=" . $port . " dbname=".$base." user=".$login." password=".$password." ";
			
		if(!$this->connect = pg_connect($r)) {
			echo "Cannot connect PgSQL DB!<br>";
			echo pg_errormessage();;
			exit;
		}
		
		
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{connect PgSQL DB}"."\n";
		$query_time+=$time;
		
		$t1=microtime(true);
		if($scheme){
			pg_query($this->connect,"set search_path = '$scheme'");
			
		}
		pg_query($this->connect,"SET NAMES 'WIN-1251'");
//		if($base){
//			pg_query("SET NAMES 'cp1251'");
//		}
		

		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{SET NAMES 'cp1251'}"."\n";
		$query_time+=$time;
		
	}
//	static function selectDB($dbname){
//		mysql_select_db($dbname);
//	}
	
	static function report($message){
		$message=str_replace('\\','\\\\',$message);
		$message=str_replace("\n",'<br>',$message);
		$message=str_replace("\r",'',$message);
		$message=str_replace("\"",'\"',$message);
		?><script type="text/javascript">
		var w=window.open("","Error",'width=800,height=600,status=yes,resizable=yes,top=200,left=200');
		w.document.write("<?=$message?>");
		</script><?
		exit;	
	}
	
	/**
	 * @param string $query
	 * @return SQLResult
	 */
	function select($query){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);

		$query=preg_replace('/LIMIT\s+(\d+),\s*(\d+)/i','LIMIT \2 OFFSET \1',$query);//PATCH MySQL to PG
			
		$result=pg_query($this->connect,$query);
//		$error=mysql_error();
		if(!$result && $this->m_showError===true){
			$exeption =new Exception();
//			$message=$exeption->getTraceAsString().'\n\n'.$error.'\n\n'.$query;
			$message=$exeption->getTraceAsString().'\n\n'.$query;
			SQL::report($message);
		}
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
		return new SQLResult($result,pg_num_rows($result));
	}
	
	
	/**
	 * @param string $query
	 * @return SQLResult
	 */
	function execute($query){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);
		$query=preg_replace('/LIMIT\s+(\d+),\s*(\d+)/i','LIMIT \2 OFFSET \1',$query);//PATCH MySQL to PG
		
		$result=pg_query($this->connect,$query);
		
//		$error=pg_errormessage();
//		if(strlen($error)>0&&$this->m_showError===true){
//			$exeption =new Exception($error);
//			$message=$exeption->getTraceAsString().'\n\n'.$error.'\n\n'.$query;
//			SQL::report($message);
//		}
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
		return new SQLResult($result,pg_num_rows($result));
	}
	function exec($query){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);

		$result=pg_query($this->connect,$query);
//		$error=mysql_error();
//		if(strlen($error)>0&&$this->m_showError===true){
//			$exeption =new Exception($error);
//			$message=$exeption->getTraceAsString().'\n\n'.$error.'\n\n'.$query;
//			SQL::report($message);
//		}
		
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
		return $result;
	}
	
	function executeInsert($query){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);
		
			$result=pg_query($this->connect,$query);
			
//			pg_errormessage();
//			$error=mysql_error();
//			$this->m_queryError=$error;
//			$this->m_queryErrorNum=mysql_errno();
			
			if($result===false&&$this->m_showError===true){
				$exeption =new Exception();
				$message=$exeption->getTraceAsString().'\n\n'.$query;
				SQL::report($message);	
			}
		
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
		
		
//		return mysql_insert_id();
	}
	function executeDelete($query){
		global $query_report,$query_time,$query_count;
		$t1=microtime(true);
			$result=pg_query($this->connect,$query);
//			$error=mysql_error();
//			$this->m_queryError=$error;
//			$this->m_queryErrorNum=mysql_errno();
			
//			if(strlen($error)>0&&$this->m_showError===true){
//				$exeption =new Exception($error);
//				$message=$exeption->getTraceAsString().'\n\n'.$error.'\n\n'.$query;
//				SQL::report($message);	
//			}		
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
//		mysql_query("COMMIT");
		return pg_affected_rows($result);
	}
	
	function insert($table_name,$vars,$returning='id'){
		global $query_report,$query_time,$query_count;
		if(!is_array($vars)){
    		return false;
    	}
        foreach($vars as $key => $val){
        	if(is_int($key)){
        		$tmp=split('=',$val);
        		$names[] = ''.trim($tmp[0]).'';
        		$vals[]=trim($tmp[1]);
        	}else{
        		$names[] = ''.$key.'';
        		if(is_int($val) || is_float($val)){
        			 $vals[]  = $val;
        		}else{
        			 $vals[]  = '\''.SQL::slashes($val).'\'';
        		}
               
        	}   
        }
        $names_string = implode(', ', $names);
        $vals_string  = implode(', ', $vals);
		
        $query = 'INSERT INTO '.$table_name.' ('.$names_string.') VALUES ('.$vals_string.')';
        if($returning){
        	$query.=" RETURNING $returning";
        }
        
        $t1=microtime(true);
		
		$result=pg_query($this->connect,$query);
		
		$time=microtime(true)-$t1;$query_count++;
		$query_report.=$time.":{".$query."}"."\n";
		$query_time+=$time;
		if($result===false&&$this->m_showError===true){
			$exeption =new Exception();
			$message=$exeption->getTraceAsString().'\n\n'.$query;
			SQL::report($message);	
		}
		
        if($ret=pg_fetch_assoc($result)){
				return $ret[$returning];
		}
      	return null;
	}
	
	function insertArr($table_name,$arr){
		if(!is_array($arr) || !$arr){
    		return false;
    	}
    	$rowNum=0;
    	foreach ($arr as $vars){
    		if($rowNum==0){
    			$rowNum++;
    			$names_string = implode(', ', array_keys($vars));
    		}
    		foreach($vars as &$val){
	            $val  = '\''.SQL::slashes($val).'\'';
	        }
	        $vals_string[]  = implode(', ', $vars);
    	}   	
    			
        $InsertSQL = 'INSERT INTO '.$table_name.' ('.$names_string.') VALUES ';
        $rowNum=0;
        foreach ($vals_string as $row){
        	if($rowNum++)$InsertSQL.=', ';
        	$InsertSQL.="($row)";
        }
        return $this->executeInsert($InsertSQL);
	}
	
	function delete($table_name,$condition=''){
		$SQL='DELETE FROM '.$table_name.' '.(($condition)?' WHERE '.$condition:'');
        return $this->executeDelete($SQL);
	}
	
	function update($table_name,$values,$condition=''){
		if(!is_array($values)){
    		return false;
    	}   	
    	foreach($values as $key => $val){
    		if(is_int($key)){
    			$names_ar[]=$val;
    		}else {
                $names_ar[]=$key.' = \''.SQL::slashes($val).'\'';
			}
        }
		$names_string = implode(', ', $names_ar);
        $UpSQL='UPDATE '.$table_name.' SET '.$names_string.(($condition)?' WHERE '.$condition:'');
        return $this->executeUpdate($UpSQL);
	}
	
	function getOne($query){
		$rs=$this->select($query);
		if($rs->next()){
			foreach ($rs->getRow() AS $v) return $v;
		}
		return '';
	}
	
	function executeUpdate($query){
		return $this->executeDelete($query);
	}
	/**
	 * return last auto incremented id value
	 *
	 * @return int
	 */
	function getInsertId(){
		return $this->m_insertId;
	}
	/**
	 * Display query error
	 *
	 */
	function showQueryError(){
		if(strlen($this->m_queryError)>0){
			echo "<font color='red' size='3'>MySQL ERROR: ".$this->m_queryError."</font><br /><b>".$this->m_queryString."</b>";
		}	
	}
	function getQueryErrorNum(){
		return $this->m_queryErrorNum;
	}
	
	function up($table_name,$id,$condition='',$id_name='id',$position_name='position'){
		$sql="SELECT ".$position_name." as current FROM ".$table_name." WHERE ".$id_name." = ".$id;
		$rs=$this->select($sql);
		if($rs->next()){
			$current_pos=$rs->getInt("current");
		}

		$sql="SELECT max(".$position_name.") AS max_sort FROM ".$table_name;		
		$sql.=" WHERE ".$position_name." < ".$current_pos." ".($condition?" AND ".$condition:'') ;
		
		$rs=$this->select($sql);
	
		if($rs->next()){
		   $max=$rs->getInt("max_sort");
		   if($max!==null){
		   $queryStr="UPDATE ".$table_name." SET ".$position_name." = ".$current_pos." + ".$max." - ".$position_name." 
				WHERE ".$position_name." IN ( ".$current_pos.", ".$max." )";
		   		$this->executeUpdate($queryStr);	
		   }  
		}
	}
	function down($table_name,$id,$condition='',$id_name='id',$position_name='position'){	
		$sql="SELECT ".$position_name." as current FROM ".$table_name." WHERE ".$id_name." = ".$id;
		$rs=$this->select($sql);
		if($rs->next()){
			$current_pos=$rs->getInt("current");
		}

		$sql="SELECT min(".$position_name.") AS min_sort FROM ".$table_name;
		$sql.=" WHERE ".$position_name." > ".$current_pos." " .($condition?" AND ".$condition:'') ;
		
		$rs=$this->select($sql);
	
		if($rs->next()){
		   $min=$rs->getInt("min_sort");
		   if($min!==null){
				$queryStr="UPDATE ".$table_name." SET ".$position_name." = ".$current_pos." + ".$min." - ".$position_name." 
				WHERE ".$position_name." IN ( ".$current_pos.", ".$min." )";
		   		$this->executeUpdate($queryStr);		
		   }
		}
	}
}
?>