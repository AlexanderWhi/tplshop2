<?php
include_once("core/component/BaseComponent.class.php");
include_once('core/num2str.php');
include_once('core/lib/MSXML.class.php');
class Prnt extends BaseComponent {
	

	function needAuth(){
		if($this->getUserId()==0 && @$_GET['access']!='allow'){
			echo 'пользователь не авторизован';
			exit ;
		}
	}
	
	function actCARD(){
		$this->needAuth();
		include('core/lib/MSXML.class.php');
		$xml=file_get_contents(dirname(__FILE__).'/schet_po_karte.xml');
		
		
		global $ST,$get;
		$q="SELECT * FROM sc_shop_order o WHERE o.id=".$get->getInt('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			$data=array(
				'SHOP_R_FIRM_BANK'=>$this->cfg('SHOP_R_FIRM_BANK'),
				'SHOP_R_FIRM_CORR_ACCOUNT'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),
				'SHOP_R_FIRM_BIK'=>$this->cfg('SHOP_R_FIRM_BIK'),
				'SHOP_R_FIRM_BANK_INN'=>$this->cfg('SHOP_R_FIRM_BANK_INN'),
				'SHOP_R_FIRM_ACCOUNT'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
				'SHOP_R_FIRM_DIRECTOR'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
				'SHOP_R_FIRM_PHONE'=>$this->cfg('SHOP_R_FIRM_PHONE'),
				'order'=>$rs->get('id'),
				'smm'=>$rs->get('man_smm')?$rs->get('man_smm'):$rs->get('total_price'),
			);
			header('Content-Type: application/vnd.ms-word');
	        header('Content-Disposition: attachment; filename=СЧЁТ_ПО_КАРТЕ_№'.$data['order'].'.doc');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        $tpl=new MSXML($xml,$data);
	        
