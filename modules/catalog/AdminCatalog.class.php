<?php
include_once 'core/component/AdminListComponent.class.php';
include_once('modules/catalog/CatalogConfig.class.php');

class AdminCatalog extends AdminListComponent {

    function needAuth() {
        $access = array('GoodsPopup');
        if (in_array($this->getAction(), $access)) {
            $this->adminGrp[] = 'operator';
        }
        parent::needAuth();
    }

    function actMoveTo() {
        global $post, $ST;
        $item = $post->getArray('item');
        $to = $post->getInt('to');
        foreach ($item as $i) {
            if ($to != intval($i)) {
                $ST->update('sc_shop_catalog', array('parentid' => $to), "id=" . intval($i));
            }
        }
        $this->_updateCatalogCacheAll();
        echo printJSON(array('msg' => 'Перемещено'));
        exit;
    }

    function getCatalog() {
        global $ST, $CATALOG;
        if (isset($CATALOG)) {
            return $CATALOG;
        }
        $catalog = array();
        $order = 'ORDER BY ';
        $ord = $this->getURIVal('ord') != 'asc' ? 'asc' : 'desc';
        if (in_array($this->getURIVal('sort'), array('name', 'id', 'sort', 'main_sort', 'export', 'img'))) {
            $order.=$this->getURIVal('sort') . ' ' . $ord;
        } else {
            $order.='sort,parentid';
        }

        $queryStr = "SELECT * FROM sc_shop_catalog WHERE 1=1  $order ";
        $rs = $ST->select($queryStr);
        while ($rs->next()) {
            foreach ($rs->getRow() as $k => $v) {
                $item[$rs->getInt('id')][$k] = $v;
            }
            if ($rs->getInt('parentid')) {
                $item[$rs->getInt('parentid')]['children'][$rs->getInt('id')] = &$item[$rs->getInt('id')];
            } else {
                $catalog[$rs->getInt('id')] = &$item[$rs->getInt('id')];
            }

//			$item['children']=$this->getCatalog($item['id']);
//			$catalog[]=$item;
        }
        return $CATALOG = $catalog;
    }

    function actDefault() {
        global $ST;
        $catalog = $this->getCatalog();

        $catalog_count = array();
        $queryStr = "SELECT count(id) as c,category FROM sc_shop_item GROUP BY category";
        $rs = $ST->select($queryStr);
        while ($rs->next()) {
            $catalog_count[$rs->get('category')] = $rs->get('c');
        }

        $catalog_counts = array();
        $queryStr = "SELECT count(itemid) as c,catid FROM sc_shop_item2cat GROUP BY catid";
        $rs = $ST->select($queryStr);
        while ($rs->next()) {
            $catalog_counts[$rs->get('catid')] = $rs->get('c');
        }

        $data['catalog'] = $catalog;
        $data['count'] = $catalog_count;
        $data['counts'] = $catalog_counts;

        $this->display($data, dirname(__FILE__) . '/admin_catalog.tpl.php');
    }

    function actSaveCatalogSort() {
        global $ST, $post;
        $export = $post->getArray('export');
        $sort = $post->getArray('sort');
        foreach ($post->getArray('main_sort') as $k => $v) {
            $d = array('main_sort' => $v, 'sort' => $sort[$k],
                'export' => in_array($k, $export) ? 1 : 0,
            );
            $ST->update('sc_shop_catalog', $d, "id=$k");
        }


        echo printJSON(array('msg' => 'Сохранено'));
        exit;
    }

    function actCatalogEdit() {
        global $ST, $get;
        $id = $get->getInt('id');
        $field = array(
            'id' => $id,
            'name' => '',
            'img' => '',
            'description' => '',
//			'color'=>'',
            'extraid' => '',
            'parentid' => $get->getInt('parent')
        );

        if ($id) {
            $rs = $this->getStatement()->execute("SELECT " . join(',', array_keys($field)) . " FROM sc_shop_catalog WHERE id=" . $id);
            if ($rs->next()) {
                $field = $rs->getRow();
            }
        }
        $field['catalog'] = $this->getCatalog();


        $this->setTitle('Редактировать каталог');
        $this->explorer[] = array('name' => 'Редактировать');

        $this->display($field, dirname(__FILE__) . '/admin_catalog_edit.tpl.php');
    }

    function actCatalogSave() {
        global $ST, $post;

        /* Сохранение */
        $id = $post->getInt('id');
        $field = array(
            'name' => $post->get('name'),
            'img' => $post->get('img'),
            'description' => $post->get('description'),
//			'color'=>$post->get('color'),
//			'extraid'=>$post->get('extraid'),
            'parentid' => $post->getInt('parentid')
        );


        if (isImg($_FILES['upload']['name'])) {
            $path = $this->cfg('CATALOG_PATH') . '/' . md5($_FILES['upload']['tmp_name']) . "." . file_ext($_FILES['upload']['name']);

            move_uploaded_file($_FILES['upload']['tmp_name'], ROOT . $path);
            $field['img'] = $path;
        }
        if ($post->getInt('clear')) {
            $field['img'] = '';
        }

        if ($id) {
            $ST->update('sc_shop_catalog', $field, "id=" . $id);
            $this->clearCache();
        } else {
            $id = $ST->insert('sc_shop_catalog', $field);
            $queryStr = "UPDATE sc_shop_catalog SET sort=id WHERE id=" . $id;
            $ST->executeUpdate($queryStr);
            $this->_updateCatalogCacheAll();
        }

        if ($post->exists('all')) {
            $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id=" . $id);
            if ($rs->next()) {
                if ($rs->get('cache_child_catalog_ids')) {
                    $arr = explode(',', $rs->get('cache_child_catalog_ids'));
                    if ($arr) {
                        $ST->update('sc_shop_catalog', array('extraid' => $field['extraid']), "id IN(" . join(',', $arr) . ")");
                    }
                }
            }
        }
        echo printJSONP(array('msg' => 'Сохранено', 'id' => $id) + $field);
        exit;
    }

    function actOnMove() {
        global $get;
        if ($this->getMoveId() != $get->getInt('id')) {
            $this->setMoveId($get->getInt('id'));
        } else {
            $this->setMoveId(0);
        }
        header('Location: .');
        exit;
    }

    function actSetPosition() {
        global $ST, $get;
        $id = $get->getInt('id');
        $move = $get->get('move');

        $rs = $ST->select("select * from sc_shop_catalog where id=" . $id);
        if ($rs->next()) {
            $condition = "parentid='" . $rs->get("parentid") . "'";
            if ($move == "up") {
                $ST->up('sc_shop_catalog', $id, $condition, 'id', 'sort');
            }
            if ($move == "down") {
                $ST->down('sc_shop_catalog', $id, $condition, 'id', 'sort');
            }
        }
        $this->clearCache();
        header('Location: .');
        exit;
    }

//	function onState(ArgumentList $args){
//		$this->getStatement()->update('sc_catalog',array('state'=>$args->getArgument('state')),'id='.$args->getInt('id'));
//		header('Location: .');exit;
//	}

    function actOnChange() {
        global $ST, $get;

        if ($this->getMoveId()) {
            $ST->update('sc_shop_catalog', array('parentid' => $get->getInt('id')), "id=" . $this->getMoveId());
            $this->_updateCatalogCacheAll();
            $this->setMoveId(0);
        }
        header('Location: .');
        exit;
    }

    function actOnRemove() {
        global $ST, $get, $post;
        if ($get->getInt('id')) {

            $ST->delete("sc_shop_catalog", "id=" . $get->getInt('id'));
            $ST->update("sc_shop_catalog", array('parentid' => 0), "parentid=" . $get->getInt('id'));
            $this->_updateCatalogCacheAll();
            header('Location: .');
            exit;
        } elseif ($item = $post->getArray('item')) {
            $ST->delete("sc_shop_catalog", "id IN(" . implode(',', $item) . ")");
            $ST->update("sc_shop_catalog", array('parentid' => 0), "parentid IN(" . implode(',', $item) . ")");
            echo printJSON(array('msg' => 'Удалено!'));
            exit;
        }
    }

