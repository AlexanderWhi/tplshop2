<?

class LibComment{
	
	
	
	private static $instance;
	/**
	 * @return LibComment
	 */
	static function getInstance(){
		if(empty(self::$instance)){
			self::$instance= new self;
		}
		return self::$instance;
	}
	
		
	function getGoodsRait($id,$category){
		$rait=array();
		$q="SELECT * FROM sc_comment_rait_ref ref 
				LEFT JOIN (SELECT AVG(rating) AS val,raitid FROM sc_comment c, sc_comment_rait r WHERE c.itemid={$id} AND type IN('','goods') GROUP BY raitid) AS rait ON ref.id=rait.raitid
				WHERE catid IN(0,{$category}) AND pos>0 ORDER BY pos";
			$rs=DB::select($q);
			while ($rs->next()) {
				$rait[]=array(
					'name'=>$rs->get('name'),
					'from'=>$rs->get('rfrom'),
					'to'=>$rs->get('rto'),
					'val'=>$rs->get('val'),
					'raitid'=>$rs->get('id'),
				);
			}
			return $rait;
	}
	function getGoodsComment($id,$all=false){
		$comment=array();
			
			
			$cond=" itemid={$id} AND TRIM(comment)<>''";
			if(!$all){
				$cond.=" AND status=1";	
			}
			
			$q="SELECT * FROM sc_comment WHERE $cond ORDER BY id LIMIT 20";
			$rs=DB::select($q);
			while ($rs->next()) {
				$comment[$rs->getInt('id')]=$rs->getRow();
			}
			if($comment){
				$rs=DB::select("SELECT * FROM sc_comment_rait r,sc_comment_rait_ref ref WHERE raitid=id AND commentid IN(".implode(',',array_keys($comment)).")");
				while ($rs->next()) {
					$comment[$rs->getInt('commentid')]['rait'][]=$rs->getRow();
				}
			}
			return $comment;
	}
	
	function getGoodsCommentCount($id){
		$comment_count=0;
		$q="SELECT COUNT(*) AS c FROM sc_comment WHERE itemid={$id} AND TRIM(comment)<>'' AND status=1";
			$rs=DB::select($q);
			if ($rs->next()) {
				$comment_count=$rs->getInt('c');
			}
			return $comment_count;
	}
	function getGoodsCommentData($id){
		$field['comment_count']=&$comment_count;
		$field['comment_rait']=&$comment_rait;
		$q="SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r  FROM sc_comment,sc_comment_rait r WHERE commentid=id AND itemid={$id} AND TRIM(comment)<>'' AND status=1 AND type IN('','goods')";
		$rs=DB::select($q);
		if ($rs->next()) {
			$comment_count=$rs->getInt('c');
			$comment_rait=round($rs->getFloat('r'));
		}
		return $field;
	}
	function canGoodsComment($id){
		$ip=$_SERVER['REMOTE_ADDR'];
			if($ip=='127.0.0.1'){
				$ip=time();
			}
			$can_comment=true;
			$q="SELECT * FROM sc_comment WHERE itemid={$id} AND ip='{$ip}' AND DATE(time)='".date('Y-m-d')."'";
			$rs=DB::select($q);
			if ($rs->next()) {
				$can_comment=false;
			}
			return $can_comment;
	}
	
}
?>