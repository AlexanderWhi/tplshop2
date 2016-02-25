<?

class ShopBonus {

    private static $salt='wuehiwuhi';
    static function genPromo($refid){
        if((int)$refid){
            return $refid.'-'.substr(md5($refid.self::$salt), 0,5);
        }
        return '';
    }
    static function getPromoRefId($promo){
        $refid=preg_replace('/-.+/', '', $promo);
        if($promo==self::genPromo($refid)){
            return $refid;
        }
        return 0;
    }
    
    static function getBonusPercent($userid) {
        $rs = DB::select("SELECT * FROM sc_users WHERE u_id=$userid");
        if ($rs->next()) {
            return 0.5;
        }
        return 0;
    }

    static function recountBasket($refid, &$basket) {
        if ($bonus = self::getBonusPercent($refid)) {
            foreach ($basket as &$item) {
                $item['discount'] = $item['awards'] * $bonus;
                $item['refawards'] = $item['awards'] * (1 - $bonus);
                $item['sum']-=$item['discount'] * $item['count'];
            }
        }
    }

    static function addRefAwards($refid, $basket) {
        if((int)$refid){
            $refawards = 0;
            if ($bonus = self::getBonusPercent($refid)) {
                foreach ($basket as &$item) {
                    if (!empty($item['refawards'])) {
                        $refawards+=$item['refawards'];
                    }
                }
            }
            if ($refawards) {
                DB::update('sc_users', array("balance=balance+$refawards"), "u_id=$refid");
            }
        }
            
            
    }

    static function getBonusOrderSumm($userid) {
        global $ST;
        $smm = 0;
        if ($userid) {
            $q = "SELECT SUM(price) AS s FROM sc_shop_order WHERE 
				order_status=3 
				AND userid=$userid
				AND stop_time BETWEEN '" . date('Y-m', strtotime('-1 month')) . "-01' AND '" . date('Y-m') . "-01'
				";

            $rs = $ST->select($q);
            if ($rs->next()) {
                $smm = $rs->getInt('s');
            }
        }

        return $smm;
    }

    static function getPercentBySmm($smm) {
        $data = array(
            0 => 3,
            5000 => 7,
            10000 => 10,
        );
        $out = 3;
        foreach ($data as $s => $p) {
            if ($s < $smm) {
                $out = $p;
            }
        }
        return $out;
    }

    static function getBonusStatus($userid) {
        $smm = ShopBonus::getBonusOrderSumm($userid);

        $data = array(
            0 => 'green',
            5000 => 'yellow',
            10000 => 'red',
        );
        $out = $data[0];
        foreach ($data as $s => $p) {
            if ($s < $smm) {
                $out = $p;
            }
        }
        return $out;
    }

}

?>