    function actSetDistributor() {
        global $ST, $post;
        $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id={$post->getInt('itemid')}");
        $ids = array();
        if ($rs->next()) {
            if ($ids = explode(',', $rs->get('cache_child_catalog_ids'))) {
                $ids[] = $rs->getInt('id');

                $ST->update('sc_shop_catalog', array('distributor' => $post->getInt('distributor')), "id IN (" . implode(',', $ids) . ")");
            }
        }
        echo printJSON(array('msg' => 'Обновлено ' . count($ids) . ' записей', 'ids' => $ids));
        exit;
    }

    function actUpload() {
        global $get;
        $paths = array();
        $imgs = array();
        if (isset($_FILES['upload'])) {
            foreach ($_FILES['upload']['tmp_name'] as $n => $tmp_name) {
                $name = md5_file($tmp_name) . '.' . file_ext($_FILES['upload']['name'][$n]);
                $path = '/storage/temp/' . $name;
                move_uploaded_file($tmp_name, ROOT . $path);
                $img = scaleImg($path, $get->get('size'));
                if ($get->get('resize') == 'true') {
                    $path = scaleImg($path, $get->get('size'));
                }
                $paths[] = $path;
                $imgs[] = $img;
            }
        }
        echo printJSONP(array('msg' => 'Сохранено', 'paths' => $paths, 'imgs' => $imgs), $get->get('cb'));
        exit;
    }

    function reCountCatalogIds($id) {
        $result = array();
        $rs = $this->getStatement()->execute("SELECT * FROM sc_shop_catalog WHERE parentid=" . $id . "");
        while ($rs->next()) {
            $result = array_merge($result, $this->reCountCatalogIds($rs->getInt('id')));
            $result[] = $rs->getInt('id');
        }
        return $result;
    }

    function _updateCatalogCache($id) {
        global $ST;
        $result = implode(',', $this->reCountCatalogIds($id));
        $ST->update('sc_shop_catalog', array('cache_child_catalog_ids' => $result), "id=" . $id);
    }

    function _updateCatalogCacheAll() {
        global $ST;
        $rs = $ST->select("SELECT * FROM sc_shop_catalog");
        while ($rs->next()) {
            $this->_updateCatalogCache($rs->getInt('id'));
        }
        $this->clearCache();
    }

    function clearCache() {
        $this->cacheMethodClear('_updateCatalog');
    }

    function actUpdateCatalogCache() {
        $this->_updateCatalogCacheAll();

        echo printJSON(array('msg' => 'Применено!'));
        exit();
    }

    ///////////////////////////////////
    //товары
    ///////////////////////////////////
    protected $catRef = array();
    public $catId = 0;
    public $search = '';

    function setSearch($search) {
        $_SESSION['__SEARCH'] = $search;
    }

    function getSearch() {
        return isset($_GET['search']) ? $_GET['search'] : '';
        return isset($_SESSION['__SEARCH']) ? $_SESSION['__SEARCH'] : '';
    }

    function actSearch() {
        global $post;
//		$this->setSearch($post->get('search'));

        $data = parse_url($_SERVER['HTTP_REFERER']);
        $p = array();
        if (!empty($data['query'])) {
            parse_str($data['query'], $p);
        }


        $this->setURI($data['path']);
        if ($post->get('search')) {
            $p['search'] = $post->get('search');
        } else {
            unset($p['search']);
        }
        if ($post->get('prop_val')) {
            $p['prop_val'] = $post->get('prop_val');
        } else {
            unset($p['prop_val']);
        }

        $url = $this->getURI(null, $p);

        header("Location: $url");
        exit;
    }

    function getGoods() {
        global $ST, $get, $post;
        if ($get->exists('category')) {
            $this->catId = $get->getInt('category');
        }

        parent::refresh();

        $catIds = array();
        if ($this->catId) {
            $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id=" . $this->catId);
            if ($rs->next()) {
                if (trim($rs->get('cache_child_catalog_ids'))) {
                    $catIds = explode(',', $rs->get('cache_child_catalog_ids'));
                }
            }
            $catIds[] = $this->catId;
        }

        $condition = "WHERE 1=1 ";

        if ($this->getUser('type') == 'vendor') {
            $condition.=" AND i.vendor={$this->getUserId()}";
        }

        if ($this->getUser('type') == 'curator') {
            $condition.=" AND i.vendor IN (SELECT u_id FROM sc_users WHERE type='vendor' AND curator={$this->getUserId()})";
        }

        if ($prop = $get->get('prop')) {
            $cc = '';
            if ($prop_val = $get->get('prop_val')) {
                $prop_val = SQL::slashes($prop_val);
                $cc = " AND value='$prop_val'";
            }
            $condition.=" AND EXISTS (SELECT item_id FROM sc_shop_prop_val WHERE prop_id={$prop} AND i.id=item_id $cc)";
        }

        if ($catIds) {
            $condition.=" AND (
				category IN('" . join("','", $catIds) . "')
			 	OR EXISTS(SELECT itemid FROM sc_shop_item2cat WHERE itemid=i.id AND catid IN('" . join("','", $catIds) . "'))
			)";
        }
        if ($search = SQL::slashes(trim(strtolower($this->getSearch())))) {
            $condition.=" AND (LOWER(i.name) LIKE '%$search%' OR (product='$search' AND product<>'0') )";
        }

        if ($man = $this->getURIIntVal('man')) {
            $condition.=" AND manufacturer_id =$man";
        }

        $query = "SELECT count(*) AS c FROM sc_shop_item i " . $condition;
        $rs = $ST->select($query);
        if ($rs->next()) {
            $this->page->all = $rs->getInt('c');
        }


        $order = 'ORDER BY ';
        $ord = $this->getURIVal('ord') != 'asc' ? 'asc' : 'desc';

        if (in_array($this->getURIVal('sort'), array('name', 'price', 'c_name', 'manufacturer', 'update_time', 'insert_time', 'm_name', 'img', 'sort', 'sort_main', 'sort1', 'sort2', 'sort3', 'in_stock', 'confirm', 'bonus'))) {
            $order.=$this->getURIVal('sort') . ' ' . $ord;
        } else {
            $order.='category ,i.name';
        }

        $queryStr = "SELECT c.name AS c_name,m.name AS m_name,i.*,u.value_desc AS unit,r.rc FROM 
		sc_shop_item i
		LEFT JOIN sc_shop_catalog AS c ON i.category=c.id
		LEFT JOIN  sc_manufacturer AS m ON i.manufacturer_id=m.id
		LEFT JOIN (SELECT * FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=i.unit
		LEFT JOIN (SELECT COUNT(id) AS rc,par FROM sc_shop_relation GROUP BY par ) as r ON r.par=i.id 
		$condition $order LIMIT " . $this->page->getBegin() . "," . $this->page->per;
        $data['rs'] = $ST->select($queryStr);
        $data['catalog'] = $this->catRef = $this->getCatalog();
        return $data;
    }

    function actGoodsPopup() {


        $data = $this->getGoods();
        $this->tplContainer = 'template/admin/pages/admin_popup.php';
        $this->display($data, dirname(__FILE__) . '/admin_goods_popup.tpl.php');
    }

