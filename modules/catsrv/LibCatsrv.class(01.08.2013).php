<?
if(!function_exists('u')){
	function u($str){
		
		$res=iconv('cp1251','utf-8',$str);
		return $res;
	}
}


class CatImp{
	
	static function cat1($file){
		$xml=simplexml_load_file($file);
		$goods_list=$xml->shop->offers->offer;
		$count=array();
		$goods=array();
		foreach ($goods_list as $data) {
			
			if((float)$data->priceRRP==0){
				continue;
			}
			$goods[(int)$data->attributes()->id]=array(
				'ext_category'=>(string)$data->categoryId,
				'name'=>u2w((string)$data->name),
				'description'=>u2w((string)$data->RussianName),
				'price'=>(float)$data->priceRRP,
				'in_stock'=>(int)$data->count,
			);	
			
			if(empty($count[(string)$data->categoryId])){
				$count[(string)$data->categoryId]=0;
			}
			$count[(string)$data->categoryId]++;			
		}
		$catalog=array();
		foreach ($xml->shop->categories->category as $cat) {
			$id=(int)$cat->attributes()->id;
			$item[$id]['name']=u2w((string)$cat);
			if($parentId=(int)$cat->attributes()->parentId){
				$item[$parentId]['ch'][$id]=&$item[$id];
			}else{
				$catalog[$id]=&$item[$id];
			}
		}
		return array('catalog'=>$catalog,'count'=>$count,'goods'=>$goods);
	}
	
	static function cat2($file){
		$xml=simplexml_load_file($file);		
		global $count,$goods;
		$count=array();
		$goods=array();
		function __cat($gr){
			global $count,$goods;
			/*
			
			G1
				MainGroup
				G2
					Group
					G3
						SubGroup
						Item
			
			*/
			$cat=array();
			foreach ($gr as $g) {
				if($n=(string)$g->MainGroup){
					$id=u2w((string)$g->MainGroup->attributes()->id);
					$cat[$id]['name']=u2w($n);
					$cat[$id]['ch']=__cat($g->G2);
				}
				
				if($n=(string)$g->Group){
					$id=u2w((string)$g->Group->attributes()->id);
					$cat[$id]['name']=u2w($n);
					$cat[$id]['ch']=__cat($g->G3);
				}
				if($n=(string)$g->SubGroup){
					$id=u2w((string)$g->SubGroup->attributes()->id);
					$cat[$id]['name']=u2w($n);
					$c=0;
					foreach ($g->Item as $i) {
						$c++;
//						if((float)$data->priceRRP==0){
//							continue;
//						}
						$goods[(int)(string)$i->No]=array(
							'ext_category'=>$id,
							'name'=>u2w((string)$i->Name),
							'description'=>u2w((string)$i->Name),
							'price'=>(float)str_replace(',','.',$i->Price),
							'in_stock'=>1,
						);	
						
						
					}
					$cat[$id]['count']=$c;
					$count[$id]=$c;
				}
			}
			return $cat;
		}
		
		return array('catalog'=>__cat($xml->G1),'count'=>$count,'goods'=>$goods);
	}
	
	
	
