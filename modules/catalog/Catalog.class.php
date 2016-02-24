<?php
include_once 'core/component/Component.class.php';
include_once 'Basket.class.php';
include_once 'modules/shop/ShopBonus.class.php';

function _recount_cat($item, $k = 'cm') {
    $res = $item[$k];
    if (isset($item['ch'])) {
        foreach ($item['ch'] as $i) {
            $res+=_recount_cat($i, $k);
        }
    }
    return $res;
}

class Catalog extends Component {

    function __construct() {
        $this->setContainer('common_container_lc.tpl.php');
    }

    function displayGoods($id) {

        global $ST, $get;

        $par = 0;
        $data['parentid'] = 0;
        $data['parentname'] = $this->mod_name;
        $data['description'] = '';
        $data['action'] = array();
        $data['id'] = 0;
        $page = new Page($this->getPageSize(), $this->cfg('PAGE_SIZE_SELECT'));
//		$page=new Page(1);		
//		$page=new Page($this->cfg('PAGE_SIZE'));		
        $catIds = array();
        if ($prop = $this->getURIIntVal('prop')) {
            $rs = $ST->select("SELECT * FROM sc_shop_prop WHERE id=$prop");
            if ($rs->next()) {
                $this->explorer[] = array('name' => $rs->get('name'), 'url' => $this->mod_uri . $this->getURIIntVal('catalog') . '/prop/' . $rs->get('id') . '/');
                $this->setTitle($rs->get('name'));
                $this->setHeader($rs->get('name'));
            }
        }
        $condition = "WHERE i.price>0";

        if ($id && is_int($id)) {
            $par = $id;
            if ($man = $this->getURIVal('man')) {
                $this->explorer[] = array('name' => $man, 'url' => $this->mod_uri . $this->getURIIntVal('catalog') . '/man/' . $man . '/');
            }

            $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id=" . $id);
            if ($rs->next()) {
                $data['description'] = $rs->get('description');
                $data['id'] = $rs->get('id');
                if (trim($rs->get('cache_child_catalog_ids'))) {
                    $catIds = explode(',', $rs->get('cache_child_catalog_ids'));
                }

//				$this->actCatalog[]=$id;
//				if(!empty($catIds[0])){
//					
//					$id=$catIds[0];
//					$rs=$ST->select("SELECT * FROM sc_shop_catalog WHERE id=".$id);
//					if($rs->next()){
//						$catIds=array();
//					}
//					$this->actCatalog[]=$id;
//				}

                $this->explorer[] = array('name' => $rs->get('name'), 'url' => $this->mod_uri . $rs->get('id') . '/');
                $this->setTitle($rs->get('name'));
                $this->setHeader($rs->get('name'));

                //Хлебные крошки
                $parent = $data['parentid'] = $rs->get('parentid');
//				$this->actCatalog[]=$parent;
                while ($parent) {
                    $rs1 = $ST->select("SELECT id,name,parentid FROM sc_shop_catalog WHERE id=" . $parent);
                    if ($rs1->next()) {
                        $parent = $rs1->getInt('parentid');
                        $this->actCatalog[] = $parent;
                        $this->explorer[] = array('name' => $rs1->get('name'), 'url' => $this->mod_uri . $rs1->get('id') . '/');
                    } else {
                        break;
                    }
                }
                if (!empty($this->explorer[1])) {
                    $data['parentname'] = $this->explorer[1]['name'];
                }
            }
            $catIds[] = $id;
        }

//		$condition="WHERE of.itemid=i.id AND i.price>=0 AND of.in_stock>0 AND of.region='{$this->getRegion()}'";/*Раздельное наличие*/

        $condition.=" AND i.in_stock>-1"; //Не показывать удалённые
//		$condition.=" AND i.category IN (SELECT id FROM sc_shop_catalog WHERE state>0)";
        if ($this->cfg('SHOP_GOODS_IN_STOCK_ONLY') == 'true') {//Настройка показывать только в наличии
            $condition.=" AND i.in_stock>0";
        }
        if ($this->cfg('SHOP_GOODS_W_IMG_ONLY') == 'true') {//Настройка показывать только с картинками
            $condition.=" AND i.img<>''";
        }
        if ($this->cfg('SHOP_GOODS_CHECKED_ONLY')) {//Настройка показывать только в подтверждённые
            $condition.=" AND i.confirm=1";
        }
        if (preg_match('/(\d*)\D*-(\d*)\D*/', $this->getURIVal('price'), $res)) {
            if ($res[1] > 0) {
                $condition.=" AND i.price>={$res[1]}";
            }
            if ($res[2] > 0) {
                $condition.=" AND i.price<={$res[2]}";
            }
        }

//		if($univ=$this->getURIIntVal('universal')){
//			$condition.=" AND (i.id=$univ OR i.universal=$univ)";
//		}

        if ($this->getURIVal('catalog') == 'fav') {
            if ($arr = $this->getFavoriteData()) {
                $condition.=" AND i.id IN('" . implode("','", $arr) . "')";
            }
        }
        if ($this->getURIVal('catalog') == 'new') {

            $condition.=" AND i.sort3>0";
            $this->setPageTitle('Новинки');
        }
        if ($this->getURIVal('catalog') == 'special') {

            $condition.=" AND i.sort>0";
            $this->setPageTitle('СПЕЦИАЛЬНОЕ ПРЕДЛОЖЕНИЕ');
        }
//		if($this->getURIVal('catalog')=='action'){
//			$condition.=" AND i.sort1>0";
//			$this->setPageTitle('СПЕЦИАЛЬНОЕ ПРЕДЛОЖЕНИЕ');
//		}

        if ($action = $this->getURIIntVal('action')) {
            $rs = $ST->select("SELECT * FROM sc_news WHERE type='action' AND state='public' AND id=$action");
            if ($rs->next()) {
                $data['action'] = $rs->getRow();
                $this->setPageTitle($rs->get('title'), "/catalog/action/$action/");
                $condition.=" AND i.sort1=$action";
            }
//			$this->setPageTitle('СПЕЦИАЛЬНОЕ ПРЕДЛОЖЕНИЕ');
        }

        if ($catIds) {
            $condition.=" 
			
				AND (
				EXISTS(SELECT id FROM sc_shop_catalog AS sc WHERE sort>-1 AND sc.id IN(" . trim(implode(",", $catIds), ',') . ") AND i.category=sc.id  )
				 
				OR EXISTS(SELECT itemid FROM sc_shop_item2cat,sc_shop_catalog sc WHERE sort>-1 AND sc.id=catid AND itemid=i.id AND sc.id IN(" . trim(implode(",", $catIds), ',') . ")  )
			)
			";
        }
        if ($prop = trim($this->getURIVal('prop'))) {

            $p_c = array();
            if ($prop = explode(',', $prop)) {
                foreach ($prop as $p) {
                    $p_c[] = " EXISTS (SELECT item_id FROM sc_shop_prop_val WHERE prop_id=" . intval($p) . " AND i.id=item_id)";
                }
            }
            if ($p_c) {
                $condition.=" AND (" . implode(' AND ', $p_c) . ")";
            }
//			$condition.=" AND EXISTS (SELECT item_id FROM sc_shop_prop_val WHERE prop_id={$prop} AND i.id=item_id)";
        }

        if (preg_match('/(\d+),(\d+)/', $this->getURIVal('rel'), $res)) {
            $condition.=" AND EXISTS (SELECT ch FROM sc_shop_relation WHERE par={$res[1]} AND type={$res[2]} AND i.id=ch )";
        }

        $prop_condition = '';
        $prop2_condition = '';
        if ($prop = $get->getArray('p')) {
            $c3 = array();
            $c4 = array();
            foreach ($prop as $pid => $val) {

                $pr = array_diff(array_keys($prop), array($pid));

                if (is_array($val)) {
                    foreach ($val as &$v) {
                        $v = SQL::slashes($v);
                    }
                    $c3[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id=$pid AND value IN('" . implode("','", $val) . "'))";
                    $c4[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND ((prop_id=$pid AND value IN('" . implode("','", $val) . "')) OR prop_id NOT IN('" . implode("','", $pr) . "') ))";
                } else {
                    $val = trim($val);
                    if (preg_match('/^([\d\.]+)-([\d\.]+)$/', $val, $res)) {
                        $c3[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id=$pid AND value>={$res[1]} AND value<={$res[2]})";
                        $c4[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND ((prop_id=$pid AND value>={$res[1]} AND value<={$res[2]}) OR prop_id NOT IN('" . implode("','", $pr) . "') ))";
                    } elseif ($val) {
                        $c3[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id=$pid AND value='" . SQL::slashes($val) . "')";
                        $c4[] = " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND ((prop_id=$pid AND value='" . SQL::slashes($val) . "') OR prop_id NOT IN('" . implode("','", $pr) . "') ))";
                    }
                }
            }
            $prop_condition.= implode('', $c3);
            $prop2_condition.= implode('', $c4);
        }

//		if($prop=$get->getArray('p')){
//			$c3=array();
//			foreach ($prop as $pid=>$val) {
//				$c3[(int)$pid]=$pid."=".SQL::slashes($val);
//				$c3[(int)$pid]=" prop_id::varchar||'='||value = '$pid=".SQL::slashes($val)."'";
//			}
////			$prop_condition.= " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id IN(".implode(',',array_keys($c3)).") AND prop_id::varchar||'='||value IN('".implode("','",$c3)."'))";;
//			$prop_condition.= " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id 
//				AND prop_id IN(".implode(',',array_keys($c3)).") 
//				AND ".implode(" AND ",$c3)."
//			)";
//		}
//		if($prop=$get->getArray('type')){//Тип устройства
//			$c3=array();
//			foreach ($prop as $pid) {
//				$c3[]=(int)$pid;
//			}	
//			$prop_condition.= " AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id IN (".implode(',',$c3)."))";
//		}
//		if($prop=$get->getArray('max')){
//			$c3=array();
//			foreach ($prop as $pid=>$val) {
//				$c3[]=" AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id=$pid AND value::numeric<=".floatval($val).")";
//			}
//			$prop_condition.= implode('',$c3);
//		}
//		if($prop=$get->getArray('min')){
//			$c3=array();
//			foreach ($prop as $pid=>$val) {
//				$c3[]=" AND EXISTS(SELECT item_id FROM sc_shop_prop_val WHERE i.id=item_id AND prop_id=$pid AND value::numeric>=".floatval($val).")";
//			}
//			$prop_condition.= implode('',$c3);
//		}


        $man_condition = '';
        if (($man = $this->getURIVal('man')) && $man = explode(',', $man)) {
            $man_condition.=" AND i.manufacturer IN('" . trim(implode("','", $man), ',') . "')";
        }
        if (($man = $this->getURIVal('manid')) && $man = explode(',', $man)) {
            $man_condition.=" AND i.manufacturer_id IN('" . trim(implode("','", $man), ',') . "')";
        }

        if ($man = $get->getArray('man')) {
            foreach ($man as &$m) {
                $m = SQL::slashes($m);
            }
            $man_condition.=" AND i.manufacturer IN('" . trim(implode("','", $man), ',') . "')";
        }
        if ($man = $get->getArray('manid')) {
            foreach ($man as &$m) {
                $m = SQL::slashes($m);
            }
            $man_condition.=" AND i.manufacturer_id IN('" . trim(implode("','", $man), ',') . "')";
        }
        $p_condition = '';
        if ($get->getInt('minp')) {
            $p_condition.=" AND i.price >={$get->getInt('minp')}";
        }
        if ($get->getInt('maxp')) {
            $p_condition.=" AND i.price <={$get->getInt('maxp')}";
        }

        $sh_arr = array(
            'hit' => 'sort',
            'new' => 'sort1',
            'act' => 'sort2',
        );
        $s_condition = '';
        if ($show = $this->getURIVal('show')) {
            $s_condition = " AND {$sh_arr[$show]} >0";
        }

        $q = trim(strtolower(SQL::slashes(urldecode($get->get('search')))));

        if ($q) {
            if ($words = Rumor::getAllForms($q)) {
                $or = array();
                foreach ($words as $w) {
                    $or[] = "i.name LIKE '%" . $w . "%'";
                }
                $condition.=" AND (" . implode(' OR ', $or) . ")";
            } else {
                $condition.=" AND (i.name LIKE '%" . SQL::slashes($q) . "%')";
            }
            //$condition.=" AND lower(i.name) LIKE '%" . $q . "%'";
        }

//		$query="SELECT count(*) AS c FROM sc_shop_item i,sc_shop_offer of $condition $man_condition $p_condition $prop_condition $s_condition" ;
        $query = "SELECT count(*) AS c FROM sc_shop_item i $condition $man_condition $p_condition $prop_condition $s_condition";

        $rs = $ST->select($query);
        if ($rs->next()) {
            $page->all = $rs->getInt('c');
        }

        $order = '';
        $sort = 'DESC';
        if ($this->getURIVal('sort') == 'desc') {
            $sort = 'DESC';
        } elseif ($this->getURIVal('sort') == 'asc') {
            $sort = 'ASC';
        }
//		$order.='sort DESC,name';
//		$order.='category,views DESC,name';
        $order.='sort1 DESC,name';

        if ($this->getURIVal('price')) {
            $order = 'price DESC';
        }

        if ($this->getURIVal('catalog') == 'new') {
            $order = 'sort DESC';
        }
        $ord = $this->getURIVal('ord');
        if (in_array($ord, array('price', 'manufacturer', 'name', 'views', 'sort1', 'sort2', 'sort3'))) {
            $order = $ord . ' ' . $sort;
        }
        if ($ord == 'hit') {
            $order = 'sort DESC';
        }
        if ($ord == 'updated') {
            $order = 'sort_print DESC';
        }

        if ($ord == 'default') {
            $order = 'sort DESC, name ' . $sort;
            $order = 'name ' . $sort;
            if ($show) {
                $order = "{$sh_arr[$show]} DESC, name " . $sort;
            }
        }
//		$queryStr="SELECT i.*,coalesce(cn.c,0) AS cnt FROM sc_shop_item i
//		LEFT JOIN(SELECT COUNT(itemid) AS c,itemid FROM sc_shop_order_item oi,sc_shop_order o WHERE o.id=oi.orderid AND o.order_status=8 GROUP BY itemid) AS cn ON cn.itemid=i.id,
//		sc_shop_offer of	
//		$condition $man_condition $p_condition $prop_condition ORDER BY $order LIMIT ".$page->getBegin().",".$page->per ;
//		$queryStr="SELECT i.* FROM sc_shop_item i,
//		sc_shop_offer of	
//		$condition $man_condition $p_condition $prop_condition $s_condition ORDER BY $order LIMIT ".$page->getBegin().",".$page->per ;
        $queryStr = "SELECT i.*,r,c FROM sc_shop_item i
		
		LEFT JOIN(SELECT COUNT(DISTINCT commentid) AS c,AVG(rating) AS r,itemid  FROM sc_comment,sc_comment_rait r 
					WHERE commentid=id  AND TRIM(comment)<>'' AND status=1 AND type IN('','goods') GROUP BY itemid) AS rait ON rait.itemid=i.id
			
		
		$condition $man_condition $p_condition $prop_condition $s_condition ORDER BY $order LIMIT " . $page->getBegin() . "," . $page->per;



        $data['manufacturer_list'] = array();
        $data['type_list'] = array();
        $data['prop_list'] = array();
        $data['show_list'] = array(0, 0, 0);
        $data['min_max_price'] = array(0, 0, 0, 0);


//			$q_vendor="SELECT DISTINCT manufacturer FROM sc_shop_item i,sc_shop_offer of $condition AND manufacturer<>''";
//			$rs=$ST->select($q_vendor);
//			while ($rs->next()) {
//				$data['manufacturer_list'][]=$rs->get('manufacturer');
//			}
//			if(count($data['manufacturer_list'])<2){
//				$data['manufacturer_list']=array();
//			}
        $q_vendor = "SELECT m.name,m.id,COUNT(m.id) AS c FROM sc_shop_item i,sc_manufacturer m $condition AND i.manufacturer_id=m.id GROUP BY m.name,m.id";
        $rs = $ST->select($q_vendor);
        while ($rs->next()) {
            $data['manufacturer_list'][] = $rs->getRow();
        }




//			$this->data=&$data;






        $rs = $ST->select($queryStr);
        $data['catalog'] = array();
        $data['page'] = $page;

        while ($rs->next()) {
            $row = $rs->getRow();

//			if($row['pack_size']>1){
//				$row['price_pack']=$row['price']*$row['pack_size']*$discount;
//			}
//			$row['unit']=@$units[$row['unit']];
//			if($row['sort']>0){
//				$row['hit']=true;
//			}
//			if($row['sort']>0){
//				$row['new']=true;
//			}

            $data['catalog'][$row['id']] = $row;
        }
        if ($data['catalog']) {
            $ids = array_keys($data['catalog']);
            $q = "SELECT * FROM sc_shop_item_nmn WHERE itemid IN (" . implode(',', $ids) . ") AND hidden=0 ORDER BY price";
            $rs = $ST->select($q);
            while ($rs->next()) {
                $data['catalog'][$rs->getInt('itemid')]['nmn'][$rs->getInt('id')] = $rs->getRow();
            }
        }

        $data['children'] = $this->getChildCategory($par);
        $data['children_man'] = array();
        if (count(explode(',', $this->getUriVal('manid'))) == 1) {

            $rs = $ST->select("SELECT c.id FROM sc_manufacturer m,sc_shop_catalog c WHERE m.name=c.name AND m.id={$this->getUriIntVal('manid')}");
            if ($rs->next()) {
                $data['children_man'] = $this->getChildCategory($rs->getInt('id'));
            }
        }


        $data['in_basket'] = array();
        $basket = $this->getBasket();
        foreach ($basket as $item) {
            $data['in_basket'][$item['id']] = $item['count'];
        }

        /* Свойства */
        $q_prop = "SELECT DISTINCT p.id AS pid,p.sort,p.name,p.type,pv.value FROM sc_shop_item i,sc_shop_prop as p,sc_shop_prop_val pv 
			$condition 
			AND i.category=$id
			AND i.id=pv.item_id
			AND pv.prop_id=p.id
			AND p.grp<>0  
			AND p.sort<>0 ORDER BY p.sort DESC, value";

//		$prop2_condition 
//			$q_prop="SELECT DISTINCT p.id AS pid,p.sort,p.name,p.type,pv.value FROM sc_shop_item i,sc_shop_prop as p,sc_shop_prop_val pv 
//			$condition 
//			$prop_condition
//			AND i.category=$id
//			AND i.id=pv.item_id
//			AND pv.prop_id=p.id
//			AND p.grp<>0  
//			AND p.sort<>0 ORDER BY p.sort, value";
//		
//			$rs=$ST->select($q_prop);
//			while ($rs->next()) {
//				$data['prop_list'][$rs->get('pid')]['name']=$rs->get('name');
//				$data['prop_list'][$rs->get('pid')]['sort']=$rs->get('sort');
//				$data['prop_list'][$rs->get('pid')]['type']=$rs->get('type');
//				$data['prop_list'][$rs->get('pid')]['v'][]=$rs->get('value');
//			}

        $q_prop = "SELECT p.id AS pid,p.sort,p.name,p.type,pv.value,pv.item_id FROM sc_shop_item i,sc_shop_prop as p,sc_shop_prop_val pv 
			$condition 
			$prop_condition
			AND i.category=$id
			AND i.id=pv.item_id
			AND pv.prop_id=p.id
			AND p.grp<>0  
			AND p.sort<>0 ORDER BY p.sort,p.name,value";

        $rs = $ST->select($q_prop);
        $pl = array();

//			$pl_item=array();

        while ($rs->next()) {
            $pl[$rs->get('pid')]['name'] = $rs->get('name');
            $pl[$rs->get('pid')]['sort'] = $rs->get('sort');
            $pl[$rs->get('pid')]['type'] = $rs->get('type');
            if (empty($pl[$rs->get('pid')]['v'][$rs->get('value')])) {
//					$pl[$rs->get('pid')]['v'][$rs->get('value')]=array();
                $pl[$rs->get('pid')]['v'][$rs->get('value')] = 0;
            }
//				$pl[$rs->get('pid')]['v'][$rs->get('value')][]=$rs->getInt('item_id');
            $pl[$rs->get('pid')]['v'][$rs->get('value')] ++;

//				$pl_item[$rs->getInt('item_id')][$rs->get('pid')]=$rs->get('value');
        }

        foreach ($pl as $pid => &$p) {
            if ($p['type'] == 4) {
                $list = $this->enum('sh_prop_list_' . $pid);
                $v = array();
                foreach ($list as $val) {
                    if (isset($p['v'][$val])) {
                        $v[$val] = $p['v'][$val];
                    }
                }
                $p['v'] = $v;
            }
        }

        $data['prop_list2'] = $pl;


//			$q_pr="SELECT MAX(i.price) AS maxp, MIN(i.price) AS minp FROM sc_shop_item i,sc_shop_offer of $condition ";
//			$q_pr="SELECT MAX(i.price) AS maxp, MIN(i.price) AS minp FROM sc_shop_item i $condition ";
//			$rs=$ST->select($q_pr);
//			if($rs->next()){
//				$data['min_max_price']=array($rs->get('minp'),$rs->get('maxp'),$rs->get('minp'),$rs->get('maxp'));
//				
//				if($data['min_max_price'][1]==$data['min_max_price'][0]){
//					$data['min_max_price'][0]=0;
//					$data['min_max_price'][2]=0;
//				}
//				
//			}
//			if($get->getInt('minp')){
//				$data['min_max_price'][0]=$get->getInt('minp');
//			}
//			if($get->getInt('maxp')){
//				$data['min_max_price'][1]=$get->getInt('maxp');
//			}

        $this->tplLeftComponent = dirname(__FILE__) . "/catalog_left.tpl.php";
        $this->display($data, dirname(__FILE__) . '/catalog.tpl.php');
    }