    function actGoods() {
        global $get;
        $data = $this->getGoods();

        $data['price_expr'] = 'PRICE+10';
        if (!empty($_COOKIE['price_expr'])) {
            $data['price_expr'] = $_COOKIE['price_expr'];
        }
        $this->setTitle('Товары');
        $this->explorer[] = array('name' => 'Товары');
        $data['shop_relation'] = $this->enum('shop_relation');



        $data['selected'] = $get->getInt('category');
//		$data['propList']=$this->getGoodsPropList(0,$data['selected']);

        $this->display($data, dirname(__FILE__) . '/admin_goods.tpl.php');
    }

    function actGoodsImg() {
        global $ST;

        $rs = $ST->select("
		SELECT *,c.name as c_name,i.name as i_name,i.img ,i.id 
		FROM sc_shop_item i,sc_shop_catalog c 
		WHERE  i.category=c.id AND price>0 AND in_stock>0 
		ORDER BY i.img<>'' DESC, category
		")->toArray();

        $data['rs'] = $rs;
//		header('Content-Type: application/vnd.ms-word');
//        header('Content-Disposition: attachment; filename=result.htm');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');	
        echo $this->render($data, dirname(__FILE__) . '/admin_goods_img.doc.php'); //exit;
    }

    function actSaveSimular() {
        global $ST, $post;

        foreach ($ids = $post->getArray('item') as $id) {
            foreach ($post->getArray('sel_item') as $sel_id) {
                if ($id != $sel_id) {
                    if ($post->get('mode') == 'join') {
                        $ST->insert('sc_shop_relation', array('type' => 1, 'par' => $id, 'ch' => $sel_id));
                    } else {
                        $ST->delete('sc_shop_relation', 'type=1 AND (par=' . $id . ' AND ch=' . $sel_id . ')');
                    }
                }
            }
        }
        echo printJSON($post->getArray('item'));
        exit;
    }

    function actSaveRelated() {
        global $ST, $post;

        foreach ($ids = $post->getArray('item') as $id) {
            foreach ($post->getArray('sel_item') as $sel_id) {
                if ($id != $sel_id) {
                    if ($post->get('mode') == 'join') {
                        $ST->insert('sc_shop_relation', array('type' => 2, 'par' => $id, 'ch' => $sel_id));
                    } else {
                        $ST->delete('sc_shop_relation', 'type=2 AND (par=' . $id . ' AND ch=' . $sel_id . ')');
                    }
                }
            }
        }
        echo printJSON($post->getArray('item'));
        exit;
    }

    function actGetRelation() {
        global $ST, $post;
        $q = "SELECT *,r.id,i.img,c.name AS cname,i.name FROM sc_shop_item i
			LEFT JOIN sc_shop_catalog AS c ON i.category=c.id,
			sc_shop_relation r 
			WHERE  r.type={$post->getInt('type')} AND r.par={$post->getInt('goods_id')} AND i.id=r.ch";
        $rs = $ST->select($q);
        $res = array();
        while ($rs->next()) {
            $res[] = array(
                'id' => $rs->get('id'),
                'name' => $rs->get('name'),
                'cname' => $rs->get('cname'),
                'img' => $rs->get('img') ? scaleImg($rs->get('img'), 'w50h50') : '',
            );
        }
        echo printJSON($res);
        exit;
    }

    function actSaveRelation() {
        global $ST, $post;
        $type = $post->getInt('relation');
        foreach ($ids = $post->getArray('item') as $id) {
            foreach ($post->getArray('sel_item') as $sel_id) {
                if ($id != $sel_id) {
                    if ($post->get('mode') == 'join') {
                        $ST->insert('sc_shop_relation', array('type' => $type, 'par' => $id, 'ch' => $sel_id));
                    } else {
                        $ST->delete('sc_shop_relation', 'type=' . $type . ' AND (par=' . $id . ' AND ch=' . $sel_id . ')');
                    }
                }
            }
        }
        echo printJSON($post->getArray('item'));
        exit;
    }

    function actRemoveRelation() {
        global $ST, $post;
        $id = $post->getInt('id');
        $rel_item = $post->getArray('rel_item');
        if ($rel_item) {
            $ST->delete('sc_shop_relation', "id IN(" . implode(',', $rel_item) . ")");
        }


        echo printJSON(array('msg' => "Удалено " . count($rel_item) . " связей"));
        exit;
    }

    /* функция перемещения в категорию */

    function actRemoveToCategory() {
        global $ST, $post, $get;
        $ids = $post->get('item');
        if (!empty($ids)) {
            $category_id = $post->getInt('category');

            if ($get->get('mode') == 'copy') {
                $ST->delete("sc_shop_item2cat", "catid=$category_id AND itemid IN (" . implode(',', $ids) . ")");
                foreach ($ids as $id) {
                    $ST->insert("sc_shop_item2cat", array('catid' => $category_id, 'itemid' => $id));
                }
                echo printJSON(array('msg' => "Товары скопированы в категорию с индексом " . $category_id));
                exit;
            } elseif ($get->get('mode') == 'remove') {
                $ST->delete("sc_shop_item2cat", "catid=$category_id AND itemid IN (" . implode(',', $ids) . ")");
                echo printJSON(array('msg' => "Товары извлечены из категории с индексом " . $category_id));
                exit;
            } else {
                foreach ($ids as $id) {
                    $ST->update("sc_shop_item", array('category' => $category_id), 'id=' . $id);
                }
                echo printJSON(array('msg' => "Товары перемещены в категорию с индексом " . $category_id));
                exit;
            }
        }
    }

    /**
     * Функция назначения свойств
     */
    function actSetGoodsProp() {
        global $ST, $post;
        $ids = $post->getArray('item');
        $pvalue = $post->getArray('pvalue');
        $remove = $post->getArray('remove');
        if (!empty($ids)) {
//        	foreach($ids as $id) {
//        		foreach ($pvalue as $k=>$v){
//        			if($v=trim($v)){
//	        			$ST->delete("sc_shop_prop_val", "item_id=$id AND prop_id=$k");
//	        			if($post->get('mode')!='remove'){
//	        				$rs=$ST->select("SELECT type FROM sc_shop_prop WHERE id=$k");
//	        				if($rs->next() && $rs->get('type')==3){
//	        					$v=floatval(str_replace(',','.',$v));
//	        				}
//        					$ST->insert("sc_shop_prop_val",array('item_id'=>$id,'prop_id'=>$k,'value'=>$v));
//        				}
//        			}
//        		}
//        		if($post->get('mode')=='remove'){
//        			foreach ($remove as $k) {
//	        			$ST->delete("sc_shop_prop_val", "item_id=$id AND prop_id=$k");
//	        		}
//        		}	
//            }


            foreach ($ids as $id) {
                if ($post->get('mode') == 'remove') {
                    foreach ($remove as $k) {
                        $ST->delete("sc_shop_prop_val", "item_id=$id AND prop_id=$k");
                    }
                } else {
                    foreach ($remove as $k) {
                        $v = '';
                        if (isset($pvalue[$k])) {
                            $v = $pvalue[$k];
                        }
                        if ($v = trim($v)) {
                            $rs = $ST->select("SELECT type FROM sc_shop_prop WHERE id=$k");
                            if ($rs->next() && $rs->get('type') == 3) {
                                $v = floatval(str_replace(',', '.', $v));
                            }
                            $rs = $ST->select("SELECT * FROM sc_shop_prop_val WHERE item_id=$id AND prop_id=$k");
                            if ($rs->next()) {
                                if ($rs->get('value') != $v) {
                                    $ST->update("sc_shop_prop_val", array('value' => $v), "item_id=$id AND prop_id=$k");
                                }
                            } else {
                                $ST->insert("sc_shop_prop_val", array('item_id' => $id, 'prop_id' => $k, 'value' => $v));
                            }
                        } else {
                            $ST->delete("sc_shop_prop_val", "item_id=$id AND prop_id=$k");
                        }
                    }
                }
            }
        }
        $new_prop_name = $post->getArray('new_prop_name');
        $new_prop_value = $post->getArray('new_prop_value');
        foreach ($new_prop_name as $n => $pname) {
            if (!empty($new_prop_value[$n])) {
                $rs = $ST->select("SELECT * FROM sc_shop_prop WHERE name='" . SQL::slashes($pname) . "'");
                if ($rs->next()) {
                    $pid = $rs->getInt('id');
                    if ($rs->get('type') == 3) {
                        $new_prop_value[$n] = floatval(str_replace(',', '.', $new_prop_value[$n]));
                    }
                } else {
                    $pid = $ST->insert('sc_shop_prop', array('name' => $pname));
                }
                foreach ($ids as $id) {
                    $ST->delete("sc_shop_prop_val", "item_id=$id AND prop_id=$pid");
                    $ST->insert("sc_shop_prop_val", array('item_id' => $id, 'prop_id' => $pid, 'value' => $new_prop_value[$n]));
                }
            }
        }

        echo printJSON(array('msg' => "Для " . count($ids) . " товаров обновлено " . (count($remove) + count($new_prop_name)) . " свойств"));
        exit;
    }

    /**
     * Функция назначения производителя
     */
    function actSetGoodsMan() {
        global $ST, $post;
        $ids = $post->getArray('item');
        $value = $post->getInt('manufecturer');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $ST->update('sc_shop_item', array('manufacturer_id' => $value), 'id=' . $id);
            }
        }
        echo printJSON(array('msg' => "Для " . count($ids) . " товаров обновлено "));
        exit;
    }

    function actSetGoodsAction() {
        global $ST, $post;
        $ids = $post->getArray('item');
        $value = $post->getInt('action');
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $ST->update('sc_shop_item', array('sort1' => $value), 'id=' . $id);
            }
        }
        echo printJSON(array('msg' => "Для " . count($ids) . " товаров обновлено "));
        exit;
    }

    function actSetPrice() {
        global $ST, $post;
        $ids = $post->get('item');
        $res = array();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $rs = $ST->select("SELECT price FROM sc_shop_item WHERE id=" . intval($id));
                if ($rs->next()) {
                    $new_price = floatval(@eval('return ' . str_replace('PRICE', $rs->get('price'), $post->get('price_expr')) . ';'));
                    if ($new_price > 0) {
                        $ST->update('sc_shop_item', array('price' => $new_price), 'id=' . $id);
                        $res[$id] = $new_price;
                    }


                    $rs = $ST->select("SELECT * FROM sc_shop_item_nmn WHERE itemid=" . intval($id) . " AND hidden=0");
                    while ($rs->next()) {
                        $new_price = floatval(@eval('return ' . str_replace('PRICE', $rs->get('price'), $post->get('price_expr')) . ';'));
                        if ($new_price > 0) {
                            $ST->update('sc_shop_item_nmn', array('price' => $new_price), 'id=' . $rs->getInt('id'));
//							$res[$id]=$new_price;
                        }
                    }
                }
            }
        }
        setcookie('price_expr', $post->get('price_expr'), time() + 3600 * 24 * 30, '/');
        echo printJSON($res);
        exit;
    }

