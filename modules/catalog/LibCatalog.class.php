<?

class LibCatalog {

    static function _recount_cat($item, $k = 'cm') {
        $res = 0;
        if (isset($item[$k])) {
            $res = $item[$k];
        }
        if (isset($item['ch'])) {
            foreach ($item['ch'] as $i) {
                $res+=self::_recount_cat($i, $k);
            }
        }
        if (isset($item['children'])) {
            foreach ($item['children'] as $i) {
                $res+=self::_recount_cat($i, $k);
            }
        }
        return $res;
    }

    private static $instance;

    /**
     * @return LibCatalog
     */
    static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return LibCatalog
     */
    static function getPrototype() {
        return new self;
    }

    function getCatalogTree() {
        global $MENU_CATALOG;
        if (!empty($MENU_CATALOG)) {
            return $MENU_CATALOG;
        }
        $q = "SELECT * FROM sc_shop_catalog cat
			LEFT JOIN (SELECT COUNT(id) AS c, category FROM sc_shop_item WHERE in_stock>-1 GROUP BY category) AS cnt ON cnt.category=cat.id
			LEFT JOIN (SELECT COUNT(itemid) AS cm, catid FROM sc_shop_item i, sc_shop_item2cat WHERE in_stock>-1 AND itemid=i.id GROUP BY catid) AS cntm ON cntm.catid=cat.id
			
			LEFT JOIN (SELECT count(DISTINCT t.itemid) as mc,t.catid FROM 
			(
					SELECT itemid,catid FROM sc_shop_item2cat, sc_shop_item WHERE itemid=id AND in_stock>-1
					UNION 
					SELECT id AS itemid,category AS catid FROM sc_shop_item WHERE in_stock>-1
			) AS t
				
			GROUP BY t.catid) AS mcnt ON mcnt.catid=cat.id
			
			
			WHERE sort>-1 AND ((sort>0 AND parentid=0) OR parentid>0)"; //AND state=1 //AND sort<20 

        $q.=" ORDER BY sort";
        $rs = DB::select($q); //WHERE parentid={$menu_catalog[1]}
        $menu = array();
        $item = array();
        $items = array();

        while ($rs->next()) {
            $items[] = $rs->get('id');
            $item[$rs->get('id')]['name'] = $rs->get('name');
            $item[$rs->get('id')]['id'] = $rs->get('id');
            $item[$rs->get('id')]['img'] = $rs->get('img');
            $item[$rs->get('id')]['c'] = $rs->getInt('c');
            $item[$rs->get('id')]['cm'] = $rs->getInt('cm');
            $item[$rs->get('id')]['mc'] = $rs->getInt('mc');

            if ($rs->get('parentid') == 0) {
                $menu[$rs->getInt('id')] = &$item[$rs->get('id')];
            } else {
                $item[$rs->get('parentid')]['children'][$rs->get('id')] = &$item[$rs->get('id')];
            }
        }

        $k = 'mc';

        if (isset($item)) {
            foreach ($item as &$i) {
                $i[$k . "r"] = LibCatalog::_recount_cat($i, $k);
            }
        }

//		if($items){
//			$rs=$ST->select("SELECT COUNT(itemid) AS c,catid FROM sc_shop_item2cat WHERE catid IN(".implode(',',$items).") GROUP BY catid");
//			while ($rs->next()) {
//				$item[$rs->get('catid')]['cm']=$rs->get('c');
//			}
//		}
        return $MENU_CATALOG = $menu;
    }

    function getManufacturer() {
        return DB::select("SELECT * FROM sc_manufacturer WHERE img<>''")->toArray();
    }

    function getMainGoods($type='sort') {
        $q = "SELECT * FROM sc_shop_item i
			LEFT JOIN(SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r,itemid  FROM sc_comment,sc_comment_rait r 
				WHERE commentid=id  AND TRIM(comment)<>'' AND status=1 AND type IN('','goods') GROUP BY itemid) AS rait ON rait.itemid=i.id
			WHERE i.$type>0 ORDER BY i.$type";
        return DB::select($q)->toArray();
    }
    

