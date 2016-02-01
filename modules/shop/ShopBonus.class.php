<?
class ShopBonus{
	static function getBonusPercent($userid){
		return ShopBonus::getPercentBySmm(ShopBonus::getBonusOrderSumm($userid));
	}
	
	static function getBonusOrderSumm($userid){
		global $ST;
		$smm=0;
		if($userid){
			$q="SELECT SUM(price) AS s FROM sc_shop_order WHERE 
				order_status=3 
				AND userid=$userid
				AND stop_time BETWEEN '".date('Y-m',strtotime('-1 month'))."-01' AND '".date('Y-m')."-01'
				";
				
			$rs=$ST->select($q);
			if($rs->next()){
				$smm = $rs->getInt('s');
			}
		}
		
		return $smm;
	}
	
	
	static function getPercentBySmm($smm){
		$data=array(
			0=>3,
			5000=>7,
			10000=>10,
		);
		$out=3;
		foreach ($data as $s=>$p) {
			if($s<$smm){
				$out=$p;
			}
		}
		return $out;
	}
	
	static function getBonusStatus($userid){
		$smm=ShopBonus::getBonusOrderSumm($userid);
		
		$data=array(
			0=>'green',
			5000=>'yellow',
			10000=>'red',
		);
		$out=$data[0];
		foreach ($data as $s=>$p) {
			if($s<$smm){
				$out=$p;
			}
		}
		return $out;
	}
}
?>