//	function actSetSort_old(){
//		global $ST,$post;
//		$ids=$post->get('sort');
//		$ids_print=$post->get('sort_print');
//		$res=array();
//		if(!empty($ids)){
//			foreach ($ids as $id=>$val){
//				$val_print=$ids_print[$id];
//				$ST->update('sc_shop_item',array('sort'=>intval($val),'sort_print'=>$val_print),'id='.intval($id));
//			}
//		}
//		echo printJSON($res);exit;
//	}
    function actSetSort() {
        global $ST, $post;
        $res = array();
        if ($this->isAdmin()) {
            $sort_main = $post->get('sort_main');
            $sort = $post->get('sort');
            $sort1 = $post->get('sort1');
            $sort2 = $post->get('sort2');
            $sort3 = $post->get('sort3');
            $ext_id = $post->get('ext_id');
            $in_stock = $post->get('in_stock');
            $confirm = $post->get('confirm');
            $price = $post->get('price');
            $bonus = $post->get('bonus');
            $export = $post->getArray('export');

            if (!empty($sort1)) {
                foreach ($sort1 as $id => $val) {
                    $d = array(
                        'sort1' => intval($val),
                        'export' => in_array($id, $export) ? 1 : 0,
                    );
                    if (isset($sort_main[$id])) {
                        $d['sort_main'] = $sort_main[$id];
                    }
                    if (isset($sort[$id])) {
                        $d['sort'] = $sort[$id];
                    }
                    if (isset($sort2[$id])) {
                        $d['sort2'] = $sort2[$id];
                    }
                    if (isset($sort3[$id])) {
                        $d['sort3'] = $sort3[$id];
                    }
                    if (isset($ext_id[$id])) {
                        $d['ext_id'] = $ext_id[$id];
                    }
                    if (isset($in_stock[$id])) {
                        $d['in_stock'] = $in_stock[$id];
                    }
                    if (isset($price[$id])) {
                        $d['price'] = $price[$id];
                    }


                    $ST->update('sc_shop_item', $d, 'id=' . intval($id));
                }
            }
        }
        echo printJSON($res);
        exit;
    }

    function actGoodsEdit() {
        global $ST, $get;
        $id = $get->getInt('id');
        $field = array(
            'id' => $id,
            'name' => '',
            'product' => '',
            'manufacturer' => '',
            'manufacturer_id' => 0,
            'category' => $get->getInt('category'),
            'description' => '',
            'html' => '',
            'html2' => '',
            'html3' => '',
            'html4' => '',
            'img' => '',
            'img_add' => '',
            'img_format' => 0,
            'price' => 0,
            'old_price' => 0,
            'awards' => 0,
            'unit' => '',
            'ext_id' => '',
            'in_stock' => 1,
            'weight_flg' => 0,
            'sort3' => 1, //новинка		
            'sort1' => 0, //Акция		
        );
        if ($this->cfg('SHOP_GOODS_NEED_CONFIRM')) {//Настройка показывать только в подтверждённые
            $field['confirm'] = 0;
        }

        if ($id) {
            $rs = $ST->select("SELECT " . join(',', array_keys($field)) . " FROM sc_shop_item WHERE id='" . $id . "'");
            if ($rs->next()) {
                $field = $rs->getRow();
            }
        }

        $field['imgList'] = array();
        if (trim($field['img'])) {
            $field['imgList'][] = $field['img'];
        }

        if ($img = trim($field['img_add'])) {
            $img = explode(',', $img);
            foreach ($img as $i) {
                $field['imgList'][] = $i;
            }
        }

        $field['img_format_list'] = Img::getFormatList();

        $field['unit_list'] = $this->enum('sh_unit');
        $field['catalog'] = $this->getCatalog();

        $field['catalogs'] = $this->getCatalog();
        $field['actions'] = LibNews::getActions();

        $field['categories'] = array();
        $rs = $ST->select("SELECT * FROM sc_shop_item2cat WHERE itemid=$id");
        while ($rs->next()) {
            $field['categories'][] = $rs->getInt('catid');
        }

        $field['offerList'] = array();

        /*
          Разбивка по предложениям (регион или магазин)
          $reg=$this->getUser('region');
          if($reg){
          $reg=explode(',',$reg);
          }

          $q="SELECT * FROM sc_enum r
          LEFT JOIN (SELECT * FROM sc_shop_offer WHERE itemid={$id}) AS so ON so.region=r.field_value
          WHERE r.field_name='regions'
          ORDER BY r.position
          ";
          $rs=$ST->select($q);
          while ($rs->next()) {
          if($reg && !in_array($rs->get('field_value'),$reg)){
          continue;
          }

          $field['offerList'][$rs->get('field_value')]=array('in_stock'=>$rs->get('in_stock'),'price'=>$rs->get('price'),'reg'=>$rs->get('value_desc'));
          } */
//		
//		$q="SELECT *,p.id as prop_id FROM sc_shop_prop p
//			LEFT JOIN (SELECT * FROM sc_shop_prop_val WHERE item_id={$id}) AS v ON v.prop_id=p.id
//			WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id AND i.category={$field['category']}) OR p.grp=1 OR p.grp=2)
//			ORDER BY p.grp";
//		$rs=$ST->select($q);
        $field['propList'] = $this->getGoodsPropList($id, $field['category']);

        $field['nmnList'] = array();

        $rs = $ST->select("SELECT * FROM sc_shop_item_nmn WHERE itemid=$id AND hidden=0 ORDER BY sort, price");
        while ($rs->next()) {
            $field['nmnList'][$rs->get('id')] = $rs->getRow();
        }

        $field['prop_grp'] = $this->enum('sh_prop_grp');

        $field['prop_grp'] = printJSON($field['prop_grp']);

        $field['shop_relation'] = $this->enum('shop_relation');

        $field['manList'] = $this->getGoodsManList(); //Список производителей

        $vendorList = array();

        $this->setTitle('Редактировать ' . $field['name']);
        $this->explorer[] = array('name' => 'Редактировать ' . $field['name']);
        $this->explorer[] = array('name' => 'Товары', 'url' => $this->mod_uri . 'goods/');

        $this->display($field, dirname(__FILE__) . '/admin_goods_edit.tpl.php');
    }

    function getGoodsPropList($itemid = 0, $category = 0) {
        global $ST, $get;
        $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc FROM sc_shop_prop p
			LEFT JOIN (SELECT * FROM sc_shop_prop_val WHERE item_id={$itemid}) AS v ON v.prop_id=p.id
			LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
			WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id AND i.category={$category}))
			ORDER BY p.grp";
        //23.01.2013
        if ($itemid) {
            $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc FROM sc_shop_prop p
			LEFT JOIN (SELECT * FROM sc_shop_prop_val WHERE item_id={$itemid}) AS v ON v.prop_id=p.id
			LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
			
			ORDER BY p.grp
			";
            if (is_array($itemid)) {
                $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc FROM sc_shop_prop p
				LEFT JOIN (SELECT * FROM sc_shop_prop_val WHERE item_id IN(" . implode(',', $itemid) . ")) AS v ON v.prop_id=p.id
				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
				WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id AND i.category={$category}))
				ORDER BY p.grp";


                $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc,'' AS value FROM sc_shop_prop p
				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
				WHERE p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v WHERE v.item_id  
				IN(" . implode(',', $itemid) . ") ) 
				ORDER BY p.grp
				";

                $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc,'' AS value FROM sc_shop_prop p
				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
				WHERE p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v WHERE 
				v.item_id IN(SELECT id FROM sc_shop_item WHERE category IN (SELECT category FROM sc_shop_item WHERE id IN (" . implode(',', $itemid) . ") ) ) ) 
				ORDER BY p.grp
				";
            } else {
                $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc FROM sc_shop_prop p
				LEFT JOIN (SELECT * FROM sc_shop_prop_val WHERE item_id={$itemid}) AS v ON v.prop_id=p.id
				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
				WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id AND i.category={$category}))
				ORDER BY p.grp";
            }
        } else {
            $q = "SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc,'' AS value FROM sc_shop_prop p
				
				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
				WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id AND i.category={$category}))
				ORDER BY p.grp
			";
        }