	        array_walk_recursive($data,'toUtf');
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
	        echo $tpl->render();exit;
//			echo $this->render($data,dirname(__FILE__).'/schet_po_karte.xml');exit;
		}
	}

	/**
	 * По счёту
	 *
	 */
	function actPOSCHETU_word(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		include('core/num2str.php');
		$xml=file_get_contents(dirname(__FILE__).'/po_schetu.xml');
		
		$q="SELECT * FROM sc_shop_order o WHERE  o.id=".$get->get('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			
			$data=array(
				'documents'=>array(
					
					'doc_number'=>$rs->get('id'),
					'doc_date'=>dte($rs->get('date')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_INN'),
					
					
					'firm_phone'=>$this->cfg('SHOP_R_FIRM_PHONE'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bik'=>$this->cfg('SHOP_R_FIRM_BIK'),
					'firm_corr_account'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),//firm_corr_account
					
					'firm_name'=>$this->cfg('SHOP_R_FIRM_NAME'),

					'doc_receiver'=>$rs->get('last_name').' '.$rs->get('first_name').' '.$rs->get('middle_name'),
					'receiver_address'=>$rs->get('address'),

					
					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('address_jur'),
					'buyer_inn'=>$rs->get('inn'),
					'buyer_kpp'=>$rs->get('kpp'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					'delivery'=>$rs->get('delivery'),
					'total_amount'=>0,
					'goods'=>array()
					
				)
			);	

			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
				sc_shop_item si 
				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
				WHERE oi.itemid=si.id AND oi.orderid=".$get->get('id'));
			
			$nds=(float)$this->cfg('SHOP_R_NDS');
			$sum_nds=0;
			$total=0;
			$n=0;
			while ($rs->next()) {
				$price_bez_nds=round($rs->get('price')/$rs->get('count'),2);
				
				$total+=$rs->get('price');
					$data['documents']['goods'][]=array(
						'num'=>++$n,
						'name'=>$rs->get('name'),
						'measure'=>$rs->get('unit'),
						'amount'=>$rs->get('count'),
						'price_bez_nds'=>round($price_bez_nds,2),
						'sum_bez_nds'=>$rs->get('price'),
//						'nds'=>$nds,
//						'nds_sum'=>round($nds_sum,2),
//						'sum'=>round($sum,2),
//						'country'=>'-',
//						'gtd'=>'-',
					);
			}
			$total+=$data['documents']['delivery'];

//			$data['documents']['sum_nds']=round($sum_nds,2);
			$data['documents']['total']=round($total,2);
			$data['documents']['total_amount']=++$n;
			$data['documents']['total_propis']=num2str($data['documents']['total']);
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-word');
	        header('Content-Disposition: attachment; filename=ВЫПИСКА_ПО_СЧЁТУ_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.doc');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}

	function getData($id){
		global $ST;
		
		$q="SELECT * FROM sc_shop_order o WHERE o.id=".$id;
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		$data=array();
		$rs=$ST->select($q);
		if($rs->next()){
			$pay_account_jur=unserialize($rs->get('pay_account_jur'));
			$data=array(
				'documents'=>array(
					
					'doc_number'=>str_pad($rs->get('id'),10,'0',STR_PAD_LEFT),
					'doc_date'=>dte($rs->get('date')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bank_account'=>$this->cfg('SHOP_R_FIRM_BANK_ACCOUNT'),
					'firm_bank_bik'=>$this->cfg('SHOP_R_FIRM_BANK_BIK'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					
					'doc_receiver'=>$pay_account_jur['name'],
					'receiver_address'=>$pay_account_jur['addr'],

					
					'doc_buyer'=>$pay_account_jur['name'],
					'buyer_address'=>$pay_account_jur['addr'],
					'buyer_inn'=>$pay_account_jur['inn'],
					'buyer_kpp'=>$pay_account_jur['kpp'],
//					'buyer_inn'=>$rs->get('inn'),
//					'buyer_kpp'=>$rs->get('kpp'),
//					'doc_buyer'=>$rs->get('company'),
//					'buyer_address'=>$rs->get('address_jur'),
					
					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					
					'sum_nds'=>0,
					'total'=>0,
					
					'goods'=>array(),
					'total_amount'=>0,
					
				)
			);	

//			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
//				sc_shop_item si 
//				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
//				WHERE oi.itemid=si.id AND oi.orderid=".$id);
			$rs=$ST->select("SELECT si.name,oi.count,oi.price FROM sc_shop_order_item oi,
				sc_shop_item si 
				WHERE oi.itemid=si.id AND oi.orderid=".$id);
			
			$nds=(float)$this->cfg('SHOP_R_NDS');
			if(!$this->cfg('SHOP_R_NDS_EXISTS')){
				$nds=0;
			}
			
			$sum_nds=0;
			$total=0;
			$i=0;
			while ($rs->next()) {
				$price_bez_nds=round($rs->get('price'),2);
				$nds_sum=$rs->get('price')*$rs->get('count')/100*$nds;
				$sum=$nds_sum+$rs->get('price')*$rs->get('count');
				$sum_nds+=$nds_sum;
				$total+=$sum;
					$data['documents']['goods'][]=array(
						'height'=>'11',
						'name'=>$rs->get('name'),
						'measure'=>'шт.',
						'amount'=>$rs->get('count'),
						'price_bez_nds'=>round($price_bez_nds,2),
						'sum_bez_nds'=>$rs->get('price')*$rs->get('count'),
						'nds'=>$nds,
						'nds_sum'=>round($nds_sum,2),
						'sum'=>round($sum,2),
						'country'=>'-',
						'gtd'=>'-',
						'n'=>++$i,
					);
				$data['documents']['total_amount']+=$rs->get('count');
			}

			$data['documents']['sum_nds']=round($sum_nds,2);
			$data['documents']['total']=round($total,2);
			
			
			
			$data['documents']['total_propis']=num2str($data['documents']['total']);
//			$data['documents']['amount_propis']=num2str($n,true,false);
		}
		return $data;
	}
	
	/**
	 * Печать акта, счёт фактуры 13.07.2012
	 *
	 */
	function actACT(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/act_shet_factura.xml');
//		$xml=file_get_contents(dirname(__FILE__).'/rep/schet_faktura.xml');
		$data=$this->getData($get->get('id'));
		
		if($data){
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment; filename=АКТ-СЧЕТ-ФАКТУРА_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.xls');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	
	/**
	 * Печать счёта 17.07.2012
	 *
	 */
	function actSHET(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/shet.xml');
//		$xml=file_get_contents(dirname(__FILE__).'/rep/schet_faktura.xml');
		$data=$this->getData($get->get('id'));
		
		if($data){
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment; filename=СЧЕТ_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.xls');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	
	
	/**
	 * Счёт фактура
	 *
	 */
	function actSCHF(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/schet_faktura.xml');
		
		$q="SELECT * FROM sc_shop_order o WHERE o.id=".$get->get('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			$pay_account_jur=unserialize($rs->get('pay_account_jur'));
			$data=array(
				'documents'=>array(
					
					'doc_number'=>str_pad($rs->get('id'),10,'0',STR_PAD_LEFT),
					'doc_date'=>dte($rs->get('date')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),

					'doc_receiver'=>$pay_account_jur['name'],
					'receiver_address'=>$pay_account_jur['addr'],

					
					'doc_buyer'=>$pay_account_jur['name'],
					'buyer_address'=>$pay_account_jur['addr'],
					'buyer_inn'=>$pay_account_jur['inn'],
					'buyer_kpp'=>$pay_account_jur['kpp'],					
					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					
					'goods'=>array()
					
				)
			);	

			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
				sc_shop_item si 
				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
				WHERE oi.itemid=si.id AND oi.orderid=".$get->get('id'));
			
			$nds=(float)$this->cfg('SHOP_R_NDS');
			$sum_nds=0;
			$total=0;
			
			while ($rs->next()) {
				$price_bez_nds=round($rs->get('price')/$rs->get('count'),2);
				$nds_sum=$rs->get('price')/100*$nds;
				$sum=$nds_sum+$rs->get('price');
				$sum_nds+=$nds_sum;
				$total+=$sum;
					$data['documents']['goods'][]=array(
						'height'=>'11',
						'name'=>$rs->get('name'),
						'measure'=>$rs->get('unit'),
						'amount'=>$rs->get('count'),
						'price_bez_nds'=>round($price_bez_nds,2),
						'sum_bez_nds'=>$rs->get('price'),
						'nds'=>$nds,
						'nds_sum'=>round($nds_sum,2),
						'sum'=>round($sum,2),
						'country'=>'-',
						'gtd'=>'-',
					);
			}

			$data['documents']['sum_nds']=round($sum_nds,2);
			$data['documents']['total']=round($total,2);
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment; filename=СЧЕТ-ФАКТУРА_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.xls');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	function actSCHF_old(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/schet_faktura.xml');
		
		$q="SELECT * FROM sc_shop_order o,sc_users_jur_ank a WHERE o.userid=a.userid AND o.id=".$get->get('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			
			$data=array(
				'documents'=>array(
					
					'doc_number'=>str_pad($rs->get('id'),10,'0',STR_PAD_LEFT),
					'doc_date'=>dte($rs->get('date')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_INN'),

					'doc_receiver'=>$rs->get('company'),
					'receiver_address'=>$rs->get('address'),

					
					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('address_jur'),
					'buyer_inn'=>$rs->get('inn'),
					'buyer_kpp'=>$rs->get('kpp'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					
					'goods'=>array()
					
				)
			);	

			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
				sc_shop_item si 
				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
				WHERE oi.itemid=si.id AND oi.orderid=".$get->get('id'));
			
			$nds=(float)$this->cfg('SHOP_R_NDS');
			$sum_nds=0;
			$total=0;
			
			while ($rs->next()) {
				$price_bez_nds=round($rs->get('price')/$rs->get('count'),2);
				$nds_sum=$rs->get('price')/100*$nds;
				$sum=$nds_sum+$rs->get('price');
				$sum_nds+=$nds_sum;
				$total+=$sum;
					$data['documents']['goods'][]=array(
						'height'=>'11',
						'name'=>$rs->get('name'),
						'measure'=>$rs->get('unit'),
						'amount'=>$rs->get('count'),
						'price_bez_nds'=>round($price_bez_nds,2),
						'sum_bez_nds'=>$rs->get('price'),
						'nds'=>$nds,
						'nds_sum'=>round($nds_sum,2),
						'sum'=>round($sum,2),
						'country'=>'-',
						'gtd'=>'-',
					);
			}

			$data['documents']['sum_nds']=round($sum_nds,2);
			$data['documents']['total']=round($total,2);
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment; filename=СЧЕТ-ФАКТУРА_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.xls');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	/**
	 * Автогенерация товарной накладной (торг 12)
	 *
	 */
	function actNAKL(){
		$this->needAuth();
		global $ST,$get;
		include('core/lib/MSXML.class.php');
		include('core/num2str.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/torg12_1.xml');
		
		$q="SELECT * FROM sc_shop_order o,sc_users_jur_ank a WHERE o.userid=a.userid AND o.id=".$get->get('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		$rs=$ST->select($q);
		if($rs->next()){
			
			$data=array(
				'documents'=>array(
					
					'req'=>str_pad($rs->get('id'),10,'0',STR_PAD_LEFT),
					'req_date'=>dte($rs->get('date')),
				
					'doc_number'=>str_pad($rs->get('id'),10,'0',STR_PAD_LEFT),
					
					'doc_date'=>dte($rs->get('date')),//date('d.m.Y'),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_phone'=>$this->cfg('SHOP_R_FIRM_PHONE'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bik'=>$this->cfg('SHOP_R_FIRM_BIK'),
					'firm_corr_account'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),//firm_corr_account
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'our_okpo'=>$this->cfg('SHOP_R_FIRM_OKPO'),

					'doc_receiver'=>$rs->get('company'),
					'receiver_address'=>$rs->get('address'),
					'receiver_account'=>$rs->get('account'),
					'receiver_bank'=>$rs->get('bank'),
					'receiver_bik'=>$rs->get('bik'),
					'receiver_cor'=>$rs->get('kor'),

					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('address_jur'),
					'receiver_inn'=>$rs->get('inn'),
					'receiver_kpp'=>$rs->get('kpp'),
					'receiver_okpo'=>$rs->get('kod_okpo'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					'doc_holiday_maker'=>'',
					'doc_date_propis'=>dte($rs->get('date')),
					
					'sum_nds'=>0,
					'total_amount'=>0,
					'sum_bez_nds'=>0,
					'sum_nds'=>0,
					'total'=>0,
					'pages'=>1,
					'amount_propis'=>'amount_propis',//
					'total_propis'=>'total_propis',//
					'sum_nds_str'=>'',
					'goods'=>array()
				),
				'pagebreaks'=>array(
					array('row'=>43)
				),
			);
			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
				sc_shop_item si 
				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
				WHERE oi.itemid=si.id AND oi.orderid=".$get->get('id'));
			
			$nds=(float)$this->cfg('SHOP_R_NDS');
			$sum_nds=0;
			$sum_bez_nds=0;
			$total=0;
			$amount_tmp=0;
			$n=0;
			while ($rs->next()) {
				$price_bez_nds=round($rs->get('price')/$rs->get('count'),2);
				$nds_sum=$rs->get('price')/100*$nds;
				$sum=$nds_sum+$rs->get('price');
				$sum_nds+=$nds_sum;
				$total+=$sum;
				
				
				$amount_tmp+=$rs->get('count');
				$sum_bez_nds+=$rs->get('price');
					$data['documents']['goods'][]=array(
						'num'=>++$n,
						'height'=>'11',
						'name'=>$rs->get('name'),
						'measure'=>$rs->get('unit'),
						'okei'=>'x',
						'amount'=>$rs->get('count'),
						'price_bez_nds'=>round($price_bez_nds,2),
						'sum_bez_nds'=>$rs->get('price'),//
						'nds'=>$nds,
						'nds_sum'=>round($nds_sum,2),
						'sum'=>round($sum,2),
//						'total'=>round($sum,2),
						'country'=>'-',
						'gtd'=>'-',
						
						
						
					);
			}

			$data['documents']['sum_nds']=round($sum_nds,2);
			$data['documents']['sum_nds_str']=num2str($data['documents']['sum_nds']);
			$data['documents']['sum_bez_nds']=round($sum_bez_nds,2);
			$data['documents']['total']=round($total,2);
			
//			echo num2str(642.12);
			
			$data['documents']['total_propis']=num2str($data['documents']['total']);
			$data['documents']['amount_propis']=num2str($n,true,false);
			
			
			$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment; filename=ТОРГ_12_№_'.$data['documents']['doc_number'].'_от_'.$data['documents']['doc_date'].'.xls');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	
	function actCoupon(){
		global $ST,$get;
		
		$m=GeoIp::getYMap("Югозападный район, Ул. Онуфриева");
		
		include('core/lib/MSXML.class.php');
//		include('core/num2str.php');
		$xml=file_get_contents(dirname(__FILE__).'/rep/coupon_doc.xml');
		
		$data=array('img'=>$m);
		$tpl=new MSXML($xml,$data);
			header('Content-Type: application/vnd.ms-word');
	        header('Content-Disposition: attachment; filename=ПЕЧАТЬ_КУПОНА_№_'.$get->get('id').'.doc');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
	        echo iconv('cp1251','utf-8',$tpl->render());exit;
	}
	function actCouponPDF(){
		global $ST,$get;
		
		$id=$get->getInt('id');
		
		
		$rs=$ST->select("SELECT * FROM sc_shop_item WHERE id={$id}");
		if($rs->next()){
			
			$data=array(
				'img'=>'',
				'num'=>$get->get('num'),//Номер
				'pass'=>$get->get('pass'),//Пароль
				'name'=>$rs->get('name'),//Пароль
				'html'=>$rs->get('html'),//Общее описание
				'html2'=>$rs->get('html2'),//условия
				'html3'=>$rs->get('html3'),//контакты
			);
			
//			$addr="Югозападный район, Ул. Онуфриева";
			$addr=$rs->get('address');
			if($addr){
				$img_path="tmp/".md5($addr).".png";
				if(!file_exists($img_path)){
					$m=GeoIp::getYMap2($addr);
					if($m){
						file_put_contents($img_path,$m);
						$data['img']=$img_path;
					}
				}else{
					$data['img']=$img_path;
				}
			}
			
			include("shared/php/mPDF/mpdf.php");
	
//			$str = "<strong>Привет</strong>";
	
			$tpl='coupon.tpl.php';
			if($data['pass']){
				$tpl='coupon_uni.tpl.php';
			}
			
			$str=$this->render($data,dirname(__FILE__)."/rep/$tpl");  
	//		$mpdf = new mPDF('UTF-8','A4','','',10,10,0,10,0,0);
			$mpdf = new mPDF('UTF-8','A4','','');
			@$mpdf->WriteHTML(iconv('windows-1251','utf-8',$str));
			@$mpdf->Output();
		}else{
			echo 'Не найдено!';
		}
	}
	
	function actShetPDF(){
		$this->needAuth();
		global $ST,$get;
		$xml=file_get_contents(dirname(__FILE__).'/schet.xml');
		
		$q="SELECT o.*,u.company,u.other_info,u.address AS u_address FROM sc_shop_order o 
		LEFT JOIN sc_users u ON u_id=o.userid
		
		WHERE  o.id=".$get->getInt('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			
			$inn='';
			if($other_info=getJSON($rs->get('other_info'))){
				$inn=@$other_info['inn'];
			}
			
			$data=array(
			
					'doc_number'=>$rs->get('id'),
					'order_num'=>$rs->get('ordernum'),
					'doc_date'=>dte($rs->get('create_time')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_INN'),
					
					'firm_phone'=>$this->cfg('SHOP_R_FIRM_PHONE'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bank_bik'=>$this->cfg('SHOP_R_FIRM_BANK_BIK'),
					'firm_corr_account'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),//firm_corr_account
					
					'firm_name'=>$this->cfg('SHOP_R_FIRM_NAME'),

					'doc_receiver'=>$rs->get('last_name').' '.$rs->get('first_name').' '.$rs->get('middle_name'),
					'receiver_address'=>$rs->get('u_address'),

					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('u_address'),
					'buyer_inn'=>$inn,
					'buyer_kpp'=>$rs->get('kpp'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					'delivery'=>$rs->get('delivery'),
					'total_amount'=>$rs->get('total_price'),
					
					'goods'=>array()
					
				
			);	


			$data['total_propis']=num2str($data['total_amount']);
//			$tpl=new MSXML($xml,$data,true);
//			header('Content-Type: application/vnd.ms-word');
//	        header('Content-Disposition: attachment; filename=СЧЕТ_НА_ОПЛАТУ_№_'.$data['order_num'].'_от_'.$data['doc_date'].'.doc');
//	        header('Expires: 0');
//	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

			include("shared/php/mPDF/mpdf.php");
	
//			$str = "<strong>Привет</strong>";
	
			$tpl='schet.htm';
			
			$data['dir']=dirname(__FILE__);
			$str=$this->render($data,dirname(__FILE__)."/$tpl");  
	//		$mpdf = new mPDF('UTF-8','A4','','',10,10,0,10,0,0);
			$mpdf = new mPDF('UTF-8','A4','','');
			@$mpdf->WriteHTML(iconv('windows-1251','utf-8',$str));
			@$mpdf->Output();
	        
		}
	    exit;
	}
	
	
	
	function actSchetWord(){
		$this->needAuth();
		global $ST,$get;
		
		$xml=file_get_contents(dirname(__FILE__).'/schet.xml');
		
		$q="SELECT o.*,u.company,u.other_info,u.address AS u_address FROM sc_shop_order o 
		LEFT JOIN sc_users u ON u_id=o.userid
		
		WHERE  o.id=".$get->getInt('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			
			$inn='';
			if($other_info=getJSON($rs->get('other_info'))){
				$inn=@$other_info['inn'];
			}
			
			$data=array(
				'documents'=>array(
					'doc_number'=>$rs->get('id'),
					'order_num'=>$rs->get('ordernum'),
					'doc_date'=>dte($rs->get('create_time')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_INN'),
					
					'firm_phone'=>$this->cfg('SHOP_R_FIRM_PHONE'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bank_bik'=>$this->cfg('SHOP_R_FIRM_BANK_BIK'),
					'firm_corr_account'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),//firm_corr_account
					
					'firm_name'=>$this->cfg('SHOP_R_FIRM_NAME'),

					'doc_receiver'=>$rs->get('last_name').' '.$rs->get('first_name').' '.$rs->get('middle_name'),
					'receiver_address'=>$rs->get('u_address'),

					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('u_address'),
					'buyer_inn'=>$inn,
					'buyer_kpp'=>$rs->get('kpp'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					'delivery'=>$rs->get('delivery'),
					'total_amount'=>$rs->get('total_price'),
					'goods'=>array()
					
				)
			);	

//			$rs=$ST->select("SELECT si.name,u.value_desc as unit,oi.count,oi.price FROM sc_shop_order_item oi,
//				sc_shop_item si 
//				LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='unit') AS u ON u.field_value=si.unit
//				WHERE oi.itemid=si.id AND oi.orderid=".$get->get('id'));
//			
//			$nds=(float)$this->cfg('SHOP_R_NDS');
//			$sum_nds=0;
//			$total=0;
//			$n=0;
//			while ($rs->next()) {
//				$price_bez_nds=round($rs->get('price')/$rs->get('count'),2);
//				
//				$total+=$rs->get('price');
//					$data['documents']['goods'][]=array(
//						'num'=>++$n,
//						'name'=>$rs->get('name'),
//						'measure'=>$rs->get('unit'),
//						'amount'=>$rs->get('count'),
//						'price_bez_nds'=>round($price_bez_nds,2),
//						'sum_bez_nds'=>$rs->get('price'),
////						'nds'=>$nds,
////						'nds_sum'=>round($nds_sum,2),
////						'sum'=>round($sum,2),
////						'country'=>'-',
////						'gtd'=>'-',
//					);
//			}
//			$total+=$data['documents']['delivery'];

//			$data['documents']['sum_nds']=round($sum_nds,2);
//			$data['documents']['total']=round($total,2);
//			$data['documents']['total_amount']=++$n;
			$data['documents']['total_propis']=num2str($data['documents']['total_amount']);
			$tpl=new MSXML($xml,$data['documents'],true);
			header('Content-Type: application/vnd.ms-word');
	        header('Content-Disposition: attachment; filename=СЧЕТ_НА_ОПЛАТУ_№_'.$data['documents']['order_num'].'_от_'.$data['documents']['doc_date'].'.doc');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        
//	        echo iconv('cp1251','utf-8',$tpl->render());exit;
	        echo $tpl->render();exit;
		}else{
			echo 'Ошибка формирования';
		}  
	}
	
	function actSberPDF(){
//		$this->needAuth();
		global $ST,$get;
		
		$q="SELECT o.*,u.company,u.other_info,u.address AS u_address FROM sc_shop_order o 
		LEFT JOIN sc_users u ON u_id=o.userid
		
		WHERE  o.id=".$get->getInt('id');
		if(strpos('user',$this->getUser('type'))!==false){
			$q.=" AND o.userid=".$this->getUserId();
		}
		
		$rs=$ST->select($q);
		if($rs->next()){
			
			$inn='';
			if($other_info=getJSON($rs->get('other_info'))){
				$inn=@$other_info['inn'];
			}
			
			$data=array(
			
					'doc_number'=>$rs->get('id'),
					'order_num'=>$rs->get('ordernum'),
					'doc_date'=>dte($rs->get('create_time')),
					'full_firm_name'=>$this->cfg('SHOP_R_FULL_FIRM_NAME'),
					'firm_name1'=>$this->cfg('SHOP_R_FIRM_NAME'),
					'firm_address'=>$this->cfg('SHOP_R_FIRM_ADRESS'),
					'firm_inn'=>$this->cfg('SHOP_R_FIRM_INN'),
					'firm_kpp'=>$this->cfg('SHOP_R_FIRM_KPP'),
					'firm_name'=>$this->cfg('SHOP_R_FIRM_INN'),
					
					'firm_phone'=>$this->cfg('SHOP_R_FIRM_PHONE'),
					'firm_account'=>$this->cfg('SHOP_R_FIRM_ACCOUNT'),
					'firm_bank'=>$this->cfg('SHOP_R_FIRM_BANK'),
					'firm_bank_bik'=>$this->cfg('SHOP_R_FIRM_BANK_BIK'),
					'firm_corr_account'=>$this->cfg('SHOP_R_FIRM_CORR_ACCOUNT'),//firm_corr_account
					
					'firm_name'=>$this->cfg('SHOP_R_FIRM_NAME'),

//					'doc_receiver'=>$rs->get('last_name').' '.$rs->get('first_name').' '.$rs->get('middle_name'),
					'doc_receiver'=>$rs->get('fullname').' '.$rs->get('first_name').' '.$rs->get('middle_name'),
					'receiver_address'=>$rs->get('u_address'),

					'doc_buyer'=>$rs->get('company'),
					'buyer_address'=>$rs->get('u_address'),
					'buyer_inn'=>$inn,
					'buyer_kpp'=>$rs->get('kpp'),

					'doc_director'=>$this->cfg('SHOP_R_FIRM_DIRECTOR'),
					'doc_accountant'=>$this->cfg('SHOP_R_FIRM_ACCOUNTANT'),
					
					'sum_nds'=>0,
					'total'=>0,
					'delivery'=>$rs->get('delivery'),
					'total_amount'=>$rs->get('total_price'),
					
					'goods'=>array()
					
				
			);	


			$data['total_propis']=num2str($data['total_amount']);
//			$tpl=new MSXML($xml,$data,true);
//			header('Content-Type: application/vnd.ms-word');
//	        header('Content-Disposition: attachment; filename=СЧЕТ_НА_ОПЛАТУ_№_'.$data['order_num'].'_от_'.$data['doc_date'].'.doc');
//	        header('Expires: 0');
//	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

			include("shared/php/mPDF/mpdf.php");
	
//			$str = "<strong>Привет</strong>";
	
			$tpl='schet_sber.htm';
			
			$data['dir']=dirname(__FILE__);
			$str=$this->render($data,dirname(__FILE__)."/$tpl");  
	//		$mpdf = new mPDF('UTF-8','A4','','',10,10,0,10,0,0);
			$mpdf = new mPDF('UTF-8','A4','','');
			@$mpdf->WriteHTML(iconv('windows-1251','utf-8',$str));
			@$mpdf->Output();
	        
		}
	    exit;
	}
	
	
}
?>