	static function cat1c1($file){
		$_cat='v8catalog.xml';
		$_price='v8ost.xml';
		
		$xml=simplexml_load_file($file."/$_cat");	
		
		$goods_list=$xml->{u('Каталог')}->{u('Товар')};
		$count=array();
		$goods=array();		
		foreach ($goods_list as $data){
			$d=array(
				'id'=>u2w($data->{u('Ид')}),
//				'category'=>u2w($data->{u('Группы')}->{u('Ид')}),
				'ext_category'=>u2w($data->{u('Группы')}->{u('Ид')}),
				'name'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
				'description'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
				'product'=>($p=u2w($data->{u('Артикул')}))?$p:'',
				'barcode'=>u2w($data->{u('Штрихкод')}),
				'unit'=>intval(1),
				'pack_size'=>0,
				'pack_only'=>0,
				'manufacturer'=>'',
			);
			
			if(!empty($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')})){
				foreach ($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')} as $data) {
					if($data->{u('Ид')}==u('КодНоменклатуры')){
						$img=ltrim(trim(u2w($data->{u('Значение')})),'0');
//						$d['image_url']="http://aptekadiana.ru/components/com_virtuemart/shop_image/product/$img.jpg"; //31.07.2013
					}
					if($data->{u('Ид')}==u('НоменклатураКоличествоВУпаковке')){
						$d['pack_size']=u2w($data->{u('Значение')});
					}
					if($data->{u('Ид')}==u('НоменклатураОтгружаетсяТолькоУпаковками')){
						$d['pack_only']=u2w($data->{u('Значение')})=='true'?1:0;
					}
					if($data->{u('Ид')}==u('НаименованиеПроизводителя')){
						$d['manufacturer']=u2w($data->{u('Значение')});
					}
				}
			}
					
			$goods[$d['id']]=$d;	
				
			if(empty($count[$d['ext_category']])){//Товаров в категории
				$count[$d['ext_category']]=0;
			}
			$count[$d['ext_category']]++;
			
					
//			if(isset($lnk[$d['category']])){
//				$d['category']=$lnk[$d['category']];
//			}elseif($d['category']=='00000000-0000-0000-0000-000000000000'){
//				$d['category']=0;//Нераспределённый товар
//			}else{
//				continue;
//			}											
		}
		
		
		
		
		/*тут по остаткам*/
		
		//цены
				
		$xml=simplexml_load_file($file."/$_price");
				
		$goods_list=$xml->{u('ПакетПредложений')}->{u('Предложения')}->{u('Предложение')};
		foreach ($goods_list as $data){	
			$id=u2w($data->{u('Ид')});	
			if(isset($goods[$id])){
				$goods[$id]['price']=u2w($data->{u('Цены')}->{u('Цена')}->{u('ЦенаЗаЕдиницу')});
				$goods[$id]['in_stock']=u2w($data->{u('Количество')});
			}
			
		}
		
		
		$catalog=array();//пока не трогаем
//		foreach ($xml->shop->categories->category as $cat) {
//			$id=(int)$cat->attributes()->id;
//			$item[$id]['name']=u2w((string)$cat);
//			if($parentId=(int)$cat->attributes()->parentId){
//				$item[$parentId]['ch'][$id]=&$item[$id];
//			}else{
//				$catalog[$id]=&$item[$id];
//			}
//		}
		return array('catalog'=>$catalog,'count'=>$count,'goods'=>$goods);
	}
	
}

class LibCatsrv{
		
	static function import($file,$offer){
		global $CONFIG,$ST;
		
		$log_file='import/import_log.txt';
		
		set_time_limit(1000);
		$start_time=time();
		$result="Не найден контрольный файл";
		$goods=array();
		if($offer==1){
			$goods=CatImp::cat1($file);
		}
		if($offer==2){
			$goods=CatImp::cat2($file);
		}
		if($offer==11){
			$goods=CatImp::cat1c1('import');
		}
		$goods=$goods['goods'];
		$lnk=array();
		$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat WHERE offer=$offer");
		while($rs->next()){
			$lnk[$rs->get('id')]=$rs->getInt('lnk');
		}
		$cnt_imp=0;
		$cnt_img=0;
		$cnt_upd=0;
		$cnt_ins=0;
		if ($goods) {
			$lnk['00000000-0000-0000-0000-000000000000']=0;
			foreach ($goods as $gid=>$g) {
				$cnt_imp++;
				if(isset($lnk[$g['ext_category']]) && isset($g['price']) ){
					$data=array(
						'price'=>$g['price'],
						'in_stock'=>1,
					);
					if($g['ext_category']=='00000000-0000-0000-0000-000000000000'){
						
					}else{
						$data['category']=$lnk[$g['ext_category']];
					}
					if(isset($g['manufacturer'])){
						$data['manufacturer']=@$g['manufacturer'];
					}
					if(true){
						$ST->update("sc_shop_item",array('in_stock'=>0),"offer=$offer");
					}
					
					$rs=$ST->select("SELECT * FROM sc_shop_item WHERE ext_id='$gid' AND offer=$offer");
					
					if($rs->next()){
						$id=$rs->getInt('id');
						if($rs->get('price')!=$data['price'] && $rs->get('in_stock')!=$data['in_stock'] ){//&& (isset($data['category']) && $rs->get('category')!=$data['category'])
							$ST->update('sc_shop_item',$data,"id={$id}");
							$cnt_upd++;
						}
	
					}else{
						$data['name']=$g['name'];
						
						$data['description']=$g['description'];
						$data['ext_id']=$gid;
						$data['offer']=$offer;
						
						$id=$ST->insert('sc_shop_item',$data);
						$cnt_ins++;
					}
//					continue;
					
					$filename=$CONFIG['CATALOG_PATH'].'/goods/'.$id.'.jpg';
					if(!empty($g['image_url']) && !file_exists(ROOT.$filename)){
						
						$img_content=@file_get_contents($g['image_url']);
						if(!$img_content || strpos($img_content,'rror')){
							continue;
						}
						file_put_contents(ROOT.$filename,$img_content);
						
						
						$ST->update('sc_shop_item',array('img'=>$filename),'id='.$id);
						$cnt_img++;
					}	
				}
			}
			$stop_time=time();
			$t=$stop_time-$start_time;
			$result=date('Y-m-d H:i:s').' - Время загрузки='.$t.'; Импортировано='.$cnt_imp.'; Добавлено='.$cnt_ins.'; Обновлено='.$cnt_upd.'; Обновлено изображний='.$cnt_img."\r\n";
			file_put_contents($log_file,$result,FILE_APPEND);
			return array(
				't'=>$t,
				'imp'=>$cnt_imp,
				'ins'=>$cnt_ins,
				'upd'=>$cnt_upd,
				'msg'=>$result,
			);
		}else{
			$result=date('Y-m-d H:i:s')." - Список пуст\r\n";
			file_put_contents($log_file,$result,FILE_APPEND);
			return array('msg'=>$result);
		}	
		return $result;
	}

	
	
	
	