//		if($get->get('prop')){
//			$q="SELECT *,p.id as prop_id,pgrp.value_desc AS pgrp_desc,'' AS value FROM sc_shop_prop p
//				
//				LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp') AS pgrp ON pgrp.field_value=p.grp
//				
//				WHERE (p.id IN(SELECT DISTINCT prop_id FROM sc_shop_prop_val v,sc_shop_item i WHERE i.id=v.item_id 
//					AND EXISTS (SELECT i1.id FROM sc_shop_prop_val v1,sc_shop_item i1 WHERE i1.id=v1.item_id AND i1.id=i.id AND v1.prop_id={$get->getInt('prop')} )
//					))
//				
//				ORDER BY p.grp
//			";
//		}

        $rs = $ST->select($q);
        $propList = array();
        while ($rs->next()) {
            $propList[$rs->get('prop_id')] = $rs->getRow();
        }
        return $propList;
    }

    function actGetPropList() {
        global $ST, $post;
        $item = $post->getArray('item');

        $data['propList'] = $this->getGoodsPropList($item);
        $data['popup_mode'] = true;
        echo $this->render($data, dirname(__FILE__) . "/admin_goods_edit_prop.tpl.php");
    }

    function getGoodsManList() {
        global $ST;
        $q = "SELECT * FROM  sc_manufacturer
			WHERE sort>-1
			ORDER BY sort,name
		";

        $rs = $ST->select($q);
        $manList = array();
        while ($rs->next()) {
            $manList[$rs->get('id')] = $rs->getRow();
        }
        return $manList;
    }

    function getGoodsActionList() {
        global $ST;
        $q = "SELECT * FROM  sc_news
			WHERE state='public' AND type='action'
			ORDER BY title";

        $rs = $ST->select($q);
        $list = array();
        while ($rs->next()) {
            $list[$rs->get('id')] = $rs->getRow();
        }
        return $list;
    }

    function actGoodsSave() {
        global $ST, $post;
        /* Сохранение */
        $id = $post->getInt('id');
        if ($post->get('mode') == 'save_as_new') {
            $id = 0;
        }
        $field = array(
//			'id'=>$id,
            'name' => $post->get('name'),
            'product' => $post->get('product'),
            'category' => $post->getInt('catalog'),
            'description' => $post->get('description'),
            'html' => $post->get('html'),
            'html2' => $post->get('html2'),
            'html3' => $post->get('html3'),
            'html4' => $post->get('html4'),
            'weight_flg' => $post->getInt('weight_flg'),
            'price' => $post->getFloat('price'),
            'old_price' => $post->getFloat('old_price'),
            'awards' => $post->getFloat('awards'),
            'in_stock' => $post->getInt('in_stock'),
            'sort3' => $post->getInt('sort3'), //Новинки
            'sort1' => $post->getInt('sort1'), //Акции
            'ext_id' => $post->get('ext_id'),
            'img_format' => $post->get('img_format'), //Тип вставки изображения
            'unit' => $post->get('unit'), //Единица стоимости
        );
        if ($post->exists('manufacturer')) {
            $field['manufacturer'] = $post->get('manufacturer');
        }
        if ($post->exists('manufacturer_id')) {
            $field['manufacturer_id'] = $post->getInt('manufacturer_id');
        }

        $field['img'] = '';
        $field['img_add'] = '';
        if ($img_list = $post->getArray('img')) {

            if ($img_sort = $post->getArray('sort')) {
                asort($img_sort);

                $temp_img = array();
                foreach ($img_sort as $k => $v) {
                    $temp_img[] = $img_list[$k];
                }
                $img_list = $temp_img;
            }

            foreach ($img_list as $n => &$img) {
                $from = ROOT . $img;
                $name = basename($from);

                $path = $this->cfg('CATALOG_PATH') . '/goods/' . $name;

                if (file_exists(ROOT . $img) && !file_exists(ROOT . $path)) {
                    rename($from, ROOT . $path);
                }
                $img = $path;
                if ($n == 0) {
                    $field['img'] = $img;
                } else {
                    $field['img_add'].=',' . $img;
                }
            }
            $field['img_add'] = trim($field['img_add'], ',');
        }


        if ($id) {
            $rs = $ST->select("SELECT * FROM sc_shop_item WHERE id=$id"); //Сохранение товара
            if ($rs->next()) {
                $this->logData('goods', $rs->getRow());
            }
            $field['update_time'] = date('Y-m-d H:i:s');
            $ST->update('sc_shop_item', $field, "id='" . $id . "'");
        } else {
            $field['insert_time'] = date('Y-m-d H:i:s');
            $id = $ST->insert('sc_shop_item', $field);
        }

        if ($id) {


            $nmn_price = $post->getArray('nmn_price');
            $nmn_description = $post->getArray('nmn_description');
            $nmn_sort = $post->getArray('nmn_sort');
            $ST->update('sc_shop_item_nmn', array('hidden' => 1), "itemid=$id");

            foreach ($nmn_price as $n => $price) {
                $price = (int) $price;
                $rs = $ST->select("SELECT * FROM sc_shop_item_nmn WHERE itemid=$id AND price=$price AND description='" . SQL::slashes($nmn_description[$n]) . "'");
                $d = array(
                    'itemid' => $id,
                    'price' => $price,
                    'description' => $nmn_description[$n],
                    'sort' => (int) $nmn_sort[$n],
                    'hidden' => 0,
                );
                if ($rs->next()) {
                    $ST->update('sc_shop_item_nmn', $d, "id={$rs->getInt('id')}");
                } else {
                    $ST->insert('sc_shop_item_nmn', $d);
                }
            }

            /*
              разбивка по предложениям (регион или поставщик)
              $in_stock=$post->getArray('in_stock');
              $ST->delete('sc_shop_offer',"itemid=$id AND region IN('".implode("','",array_keys($in_stock))."')");

              foreach ($in_stock as $reg=>$val){
              $ST->insert('sc_shop_offer',array('itemid'=>$id,'region'=>$reg,'in_stock'=>(int)$val),null);

              }

              $rs=$ST->select("SELECT SUM(in_stock) AS s FROM sc_shop_offer WHERE itemid=$id");
              if($rs->next()){
              $ST->update('sc_shop_item',array('in_stock'=>$rs->getFloat('s')),"id='".$id."'");
              } */

            //23.01.2013
            $pvalue = $post->getArray('pvalue');
            foreach ($pvalue as $propid => $value) {
                $type = 0;
                $rs = $ST->select("SELECT * FROM sc_shop_prop WHERE id=$propid");
                if ($rs->next()) {
                    $type = $rs->get('type');
                }


                $rs = $ST->select("SELECT pv.id,pv.prop_id FROM sc_shop_prop_val pv WHERE prop_id={$propid} AND item_id={$id}");
                $value = trim($value);
                if ($type == 3) {
                    $value = floatval(str_replace(',', '.', $value));
                }
                if ($rs->next()) {

                    if ($value) {
                        $ST->update('sc_shop_prop_val', array('value' => $value), 'id=' . $rs->getInt('id'));
                    } else {
                        $ST->delete('sc_shop_prop_val', 'id=' . $rs->getInt('id'));
                    }
                    $ST->delete('sc_shop_prop_val', "prop_id={$propid} AND item_id={$id} AND id<>{$rs->getInt('id')}"); //Кастыль, удалиить дублирующие свойства
                } else {
                    if ($value) {
                        $ST->insert('sc_shop_prop_val', array('value' => $value, 'prop_id' => $propid, 'item_id' => $id));
                    }
                }
            }
            if ($pids = array_keys($pvalue)) {
                $ST->delete('sc_shop_prop_val', "item_id=$id AND prop_id NOT IN(" . implode(',', $pids) . ")");
            }


            $pvalue = $post->getArray('new_prop_value');
            $pname = $post->getArray('new_prop_name');

            foreach ($pname as $i => $name) {
                $rs = $ST->select("SELECT * FROM sc_shop_prop WHERE name='" . SQL::slashes($name) . "'");

                $type = 0;
                if ($rs->next()) {
                    $pid = $rs->getInt('id');
                    $type = $rs->get('type');
                } else {
                    $pid = $ST->insert('sc_shop_prop', array('name' => $name));
                }

                $pvalue[$i] = trim($pvalue[$i]);
                if ($type == 3) {
                    $pvalue[$i] = floatval(str_replace(',', '.', $pvalue[$i]));
                }
                $rs = $ST->select("SELECT * FROM sc_shop_prop_val WHERE prop_id={$pid} AND item_id={$id}");
                if ($rs->next()) {
                    $ST->update('sc_shop_prop_val', array('value' => $pvalue[$i]), "id={$rs->getInt('id')}");
                } else {
                    $ST->insert('sc_shop_prop_val', array('value' => $pvalue[$i], 'prop_id' => $pid, 'item_id' => $id));
                }
            }


            //Обновить привязку к каталогам //02,04,2014
            $ST->delete('sc_shop_item2cat', "itemid=$id");
            foreach ($post->getArray('catalogs') as $catid) {
                $ST->insert('sc_shop_item2cat', array('itemid' => $id, "catid" => $catid));
            }
        }

        echo printJSON(array('msg' => 'Сохранено', 'id' => $id, 'field' => $field, 'post' => $post->get()));
        exit;
    }

    function actDeleteGoods() {
        global $ST, $post, $get;
        if ($ids = $post->get('item')) {

            $ST->delete('sc_shop_prop_val', "item_id IN(" . implode(',', $ids) . ")");
            $ST->delete('sc_shop_item', "id IN(" . implode(',', $ids) . ")");
        } elseif ($id = $get->getInt('id')) {
            $ST->delete('sc_shop_prop_val', "item_id=$id");
            $ST->delete('sc_shop_item', "id=$id");
            $ids[] = $id;
        }
        echo printJSON(array('msg' => 'Удалено', 'ids' => $ids));
        exit;
    }

    function getPropGroup() {
        return $this->enum('sh_prop_grp');
    }

    function getPropType() {
        return array(1 => 'Да/нет', 2 => 'Строка', 3 => 'Число', 4 => 'Список выбор');
//		return array(4=>'Список выбор');
    }

    function actProp() {
        global $ST;


        $order = 'ORDER BY ';
        $ord = $this->getURIVal('ord') != 'asc' ? 'asc' : 'desc';
        if ($sort = $this->getURIVal('sort')) {
            $order.=$sort . ' ' . $ord;
        } else {
            $order.='grp_desc,sort DESC, name';
        }

        $q = "SELECT *,pgrp.value_desc AS grp_desc FROM sc_shop_prop p 
			LEFT JOIN (SELECT COUNT(id) AS cnt, prop_id FROM sc_shop_prop_val GROUP BY prop_id ) AS pv ON pv.prop_id=p.id
			LEFT JOIN (SELECT field_value, value_desc FROM sc_enum WHERE field_name='sh_prop_grp' ) AS pgrp ON pgrp.field_value=p.grp
		
			$order
			";
        $data['rs'] = $ST->select($q);
        $this->setTitle('Свойства товаров');

        $this->explorer[] = array('name' => 'Свойства товаров', 'url' => $this->mod_uri . 'props/');

        $this->display($data, dirname(__FILE__) . '/admin_prop.tpl.php');
    }

    function actDeleteProps() {
        global $ST, $post;
        if ($ids = $post->get('item')) {
            $ST->delete('sc_shop_prop_val', "prop_id IN(" . implode(',', $ids) . ")");
            $ST->delete('sc_shop_prop', "id IN(" . implode(',', $ids) . ")");
        } elseif ($id = $post->getInt('id')) {
            $ST->delete('sc_shop_prop_val', "prop_id=$id");
            $ST->delete('sc_shop_prop', "id=$id");
            $ids[] = $id;
        }
        echo printJSON(array('msg' => 'Удалено', 'ids' => $ids));
        exit;
    }

    function actPropsSave() {
        global $post, $ST;


        $data = array(
            'name' => $post->get('name'),
            'grp' => $post->get('grp'),
            'type' => $post->get('type'),
        );
        if ($id = $post->getInt('id')) {
            $ST->update('sc_shop_prop', $data, "id=$id");
        } else {
            $ST->insert('sc_shop_prop', $data);
        }
        echo printJSON(array('msg' => 'Сохранено'));
        exit;
    }

    function actApplyProps() {
        global $ST, $post;
        $names = $post->get('name');
        if ($sort = $post->get('sort')) {
            foreach ($sort as $id => $val) {
                $ST->update('sc_shop_prop', array('sort' => (int) $val, 'name' => $names[$id]), "id=$id");
            }
        }
        echo printJSON(array('msg' => 'Обновлено!'));
        exit;
    }

    function actMergeProps() {
        global $ST, $post;
        $cur = $post->getInt('cur');
        if ($item = $post->get('item')) {
            $ST->update('sc_shop_prop_val', array('prop_id' => $cur), "prop_id IN (" . implode(',', $item) . ")");
        }
        echo printJSON(array('msg' => 'Обновлено!'));
        exit;
    }

    function actPropToNum() {
        global $ST, $get;
        $rs = $ST->select("SELECT * FROM sc_shop_prop_val WHERE prop_id={$get->getInt('id')}");
        $c = 0;
        while ($rs->next()) {
            $v = $rs->get('value');
            if (trim($v)) {
                if ($v != (string) floatval($v)) {
                    $v = str_replace(',', '.', $v);
                    $v = preg_replace('/[^\.\d]/', '', $v);
                    $d['value'] = $v;
                    $ST->update('sc_shop_prop_val', $d, "id={$rs->getInt('id')}");
                    $c++;
                }
            } else {
                $ST->delete('sc_shop_prop_val', "id={$rs->getInt('id')}");
            }
        }

        echo printJSON(array('msg' => "Обновлено $c!"));
        exit;
    }

