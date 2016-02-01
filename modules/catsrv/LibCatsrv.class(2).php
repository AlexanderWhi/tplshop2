<?
if(!function_exists('u')){
	function u($str){
		return iconv('cp1251','utf-8',$str);
	}
}




class LibCatsrv{
	
	static function import1c($params=array()){
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
		
		
		if(file_exists($ok_file) ){//&& extract_files(CATALOG_DIR.'/'.CATALOG_ARCHIV_NAME)
			///////////////////////////////////////////////////////////////////
			//товары
			if(file_exists($goods) && file_exists($goods_price)){
				
				
				$cnt_imp=0;
				$lnk=array();
				$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat");
				while($rs->next()){
						$lnk[$rs->get('id')]=$rs->getInt('lnk');
				}
				$ST->exec("TRUNCATE TABLE sc_shop_item_tmp");
				
				$handle = fopen($goods, "r");
				$i=0;
				$j=0;
				
				$rows=array();
				
				
				
				$xml=simplexml_load_file($goods);
				
				$goods_list=$xml->{u('Каталог')}->{u('Товары')}->{u('Товар')};
				
				foreach ($goods_list as $data){
			
					$d=array(
						'id'=>u2w($data->{u('Ид')}),
						'category'=>u2w($data->{u('Группы')}->{u('Ид')}),
						'name'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
						'description'=>str_replace('.','. ',u2w($data->{u('Наименование')})),
						'product'=>($p=u2w($data->{u('Артикул')}))?$p:'',
						'barcode'=>u2w($data->{u('Штрихкод')}),
						'unit'=>intval(1),
						'pack_size'=>0,
						'img'=>'',
					);
					
					if(!empty($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')})){
						foreach ($data->{u('ЗначенияСвойств')}->{u('ЗначенияСвойства')} as $data) {
							if($data->{u('Ид')}==u('НоменклатураКод')){
								$d['img']=trim(u2w($data->{u('Значение')}));
								$d['product']=$d['img'];
//								break;
							}
							if($data->{u('Ид')}==u('НоменклатураКоличествоВУпаковке')){
								$d['pack_size']=u2w($data->{u('Значение')});
//								break;
							}
						}
					}
					
					
					
					
					
					
					
					
					if(isset($lnk[$d['category']])){
						$d['category']=$lnk[$d['category']];
					}else{
						continue;
					}
					$rows[]=$d;
					if(++$i && ( $i %200)==0){
						$ST->insertArr("sc_shop_item_tmp",$rows);
						$rows=array();
					}
					$cnt_imp++;			
				}
				$ST->insertArr("sc_shop_item_tmp",$rows);
				
			
				//цены
				
				$xml=simplexml_load_file($goods_price);
				
				$goods_list=$xml->{u('ПакетПредложений')}->{u('Предложения')}->{u('Предложение')};
				foreach ($goods_list as $data){
					$id=u2w($data->{u('Ид')});
					
					$d=array(
//						'price_base'=>str_replace(',','.',$data[2]),
						'price'=>u2w($data->{u('Цены')}->{u('Цена')}->{u('ЦенаЗаЕдиницу')}),
						'in_stock'=>u2w($data->{u('Количество')})
					);
					if(floatval($d['price']) && floatval($d['in_stock'])){
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
				
				//Сначало обновим то что можно
				$res=$ST->select("SELECT i.id,t.category,t.price,t.in_stock,i.price as old_price
				FROM sc_shop_item_tmp t,sc_shop_item i 
				WHERE t.price>0 AND t.in_stock>0 AND t.id=i.ext_id AND (t.price <> i.price OR t.in_stock<>i.in_stock)");
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
					$ST->update("sc_shop_item",$d,"id=".$res->get('id').'');
					$cnt_upd++;
		//			file_put_contents('log.txt',$r,FILE_APPEND);
				}
				///////////////////////////////////////////////////////////////////////////////
				//Потом добавим то чего нет
				$res=$ST->select("SELECT id,product,pack_size,category,name,description,price,unit,weight_flg,in_stock 
					FROM sc_shop_item_tmp t 
					WHERE t.price>0 AND t.in_stock>0 AND NOT EXISTS(SELECT id FROM sc_shop_item i WHERE t.id=i.ext_id)");
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
				$ST->insertArr("sc_shop_item",$rows);
				
				
				//импорт картинок
				function getImgList($dir){
					global $ST,$CONFIG;
					if(!file_exists($dir)){
						return;
					}
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
				
				getImgList(CATALOG_DIR.'/'.CATALOG_IMG);
//				removeDirectory(CATALOG_DIR.'/'.CATALOG_IMG);
		
				if(!file_exists($save_dir)){
					mkdir($save_dir);
				}
				
//				$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_FILE_NAME.'.gz';
//				$f = gzopen($fname, "w9");
//				gzwrite($f, file_get_contents($goods)); // записать строку в файл
//				gzclose($f);
//				if(!DEBUG_IMPORT)unlink($goods);//Не удалять	
					
		//		
				
//				$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_PRICE_FILE_NAME.'.gz';
//				$f = gzopen($fname, "w9");
//				gzwrite($f, file_get_contents($goods_price)); // записать строку в файл
//				gzclose($f);
//				if(!DEBUG_IMPORT)unlink($goods_price);
				
//				if(file_exists($goods_skidki)){
//					$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_SKIDKI_FILE_NAME.'.gz';
//					$f = gzopen($fname, "w9");
//					gzwrite($f, file_get_contents($goods_skidki)); // записать строку в файл
//					gzclose($f);
////					if(!DEBUG_IMPORT)unlink($goods_skidki);
//				}
					
				
//				if(!DEBUG_IMPORT)unlink($ok_file);
//				rename(CATALOG_DIR.'/'.CATALOG_ARCHIV_NAME,$save_dir.'/'.CATALOG_ARCHIV_NAME.date('YmdHis').'.zip');
				
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
	
	static function import1cV2($params=array()){
		global $CONFIG,$ST;
		set_time_limit(1000);
		$start_time=time();
			
		$dir=CATALOG_DIR;

		$goods_price=$dir.'/'.GOODS_PRICE_FILE_NAME;
		$log_file=$dir.'/log.txt';
		
		$cnt_upd_price=0;
		$cnt_ins=0;
		$cnt_upd=0;
		
			///////////////////////////////////////////////////////////////////
			//остатки
			if(file_exists($goods_price)){
				$result=date('Y-m-d H:i:s')."\r\n";
				
				file_put_contents($log_file,$result,FILE_APPEND);
				
				//цены
				$xml=simplexml_load_file($goods_price);
				
				//Склад
//				$sklad=$xml->{u('ПакетПредложений')}->{u('Владелец')}->{u('Ид')};
				
				$goods_list=$xml->{u('ПакетПредложений')}->{u('Предложения')}->{u('Предложение')};
				foreach ($goods_list as $data){
					$id=u2w($data->{u('Ид')});
					
					$d=array(
						'price'=>u2w($data->{u('Цены')}->{u('Цена')}->{u('ЦенаЗаЕдиницу')}),
						'in_stock'=>u2w($data->{u('Количество')})
					);
					
					$rs=$ST->select("SELECT * FROM sc_shop_item WHERE ext_id='".$id."' LIMIT 1");
					if($rs->next()){
						$itemid=$rs->getInt('id');
						if($d['price'] !=$rs->get('price')){
							$ST->update('sc_shop_item',array('price'=>(float)$d['price']),"ext_id='".$id."'");
							$cnt_upd_price++;
						}
						$rs=$ST->select("SELECT * FROM sc_shop_offer WHERE itemid=$itemid AND region='ekt' LIMIT 1");/* @TODO region*/
						if($rs->next()){
							if($d['in_stock']!=$rs->get('in_stock')){
								$ST->update('sc_shop_offer',array('in_stock'=>(int)$d['in_stock']),"itemid=$itemid AND region='ekt'");
								$cnt_upd++;
							}
							
						}else{
							$ST->insert('sc_shop_offer',array('in_stock'=>(int)$d['in_stock'],'itemid'=>$itemid,'region'=>'ekt'));
							$cnt_ins++;
						}
					}else{
						file_put_contents($log_file,"$id не найден\n",FILE_APPEND);
					}
				}
				
				$stop_time=time();
				$t=$stop_time-$start_time;
				
				$result=' - Время загрузки='.$t.'; Обновлено остатков='.$cnt_upd.'; Добавлено='.$cnt_ins.'; Обновлено цен='.$cnt_upd_price."\r\n";
				
				file_put_contents($log_file,$result,FILE_APPEND);
			
			}else{
				$result=date('Y-m-d H:i:s')." - не найден один из файлов $goods_price\r\n";
				file_put_contents($log_file,$result,FILE_APPEND);
			}
			
		return $result;
	}
		
	
	
	static function import($mode=null,$params=array()){
		global $CONFIG,$ST;
		set_time_limit(1000);
		$start_time=time();
		$result="Не найден контрольный файл";
		
		$PRICE_EXPR=$CONFIG['SHOP_SRV_PRICE_EXPR'];
		$PRICE_EXPR_ACT=$CONFIG['SHOP_SRV_PRICE_EXPR_ACT'];		
		$dir=CATALOG_DIR;
		$save_dir=$dir.'/save';
		$goods=$dir.'/'.GOODS_FILE_NAME;
		$goods_skidki=$dir.'/'.GOODS_SKIDKI_FILE_NAME;
		$goods_price=$dir.'/'.GOODS_PRICE_FILE_NAME;
		$ok_file=$dir.'/'.CATALOG_FILE_CONFIRM;
		$log_file=$dir.'/log.txt';
		
		if(file_exists($ok_file)){
			///////////////////////////////////////////////////////////////////
			//товары
			if(file_exists($goods) && file_exists($goods_price)){
				
				if($mode==null || $mode=='imp'){///////////////////////////////import
					
				
				$cnt_imp=0;
				$lnk=array();
				$rs=$ST->select("SELECT * FROM sc_shop_srv_extcat");
				while($rs->next()){
						$lnk[$rs->getInt('id')]=$rs->getInt('lnk');
				}
				$ST->exec("TRUNCATE TABLE sc_shop_item_tmp");
				
				$handle = fopen($goods, "r");
				$i=0;
				$j=0;
				
				$rows=array();
				
				while (($data = fgetcsv($handle, 150, ";")) !== FALSE ) {
					if($i++==0){continue;}
		
					$d=array(
						'id'=>$data[1],
						'category'=>$data[0],
						'name'=>ucfirst(strtolower($data[2])),
						'description'=>ucfirst(strtolower($data[3])),
						'unit'=>intval($data[5]),
						'weight_flg'=>$data[4]=='True'?1:0,
					);
					
					if(isset($lnk[$d['category']])){
						$d['category']=$lnk[$d['category']];
					}else{
						continue;
					}
					$rows[]=$d;
					if(($i%200)==0){
						$ST->insertArr("sc_shop_item_tmp",$rows);
						$rows=array();
					}
					$cnt_imp++;			
				}
				$ST->insertArr("sc_shop_item_tmp",$rows);
				
				fclose($handle);
				//цены
				$handle = fopen($goods_price, "r");
				$i=0;
				while (($data = fgetcsv($handle, 50, ";")) !== FALSE ) {
					if($i++==0){continue;}
					$d=array(
						'id'=>$data[0],
						'price_base'=>str_replace(',','.',$data[2]),
						'price'=>str_replace(',','.',$data[2]),
						'in_stock'=>str_replace(',','.',$data[1])
					);
					if(floatval($d['price']) && floatval($d['in_stock'])){
						if($new_price=floatval(@eval('return '.str_replace('PRICE',$d['price'],$PRICE_EXPR).';'))){
							$d['price']=round($new_price,2);
						}
						$ST->update("sc_shop_item_tmp",$d,"id=".$d['id'].' ');
					}
				}
				fclose($handle);
				
				
				$cnt_imp_act=0;
				//Товары по акции
				if(file_exists($goods_skidki)){
					$handle = fopen($goods_skidki, "r");
					$i=0;
					while (($data = fgetcsv($handle, 150, ";")) !== FALSE ) {
						if($i++==0){continue;}
						
						$id=$data[0];
						
						$ST->executeUpdate("UPDATE sc_shop_item_tmp SET price=".str_replace('PRICE','price_base',$PRICE_EXPR_ACT)." WHERE id=".$id);
						$cnt_imp_act++;
						
					}
					fclose($handle);
				}
				if($params){
					echo json_encode(array('start_time'=>$start_time,'cnt_imp'=>$cnt_imp,'cnt_imp_act'=>$cnt_imp_act));exit;
				}
				
				}///////////////////////////////import
				
				if($mode==null || $mode=='upd'){///////////////////////////////update
					if($params){
						if(!empty($params['start_time']))$start_time=$params['start_time'];
						if(!empty($params['cnt_imp']))$cnt_imp=$params['cnt_imp'];
						if(!empty($params['cnt_imp_act']))$cnt_imp_act=$params['cnt_imp_act'];
					}
					
							
				$cnt_upd=0;
				$cnt_ins=0;
				
				
				//Обнулим что нужно
				$ST->update("sc_shop_item i",array('price'=>0,'in_stock'=>0),"EXISTS(SELECT id FROM sc_shop_item_tmp t WHERE t.id=i.product AND (t.in_stock=0 or t.price=0))");
				
				//Сначало обновим то что можно
				$res=$ST->select("SELECT i.id,t.category,t.price,t.in_stock,i.price as old_price
				FROM sc_shop_item_tmp t,sc_shop_item i 
				WHERE t.price>0 AND t.in_stock>0 AND t.id=i.product AND (t.price <> i.price OR t.in_stock<>i.in_stock)");
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
					$ST->update("sc_shop_item",$d,"id=".$res->get('id').' ');
					$cnt_upd++;
		//			file_put_contents('log.txt',$r,FILE_APPEND);
				}
				///////////////////////////////////////////////////////////////////////////////
				//Потом добавим то чего нет
				$res=$ST->select("SELECT id,category,name,description,price,unit,weight_flg,in_stock 
					FROM sc_shop_item_tmp t 
					WHERE t.price>0 AND t.in_stock>0 AND NOT EXISTS(SELECT id FROM sc_shop_item i WHERE t.id=i.product)");
				$i=0;
				$rows=array();
				while ($res->next()){
//					$r= ++$i.' I '.$res->get('id').'<br>'."\n";
//					echo $r;
//					ob_flush();
					$d=$res->getRow();
					$d['product']=$d['id'];
					unset($d['id']);
//					$ST->insert("sc_shop_item",$d);
					$cnt_ins++;
		//			file_put_contents('log.txt',$r,FILE_APPEND);	
		
					$rows[]=$d;
					if(($i%200)==0){
						$ST->insertArr("sc_shop_item",$rows);
						$rows=array();
//						$r= ++$i.' '.++$j.' Imp '.'<br>'."\n";
//						echo $r;
//						ob_flush();
					}
				}
				$ST->insertArr("sc_shop_item",$rows);
		
				$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_FILE_NAME.'.gz';
				$f = gzopen($fname, "w9");
				gzwrite($f, file_get_contents($goods)); // записать строку в файл
				gzclose($f);
				if(!DEBUG_IMPORT)unlink($goods);	
		//		
				
				$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_PRICE_FILE_NAME.'.gz';
				$f = gzopen($fname, "w9");
				gzwrite($f, file_get_contents($goods_price)); // записать строку в файл
				gzclose($f);
				if(!DEBUG_IMPORT)unlink($goods_price);
				
				if(file_exists($goods_skidki)){
					$fname=$save_dir.'/'.date('Y_m_d_H_i_s').GOODS_SKIDKI_FILE_NAME.'.gz';
					$f = gzopen($fname, "w9");
					gzwrite($f, file_get_contents($goods_skidki)); // записать строку в файл
					gzclose($f);
					if(!DEBUG_IMPORT)unlink($goods_skidki);
				}
					
				
				if(!DEBUG_IMPORT)unlink($ok_file);
				
				$stop_time=time();
				$t=$stop_time-$start_time;
				
				$result=date('Y-m-d H:i:s').' - Время загрузки='.$t.'; Импортировано='.$cnt_imp.'; Импортировано по акции='.$cnt_imp_act.'; Добавлено='.$cnt_ins.'; Обновлено='.$cnt_upd."\r\n";
				
				file_put_contents($log_file,$result,FILE_APPEND);
				
				}///////////////////////////////update
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
}?>