    protected $data = array();

    function actFilter() {
        global $post;
        $prop = array();
        foreach ($post->getArray('prop') as $pid => $p) {
            if ($p) {
                $prop[] = "$pid-{$p}";
            }
        }
        $params = array();

        $query = '';
        if ($prop) {
            $query.='&val=' . implode(',', $prop);
        }
        if ($post->get('minp') || $post->get('maxp')) {
            $query.='&pr=' . "{$post->get('minp')},{$post->get('maxp')}";
            $params['pr'] = "{$post->get('minp')},{$post->get('maxp')}";
        }
        header("Location: " . $this->getURI(array(), $query));
        exit;
    }

    function displaySort() {
        $names = array(
//			'manufacturer'=>'По производителю',
            'price' => 'по&nbsp;цене',
//			'name'=>'по&nbsp;наименованию',
            '^sort1' => 'по&nbsp;акции',
//			'^sort2'=>'по&nbsp;хит',
//			'^sort3'=>'по&nbsp;новинки',
//			'views'=>'по&nbsp;популярности',
//			'default'=>'по&nbsp;умолчанию',
        );
        $i = 0;
        foreach ($names as $name => $label) {
            /*             * if($i++){?>, <?} */
            $sort_def = 'asc';
            if (preg_match('/^\^/', $name)) {
                $sort_def = 'desc';
            }
            $name = trim($name, '^');
            if ($this->getURIVal('ord') == $name) {
                if ($this->getURIVal('sort') == 'asc') {
                    ?><a class="act desc ord_<?= $name ?>" href="<?= $this->getURI(array('ord' => $name, 'sort' => 'desc'), true) ?>"><ico><span><?= $label ?></span></ico></a><?
                } else {
                    ?><a class="act asc ord_<?= $name ?>" href="<?= $this->getURI(array('ord' => $name, 'sort' => 'asc'), true) ?>"><ico><span><?= $label ?></span></ico></a><?
                            }
                        } else {
                            ?><a class="ord_<?= $name ?>" href="<?= $this->getURI(array('ord' => $name, 'sort' => $sort_def), true) ?>"><ico><span><?= $label ?></span></ico></a><?
                        }
                    }
                }

