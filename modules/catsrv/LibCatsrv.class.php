<?
if(!function_exists('u')){
	function u($str){
		$res=iconv('cp1251','utf-8',$str);
		return $res;
	}
}
function _recount_cat($item,$k='c' ){
			$res=0;
			if(isset($item[$k]))$res=$item[$k];
			if(isset($item['ch'])){
				foreach ($item['ch'] as $i) {
					$res+=_recount_cat($i,$k );
				}
			}
			return $res;
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
	
	static function cat4($file){
		$xml=simplexml_load_file($file);
		$goods_list=$xml->offers->offer;
		if(empty($goods_list)){
			$goods_list=$xml->shop->offers->offer;
		}
		$count=array();
		$goods=array();
		foreach ($goods_list as $data) {
			
			$d=array();
			
			$cid=(int)trim($data->categoryId);
				
			$id=(int)trim($data->attributes()->id);
			$in_stock=(int)trim($data->attributes()->available);
			
//			if((float)$data->price==0){
//				continue;
//			}
			
			$goods[$id]=array(
				'ext_category'=>$cid,
				'name'=>trim(u2w((string)$data->name)),
				'description'=>trim(u2w((string)$data->Description)),
				'html2'=>trim(u2w((string)$data->html2)),
				'price'=>(float)trim($data->price),
				'vendor'=>trim(u2w($data->Vendor)),
				'weight_flg'=>trim(u2w($data->weight_flg)),
				'in_stock'=>$in_stock,
				'param'=>array(),
			);
			if(isset($data->param)){
				foreach ($data->param as $p) {
					$goods[$id]['param'][trim(u2w($p->attributes()->name))]=trim(u2w((string)$p));
				}
			}
				
			
			if(empty($count[$cid])){
				$count[$cid]=0;
			}
			$count[$cid]++;			
		}
		$catalog=array();
		
		$only_changes=false;
		if(!empty($xml->shop->only_changes) && $xml->shop->only_changes=='true'){
			$only_changes=true;
		}
		if(!empty($xml->only_changes) && $xml->only_changes=='true'){
			$only_changes=true;
		}
		
		
		$category=$xml->Categories->category;
		if(empty($category)){
			$category=$xml->shop->Categories->category;
		}
		if(empty($category)){
			$category=$xml->shop->categories->category;
		}
		
		if(!empty($category)){
			$item=array();
			foreach ($category as $cat) {
				$id=(int)$cat->attributes()->id;
				$n=u2w((string)$cat);
	//			if(strpos('НЕ ИСП',$n)!==false){
	//				continue;
	//			}
				if(strpos($n,'НЕ ИСП')!==false){
					continue;
				}
				
				$item[$id]['name']=u2w((string)$cat);
				$item[$id]['c']=isset($count[$id])?$count[$id]:0;
				if($parentId=(int)$cat->attributes()->parent){
					$item[$parentId]['ch'][$id]=&$item[$id];
				}elseif($parentId=(int)$cat->attributes()->parentId){
					$item[$parentId]['ch'][$id]=&$item[$id];
				}else{
					$catalog[$id]=&$item[$id];
				}
			}
			foreach ($item as $id=>&$i) {
				if(isset($i['ch'])){
					$i['cr']=_recount_cat($i);
				}
			}
		}
		
		return array('catalog'=>$catalog,'count'=>$count,'goods'=>$goods,'only_changes'=>$only_changes);
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
		
//		$xml=simplexml_load_file($file."/$_cat");	
		
//		$goods_list=$xml->{u('Каталог')}->{u('Товар')};
		$count=array();
		$goods=array();		
		
		$fp=fopen($file."/$_cat",'r');
		
		$xml='';
		while ($line=fgets($fp,150)) {
			if(preg_match("/<".u('Товар').">/",$line) || $xml){
				$xml.=$line;
			}
			if(preg_match("|<\/".u('Товар').">|",$line)){
				
				$data=simplexml_load_string($xml);
				$d=array(
					'id'=>u2w($data->{u('Ид')}),
	//				'category'=>u2w($data->{u('Группы')}->{u('Ид')}),
					'ext_category'=>u2w($data->{u('Группы')}->{u('Ид')}),
					'name'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
					'description'=>'',//str_replace('.','. ',u2w($data->{u('Наименование')})),
					'product'=>'',//($p=u2w($data->{u('Артикул')}))?$p:'',
					'barcode'=>'',//u2w($data->{u('Штрихкод')}),
					'unit'=>intval(1),
//					'pack_size'=>0,
//					'pack_only'=>0,
					'manufacturer'=>'',
				);
				$d=array(
					'id'=>u2w($data->{u('Ид')}),
					'ext_category'=>u2w($data->{u('Группы')}->{u('Ид')}),
					'name'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
				);
				
				if(!empty($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')})){
					foreach ($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')} as $data) {
						/*if($data->{u('Ид')}==u('КодНоменклатуры')){
							$img=ltrim(trim(u2w($data->{u('Значение')})),'0');
	//						$d['image_url']="http://aptekadiana.ru/components/com_virtuemart/shop_image/product/$img.jpg"; //31.07.2013
						}*/
						/*if($data->{u('Ид')}==u('НоменклатураКоличествоВУпаковке')){
							$d['pack_size']=u2w($data->{u('Значение')});
						}
						if($data->{u('Ид')}==u('НоменклатураОтгружаетсяТолькоУпаковками')){
							$d['pack_only']=u2w($data->{u('Значение')})=='true'?1:0;
						}*/
						if($data->{u('Ид')}==u('НаименованиеПроизводителя')){
							$d['manufacturer']=u2w($data->{u('Значение')});
						}
					}
				}
						
				$goods[$d['id']]=$d;//	
					
				if(empty($count[$d['ext_category']])){//Товаров в категории
					$count[$d['ext_category']]=0;
				}
				$count[$d['ext_category']]++;
				
				$xml='';
			}
			
			
		}
		
		
		
		/*foreach ($goods_list as $data){
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
		}*/
		
		
		
		
		/*тут по остаткам*/
		
		//цены
				
		$xml=simplexml_load_file($file."/$_price");
			
		$only_changes=false;
		if(!empty($xml->{u('ПакетПредложений')}->attributes()->{u('СодержитТолькоИзменения')}) && $xml->{u('ПакетПредложений')}->attributes()->{u('СодержитТолькоИзменения')}=='true'){
			$only_changes=true;
		}
			
		$xml=$xml->{u('ПакетПредложений')}->{u('Предложения')}->{u('Предложение')};
		foreach ($xml as $data){	
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
		return array('catalog'=>$catalog,'count'=>$count,'goods'=>$goods,'only_changes'=>$only_changes);
	}
	
}

class LibCatsrv{
		
	
	static function importCartshop($tmp_file,$file,&$out){
		global $CONFIG,$ST;
		$dir='shared/php/';
		include("{$dir}PHPExcel.php");
		include("{$dir}PHPExcel/IOFactory.php");
		PHPExcel_Settings::setLocale('ru');
		if(file_ext($file)=='xlsx'){
			$objReader = new PHPExcel_Reader_Excel2007();
		}elseif(file_ext($file)=='xls'){
			$objReader = new PHPExcel_Reader_Excel5();
		}
		
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($tmp_file);
		
		/*$objPHPExcel->setActiveSheetIndex(0);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		
		$keys=array();
		$keys_flg=array('category_id'=>'id',
			'parent_id'=>'parentid',
			'name'=>'name',
			'description'=>'description',
			'image_name'=>'img',
			'sort_order'=>'main_sort',
		);
		
		
		$out=array();
		foreach ($objWorksheet->getRowIterator() as $row) {
			$data=array();
			$field=array();	
			$field_ext=array();	
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
			
			$fld_flg=false;//Строка поля
			$no_user_itm='';
						

			foreach ($cellIterator as $cell) {  
				$val=trim(u2w($cell->getValue()));

				if(!$fld_flg && $val=='category_id'){
					$fld_flg=true;
				}
				if($fld_flg){//Грузим поля
					if(isset($keys_flg[$val])){
						$keys[$cell->getColumn()]=$keys_flg[$val];
					}
//					print_r($keys);
					continue;
				}

				
			  	if($keys && isset($keys[$cell->getColumn()])){
			  		if(is_integer($keys[$cell->getColumn()])){				  		
				  		$field["{$keys[$cell->getColumn()]}"]=$val;

			  		}else{
			  			$field_ext["{$keys[$cell->getColumn()]}"]=$val;
			  		}
				  		
			  	}
			}
			if(!$keys){
				continue;
			}

			if(!empty($field_ext['id'])){
				if($field_ext['img']){
					if($img=@file_get_contents('http://www.farmcosmetica.ru/image/'.$field_ext['img'])){
						$img_name=preg_replace('|^data/|','',$field_ext['img']);
						$img_name=str_replace('/','_',$img_name);
						$img_name="storage/catalog/".$img_name;
						if(!file_exists($img_name)){
							file_put_contents($img_name,$img);
						}
						$field_ext['img']="/".$img_name;
					}else{
						$field_ext['img']="";
					}
				}
					
				$rs=$ST->select("SELECT * FROM sc_shop_catalog WHERE id={$field_ext['id']}");
				if($rs->next()){
					$ST->update('sc_shop_catalog',$field_ext,"id={$field_ext['id']}");
				}else{
					$ST->insert('sc_shop_catalog',$field_ext);
				}
				$out[$field_ext['id']]=$field_ext;
			}
		}
		*/
		
		
		$objPHPExcel->setActiveSheetIndex(1);

		$objWorksheet = $objPHPExcel->getActiveSheet();
		
		$keys=array();
		$keys_flg=array('product_id'=>'id',
			'categories'=>'categories',
			'name'=>'name',
			'quantity'=>'in_stock',
			'image_name'=>'img',
			'additional image names'=>'img_add',
			'price'=>'price',
			'date_added'=>'insert_time',
			'date_modified'=>'update_time',
			'viewed'=>'views',
			'description'=>'html',
			
//			'model'=>'Объем',
//			'manufacturer'=>'manufacturer',
		);
		
		
		$out=array();
		foreach ($objWorksheet->getRowIterator() as $row) {
			$data=array();
			$field=array();	
			$field_ext=array();	
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
			
			$fld_flg=false;//Строка поля
			$no_user_itm='';
						
//			echo "\n";
			foreach ($cellIterator as $cell) {  
				$val=trim(u2w($cell->getValue()));

				if(!$fld_flg && $val=='product_id'){//$cell->getColumn()=='B' &&
					$fld_flg=true;
				}
				if($fld_flg){//Грузим поля
					if(isset($keys_flg[$val])){
						$keys[$cell->getColumn()]=$keys_flg[$val];
					}
//					print_r($keys);
					continue;
				}

				
			  	if($keys && isset($keys[$cell->getColumn()])){
			  		if(is_integer($keys[$cell->getColumn()])){				  		
				  		$field["{$keys[$cell->getColumn()]}"]=$val;

			  		}else{
			  			$field_ext["{$keys[$cell->getColumn()]}"]=$val;
			  		}
				  		
			  	}
			}
			if(!$keys){
				continue;
			}

			if(!empty($field_ext['id'])){
				if($field_ext['img']){
					if($img=@file_get_contents('http://www.farmcosmetica.ru/image/'.iconv('cp1251','utf-8',$field_ext['img']))){
						$img_name=preg_replace('|^data/|','',$field_ext['img']);
						$img_name=str_replace('/','_',$img_name);
						$img_name="storage/catalog/goods/".$img_name;
						if(!file_exists($img_name)){
							file_put_contents($img_name,$img);
						}
						$field_ext['img']="/".$img_name;
					}else{
						$field_ext['img']="";
					}
				}
				if($field_ext['img_add']){
					$images=explode(',',$field_ext['img_add']);
					$field_ext['img_add']=array();
					foreach ($images as $i) {
						if($img=@file_get_contents('http://www.farmcosmetica.ru/image/'.iconv('cp1251','utf-8',$i))){
							$img_name=preg_replace('|^data/|','',$i);
							$img_name=str_replace('/','_',$img_name);
							$img_name="storage/catalog/goods/".$img_name;
							if(!file_exists($img_name)){
								file_put_contents($img_name,$img);
							}
							$field_ext['img_add'][]="/".$img_name;
						}
					}
					$field_ext['img_add']=implode(',',$field_ext['img_add']);
						
				}
					
				$rs=$ST->select("SELECT * FROM sc_shop_item WHERE id={$field_ext['id']}");
				
				
				$categories=$field_ext['categories'];
				unset($field_ext['categories']);
				
				if($rs->next()){
					$ST->update('sc_shop_item',$field_ext,"id={$field_ext['id']}");
				}else{
					$ST->insert('sc_shop_item',$field_ext);
				}
//				if($categories){
//					$categories=explode(',',$categories);
//					$ST->delete('sc_shop_item2cat',"itemid={$field_ext['id']}");
//					foreach ($categories as $c) {
//						$ST->insert('sc_shop_item2cat',array('itemid'=>$field_ext['id'],'catid'=>$c));
//					}
//					
//				}
//				if($field_ext['manufacturer']){
//					$rs=$ST->select("SELECT * FROM sc_manufacturer WHERE name='".SQL::slashes($field_ext['manufacturer'])."'");
//					if($rs->next()){
//						$man_id=$rs->getInt('id');
//					}else{
//						$man_id=$ST->insert("sc_manufacturer",array('name'=>$field_ext['manufacturer']));
//					}
//					$ST->update('sc_shop_item',array('manufacturer_id'=>$man_id),"id={$field_ext['id']}");
//					
//					
//				}
//				if($field_ext['Объем']){
//					$rs=$ST->select("select * FROM sc_shop_prop_val WHERE prop_id=1 AND item_id={$field_ext['id']}");
//					if($rs->next()){
//						$ST->update('sc_shop_prop_val',array('value'=>$field_ext['Объем']),"id={$rs->get('id')}");
//					}else{
//						$ST->insert('sc_shop_prop_val',array('value'=>$field_ext['Объем'],'prop_id'=>1,"item_id"=>$field_ext['id']));
//					}
//				}
				
				
				$out[$field_ext['id']]=$field_ext;
			}
		}
	}
	
	
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
		if($offer==4){
			$goods=CatImp::cat4($file);
		}
		if($offer==11){
			$goods=CatImp::cat1c1('import');
		}
		$only_changes=null;
		if(isset($goods['only_changes'])){
			$only_changes=$goods['only_changes'];
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
			
			
				if($only_changes===false){
					$gids=array_keys($goods);
					if(@$CONFIG['SHOP_IMP_FULL_REMOVE']=='true'){//удалять товары при полной выгрузке
						$ST->update("sc_shop_item",array('in_stock'=>-1),"offer=$offer AND ext_id NOT IN(".implode(',',$gids).")");
					}else{//просто обнулять
						$ST->update("sc_shop_item",array('in_stock'=>0),"offer=$offer AND in_stock>-1 AND ext_id NOT IN(".implode(',',$gids).")");
					}
	
				}
			

			$lnk['00000000-0000-0000-0000-000000000000']=0;
			foreach ($goods as $gid=>$g) {
				$cnt_imp++;
				
				$data=array(
					'in_stock'=>$g['in_stock'],
				);
				if($g['price']){
					$data['price']=$g['price'];
				}
				
					
				
//				if(isset($lnk[$g['ext_category']]) && isset($g['price']) ){
					
					if($g['ext_category']=='00000000-0000-0000-0000-000000000000'){
						
					}elseif(isset($lnk[$g['ext_category']])){
						$data['category']=$lnk[$g['ext_category']];
					}
					if(isset($g['manufacturer'])){
						$data['manufacturer']=@$g['manufacturer'];
					}
					
					if(!empty($g['vendor'])){
						$rs=$ST->select("SELECT * FROM sc_manufacturer WHERE name='".SQL::slashes($g['vendor'])."'");
						if($rs->next()){
							$data['manufacturer_id']=$rs->getInt('id');
						}else{
							$data['manufacturer_id']=$ST->insert('sc_manufacturer',array('name'=>$g['vendor']));
							
						}
					}
					if(!empty($g['name'])){
						$data['name']=$g['name'];
					}
					if(!empty($g['description'])){
						$data['description']=$g['description'];
					}
					if(!empty($g['html2'])){
						$data['html2']=$g['html2'];
					}
					if(!empty($g['weight_flg'])){
						$data['weight_flg']=$g['weight_flg']=='true'?1:0;
					}
					
					$rs=$ST->select("SELECT * FROM sc_shop_item WHERE ext_id='$gid' AND offer=$offer");
									
					if($rs->next()){
						$id=$rs->getInt('id');
						
						$add_data=array();
						foreach (array('category','manufacturer_id','weight_flg','name','price','in_stock','description','html2') as $k) {
							if(isset($data[$k]) && $rs->get($k)!=$data[$k]){
								$add_data[$k]=$data[$k];
							}
							
						}
						if($add_data){
							foreach ($add_data as $k=>$v) {
								if(!in_array($k,array('price','in_stock'))){
									$add_data['sort3']=1;break;
								}
							}
//							if(isset($add_data['price']) && $rs->getFloat('price')!=$add_data['price']){
//								$add_data['old_price']=$rs->getFloat('price');
//							}
							$add_data['update_time']=date('Y-m-d H:i:s');
							$ST->update('sc_shop_item',$add_data,"id={$id}");
							$cnt_upd++;
							$ST->insert('sc_shop_log',array('type'=>'goodsimport','time'=>date('Y-m-d H:i:s'),'data'=>serialize(array('id'=>$id)+$add_data)));
						}

					}else{
						if(empty($data['category'])){
							continue;
						}
						$data['ext_id']=$gid;
						$data['offer']=$offer;
						$data['sort3']=1;
						$data['insert_time']=date('Y-m-d H:i:s');
						
						$id=$ST->insert('sc_shop_item',$data);
						$cnt_ins++;
					}
					
					//Свойства
					if(!empty($g['param'])){
						foreach ($g['param'] as $p=>$v) {
							$rs=$ST->select("SELECT id FROM sc_shop_prop WHERE name='".SQL::slashes($p)."'");
							if($rs->next()){
								$pid=$rs->getInt('id');
							}else{
								$pid=$ST->insert('sc_shop_prop',array('name'=>$p));
							}
							$rs=$ST->select("SELECT * FROM sc_shop_prop_val WHERE item_id=$id AND prop_id=$pid");
							if($rs->next()){
								if($v!=$rs->get('value')){
									$ST->update('sc_shop_prop_val',array('value'=>$v),"id={$rs->get('id')}");
								}
							}else{
								$ST->insert('sc_shop_prop_val',array('value'=>$v,'item_id'=>$id,'prop_id'=>$pid));
							}
						}
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
//				}
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
  <name>'.@$CONFIG['SHOP_YML_NAME'].'</name>
  <company>'.@$CONFIG['SHOP_YML_COMPANY'].'</company>
  <url>http://'.$_SERVER['HTTP_HOST'].'/</url>
  <currencies><currency id="RUR" rate="1"/></currencies>
  <categories>
  ';
		global $cat;
		$cat=array();
		function getCatalog($par=0){
			global $ST,$cat;
			$yml='';
			$rs=$ST->select("SELECT * FROM sc_shop_catalog c WHERE parentid=$par AND export=1");
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
	if($cat){
		$rs=$ST->select("SELECT * FROM sc_shop_item i WHERE price>0 AND in_stock>-1 AND category IN (".implode(',',$cat).") AND export=1");
		while ($rs->next()) {
			$yml.='<offer id="'.$rs->get('id').'"';
			$yml.=' available="'.($rs->get('in_stock')>0?'true':'false').'"';
			$yml.='>';// bid="'.$rs->getInt('parentid').'"
			$yml.='<url>http://'.$_SERVER['HTTP_HOST'].'/catalog/goods/'.$rs->getInt('id').'/</url>';
			$yml.='<price>'.$rs->get('price').'</price>';
			$yml.='<currencyId>RUR</currencyId>';
			$yml.='<categoryId>'.$rs->get('category').'</categoryId>';
			if(($img=$rs->get('img')) && !preg_match('/ /',$img) && file_exists(ROOT.$img)){
				$yml.='<picture>http://'.$_SERVER['HTTP_HOST'].$img.'</picture>';
			}
			$yml.='<store>true</store>';
			$yml.='<pickup>'.@$CONFIG['SHOP_YML_PICKUP'].'</pickup>';
			$yml.='<delivery>'.@$CONFIG['SHOP_YML_DELIVERY'].'</delivery>';
			$yml.='<local_delivery_cost>'.@$CONFIG['SHOP_YML_DELIVERY_COST'].'</local_delivery_cost>';
			
			$yml.='<name>'.htmlspecialchars($rs->get('name')).'</name>';
			$yml.='<description>'.htmlspecialchars($rs->get('description')).'</description>';
			
			if($barcode=$rs->get('product')){
				$yml.='<barcode>'.htmlspecialchars($barcode).'</barcode>';
			}
			$yml.='</offer>
			';
		}
	}
		
	$yml.='</offers>
	</shop>
	</yml_catalog>';
	$file_name='shop.yml';
	if(file_exists(ROOT.'/'.$file_name)){
		unlink(ROOT.'/'.$file_name);
	}
//	file_put_contents(ROOT.'/'.$file_name,$yml);
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