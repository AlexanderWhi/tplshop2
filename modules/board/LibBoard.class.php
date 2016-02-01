<?
class LibBoard{
	/**
	 * @return LibBoard
	 */
	static function getInstance(){
		return new self;
	}
	
	function getBoard($opt=array()){
		
		$condition="b.status=1 AND date_to>='".date('Y-m-d')."'";
		
		if(!empty($opt['category'])){
			$condition.=" AND b.category={$opt['category']}";
		}
		
		$order=" ORDER BY time DESC";
		$queryStr="SELECT b.*,u.name,c.name AS c_name,c.id AS c_id FROM sc_shop_board b
			LEFT JOIN sc_users u ON u.u_id=b.userid 
			LEFT JOIN sc_shop_catalog c ON c.id=b.category 
		WHERE $condition $order LIMIT 20";
		
		$q="SELECT * FROM sc_shop_board b
		
		LEFT JOIN sc_users u ON b.userid=u.u_id
		
		
		WHERE b.status=1 AND ";
		
		return DB::select($queryStr)->toArray();
	}
	
		
}
?>