                function bsort($name, $label) {
                    global $post;
                    if ($post->get('ord') == $name) {
                        if ($post->get('sort') == 'asc') {
                            ?><a class="bsort act desc" href="javascript:bsort('<?= $name ?>','desc')"><span><?= $label ?></span></a><?
                        } else {
                            ?><a class="bsort act asc" href="javascript:bsort('<?= $name ?>','asc')"><span><?= $label ?></span></a><?
                    }
                } else {
                    ?>
            <a class="bsort " href="javascript:bsort('<?= $name ?>','asc')"><span><?= $label ?></span></a><?
        }
    }

    function actChView() {

        setcookie('catalog_view', $_GET['mode'], COOKIE_EXP, '/');
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function getView() {
//		return 'list';
        if (!empty($_COOKIE['catalog_view']) && in_array($_COOKIE['catalog_view'], array('list', 'table', 'full',))) {
            return $_COOKIE['catalog_view'];
        }
        return 'table';
    }

    function displayCatalog() {
        global $ST;
        $data = array(
            'parentid' => 0,
        );

        //Хлебные крошки
        $parent = 0;
        while ($parent) {
            $rs1 = $ST->select("SELECT id,name,parentid FROM sc_shop_catalog WHERE id=" . $parent);
            if ($rs1->next()) {
                $parent = $rs1->getInt('parentid');
//				$this->actCatalog[]=$parent;
                $this->explorer[] = array('name' => $rs1->get('name'), 'url' => $this->mod_uri . $rs1->get('id') . '/');
                $this->setTitle($rs1->get('name'));
                $this->setHeader($rs1->get('name'));
            } else {
                break;
            }
        }
        $data['manufacturer'] = LibCatalog::getInstance()->getManufacturer();
        $data['hit_list'] = LibCatalog::getInstance()->getMainGoods('sort2');

        $this->tplLeftComponent = dirname(__FILE__) . "/catalog_left.tpl.php";
        $this->display($data, dirname(__FILE__) . '/catalog_all.tpl.php');
    }

    function actDefault() {
        global $get, $ST;
        $id = $this->getURIIntVal('catalog');

        if ($gid = $this->getURIIntVal('goods')) {
            return $this->actGoods($gid);
        }

//		$major=explode(',',$this->cfg('SHOP_CATALOG_MAJOR'));
//		
//		if(($id && in_array($id,$major)) || (empty($id) && !$get->exists('search'))){
//			$this->displayCatalog($id);
//			return;
//		}

        if ($id || $get->exists('search')) {
            $this->displayGoods($id);
        } elseif (in_array($this->getURIVal('catalog'), array('fog', 'manid', 'prop', 'action')) || $this->cfg('SHOP_SHOW_GOODS')) {
            $this->displayGoods(true);
        } else {
//			$this->displayGoods(true);
            $this->displayCatalog();
        }
    }

    function actBrand() {
        global $ST;
        $this->setPageTitle('Партнёры');
        $data = array('rs' => $ST->select("SELECT * FROM sc_manufacturer ORDER BY sort, name")->toArray());

        $this->setCommonCont();
        $this->display($data, dirname(__FILE__) . '/brand.tpl.php');
    }

    function actClaim() {
        global $ST;
        $this->setPageTitle('Заказ продукции');

        $q = "SELECT id,name FROM sc_shop_catalog WHERE 1=1 ORDER BY main_sort";
        $rs = $ST->select($q);
        $collect = array();
        while ($rs->next()) {
            $collect[$rs->get('id')] = $rs->getRow();
        }
        $q = "SELECT id,name,price,category FROM sc_shop_item WHERE category IN(" . implode(',', array_keys($collect)) . ") ";
        $rs = $ST->select($q);
        $goods = array();
        while ($rs->next()) {
            $collect[$rs->get('category')]['item'][$rs->get('id')] = $rs->getRow();
            $goods[] = $rs->get('id');
        }
        $data['collect'] = $collect;
        $data['prop'] = array();

        $rs = $ST->select("SELECT * FROM sc_shop_prop p,sc_shop_prop_val pv WHERE prop_id=p.id AND item_id IN(" . implode(',', $goods) . ")");
        while ($rs->next()) {
            $data['prop'][$rs->get('item_id')][$rs->get('prop_id')] = $rs->getRow();
        }

        $this->display($data, $this->getTpl('claim.tpl.php'));
    }

    /**
     * Сборка (например комп по запчастям)
     *
     */
    function actCollect() {
        global $ST;
        $this->setPageTitle('Покулибничать');
        if ($this->getURIVal('collect') == 'simple') {
            $this->display(array(), dirname(__FILE__) . '/collect_simple.tpl.php');
        } else {
            $q = "SELECT id,name FROM sc_shop_catalog WHERE main_sort>0 ORDER BY main_sort";
            $rs = $ST->select($q);
            $collect = array();
            while ($rs->next()) {
                $collect[$rs->get('id')] = $rs->getRow();
            }
            $q = "SELECT id,name,price,category FROM sc_shop_item WHERE category IN(" . implode(',', array_keys($collect)) . ") ";
            $rs = $ST->select($q);
            while ($rs->next()) {
                $collect[$rs->get('category')]['item'][$rs->get('id')] = $rs->getRow();
            }
            $data['collect'] = $collect;

            $this->display($data, dirname(__FILE__) . '/collect.tpl.php');
        }
    }

    function actSearchGoods() {
        global $post;
        header("Location: " . $this->mod_uri . '?search=' . urlencode($post->get('search')));
        exit;
    }

    function getChildCategory($id = 0) {
        global $ST, $get;
        $res = array();
        $cond = "";
        $man_cond = "";
        if (($manid = $this->getURIVal('manid')) && $manid = explode(',', $manid)) {
            $man_cond.=" AND manufacturer_id IN ('" . implode("','", $manid) . "')";
        }

        $q = trim(strtolower(SQL::slashes(urldecode($get->get('search')))));

        if ($q) {
            $man_cond.=" AND (lower(i.name) LIKE '%" . $q . "%')";
        }
        if ($action = $this->getURIIntVal('action')) {
            $man_cond.=" AND i.sort1=$action";
//			$this->setPageTitle('СПЕЦИАЛЬНОЕ ПРЕДЛОЖЕНИЕ');
        }
        if ($this->cfg('SHOP_GOODS_IN_STOCK_ONLY') == 'true') {//Настройка показывать только в наличии
            $cond.=" AND i.in_stock>0";
        }
        $rs = $ST->select("SELECT name,img,parentid,id,c,cm FROM sc_shop_catalog cat
			LEFT JOIN (SELECT COUNT(id) AS c, category FROM sc_shop_item i WHERE  in_stock>-1 $cond $man_cond GROUP BY category) AS cnt ON cnt.category=cat.id
			LEFT JOIN (SELECT COUNT(itemid) AS cm, catid FROM sc_shop_item i, sc_shop_item2cat WHERE in_stock>-1 $cond AND itemid=i.id $man_cond GROUP BY catid) AS cntm ON cntm.catid=cat.id	
		WHERE cat.sort>-1  ORDER BY sort,name");

        $menu = array();
        while ($rs->next()) {
            foreach ($rs->getRow() as $k => $v) {
                $item[$rs->get('id')][$k] = $v;
            }
            $item[$rs->get('id')]['name'] = preg_replace('|,\s*|', ', ', $item[$rs->get('id')]['name']); //Фикс пробела после запятой;
            if ($rs->getInt('parentid')) {
                $item[$rs->get('parentid')]['ch'][$rs->getInt('id')] = &$item[$rs->get('id')];
            } else {
                $menu[$rs->get('id')] = &$item[$rs->get('id')];
            }
        }
        $item[0]['ch'] = &$menu;
        $k = 'c';
        if (isset($item[$id]['ch'])) {
            foreach ($item[$id]['ch'] as &$i) {
                $i[$k . "r"] = _recount_cat($i, $k);
            }
        }
        return @$item[$id]['ch'];
    }

    function getLastView($limit = 20) {
        return LibCatalog::getLastView($limit);
    }

    function addLastView($id) {
        if (!isset($_SESSION['__LAST_VIEW'])) {
            $_SESSION['__LAST_VIEW'] = array();
        }
        if (!in_array($id, $_SESSION['__LAST_VIEW'])) {
            $_SESSION['__LAST_VIEW'][] = $id;
        }
    }

    function actGoods($goods_id = 0) {
        global $ST;
        if (!$id = $goods_id) {
            $id = $this->getURINumVal('goods');
        }

        $field = array();


//		$q="SELECT i.* FROM sc_shop_item i,sc_shop_offer of 
//			WHERE of.itemid=i.id  AND i.price>=0 AND of.in_stock>0 AND i.id=$id AND of.region='{$this->getRegion()}'";//@in_stock

        $q = "SELECT i.*,m.name AS m_name FROM sc_shop_item i
		LEFT JOIN sc_manufacturer AS m ON m.id=i.manufacturer_id
		WHERE i.price>=0 AND i.id=$id";

        $rs = $ST->select($q);
        if ($rs->next()) {
            $this->addLastView($id);
            $ST->update('sc_shop_item', array('views=views+1'), "id=" . (int) $id); //Увеличим счётчик просмотров
            $field = $rs->getRow();
            $field['class'] = 'goods';
            $field['imgList'] = array();
            if ($field['img']) {
                $field['imgList'][] = $field['img'];
            }
            if ($field['img_add'] && $img = explode(',', $field['img_add'])) {
                foreach ($img as $i) {
                    $field['imgList'][] = $i;
                }
            }


            $field+=$this->getDeliveryCost($field['price']);

            $field['parentid'] = 0;

            $this->explorer[] = array('name' => $field['name']);
            $this->setTitle($field['name']);
            $this->setHeader($field['name']);
            //Хлебные крошки
            if ($parentid = $this->getURIIntVal('catalog')) {
                $field['category'] = $parentid;
            }
            $parent = $field['category'];
            $this->actCatalog[] = $parent;
            while ($parent) {
                $rs = $ST->select("SELECT id,name,parentid FROM sc_shop_catalog WHERE id=" . $parent);
                if ($rs->next()) {
                    $parent = $rs->getInt('parentid');
                    if (!$field['parentid']) {
                        $field['parentid'] = $parent;
                    }
                    $this->actCatalog[] = $parent;
                    $this->explorer[] = array('name' => $rs->get('name'), 'url' => $this->mod_uri . $rs->get('id') . '/');
                } else {
                    break;
                }
            }

            $field['prop'] = array();
            //Свойства
            $q = "SELECT v.value,p.name,p.grp,p.id,p.sort,pg.value_desc AS p_grp FROM sc_shop_prop p
			,sc_shop_prop_val v,
			(SELECT position,field_value,value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pg
			WHERE pg.field_value=p.grp AND p.grp>0 AND position<0 AND v.item_id={$id} AND v.prop_id=p.id ORDER BY pg.position,p.sort";

            $q = "SELECT v.value,p.name,p.grp,p.id,p.sort,pg.value_desc AS p_grp FROM sc_shop_prop p
			LEFT JOIN (SELECT position,field_value,value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pg ON pg.field_value=p.grp
			,sc_shop_prop_val v
			
			WHERE v.item_id={$id} AND v.prop_id=p.id AND p.grp>0 ORDER BY pg.position,p.sort";



            $rs = $ST->select($q);
            while ($rs->next()) {
                $field['prop'][$rs->get('grp')]['name'] = $rs->get('p_grp');
                $field['prop'][$rs->get('grp')]['p'][$rs->get('id')] = $rs->getRow();
            }

            //Товары связанные
            $field+=LibCatalog::getInstance()->getRelation($id);

            $field['rait'] = LibComment::getInstance()->getGoodsRait($field['id'], $field['category']);

            $field+=LibComment::getInstance()->getGoodsCommentData($field['id']);

            $field['comment'] = LibComment::getInstance()->getGoodsComment($field['id'], $this->isAdmin());

            $field['can_comment'] = LibComment::getInstance()->canGoodsComment($field['id']);


//			$this->tplLeftComponent=dirname(__FILE__).'/catalog_left.tpl.php';
//			$this->setContainer('common_container_lc.tpl.php');
            $this->setCommonCont();
            $this->display($field, dirname(__FILE__) . '/goods.tpl.php');
        } else {
            header("Location: /404/");
            exit;
        }
    }

    function saveBasketData($basket, $userid = 0) {
        $basket = json_encode($basket);

        if ($this->getUserId()) {
            $userid = $this->getUserId();
        }
        if ($userid && false) {
            global $ST;
            $ST->update('sc_users', array('basket' => $basket), "u_id={$userid}");
        } else {
            setcookie('basket', $_COOKIE['basket'] = $basket, COOKIE_EXP, '/');
        }
    }

    function addBasket($id, $count = 0, $nmn = null) {

        $basket = LibCatalog::getInstance()->getBasketData();
//		if(isset($_COOKIE['basket'])){			
//			$basket=json_decode(stripslashes($_COOKIE['basket']),true);
//		}
        $key = 'id' . $id;
        if ($nmn) {
            $key.="_" . $nmn;
        }

        if ($count == 0) {
            if (isset($basket[$key])) {
                $basket[$key] ++;
            } else {
                $basket[$key] = 1;
            }
        } else {

            if ($count === '+1') {
                if (isset($basket[$key])) {
                    $basket[$key] ++;
                } else {
                    $basket[$key] = 1;
                }
            } elseif ($count === '-1') {
                if ($basket[$key] > 1) {
                    $basket[$key] --;
                }
            } else {
                $basket[$key] = $count;
            }
        }
        $this->saveBasketData($basket);
//		setcookie('basket',$_COOKIE['basket']=json_encode($basket),COOKIE_EXP,'/');
    }

    function actRemoveComment() {
        global $post, $ST;
        if ($this->isAdmin()) {
            $ST->update('sc_comment', array('status' => 0), "id=" . $post->getInt('id'));
        }
        echo printJSON(array());
        exit;
    }

    function actShowComment() {
        global $post, $ST;
        if ($this->isAdmin()) {
            $ST->update('sc_comment', array('status' => 1), "id=" . $post->getInt('id'));
        }
        echo printJSON(array());
        exit;
    }

    function actSendComment() {
        global $ST, $post;

        if ($this->checkCapture($post->get('capture'), 'gfb')) {
            $d = array(
                'itemid' => $post->getInt('itemid'),
                'comment' => $post->get('comment'),
                'name' => $post->get('name') ? $post->get('name') : $this->getUser('name'),
                'mail' => $post->get('mail') ? $post->get('mail') : $this->getUser('mail'),
                'time' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'status' => 1, //по умолчанию одобрено
            );
            $id = $ST->insert("sc_comment", $d, 'id');

            $d['fullurl'] = "{$_SERVER['HTTP_HOST']}" . $this->getURI();


//			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
            $this->sendTemplateMail($this->cfg('MAIL_CONTACTS'), 'notice_goods_comment', $d);

            $rait = $post->getArray('rait');
            foreach ($rait as $k => $v) {
                $d = array(
                    'commentid' => (int) $id,
                    'raitid' => (int) $k,
                    'rating' => (int) $v,
                );

                $ST->insert('sc_comment_rait', $d, 'raitid');
            }
            echo printJSON(array('res' => 'ok'));
            exit;
        } else {
            echo printJSON(array('err' => 'Введите правильный код'));
            exit;
        }
    }

    function actGifts() {
        global $get;

        $this->addBasket($get->getInt('id'));

        header("Location: /catalog/order/?d=2");
        exit;
    }

    function actAdd() {
        global $ST, $post;

        $rs = $ST->select("SELECT * FROM sc_shop_item  WHERE id={$post->getInt('id')}");
        if ($rs->next()) {
            $out = $rs->getRow();
            $out['img'] = scaleImg($out['img'], 'w200h170');

            if ($rs->get('in_stock') == 0) {
                echo printJSON(array('error' => 'временно не продается'));
                exit;
            }
        }

        $this->addBasket($post->get('id'), $post->get('count'), $post->getInt('nmn'));

        if ($post->exists('refresh')) {
            echo $this->renderBasketContent();
            exit;
        }

        $basket = $this->getBasket();
//		$out['html']=$this->render(array('basket'=>$basket),dirname(__FILE__).'/basket_content_container.php');
        $summ = 0;
        $count = 0;
        foreach ($basket as $item) {
            $summ+=$item['sum'];
            $count+=$item['count'];
        }
        $out['count'] = $count;
        $out['summ'] = $summ;
        $out['html'] = $this->renderBasketContentContainer();


        echo printJSON($out);
        exit;
    }

    function actAddSelected() {
        global $ST, $post;
        $basket = $this->getBasketData();
//		if(isset($_COOKIE['basket'])){			
//			$basket=json_decode(stripslashes($_COOKIE['basket']),true);
////			unset($basket['id'.$post->get('id')]);
//		}

        $selected = $post->getArray('item');
        $count = $post->getArray('count');
        $unit_sale = $post->getArray('unit_sale');
        foreach ($selected as $id) {
            if (isset($count[$id])) {
                $key = 'id' . $id;
                if (!empty($unit_sale[$id])) {
                    $key.='_' . $unit_sale[$id];
                }
                $basket[$key] = (float) $count[$id];
            }
        }
        $this->saveBasketData($basket);
//		setcookie('basket',$_COOKIE['basket']=json_encode($basket),COOKIE_EXP,'/');

        if ($post->exists('refresh')) {
            echo $this->renderBasketContent();
            exit;
        }
        echo $this->render(array(), dirname(__FILE__) . '/basket_content_container.php');
        exit;
    }

    function actClear() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $basket = $this->getBasketData();
//			if(isset($_COOKIE['basket'])){			
//				$basket=json_decode(stripslashes($_COOKIE['basket']),true);
//			}
            unset($basket['id' . $id]);
            $exp = 3600 * 24 * 180 + time();
            $this->saveBasketData($basket);
//			setcookie('basket',json_encode($basket),$exp,'/');
            exit;
        }

        $d = $this->getOrderData();
        unset($d['additionally']);
        $this->saveOrderData($d);
        $this->saveBasketData(array());
//		setcookie('basket',null,time(),'/');
        exit;
    }

    function actSaveOrder() {
        $this->saveOrderData($_POST);
    }

    function saveOrderData($data) {
        $data = printJSON($data);
        Cookie::set('__ORDER', base64_encode($data));
    }

    function getOrderData() {
        $out = array();
        $d = base64_decode(Cookie::get('__ORDER'));
        if ($d = getJSON($d)) {
            return $d;
        }
        return $out;
    }

    function actBasket() {
        global $ST;

        $this->setPageTitle('Корзина');

        $data = array(
            'name' => $this->getUser('name'),
            'fullname' => $this->getUser('name'),
            'mail' => $this->getUser('mail'),
            'phone' => $this->getUser('phone'),
            'address' => $this->getUser('address'),
            'region' => '',
            'city' => $this->getUser('city') ? $this->getUser('city') : $this->cfg('DEFAULT_CITY'),
            'district' => $this->getUser('district'),
            'street' => $this->getUser('street'),
            'house' => $this->getUser('house'),
            'flat' => $this->getUser('flat'),
            'porch' => $this->getUser('porch'),
            'floor' => $this->getUser('floor'),
            'additionally' => '',
            'date' => date('d.m.Y'),
            'address_list' => array(),
            'jur_list' => array(),
        );

        if ($d = $this->getOrderData()) {
            foreach ($data as $k => $v) {
                if (isset($d[$k]) && !trim($v)) {
                    $data[$k] = $d[$k];
                }
            }
        }
        //свойства города деладось для стрекозы
//		$cityProp=$this->getCityProperties();
//		$data['delivery_details_list']=array();
//		if(trim($cityProp['details'])){
//			$arr=explode("\n",$cityProp['details']);
//			foreach ($arr as $row){
//				if($row=trim($row)){
//					$data['delivery_details_list'][]=$row;
//				}
//			}
//		}
        $d = array('delivery_type' => 1);
        $d = array('delivery_zone' => 0);
//		if($data['delivery_details_list'] && !empty($data['delivery_details_list'][0])){
//			$d['delivery_details']=$data['delivery_details_list'][0];
//		}
        $data = array_merge($data, $this->getBasketInfo($d));



        $data['related'] = array();


        //Товары связанные
        $ids = array();
        foreach ($data['basket'] as $item) {
            $ids[] = $item['id'];
        }

        if ($ids) {//5 вместе с выбранными товарами покупают
            $q = "SELECT i.* FROM sc_shop_relation r,sc_shop_item i
				WHERE   i.price>=0  
					AND r.type=1 AND i.id=r.ch 
					AND r.par IN(" . implode(",", $ids) . ")";

            $rs = $ST->select($q);
            while ($rs->next()) {
                $row = $rs->getRow();
                $data['related'][] = $row;
            }
        }
//		$this->setCommonCont();
        $data['time_list'] = array();
        if ($this->cfg('SHOP_CHECK_DELIVERY_TIME') == '1') {
            $data['time_list'] = $this->enum('sh_delivery_time');
        }

        $data['inn'] = '';
        $other_info = getJSON($this->getUser('other_info'));
        if (isset($other_info['inn'])) {
            $data['inn'] = $other_info['inn'];
        }
        $data['addr_list'] = array();
//		if($this->getUserId()){
//			$q="SELECT DISTINCT city,address FROM sc_shop_order WHERE userid='".$this->getUserId()."' ORDER BY city,address DESC";
//			$rs=$ST->select($q);
//			while ($rs->next()) {
//				$data['addr_list'][]="{$rs->get('city')}, {$rs->get('address')}";
//			}
//		}
        if ($this->getUser('other_info') && $other_info = getJSON($this->getUser('other_info'))) {
            if (is_array($other_info)) {
                $data['addr_list'] = array_merge($data['addr_list'], $other_info);
            }
        }
        asort($data['addr_list']);
        $data['pay_system_list'] = $this->enum('sh_pay_system');
        $data['delivery_type_list'] = $this->enum('sh_delivery_type');
        $data['city_list'] = $this->enum('city');



        $this->setCommonCont();
        $this->display($data, dirname(__FILE__) . '/basket.tpl.php');
    }

    function actRefresh() {
        global $post;
        $d = array();
        if ($basket = LibCatalog::getInstance()->getBasketData()) {
            foreach ($post->getArray('count') as $k => $val) {
                if (isset($basket[$k])) {
                    $basket[$k] = floatval(str_replace(',', '.', $val));
                    if ($basket[$k] == 0) {
                        unset($basket[$k]);
                    }
                }
            }
            $this->saveBasketData($basket);


            if ($post->exists('delivery_type')) {
                $d['delivery_type'] = $post->get('delivery_type');
            }
//			if($post->exists('not_use_bonus')){
//				$d['not_use_bonus']=$post->get('not_use_bonus');
//			}
            $d['use_bonus'] = 1;
            if (!$post->exists('use_bonus')) {
                $d['use_bonus'] = $post->get('use_bonus');
            }
            if ($post->exists('delivery_zone')) {
                $d['delivery_zone'] = $post->getInt('delivery_zone');
            }
            if ($post->exists('item_comment')) {
                $d['item_comment'] = $post->getArray('item_comment');
            }
            if ($post->exists('sort')) {
                $d['sort'] = $post->get('sort');
            }
            if ($post->exists('ord')) {
                $d['ord'] = $post->get('ord');
            }
        }
        echo $this->renderBasketContent($d);
        exit;
    }

    function actSuccess() {
        global $ST, $get;
        if (!$id = Cookie::get('order_id')) {
            $id = $get->getInt('id');
        }



        $cond = "";
        if (!$this->isAdmin()) {
            $cond = " AND userid={$this->getUserId()}";
        }

        $rs = $ST->select("SELECT * FROM sc_shop_order WHERE id={$id} $cond");
        if ($rs->next()) {
            $ps_href = '';
            $data = $rs->getRow();
            $data['items'] = array();

            $data['metrica'] = array(
                'order_id' => $id,
                'order_price' => $rs->getFloat('total_price'),
                'currency' => 'RUR',
                'exchange_rate' => 1,
                'goods' => array(),
            );


            $rs1 = $ST->select("SELECT * FROM sc_shop_item i,sc_shop_order_item  WHERE orderid={$id} AND itemid=i.id");
            while ($rs1->next()) {
                $data['metrica']['goods'][] = array(
                    'id' => $rs1->getInt('itemid'),
                    'name' => $rs1->get('name'),
                    'price' => $rs1->getFloat('price'),
                    'quantity' => $rs1->getInt('count'),
                );
            }

            if ($data['pay_status'] != 1 && isset($data['pay_system']) && $data['pay_system'] == 3 && $data['total_price']) {//Если электронные платежи и есть сумма
                $rs1 = $ST->select("SELECT * FROM sc_pay_system WHERE name='paymaster'");

                if ($rs1->next()) {
                    include_once("core/lib/PSPaymaster.class.php");
                    $ps = new PSPaymaster(unserialize($rs1->get('config')));
                    $ps->setDesc('Покупка товара');
                    $ps->setSumm($data['total_price']);
                    $ps->setEmail($this->getUser('mail'));
                    $ps->setOrderNum($id);

                    $ps_href = $ps->getUrl();
                }
            }
            $data['ps_href'] = '';
            if ($ps_href) {
                $data['ps_href'] = "Для того, чтобы оплатить заказа перейдите по ссылке <a href=\"{$ps_href}\">ОПЛАТИТЬ</a>";
            }

            if (isset($data['pay_system']) && $data['pay_system'] == 1) {
                $url = "http://{$_SERVER['HTTP_HOST']}/prnt/SBERpdf/?id=$id";
                $data['ps_href'] = "<a href=\"{$url}\">Распечатать счёт</a>";
//				$att[]=array('name'=>'Счёт.xls','file'=>$url."&access=allow");
            }

            $data['ps_href'].="<br><span class='pay_status'>" . $this->enum('sh_pay_status', $data['pay_status']) . "</span>";

            $data['text'] = BaseComponent::getText('order_report_success');
            if (preg_match_all('/\{([\w\d]+)\}/U', $data['text'], $res)) {
                foreach ($res[1] as $k) {
                    if (isset($data[$k])) {
                        $data['text'] = str_replace("{{$k}}", $data[$k], $data['text']);
                    }
                }
            }
            $this->setCommonCont();
            $this->setPageTitle('Заказ оформлен');
            $this->display($data, dirname(__FILE__) . '/success.tpl.php');
        }
    }

    private function getDeliveryType($p = 0) {
        return isset($_COOKIE['delivery_type' . $p]) ? (int) $_COOKIE['delivery_type' . $p] : 1;
    }

    function getDelivery($type = 0, $summ = 0) {
        global $DELIVERY_LIST, $ST;
        $delivery = false;
        if (in_array($type, array(1))) {//Доставка курьером или доставка в подарок
            if (empty($DELIVERY_LIST)) {
                $DELIVERY_LIST = $ST->select("SELECT summ,price FROM sc_shop_delivery ORDER BY summ")->toArray();
            }
            if (!empty($DELIVERY_LIST)) {
                foreach ($DELIVERY_LIST as $row) {
                    if ($summ > $row['summ']) {
                        $delivery = $row['price'];
                    } else {
                        return $delivery;
                    }
                }
            } elseif ($delivery = $this->cfg('SHOP_DELIVERY')) {
                return $delivery;
            }
        }
        return $delivery;
    }

    function getDeliveryByTime($time_desc) {
        return preg_replace('/.*\s*-\s*(\d+).*/', '\1', $time_desc);
    }

    private function setDeliveryType($type, $p = 0) {
        setcookie('delivery_type' . $p, $_COOKIE['delivery_type' . $p] = $type, COOKIE_EXP, '/');
    }

    function updateBasketData() {
        $data['posible'] = true;

        $data['delivery_type'] = $this->getDeliveryType();
        $data['delivery_cond'] = (int) $this->cfg('SHOP_DELIVERY_COND');
        $data['delivery_order_cond'] = (int) $this->cfg('SHOP_ORDER_COND');
        $data['delivery_express_price'] = (float) $this->cfg('SHOP_DELIVERY_EXPRESS');
        $data['delivery_pickup'] = (float) $this->cfg('SHOP_DELIVERY_PICKUP');

        $basket = $this->getBasketExt();

        foreach ($basket as $dist => &$prop) {
            $bsk = new Basket($prop['item']);
            if ($dist == 0) {//исключение для кировского
                $prop['delivery_order_cond'] = $data['delivery_order_cond'];
                if ($bsk->getPrice() < $data['delivery_cond'] && $this->getUser('type') != 'user_1') {
                    $bsk->delivery = (float) $this->cfg('SHOP_DELIVERY');
                }
                if ($this->getUser('type') == 'user_1') {
                    $prop['delivery_order_cond'] = 0;
                }
                if ($data['delivery_type'] == 2) {
                    $bsk->delivery = (float) $data['delivery_express_price'];
                }
                $bsk->discount = (float) $this->getUser('discount');
                $data['delivery_pickup'] = max($bsk->discount, $data['delivery_pickup']);
                if ($data['delivery_type'] == 3) {
                    $bsk->discount = $data['delivery_pickup'];
                    $bsk->delivery = 0;
                }
            } else {
                $bsk->delivery = $prop['delivery'];
                if ($prop['delivery_cond'] && $bsk->getPrice() >= $prop['delivery_cond']) {
                    $bsk->delivery = 0;
                }
            }
            $prop['posible'] = true;
            if ($prop['delivery_order_cond'] > $bsk->getPrice()) {
                $prop['posible'] = false;
                $data['posible'] = false;
            }

            $basket[$dist]['basket'] = $bsk;
        }
        $data['glob_basket'] = $basket;

        return $data;
    }

    function actGetRenderBasket() {
        global $post;

        echo $this->renderBasketContent($post->get());
        exit;
    }

    /* Стоимость по городам */

    function getDeliveryCost($smm = 0) {
        $data['delivery'] = 0;
        $data['price_condition'] = 0;
//		$cityProp=$this->getCityProperties();
//		if(isset($cityProp['price'])){
//			$data['delivery']=$cityProp['price'];
//			if($cityProp['price_condition']){
//				$data['price_condition']=$cityProp['price_condition'];
//			}
//			if($cityProp['price_condition'] && $smm>$cityProp['price_condition']){
//				$data['delivery']=0;
//				
//			}
//		}
        return $data;
    }

    /* Стоимость по зонам */

    function getDeliveryCostZone($smm = 0, $zone = 0) {
        global $ST;


        $data['delivery'] = null;
        $data['price_condition'] = 0;

        $rs = $ST->select("SELECT * FROM sc_shop_delivery_zone WHERE id=$zone");
        if ($rs->next()) {
            $smm_cfg_arr = array();
            if ($smm_cfg = $rs->get('smm_cfg')) {
                foreach (explode(',', $smm_cfg) as $row) {
                    if (preg_match('/(\d+):(\d+)/', $row, $res)) {
                        $smm_cfg_arr[$res[1]] = $res[2];
                    }
                }
                ksort($smm_cfg_arr);
                $data['delivery'] = false;
                foreach ($smm_cfg_arr as $cond => $delivery) {
                    if ($smm >= $cond) {
                        $data['delivery'] = $delivery;
                        $data['price_condition'] = $cond;
//						break;
                    }
                }
            }
        }
        return $data;
    }

    function getBasketInfo($d = array()) {
        $data['basket'] = $this->getBasket();

        $order = $this->getOrderData();

        foreach ($data['basket'] as &$item) {
            if (isset($order['item_comment'][$item['key']])) {
                $item['item_comment'] = $order['item_comment'][$item['key']];
            }
        }


        foreach ($data['basket'] as &$item) {
            if (isset($d['item_comment'][$item['key']])) {
                $item['item_comment'] = $d['item_comment'][$item['key']];
            }
        }

        $bsk = new Basket($data['basket']); //для всяких расчётов


        foreach ($d as $k => $v) {
            $data[$k] = $v;
        }

        $delivery_zone = 0;
        if (isset($d['delivery_zone'])) {
            $delivery_zone = (int) $d['delivery_zone'];
        }
        //Если есть для города доставка
//		$data+=$this->getDeliveryCost($bsk->getSum());
        //Если есть зоны доставка
        $data+=$this->getDeliveryCostZone($bsk->getSum(), $delivery_zone);

//		$data['delivery']=0;
//		$data['price_condition']=0;
//		if(isset($cityProp['price'])){
//			$data['delivery']=$cityProp['price'];
//			if($cityProp['price_condition']){
//				$data['price_condition']=$cityProp['price_condition'];
//			}
//			if($cityProp['price_condition'] && $bsk->getSum()>$cityProp['price_condition']){
//				$data['delivery']=0;
//				
//			}
//		}
        $data['margin'] = 0;
//		if(isset($cityProp['margin'])){
//			$data['margin']=$cityProp['margin'];
//		}
        //но есть прописана цена по стоимости
        if (isset($d['delivery_type'])) {
            $data['price_condition'] = 0;
            $data['delivery'] = $this->getDelivery($d['delivery_type'], $bsk->getSum());
        }

        //подсчитаем скидку
        $data['discount'] = 0;
        if ($discount = $this->getUser('discount')) {
            $bsk->discount = $data['discount'] = (float) $discount;
        }
        //подсчитаем бонусы
        $data['bonus'] = 0;
//		if(empty($d['not_use_bonus']) && $bonus=$this->getUser('bonus')/10){
//			if($bonus>$bsk->getSum()){
//				$bonus=$bsk->getSum();
//			}
//			
//			$bsk->bonus=$data['bonus']=(float)$bonus;
//		}
        if (!empty($d['use_bonus']) && $bonus = $this->getUser('bonus') / 10) {
            if ($bonus > $bsk->getSum()) {
                $bonus = $bsk->getSum();
            }

            $bsk->bonus = $data['bonus'] = (float) $bonus;
        }



        $bsk->delivery = $data['delivery'];
        $data['sum'] = $bsk->getSum();
        $data['count'] = $bsk->getCount();
        $data['total_sum'] = $bsk->getTotalSum();

        $data['sort'] = isset($d['sort']) ? $d['sort'] : '';
        $data['ord'] = isset($d['ord']) ? $d['ord'] : '';

        return $data;
    }

    function renderBasketContent($d = array()) {
        $data = $this->getBasketInfo($d);
        return $this->render($data, dirname(__FILE__) . '/basket_content.tpl.php');
    }

    function renderBasketContentContainer() {
        return $this->render(array(), dirname(__FILE__) . '/basket_content_container.php');
    }

    function actRemove() {
        global $get;
        $key = $get->get('key');
        $basket = LibCatalog::getInstance()->getBasketData();
        unset($basket[$key]);
        $this->saveBasketData($basket);
        echo printJSON(array('key' => $key));
        exit;
    }

    function getUserAddrList() {
        
    }

    function getUserJurList($userid) {
        global $ST;
        $res = array();
        $rs = $ST->select("SELECT DISTINCT pay_account_jur FROM sc_shop_order WHERE userid={$userid} ORDER BY id DESC");
        while ($rs->next()) {
            if ($jur = @unserialize($rs->get('pay_account_jur'))) {
                $res[] = $jur;
            }
        }
        return $res;
    }

    function actLogon() {
        global $ST, $post;
        $rs = $ST->select("
			SELECT u_id,address,city,district,street,house,flat,porch,floor,name,mail,phone 
			FROM sc_users 
			WHERE (login='" . SQL::slashes($post->get('login')) . "' OR mail='" . SQL::slashes($post->get('login')) . "' OR mail='" . SQL::slashes($post->get('login')) . "')
				AND password=MD5('" . SQL::slashes($post->get('password')) . "')");
        if ($rs->next()) {

            if (!session_id()) {
//				if($post->exists('save')){
                session_set_cookie_params(3600 * 24 * 15, '/');
//				}
                session_start();
            }
            $_SESSION['_USER']['u_id'] = $rs->getInt('u_id');
            $row = $rs->getRow();
//			$row['addrbk']=$this->addrBk();
//			setcookie('sid',session_id(),time() + 3600*24*30,'/');
//			setcookie('sid',session_id(),time() + 3600*24*30,'/');
            echo printJSON($row);
            exit;
        }
        echo printJSON(array('msg' => 'Ошибка авторизации'));
        exit;
    }

    function checkOrder($args, $basket) {
        $error = array();


//		if(!trim($args->get('fullname' ))){$error['fullname']="Введите ФИО!";}
        if (!trim($args->get('phone'))) {
            $error['phone'] = 'Введите телефон';
        }
        if (!trim($args->get('address'))) {
            $error['address'] = 'Введите адрес';
        }


//		if($err=$this->checkMail($args->get('mail'),false))$error['mail']=$err;

        if ($args->exists('date')) {//Если передаём дату доставки
            if (!trim($args->get('date'))) {
                $error['time'] = 'Введите дату';
            } elseif (!preg_match('|\d{2}\.\d{2}\.\d{4}|', $args->get('date'))) {
                $error['time'] = 'Введите дату корректно [dd.mm.yyyy]';
            }
        }

        if ($this->cfg('SHOP_CHECK_DELIVERY_TIME') == 1 && time() > strtotime($args->get('date') . ' ' . $args->get('time') . ':00:00')) {
            $error['time'] = BaseComponent::getText('delivery_time_error_notice');
            $error['time'].='<small style="color:#aaa">Текущее время ' . date('H:i:s') . '</small>';
        }

//		if($args->getInt('reg')==1 && !$args->exists('auto_pass')){//Хочет реги и не автопароль
//			if(strlen($args->get('reg_password'))<6){
//				$error['reg_password']='длина пароля не должна быть меньше 6 символов';
//			}elseif($args->get('reg_password')!==$args->get('cpassword')){
//				$error['reg_password']='пароль и подтверждение не совпадают';
//			}
//		}
//		if($basket['delivery']!==false){
//			if(!$args->get('pay_system')){
//				$error['pay_system']='Выберите способ оплаты';
//			}
//		}


        if (empty($basket['basket'])) {
            $error['basket'] = 'Корзина пуста';
        }

        $bsk = new Basket($this->getBasket());
        if ($bsk->getTotalPrice() < (int) $this->cfg('SHOP_ORDER_COND')) {
            $error['basket'] = "Сумма заказа не менее {$this->cfg('SHOP_ORDER_COND')} р.";
        }else{
            foreach ($this->getBasket() as $item){
                if($item['in_stock']<0){
                    $error['basket']=$item['name']." НЕТ В НАЛИЧИИ";
                    break;
                }
            }
        }
        return $error;
    }

    function checkLogin($login) {
        global $ST;
        if (!trim($login)) {
            return 'Введите логин';
        } elseif (strlen($login) > 16) {
            return 'Логин должен содержать не более 16 символов';
        } else {
            $rs = $ST->select("SELECT * FROM sc_users WHERE login='" . SQL::slashes($login) . "'  LIMIT 1");
            if ($rs->next()) {
                return 'Логин уже зарегистрирован';
            }
        }
        return '';
    }

    function addrBk() {
        if (!$userId = $this->getUserId())
            return '';
        global $ST;
        $addr = array();
        $out = '';
        $rs = $ST->select("SELECT DISTINCT address FROM sc_shop_order WHERE userid=$userId");
        while ($rs->next()) {
            //$out.=$rs->get('address');
            if (preg_match('/([^,]+), ([^\,]+)-([^\,]*),\D+(\d*),\D+(\d*)/', $rs->get('address'), $res)) {//Малышева, 111б-47, подъезд 4, этаж 2
                $addr[] = array("{$res[1]}|{$res[2]}|{$res[3]}|{$res[4]}|{$res[5]}|", $rs->get('address'));
                $out.='<a href="#" alt="' . "{$res[1]}|{$res[2]}|{$res[3]}|{$res[4]}|{$res[5]}" . '">' . $rs->get('address') . '</a>';
            }
        }
        return $out;
    }

    function actSendOrder() {
        global $ST, $post;
        $basket = $this->getBasketInfo($post->get());

        if ($error = $this->checkOrder($post, $basket)) {
            echo printJSON(array('error' => $error));
            exit;
        } else {


            $address = $post->get('address');


            /* Информация о заказчике */
            $data = array(
                'phone' => $post->get('phone'),
                'city' => $post->get('city'),
//					'district'=>$post->get('district'),
                'address' => $address,
//					"street" =>$post->get('street'),
//					"house" =>$post->get('house'),
//					"flat" =>$post->get('flat'),
//					"porch" =>$post->get('porch'),
//					"floor" =>$post->get('floor'),
//					'mail'=>$post->get('mail'),
//					'name'=>$post->get('from_name'),
//					'name'=>trim("{$post->get('last_name')} {$post->get('first_name')} {$post->get('middle_name')}"),
//					'last_name'=>$post->get('last_name'),
//					'first_name'=>$post->get('first_name'),
//					'middle_name'=>$post->get('middle_name'),
//					'name'=>"{$post->get('last_name')} {$post->get('first_name')} {$post->get('middle_name')}",
//					'company'=>$post->get('company'),
            );

//Добавим реферала
            if ($refid = $post->getInt('refid')) {
                $rs = DB::select("SELECT * FROM sc_users WHERE u_id=$refid");
                if ($rs->next()) {
                    $data['refid'] = $post->getInt('refid');
                } else {
                    //Если неправильный refid
                }
            }

            if (!$this->getUserId() && $post->getInt('reg') == 1 /* $post->exists('reg_login') */) {//&& $post->exists('want_reg')
                if ($post->exists('mail')) {
                    $data['login'] = $post->get('mail');
                    $data['mail'] = $post->get('mail');

                    if ($post->exists('auto_pass')) {//Всегда назначать пароль
                        $password = substr(md5(time()), 0, 6);
                        $data[] = "password=MD5('" . $password . "')";
                    } else {
                        $password = $post->get('password');
                        $data[] = "password=MD5('" . SQL::slashes($password) . "')";
                    }
                    if (!session_id()) {
                        session_set_cookie_params(3600 * 24 * 15, '/');
                        session_start();
                    }
                    $_SESSION['_USER']['u_id'] = $ST->insert('sc_users', $data, 'u_id');

                    //уведомление о регистрации
                    $this->sendTemplateMail($data['mail'], 'notice_new_user', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $data['login'], 'PASSWORD' => $password)
                    );
                    //уведомление о регистрации админу
                    $this->sendTemplateMail($this->cfg('MAIL'), 'notice_new_user4admin', array('FROM_SITE' => FROM_SITE, 'LOGIN' => $data['login'], 'name' => $data['name'])
                    );
                    $this->noticeICQ($this->cfg('ICQ'), 'Новый пользователь на сайте');
                }
            } elseif ($this->getUserId()) {
                //Обновим пользователя	
                if (!empty($basket['bonus'])) {
                    $data['bonus'] = $this->getUser('bonus') - $basket['bonus'];
                    $inc = array(
                        'userid' => $this->getUserId(),
                        'sum' => $basket['bonus'],
                        'balance' => $data['bonus'],
                        'type' => 'bonus',
                        'description' => 'Списание бонуса',
                        'time' => date('Y-m-d H:i:s'),
                    );
                    $ST->insert('sc_income', $inc);
                }


                $ST->update('sc_users', $data, 'u_id=' . $this->getUserId());
            }
            $this->setUser($data);

            $time = $post->get('time');
            if ($t = $this->enum('sh_delivery_time', $time)) {
                $time = $t;
            }

            $delivery_type = 1; //доставка курьером
            if ($post->getInt('delivery_type')) {
                $delivery_type = $post->getInt('delivery_type');
            }
            if ($basket['delivery'] === false) {//доставка не возможна
                $delivery_type = 2;
            }


            $data = array(
                'userid' => $this->getUserId(),
                'fullname' => $this->getUser('name'),
                'date' => $post->get('date') ? dte($post->get('date'), 'Y-m-d') : date('Y-m-d'),
                'time' => $time,
                'mail' => $post->get('mail') ? $post->get('mail') : $this->getUser('mail'),
                'pay_system' => $post->get('pay_system'),
                'phone' => $post->get('phone'),
                'address' => $address,
//					'postcard'=>$post->get('postcard'),
                'additionally' => $post->get('additionally'),
                'price' => $basket['sum'], //это стоимость заказа
                'total_price' => $basket['total_sum'], //Стоимость с учётом доставки и скидки
                'order_status' => 0,
                'delivery_type' => $delivery_type,
                'pay_system' => $post->get('pay_system'),
                'delivery' => $basket['delivery'],
                'pay_bonus' => $basket['bonus'],
//					'country'=>$this->getCountry(),
//					'region'=>$this->getRegion(),
                'city' => $post->get('city'),
                'discount' => $basket['discount'],
                'margin' => $basket['margin'],
            );

//			$order_data=array(
//				'from_name'=>$post->get('from_name'),
//				'from_phone'=>$post->get('from_phone'),
//				'from_city'=>$post->get('from_city'),
//				'remember'=>$post->get('remember'),
//				'report'=>$post->get('report'),
//				'call'=>$post->get('call'),
//				'call_no_report'=>$post->get('call_no_report'),
//			);
//			$data['order_data']=printJSON($order_data);
//			if(!trim($data['address'])){
//					$data['address']=serialize(array(
////						'region'=>$post->get('region'),
//						'city'=>$post->get('city'),
//						'district'=>$post->get('district'),
//						'street'=>$post->get('street'),
//						'house'=>$post->get('house'),
//						'flat'=>$post->get('flat'),
//						'porch'=>$post->get('porch'),
//						'floor'=>$post->get('floor'),
//				));
//			
//			}
            //Добавим заказ
            $id=LibShop::addOrder($data,$basket['basket']);

            $ps_href = '';

            if (isset($data['pay_system']) && $data['pay_system'] == 3 && $data['total_price']) {//Если электронные платежи и есть сумма
                $rs1 = $ST->select("SELECT * FROM sc_pay_system WHERE name='paymaster'");
                if ($rs1->next()) {
                    include_once("core/lib/PSPaymaster.class.php");
                    $ps = new PSPaymaster(unserialize($rs1->get('config')));
                    $ps->setDesc('Покупка товара');
                    $ps->setSumm($data['total_price']);
                    $ps->setEmail($this->getUser('mail'));
                    $ps->setOrderNum($id);

                    $ps_href = $ps->getUrl();
                }
            }

//            $icq_notice = "Новый заказ на сайте {$_SERVER['HTTP_HOST']}\n";
//
//            
//            $icq_notice.="Итого: {$basket['sum']}\n";
//            $icq_notice.="Заказчик: {$post->get('from_name')}\n";
//            $icq_notice.="Контактный телефон: {$post->get('from_phone')}\n";
//            $icq_notice.="Адрес: {$post->get('address')}\n";
////			$icq_notice.="Сообщение: {$post->get('comment')}\n";
//            $icq_notice.="Время доставки: {$post->get('date')} {$post->get('time')}\n";

            //уведомление о заказе пользователю

            $notice = $data; //+$order_data;
            $notice['date'] = dte($notice['date']);
//			$notice['description']='';
//			foreach (array('remember','report','call','call_no_report',) as $v){
//				$notice['description'].=$this->enum('field_label',@"{$v}_{$notice[$v]}")."<br>";
//			}

            $notice['ps_href'] = '';
            if ($ps_href) {
                $notice['ps_href'] = "Для того, чтобы оплатить заказа перейдите по ссылке <a href=\"{$ps_href}\">ОПЛАТИТЬ</a>";
            }

            $delivery_list = $this->enum('sh_delivery_type');
            $pay_system_list = $this->enum('sh_pay_system');

            $notice['ORDER_NUM'] = $order_num = $id;
//			$notice['ORDER_NUM']=$order_num;			
            $notice['NAME'] = $this->getUser('name');

            $notice['FROM_SITE'] = FROM_SITE;
            $notice['basket'] = $this->render(array_merge($basket, array('is_order' => true, 'is_letter' => true)), dirname(__FILE__) . '/basket_content.tpl.php');

            $notice['delivery_type'] = @$delivery_list[$notice['delivery_type']];
            $notice['pay_system'] = @$pay_system_list[$notice['pay_system']];


            include('function.tpl.php');


            $notice['address'] = parsAddr($notice['address']);

//			$url="http://{$_SERVER['HTTP_HOST']}/prnt/SHET/?id=$id&PHPSESSID=".session_id();
//			$url="http://{$_SERVER['HTTP_HOST']}/prnt/SBER/?id=$id&PHPSESSID=".session_id();
//			$content=file_get_contents($url);
            $att = array();
            if (isset($data['pay_system']) && $data['pay_system'] == 1) {
                $url = "http://{$_SERVER['HTTP_HOST']}/prnt/SBERpdf/?id=$id";
                $notice['ps_href'] = "<a href=\"{$url}\">Распечатать счёт</a>";
//				$att[]=array('name'=>'Счёт.xls','file'=>$url."&access=allow");
            }

            /* if($post->getInt('is_jur')){ //Печатать не надо
              $url="http://{$_SERVER['HTTP_HOST']}/prnt/SchetWord/?id=$id";
              $notice['ps_href']="<a href=\"{$url}\">Распечатать счёт</a>";
              $att[]=array('name'=>'na_oplatu'.date('Y_m_d').'.doc','file'=>$url."&access=allow");
              } */
            $mail = $post->exists('mail') ? $post->get('mail') : $this->getUser('mail');
            if ($mail) {
                $this->sendTemplateMail($post->get('mail'), 'notice_new_order', $notice, $att
                );
            }

            //уведомление о заказе админу
//			$mail_contacts=$this->enum('mail_contacts',$this->getRegion());
            $this->sendTemplateMail($this->cfg('MAIL'), 'notice_new_order4admin', $notice
            );

           // $this->noticeICQ($this->cfg('ICQ'), $icq_notice);

            $d = $this->getOrderData();
            unset($d['additionally']);
            $this->saveOrderData($d);
            $this->saveBasketData(array());
//			setcookie('basket',null,0,'/');//Очистим корзину
            Cookie::set('order_id', $id);
            $redirect_href = "/catalog/success/?id=$id";
            if ($ps_href) {
                $redirect_href = $ps_href;
            }
            echo printJSON(array(
                'id' => $id,
                'order_num' => $order_num,
                'error' => '',
                'count' => $basket['count'],
                'delivery' => $data['delivery'],
                'total_price' => $data['total_price'],
                'ps_href' => $notice['ps_href'],
                'redirect_href' => $redirect_href,
                'date' => "{$post->get('date')} {$post->get('time')}"));
            exit;
        }
    }

    ///////////////////
    //Избранное
    ////////////////////
    function actFavorite() {
        global $ST;

        $data = array(
            'rs' => array()
        );

        $cond = " AND i.id IN('" . implode("','", $this->getFavoriteData()) . "') ";

        $q = "SELECT i.*,c.id AS c_id,c.name AS c_name,c.img AS c_img FROM sc_shop_item i, sc_shop_catalog c WHERE i.category=c.id $cond";
        $rs = $ST->select($q);
        while ($rs->next()) {
            $data['rs'][] = $rs->getRow();
        }
        $this->setPageTitle('Избранное');
        $this->tplLeftComponent = dirname(__FILE__) . "/catalog_left.tpl.php";
        $this->display($data, dirname(__FILE__) . "/favorite.tpl.php");
    }

    function getFavorites($limit = 20) {
        global $ST;
        $res = array();
        if ($arr = $this->getFavoriteData()) {
            $arr = array_slice(array_reverse($arr), 0, $limit);

            $q = "SELECT * FROM 
				(SELECT * FROM sc_shop_item WHERE id IN('" . implode("','", $arr) . "') LIMIT $limit) AS i
				LEFT JOIN (SELECT MIN(price) AS minp,MAX(price) AS maxp,itemid FROM sc_shop_offer WHERE itemid IN('" . implode("','", $arr) . "') GROUP BY itemid ) AS of ON i.id=of.itemid
			";

            $rs = $ST->select($q);
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

    function actFavRemove() {
        global $get;
        LibCatalog::favRemove($get->getInt('id'));
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
        echo printJSON(array('msg' => 'ok', 'id' => $get->getInt('id')));
        exit;
    }

    function actAddFav() {
        global $post;
        $count = LibCatalog::addFav($post->getInt('id'));
        echo printJSON(array('count' => $count));
        exit;
    }

    ///////////////////
    // Сравнение
    ////////////////////
    function actAddCompare() {
        global $ST, $post;
        $compare = $this->getCompare();
        $id = $post->get('id');
        if (!in_array($id, $compare)) {
            $compare[] = $id;
        }
        setcookie('compare', $_COOKIE['compare'] = json_encode($compare), COOKIE_EXP, '/');

        $out['count'] = count($compare);
        echo printJSON($out);
        exit;
    }

    function getCompare() {
        $compare = array();
        if (isset($_COOKIE['compare'])) {
            $compare = (array) json_decode(stripslashes($_COOKIE['compare']), true);
        }
        return $compare;
    }

    function actCompare() {
        global $ST;

        $data = array();

        $compare = $this->getCompare();

        $cond = "WHERE i.id IN(" . implode(',', $compare) . ") AND of.region='{$this->getRegion()}' AND of.itemid=i.id";
        if ($cat = $this->getURIIntVal('cat')) {
            $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id=$cat");
            $cat_arr = array();
            if ($rs->next()) {
                if ($rs->get('cache_child_catalog_ids')) {
                    $cat_arr = explode(',', $rs->get('cache_child_catalog_ids'));
                }
            }
            $cat_arr[] = $cat;

            $cond.=" AND i.category IN (" . implode(',', $cat_arr) . ")";
        }
        $goods = array();
        $rs = $ST->select("SELECT *,i.price FROM sc_shop_item i,sc_shop_offer of $cond LIMIT 5");
        while ($rs->next()) {
            $goods[$rs->get('id')] = $rs->getRow();
        }
        $prop = array();
        if ($goods) {
            $q = "SELECT * FROM sc_shop_prop p,
				(SELECT field_value::numeric AS prop_grp,value_desc AS prop_grp_name,position FROM sc_enum WHERE field_name='sh_prop_grp') AS pg,
			
				sc_shop_prop_val v 
				WHERE p.grp=pg.prop_grp
				
				AND v.prop_id=p.id AND item_id IN (" . implode(',', array_keys($goods)) . ")
				
				ORDER BY pg.position,p.sort";

            $rs = $ST->select($q);



            while ($rs->next()) {
                $prop[$rs->get('grp')]['name'] = $rs->get('prop_grp_name');
                $prop[$rs->get('grp')]['prop'][$rs->get('prop_id')]['name'] = $rs->get('name');
                $prop[$rs->get('grp')]['prop'][$rs->get('prop_id')]['val'][$rs->get('item_id')] = $rs->get('value');
            }
        }


        $data['goods'] = $goods;
        $data['prop'] = $prop;

        $this->explorer[] = array('name' => 'Сравни');
        $this->setTitle('Сравни');
        $this->setHeader('Сравни');
        $this->display($data, dirname(__FILE__) . '/compare.tpl.php');
    }

    function actRemoveCompare() {
        global $get;
        $id = $get->getInt('id');
        $compare = array();
        if (isset($_COOKIE['compare'])) {
            $compare = (array) json_decode(stripslashes($_COOKIE['compare']), true);
        }
        $compare_new = array();
        foreach ($compare as $v) {
            if ($id != $v) {
                $compare_new[] = $v;
            }
        }
        setcookie('compare', $_COOKIE['compare'] = json_encode($compare_new), COOKIE_EXP, '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function actSrv1() {
        global $ST;
        $rs = $ST->select("SELECT * FROM sc_shop_item WHERE description<>''");
        while ($rs->next()) {
            $desc = preg_replace("/\r/", '', $rs->get('description'));
//			$desc=preg_replace('/\n+/',"\n",$desc);
            $ST->update('sc_shop_item', array('description' => trim($desc)), "id={$rs->get('id')}");
        }
    }

}
?>