	static function import1c_old($params=array()){
		global $CONFIG,$ST;
		set_time_limit(1000);
		$start_time=time();
		$result="Не найден контрольный файл";
		
//		$PRICE_EXPR=$CONFIG['SHOP_SRV_PRICE_EXPR'];
//		$PRICE_EXPR_ACT=$CONFIG['SHOP_SRV_PRICE_EXPR_ACT'];		
		$dir=CATALOG_DIR;
		$save_dir=$dir.'/save';
		$goods=$dir.'/'.GOODS_FILE_NAME;
//		$goods_skidki=$dir.'/'.GOODS_SKIDKI_FILE_NAME;
		$goods_price=$dir.'/'.GOODS_PRICE_FILE_NAME;
		$ok_file=$dir.'/'.CATALOG_FILE_CONFIRM;
		$log_file=$dir.'/log.txt';
		
		

		
	
		
		if(file_exists($ok_file) || true){//&& extract_files(CATALOG_DIR.'/'.CATALOG_ARCHIV_NAME)
			///////////////////////////////////////////////////////////////////
			//товары
			if(file_exists($goods) && file_exists($goods_price)){
				
				
				$cnt_imp=0;
				$lnk=array();
				$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat");
				while($rs->next()){
						$lnk[$rs->get('id')]=$rs->getInt('lnk');
				}
//				$ST->exec("TRUNCATE TABLE sc_shop_item_tmp");
				
				$i=0;
				$j=0;
				
				$rows=array();
				
				
				
				$xml=simplexml_load_file($goods);
				
				$goods_list=$xml->{u('Каталог')}->{u('Товар')};
				
				foreach ($goods_list as $data){
					break;
					$d=array(
						'id'=>u2w($data->{u('Ид')}),
						'category'=>u2w($data->{u('Группы')}->{u('Ид')}),
						'name'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
						'description'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
						'product'=>($p=u2w($data->{u('Артикул')}))?$p:'',
						'barcode'=>u2w($data->{u('Штрихкод')}),
						'unit'=>intval(1),
						'pack_size'=>0,
						'pack_only'=>0,
						'img'=>'',
//						'kod'=>'',
					);
					
//					if('133a0fa1-4694-11e2-922a-00155d0a010e'==$d['id']){
//						file_put_contents(dirname(__FILE__).'/test.txt',$d['id'],FILE_APPEND);
//					}
					
					if(!empty($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')})){
						foreach ($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')} as $data) {
							if($data->{u('Ид')}==u('НоменклатураКод')){
//								$d['img']=trim(u2w($data->{u('Значение')}));
//								$d['product']=$d['img'];
								//22.11.2012
								$d['img']=trim(u2w($data->{u('Значение')}));
//								$d['kod']=$d['img'];
//								break;
							}
							if($data->{u('Ид')}==u('НоменклатураКоличествоВУпаковке')){
								$d['pack_size']=u2w($data->{u('Значение')});
//								break;
							}
							if($data->{u('Ид')}==u('НоменклатураОтгружаетсяТолькоУпаковками')){
								$d['pack_only']=u2w($data->{u('Значение')})=='true'?1:0;
//								break;
							}
						}
					}
					
					
					
					if(isset($lnk[$d['category']])){
						$d['category']=$lnk[$d['category']];
					}elseif($d['category']=='00000000-0000-0000-0000-000000000000'){
						$d['category']=0;//Нераспределённый товар
					}else{
						continue;
					}
					$rows[]=$d;
					
//					if('133a0fa1-4694-11e2-922a-00155d0a010e'==$d['id']){
//						file_put_contents(dirname(__FILE__).'/test.txt',print_r($d,true),FILE_APPEND);
//						
//						$ST->insert("sc_shop_item_tmp",$d);
//					}
					
					
					
					if(++$i && ( $i %200)==0){
						$ST->insertArr("sc_shop_item_tmp",$rows);
						$rows=array();
					}
					$cnt_imp++;			
				}
				if($rows){
					$ST->insertArr("sc_shop_item_tmp",$rows);
				}
				
				
			
				//цены
				
				$xml=simplexml_load_file($goods_price);
				
				$goods_list=$xml->{u('ПакетПредложений')}->{u('Предложения')}->{u('Предложение')};
				foreach ($goods_list as $data){
					break;
					$id=u2w($data->{u('Ид')});
					
					$d=array(
//						'price_base'=>str_replace(',','.',$data[2]),
						'price'=>u2w($data->{u('Цены')}->{u('Цена')}->{u('ЦенаЗаЕдиницу')}),
						'in_stock'=>u2w($data->{u('Количество')})
					);
					if(floatval($d['price'])){//только при наличии цены(скорее всего в любом случае)
//						if($new_price=floatval(@eval('return '.str_replace('PRICE',$d['price'],$PRICE_EXPR).';'))){
//							$d['price']=round($new_price,2);
//						}
						$ST->update("sc_shop_item_tmp",$d,"id='$id'");
					}
				}

							
				$cnt_upd=0;
				$cnt_ins=0;
				
				
				//Обнулим что нужно
				$ST->update("sc_shop_item i",array('in_stock'=>0),"EXISTS(SELECT id FROM sc_shop_item_tmp t WHERE t.id=i.ext_id AND (t.in_stock=0))");
				
				//Сначало обновим то что можно//t.pack_only,
				$res=$ST->select("SELECT i.id,t.category,t.product,t.price,t.in_stock,i.price as old_price
				FROM sc_shop_item_tmp t,sc_shop_item i 
				WHERE t.price>0 AND  t.id=i.ext_id AND (t.price <> i.price OR t.in_stock<>i.in_stock OR t.category<>i.category)");
				$i=0;
				while ($res->next()){
					
//					$r= ++$i.' U '.$res->get('id').'<br>'."\n";
//					echo $r;
//					ob_flush();
					$d=$res->getRow();
					if(floatval($d['price'])!=floatval($d['old_price'])){
						$ST->insert('sc_shop_srv_diff',array('product'=>$d['id'],'price_old'=>floatval($d['old_price']),'price_new'=>floatval($d['price'])));
					}		
					unset($d['old_price']);
					
					unset($d['id']);
					
					if($d['category']==0){
						unset($d['category']);
					}
					
					$ST->update("sc_shop_item",$d,"id=".$res->get('id').' LIMIT 1');
					$cnt_upd++;
		//			file_put_contents('log.txt',$r,FILE_APPEND);
				}
				///////////////////////////////////////////////////////////////////////////////
				//Потом добавим то чего нет
				//pack_size,pack_only,,unit,weight_flg
				$res=$ST->select("SELECT id,product,category,name,description,price,in_stock 
					FROM sc_shop_item_tmp t 
					WHERE t.price>0 AND  NOT EXISTS(SELECT id FROM sc_shop_item i WHERE t.id=i.ext_id)");
				$i=0;
				$rows=array();
				while ($res->next()){

					$d=$res->getRow();
					$d['ext_id']=$d['id'];
					unset($d['id']);

					$cnt_ins++;
	
					$rows[]=$d;
					if(++$i && ( $i %200)==0){
						$ST->insertArr("sc_shop_item",$rows);
						$rows=array();
					}
				}
				if($rows){
					$ST->insertArr("sc_shop_item",$rows);
				}
				
				
				
				//импорт картинок
				function getImgList($dir){
					global $ST,$CONFIG;
					$d=opendir($dir);
					while ($file=readdir($d)) {
						if($file!='.' && $file!='..'){
							
							if(is_dir($dir.'/'.$file)){
								getImgList($dir.'/'.$file);
							}else{
								$ext_id=preg_replace('/\.[a-z]+$/','',$file);
//								$rs=$ST->select("SELECT id FROM sc_shop_item WHERE ext_id='$ext_id'");
								$rs=$ST->select("SELECT i.id FROM sc_shop_item i,sc_shop_item_tmp it WHERE i.ext_id=it.id AND it.img='$ext_id'");
								if($rs->next()){
									
									copy($dir.'/'.$file,'.'.$CONFIG['CATALOG_PATH'].'/goods/'.$file);
									
									$ST->update('sc_shop_item',array('img'=>$CONFIG['CATALOG_PATH'].'/goods/'.$file),'id='.$rs->get('id'));
								}
							}
						}
					}
					closedir($d);
				}
				if(file_exists(CATALOG_DIR.'/'.CATALOG_IMG)){
					getImgList(CATALOG_DIR.'/'.CATALOG_IMG);
				}
				
//				removeDirectory(CATALOG_DIR.'/'.CATALOG_IMG);
		
				if(!file_exists($save_dir)){
					mkdir($save_dir);
				}
				
				
				$stop_time=time();
				$t=$stop_time-$start_time;
				
				$result=date('Y-m-d H:i:s').' - Время загрузки='.$t.'; Импортировано='.$cnt_imp.'; Добавлено='.$cnt_ins.'; Обновлено='.$cnt_upd."\r\n";
				
				file_put_contents($log_file,$result,FILE_APPEND);
				
			
			}else{
				$result=date('Y-m-d H:i:s')." - не найден один из файлов $goods, $goods_price\r\n";
				file_put_contents($log_file,$result,FILE_APPEND);
			}
		}	
		return $result;
	}
	

	static function makeYandexYML(){
		global $ST,$CONFIG;
		$yml='<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:i').'">
<shop>
  <name>'.$CONFIG['HEADER'].' '.$CONFIG['HEADER1'].'</name>
  <company>'.$CONFIG['HEADER'].' '.$CONFIG['HEADER1'].'</company>
  <url>http://'.$_SERVER['HTTP_HOST'].'/</url>
  <currencies><currency id="RUR" rate="1"/></currencies>
  <categories>
  ';
		global $cat;
		$cat=array();
		function getCatalog($par=0){
			global $ST,$cat;
			$yml='';
			$rs=$ST->select("SELECT * FROM sc_shop_catalog c WHERE parentid=$par");
				while ($rs->next()) {
					$yml.='<category id="'.$rs->getInt('id').'"'.($rs->getInt('parentid')?' parentId="'.$rs->getInt('parentid').'"':'').'>'.$rs->get('name').'</category>
					';
					$cat[]=$rs->getInt('id');
					$yml.=getCatalog($rs->getInt('id'));
					
				}
			return $yml;
		}
		
		
	$yml.=getCatalog();
	$yml.='</categories>
	<offers>
	';
	$rs=$ST->select("SELECT * FROM sc_shop_item i WHERE price>0 AND in_stock>0 AND category IN (".implode(',',$cat).")");
	while ($rs->next()) {
		$yml.='<offer id="'.$rs->get('id').'">';// bid="'.$rs->getInt('parentid').'"
		$yml.='<url>http://'.$_SERVER['HTTP_HOST'].'/catalog/goods/'.$rs->getInt('id').'/</url>';
		$yml.='<price>'.$rs->get('price').'</price>';
		$yml.='<currencyId>RUR</currencyId>';
		$yml.='<categoryId>'.$rs->get('category').'</categoryId>';
		if(($img=$rs->get('img')) && !preg_match('/ /',$img) && file_exists(ROOT.$img)){
			$yml.='<picture>http://'.$_SERVER['HTTP_HOST'].$img.'</picture>';
		}
		$yml.='<delivery> true </delivery>';
		$yml.='<name>'.htmlspecialchars($rs->get('name')).'</name>';
		$yml.='<description>'.htmlspecialchars($rs->get('description')).'</description>';
		
		if($barcode=$rs->get('product')){
			$yml.='<barcode>'.htmlspecialchars($barcode).'</barcode>';
		}
		$yml.='</offer>
		';
	}
	$yml.='</offers>
	</shop>
	</yml_catalog>';
	$file_name='shop.yml';
	if(file_exists(ROOT.'/'.$file_name)){
		unlink(ROOT.'/'.$file_name);
	}
	file_put_contents(ROOT.'/'.$file_name,$yml);
	return $yml;
	}
	
	
	
	
	
	
	
	
	static function export1c(){
		global $CONFIG,$ST;
		set_time_limit(1000);
		$data=array(
			'make_date'=>date('Y-m-d'),
			'make_time'=>date('H:i:s'),
			'document'=>array()
		
		);
		$date_from=date('Y-m-d');
		
		$date_to=date('Y-m-d',time()+3600*24);

		if(isset($_GET['d']) && $t=strtotime($_GET['d'])){
			$date_from=date('Y-m-d',$t);
			$date_to=date('Y-m-d',$t+3600*24);
		}
		
//		if($get->get('date_from')){
//			$date_from=$get->get('date_from');
//		}
//		
//		if($get->get('date_to')){
//			$date_to=$get->get('date_to');
//		}
		
		
		$q="SELECT u.*,o.* FROM sc_shop_order o
		LEFT JOIN sc_users AS u ON u.u_id=o.userid
		WHERE 
		 o.create_time>='$date_from' AND o.create_time<='$date_to'
		";
		$rs=$ST->select($q);
		while ($rs->next()) {
			$d=array(
				'id'=>$rs->get('id'),
				'num'=>$rs->get('id'),
				'date'=>dte($rs->get('create_time'),'Y-m-d'),
				'summ'=>$rs->get('total_price'),
				'contragent'=>array(
					'id'=>$rs->get('u_id').'#'.$rs->get('login').'#'.$rs->get('fullname').'#'.$rs->get('phone'),
					'name'=>($rs->get('type')=='user_jur' && trim($rs->get('company')))?$rs->get('company'):$rs->get('fullname'),
					'address'=>$rs->get('address'),
					'mail'=>$rs->get('mail'),
				),
				
				'time'=>dte($rs->get('create_time'),'H:i:s'),
				'additionally'=>$rs->get('additionally'),
				'goods'=>array(),
			);
			$q="SELECT *,ec.id AS ext_cat_id, oi.price AS price 	
			FROM sc_shop_order_item AS oi, sc_shop_item AS si
			LEFT JOIN sc_shop_srv_extcat AS ec ON ec.lnk=si.category
			WHERE
				si.id=oi.itemid
				AND oi.orderid={$rs->get('id')} 
				
			";
			$q="SELECT *, oi.price AS price 	
			FROM sc_shop_order_item AS oi, sc_shop_item AS si
			
			WHERE
				si.id=oi.itemid
				AND oi.orderid={$rs->get('id')} ";
			
			$rs1=$ST->select($q);
			while ($rs1->next()) {
				$g=array(
					'name'=>$rs1->get('name'),
					'id'=>$rs1->get('ext_id'),
//					'cat_id'=>$rs1->get('ext_cat_id'),
					'price'=>$rs1->get('price'),
					'count'=>$rs1->get('count'),
					'summ'=>$rs1->get('price')*$rs1->get('count'),
				);
				
				if($rs1->get('unit_sale')==2){
					if($rs1->getInt('pack_size')!=0){//03.02.2013
						$g['count']*=$rs1->getInt('pack_size');
						$g['price']/=$rs1->getInt('pack_size');
						$g['price']=round($g['price'],2);
					}
					
				}
				$d['goods'][]=$g;
			}
			
			$data['document'][]=$d;
		}
		
		$file=ROOT.'/'.CATALOG_EXPORT_DIR.'/'.'order.xml';
		if(isset($_GET['d']) && $t=strtotime($_GET['d'])){
			$file=ROOT.'/'.CATALOG_EXPORT_DIR.'/'.date('Y_m_d',$t).'.xml';
		}
		
		
		
		$out= '<?xml version="1.0" encoding="windows-1251"?>';
		$out.= LibCatsrv::render($data,dirname(__FILE__).'/order1c.xml.php');
		
		if(isset($_GET['get'])){
			return $out;
		}else{
			file_put_contents($file,$out);exit;
		}
		
	}
	static function render($data=array(),$tpl=null){
		if(!$tpl)return;
		foreach ($data as $k=>$v){
			$$k=is_string($v)?htmlspecialchars($v):$v;
		}
		ob_start();
		include($tpl);
		$output= ob_get_contents(); 
		ob_end_clean();
		return $output;
	}
	
	
	
}?>