    function getRelation($id) {
        $field = array();
        $rs = DB::select("SELECT i.*,r.type,rait.* 
				FROM sc_shop_relation r,sc_shop_item i 
				LEFT JOIN(SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r,itemid  FROM sc_comment,sc_comment_rait r 
					WHERE commentid=id  AND TRIM(comment)<>'' AND status=1 AND type IN('','goods') GROUP BY itemid) AS rait ON rait.itemid=i.id
			
				WHERE   i.price>=0 AND i.in_stock>-1 AND i.id=r.ch AND r.par='" . $id . "'");
        while ($rs->next()) {
            $row = $rs->getRow();
            $field['rel' . $rs->get('type')][] = $row;
        }
        return $field;
    }

    static function getLastView($limit = 20) {
        if (Cfg::get('SHOP_LAST_VIEW_LIMIT')) {
            $limit = (int) Cfg::get('SHOP_LAST_VIEW_LIMIT');
        }
        $res = array();
        if (!empty($_SESSION['__LAST_VIEW'])) {
            $arr = array_slice(array_reverse($_SESSION['__LAST_VIEW']), 0, $limit);

            $rs = DB::select("SELECT * FROM sc_shop_item i
				LEFT JOIN(SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r,itemid  FROM sc_comment,sc_comment_rait r 
					WHERE commentid=id  AND TRIM(comment)<>'' AND status=1 AND type IN('','goods') GROUP BY itemid) AS rait ON rait.itemid=i.id
			
			WHERE  i.price>0 AND i.in_stock>0 AND i.id IN('" . implode("','", $arr) . "') LIMIT $limit");
            while ($rs->next()) {
                $res[$rs->get('id')] = $rs->getRow();
            }
            /* отсортируем */
            $tmp_res = array();
            foreach ($arr as $id) {
                if (!empty($res[$id])) {
                    $tmp_res[] = $res[$id];
                }
            }
            $res = $tmp_res;
        }
        return $res;
    }

    /**
     * Содержимое товаров в куки
     *
     * @return array
     */
    function getBasketData() {
        $basket = array();
        if (!empty($_COOKIE['basket'])) {
            $basket = json_decode(stripslashes($_COOKIE['basket']), true);
        }
        return $basket;
    }

    function getPrice($price) {
        $margin = 0; //Наценка//14,09,2013 /admin/catsrv/delivery/
        //Условия наценки для стрекозы
//		if(($cityProp=$this->getCityProperties()) && isset($cityProp['margin'])){
//			$margin=(int)$cityProp['margin'];
//		}
        return $price + $price / 100 * $margin;
    }

    function getBasket() {
        global $ST, $BASKET;

        if (!empty($BASKET)) {
            return $BASKET;
        }
        $output = array();

        if ($basket = $this->getBasketData()) {

            $ids = array();
            foreach ($basket as $key => $val) {
                if (preg_match('/id(\d+)/', $key, $res)) {
//					$ids[]=preg_replace('/id(\d+)(_\d+)?/','\1',$key);//Выбираем идентификатор товара
                    $ids[] = $res[1]; //Выбираем идентификатор товара
                }
            }
            if ($ids) {
                $data = array();
//				$rs=$ST->select("SELECT i.*,p.*,v.company FROM sc_shop_item i,sc_shop_proposal p,sc_users v
//					WHERE i.id=p.itemid AND vendor=v.u_id AND p.id IN(".implode(",",$ids).")");
//				$rs=$ST->select("SELECT i.*,v.company FROM sc_shop_item i,sc_users v
//					WHERE  vendor=v.u_id AND i.id IN(".implode(",",$ids).")");
                $rs = $ST->select("SELECT i.* FROM sc_shop_item i
					WHERE  i.id IN(" . implode(",", $ids) . ")");

                while ($rs->next()) {
                    $item['id'] = $rs->get('id');
                    $item['name'] = $rs->get('name');
                    $item['product'] = $rs->get('product');
                    $item['category'] = $rs->get('category');
//					$item['vendor']=$rs->get('vendor');
                    $item['img'] = $rs->get('img');
                    $item['description'] = $rs->get('description');
                    $item['price'] = $rs->getFloat('price'); //цена с наценкой//14,09,2013
                    $item['unit'] = $rs->getInt('unit');
//					$item['company']=$rs->get('company');
                    $item['weight_flg'] = $rs->getInt('weight_flg');
                    $data['id' . $rs->get('id')] = $item;
                }

                foreach ($basket as $k => $v) {

                    if (!preg_match('/(id\d+)(_proposal(\d+))?/', $k, $res)) {
                        continue;
                    }
                    $id = $res[1];
                    if (empty($data[$id]))
                        continue;
//					$unit_sale=!empty($res[3])?$res[3]:1;

                    $item = array();
                    foreach ($data[$id] as $key => $val) {
                        $item[$key] = $val;
                    }
                    $item['count'] = $v;
                    $item['key'] = $k;

//					if(!empty($res[3]) && $nmn=(int)$res[3]){
//						$rs=$ST->select("SELECT * FROM sc_shop_item_nmn WHERE id={$nmn}");
//						if($rs->next()){
//							$item['price']=$this->getPrice($rs->getFloat('price'));
//							$item['nmn']=$rs->get('description');
//							$item['nmnid']=$nmn;
//						}
//					}
                    $item['sum'] = $item['price'] * $item['count'];
                    $output[] = $item;
                }
            }
        }
        return $BASKET = $output;
    }

    private static $fav = array();

    static function getFav() {
        if (!empty(self::$fav)) {
            return self::$fav;
        }
        if (!empty($_COOKIE['favorite']) && ($favorite = json_decode(stripslashes($_COOKIE['favorite']), true)) && is_array($favorite)) {
            return self::$fav = $favorite;
        }
        return array();
    }

    static function addFav($id) {

        $favorite = array();
        if (isset($_COOKIE['favorite'])) {
            $favorite = json_decode(stripslashes($_COOKIE['favorite']), true);
        }

        if (!in_array($id, $favorite)) {
            $favorite[] = $id;
        }
        $count = count($favorite);
        setcookie('favorite', $_COOKIE['favorite'] = json_encode($favorite), COOKIE_EXP, '/');
        return $count;
    }

    static function favRemove($id) {
        $favorite = array();
        if (isset($_COOKIE['favorite'])) {
            $favorite = json_decode(stripslashes($_COOKIE['favorite']), true);
        }
        $favorite = array_diff($favorite, array($id));
        setcookie('favorite', $_COOKIE['favorite'] = json_encode($favorite), COOKIE_EXP, '/');
    }

}

?>