//	function onRemoveGoods(ArgumentList $args){
//		global $ST;
//		$ST->delete('sc_shop_item',"id =".$args->getArgument("id"));
//		$this->callSelfComponent();
//	}
//	function actRemoveGoods(){
//		global $ST,$get;
//		$ST->delete('sc_shop_item',"id =".$get->getInt("id"));
//		echo printJSON(array('id'=>$get->getInt("id")));exit;
//	}
    function onChangeStatus(ArgumentList $args) {
        global $ST;
        $ST->update('sc_shop_item', array('status' => $args->getInt('status')), "id=" . $args->getInt('id'));
        $this->callSelfComponent();
    }

    function displayCatalog($catalog, $selected) {
        $n = 0;
        ?><select class="input" name="catalog">
            <option style="<?= ($n++ % 2) ? 'background-color:#EEEEEE' : '' ?>" value="0">[раздел не указан]</option>
            <? $this->_displayCatalog($catalog, $selected, 0, $n); ?>
        </select><?
    }

    function displayCatalogs($catalog, $selected = array()) {
        $n = 0;
        ?><select class="input" name="catalogs[]" multiple style="height:300px">
        <? $this->_displayCatalog($catalog, $selected, 0, $n); ?>
        </select><?
    }

    function _displayCatalog($catalog, $selected, $deep = 0, $n = 0) {
        ?>
        <? foreach ($catalog as $item) { ?>
            <option style="<?= ($n++ % 2) ? 'background-color:#EEEEEE' : '' ?>" value="<?= $item['id'] ?>"
                    <? if ((is_array($selected) && in_array($item['id'], $selected)) || $item['id'] == $selected) { ?>selected<? } ?> 

                    >
                        <?= str_repeat('&nbsp;&nbsp;&nbsp;|&nbsp;--&nbsp;', $deep) ?>
                        <?= $item['name'] ?>
            </option>
            <?
            if (!empty($item['children'])) {
                $this->_displayCatalog($item['children'], $selected, $deep + 1, $n);
            }
            ?>
        <? } ?>	
        <?
    }

    function displayPageControl($filter = true) {
        global $ST;
        ?>

        <table style="width:100%" class="form">
            <tr>

                <td>
        <? if ($this->getURIVal('catalog') != 'goodsPopup') { ?>
                        <a href="<?= $this->mod_uri ?>goodsEdit/<? if (!empty($_GET['category'])) { ?>?category=<?= $_GET['category'] ?><? } ?>"><img src="/img/pic/add_16.gif" title="Добавить" alt="Добавить"/>Добавить</a>

                        | <a href="<?= $this->getURI(array('offer' => $this->getURIBoolVal('offer') ? null : '1'), true) ?>">Расширенный вид</a>
                        | <a href="<?= $this->getURI(array('imgmode' => $this->getURIBoolVal('imgmode') ? '0' : '1'), true) ?>">Изображения</a>
                        | <a href="/admin/catalog/prop/">Свойства товаров</a>
                        | <a href="?act=Manufacturer">Производитель</a>
                        | <a href="/admin/catalog/relation/">Связи</a>
        <? } ?>
                </td>

                <td style="text-align:right">
            <? $this->page->display(); ?> | Показать:<? $this->page->displayPageSize($this->getConfig('PAGE_SIZE_SELECT'), $this->pageSize); ?>
                </td>
            </tr>
        <? if ($filter) { ?>
                <tr>
                    <td>
                        Товар: <input class="input-text" name="search" value="<?= htmlspecialchars($this->getSearch()) ?>" style="width:200px">

                        <?
                        if (isset($_GET['prop']) && $p = intval($_GET['prop'])) {
                            $rs = $ST->select("SELECT * FROM sc_shop_prop WHERE id=$p");
                            if ($rs->next()) {
                                ?>Свойство <strong><?= $rs->get('name') ?></strong>: 
                                <input class="input-text" name="prop_val" value="<?= @$_GET['prop_val'] ?>" style="width:200px">
                                <?
                            }
                        }
                        ?>


                        <button type="submit" name="find" class="button">Найти</button>
                    </td>

                    <td style="text-align:right"><? $this->displayCatalog($this->catRef, $this->catId); ?></td>
                </tr>
        <? } ?>
        </table>
        <?
    }

    function actImport() {
        $this->setTitle('Импорт');
        $this->explorer[] = array('name' => 'Импорт');
        $data['price_expr'] = 'PRICE+10';
        if (!empty($_COOKIE['price_expr'])) {
            $data['price_expr'] = $_COOKIE['price_expr'];
        }
        $this->display($data, dirname(__FILE__) . '/admin_catalog_import.tpl.php');
    }

    function actImp() {
        global $post;
        set_time_limit(0);
        $start_time = time();
        global $ST;
        $dir = '../КаталогНоменклатуры';
        $goods = 'Номенклатура.txt';
        $goods_price = 'ОстаткиЦеныНоменклатуры.txt';
        $catalog = 'КаталогНоменклатуры.txt';
        $catalog = 'Мой каталогизатор.csv';
        //Каталог
        $handle = fopen($dir . '/' . $catalog, "r");
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
//			if($i++==0){continue;}
            $d = array(
                'id' => $data[1],
                'parentid' => $data[0],
                'name' => ucfirst(strtolower($data[2])),
                'description' => $data[2],
            );

            $rs = $ST->select("SELECT * FROM sc_shop_catalog WHERE id=" . $d['id']);
            if ($rs->next()) {
                $ST->update("sc_shop_catalog", $d, "id=" . $d['id']);
            } else {
                $ST->insert("sc_shop_catalog", $d);
            }
        }
        fclose($handle);
        $this->_updateCatalogCacheAll();

        echo printJSON(array('msg' => 'Импортировано'));
        exit;

        ///////////////////////////////////////////////////////////////////
        //товары
        $handle = fopen($dir . '/' . $goods, "r");
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($i++ == 0) {
                continue;
            }
            $d = array(
                'id' => $data[1],
                'product' => $data[1],
                'category' => $data[0],
                'name' => ucfirst(strtolower($data[2])),
                'description' => ucfirst(strtolower($data[3])),
            );
            $rs = $ST->select("SELECT * FROM sc_shop_item WHERE id=" . $d['id']);
            if ($rs->next()) {
                $ST->update("sc_shop_item", $d, "id=" . $d['id']);
            } else {
                $ST->insert("sc_shop_item", $d);
            }
        }
        fclose($handle);

        //цены
        $handle = fopen($dir . '/' . $goods_price, "r");
        $i = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($i++ == 0) {
                continue;
            }
            $d = array(
                'id' => $data[0],
                'price' => str_replace(',', '.', $data[2]),
                'in_stock' => str_replace(',', '.', $data[1])
            );

            if ($new_price = floatval(@eval('return ' . str_replace('PRICE', $d['price'], $post->get('price_expr')) . ';'))) {
                $d['price'] = round($new_price, 2);
            }

            $rs = $ST->select("SELECT * FROM sc_shop_item WHERE id=" . $d['id']);
            if ($rs->next()) {
                $ST->update("sc_shop_item", $d, "id=" . $d['id']);
            }
        }
        fclose($handle);
        $stop_time = time();
        $t = $stop_time - $start_time;

        setcookie('price_expr', $post->get('price_expr'), time() + 3600 * 24 * 30, '/');

        echo printJSON(array('msg' => 'Импортировано за ' . $t . ' сек.'));
        exit;
    }

    function actExport() {
        global $ST, $post;
        $queryStr = "SELECT *,c.name AS c_name,i.name as i_name,i.id as i_id FROM sc_shop_item i, sc_shop_catalog c WHERE c.id=i.category AND i.price>0 AND i.in_stock>0 ORDER BY c.name,i.name,i.price ";
        $data['rs'] = $ST->select($queryStr)->toArray();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=result.xls');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        echo $this->render($data, dirname(__FILE__) . '/admin_catalog_export.tpl.php');
        exit;
    }

    function actManufacturer() {
        global $ST;

        $data = array(
            'man' => array(),
        );
        $q = "SELECT * from sc_manufacturer m
		LEFT JOIN (SELECT COUNT(id) AS c,manufacturer_id FROM sc_shop_item GROUP BY manufacturer_id ) AS cnt ON cnt.manufacturer_id =m.id
		";
        $rs = $ST->select($q);
        while ($rs->next()) {
            $data['man'][] = $rs->getRow();
        }
        $this->setPageTitle('Производители');
        $this->display($data, dirname(__FILE__) . '/admin_manufecturer.tpl.php');
    }

    function actManEdit() {
        global $get, $ST;
        $rs = $ST->select("SELECT * FROM sc_manufacturer WHERE id={$get->getInt('id')}");
        if ($rs->next()) {
            echo printJSON($rs->getRow());
            exit;
        }
    }

    function actManSave() {
        global $ST, $post;

        $data = array(
            'name' => $post->get('name'),
            'description' => $post->get('description'),
        );

        if ($id = $post->getInt('id')) {
            $ST->update('sc_manufacturer', $data, "id=$id");
        } else {
            $id = $ST->insert('sc_manufacturer', $data);
        }
        if (isImg($_FILES['image']['name'])) {
            $path = 'storage/manufecturer/' . $id . "_" . time() . "." . file_ext($_FILES['image']['name']);
            if (file_exists($path)) {
                rename($path, $path . '.' . time());
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $path);
            $ST->update('sc_manufacturer', array('img' => "/" . $path), "id=$id");
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
//		echo printJSON(array('msg'=>'Сохранено ','id'=>$id));exit;
    }

    function actApplyMans() {
        global $ST, $post;

        if (isset($_FILES['img'])) {
            $img = array();
            foreach ($_FILES['img']['name'] as $id => $name) {
                if (isImg($name)) {
                    $path = 'storage/manufecturer/' . $id . "_" . time() . "." . file_ext($name);
                    if (file_exists($path)) {
                        rename($path, $path . '.' . time());
                    }
                    move_uploaded_file($_FILES['img']['tmp_name'][$id], $path);
                    $ST->update('sc_manufacturer', array('img' => "/" . $path), "id=$id");
                    $img[$id] = "/" . $path;
                }
            }
            echo printJSONP(array('msg' => 'Обновлено!', 'img' => $img));
            exit;
        }


        $names = $post->get('name');
        if ($sort = $post->get('sort')) {
            foreach ($sort as $id => $val) {
                $ST->update('sc_manufacturer', array('sort' => (int) $val, 'name' => $names[$id]), "id=$id");
            }
        }
        echo printJSON(array('msg' => 'Обновлено!'));
        exit;
    }

    function actDeleteMans() {
        global $ST, $post;
        if ($ids = $post->get('item')) {
            $ST->delete('sc_manufacturer', "id IN(" . implode(',', $ids) . ")");
        }
        echo printJSON(array('msg' => 'Удалено', 'ids' => $ids));
        exit;
    }

    function actTruncMans() {
        global $ST;
        $ST->exec("TRUNCATE TABLE sc_manufacturer");
        echo printJSON(array('msg' => 'Удалено'));
        exit;
    }

    function act_recountMan() {
        global $ST;

        $rs = $ST->select("SELECT * FROM sc_shop_item WHERE manufacturer <>''");
        while ($rs->next()) {
            $rs1 = $ST->select("SELECT * FROM sc_manufacturer WHERE name='" . SQL::slashes($rs->get('manufacturer')) . "'");
            if ($rs1->next()) {
                $manid = $rs1->get('id');
            } else {
                $manid = $ST->insert('sc_manufacturer', array('name' => $rs->get('manufacturer')));
            }
            $ST->update('sc_shop_item', array('manufacturer_id' => $manid), 'id=' . $rs->get('id'));
        }
    }

    function actRelation() {
        global $ST;

        $data = array(
            'rs' => array()
        );
        parent::refresh();

        $cond = " r.par=p.id AND r.ch=ch.id";

        if ($par = $this->getURIIntVal('relation')) {
            $cond.=" AND par=$par";
        }

        $query = "SELECT count(*) AS c FROM sc_shop_item AS p,sc_shop_item AS ch,sc_shop_relation r WHERE" . $cond;
        $rs = $ST->select($query);
        if ($rs->next()) {
            $this->page->all = $rs->getInt('c');
        }

        $q = "SELECT p.*,ch.name AS ch_name,ch.id AS ch_id,ch.img AS ch_img,r.type,r.id AS r_id,rt.value_desc AS type_desc FROM sc_shop_item AS p,sc_shop_item AS ch,sc_shop_relation r
			LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='shop_relation') AS rt ON rt.field_value=r.type
		WHERE $cond LIMIT {$this->page->getBegin()},{$this->page->per}";
        $rs = $ST->select($q);
        while ($rs->next()) {
            $data['rs'][] = $rs->getRow();
        }
        $this->setPageTitle('Связанные');
        $this->display($data, dirname(__FILE__) . '/admin_relation.tpl.php');
    }

    function actRelationRemove() {
        global $ST, $post;

        $ST->delete('sc_shop_relation', "id IN ('" . implode("','", $post->getArray('item')) . "')");

        header("Location: .");
        exit;
    }

    function actConfig() {

        $config = new CatalogConfig();
        $config->load();
        $data['config'] = $config;

        $this->setTitle('Настройки');
        $this->explorer[] = array('name' => 'Настройки');

        $this->display($data, dirname(__FILE__) . '/admin_config.tpl.php');
    }

    function actSaveConfig() {
        global $ST, $post;
        $config = new CatalogConfig();
        $config->save($post->get());

        echo printJSON(array('msg' => 'Сохранено'));
        exit;
    }

}
?>