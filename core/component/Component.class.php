<?php
include_once 'BaseComponent.class.php';
class Component extends BaseComponent {
	/**
	 * Основные настройки компоненты
	 */
	protected  $tplContainer="template/www/pages/common_container.tpl.php";
//	protected  $tplContainer="core/tpl/www/common_container_lc.tpl.php";
//	protected  $tplContainer="core/tpl/www/main_container.tpl.php";
	protected  $tplComponent="";
	protected  $tplLeftComponent="";
	public $show_header=true;
	function setCommonCont(){
		$this->tplContainer="template/www/pages/common_container.tpl.php";
	}
	function setContainer($tpl){
		$this->tplContainer="template/{$this->theme()}/pages/$tpl";
	}
	
	function actDefault(){
		
		$side_menu=$this->getContentMenu();
//		$this->tplContainer="template/www/pages/common_container_lc.tpl.php";
		$this->display(array('side_menu'=>$side_menu),"template/{$this->theme()}/pages/content.tpl.php");
	}
	
	function theme(){
		global $CONFIG;
		return $CONFIG['THEME'];
	}
	
	function subTheme(){
		$theme='day';
//		if(date('H')>=20 || date('H')<=8){
		if(date('H')>=17 || date('H')<=8){
			$theme='night';
		}else{
			$theme='day';
		}
		if(isset($_COOKIE['theme'])){
			if($_COOKIE['theme']=='day'){
				$theme='day';
			}elseif ($_COOKIE['theme']=='night'){
				$theme='night';
			}elseif ($_COOKIE['theme']=='old'){
				$theme='old';
			}	
		}
		if(isset($_GET['theme'])){
			if($_GET['theme']=='day'){
				$theme='day';
			}elseif ($_GET['theme']=='night'){
				$theme='night';
			}elseif ($_GET['theme']=='old'){
				$theme='old';
			}
			setcookie ('theme',$theme,time()+(60*60),"/","",0);
		}
		//$theme='night';
		//$theme='day';
		return $theme;	
	}
	
	function show(){
		if($this->isAdmin()){
			echo '<a href="/admin/content/?act=edit&id='.$this->mod_content_id.'" class="coin-text-edit" title="Редактировать"></a>';
		}
		echo $this->getContent();
	}
	function getContent(){
		global $ST;
		$rs=$ST->select('SELECT c_text FROM sc_content WHERE c_id='.$this->mod_content_id);
		if($rs->next()){
			return $rs->get('c_text');
		}
		return '';
	}

	private $advertising=array();
	private $advertisingView=array();
		
	function refreshContainer(){
//		$this->updateCatalog();
		$this->updateAdvertising();
	}
		
	function needAuth(){
		if($this->getUserId()==0){
			header("Location: /join/?rel={$this->getURI()}");exit;
		}
	}
	

	function getContentMenu($loc='main',$parent=0){
		if(!$parent){
			$parent=$this->mod_id;
		}
		$menu=array();
		
		
		
		$query="SELECT *  
			FROM sc_module 
			WHERE mod_state>0  
				AND mod_location like '%$loc%' 
				AND mod_parent_id=$parent  
			ORDER BY mod_position";
		$rs=DB::select($query);
		while($rs->next()){
			$menu[$rs->get('mod_id')]=$rs->getRow();
		}
		if(!$menu){
			$query="SELECT *  
				FROM sc_module 
				WHERE mod_state>0  
					AND mod_location like '%$loc%' 
					AND mod_parent_id=$this->mod_parent_id  
				ORDER BY mod_position";
			$rs=DB::select($query);
			while($rs->next()){
				$menu[$rs->get('mod_id')]=$rs->getRow();
			}
			$parent=$this->mod_parent_id ;
		}
		
			$query="SELECT * 
				FROM sc_module 
				WHERE mod_state>0  
					AND mod_location like '%$loc%' 
					AND mod_id=$parent  
				ORDER BY mod_position";
			$rs=DB::select($query);
			if($rs->next()){
				$menu=array($rs->getRow())+$menu;
			}
		
		
		
		
			
		
		
		return $menu;
	}
	
	function getMenu($loc='main'){
		global $ST;
		$menu=array();
		
		$cond="AND mod_location like '%$loc%'";
		if(is_array($loc)){
			$or=array();
			foreach ($loc as $l) {
				$or[]="mod_location like '%$l%'";
			}
			$cond=" AND (".implode(' OR ',$or).")";
		}
		
		$query="SELECT mod_id,mod_parent_id,mod_alias,mod_title,mod_name ,mod_position 
			FROM sc_module 
			WHERE mod_state>0  
			$cond	 
			ORDER BY mod_position";
		$rs=$ST->select($query);
		while($rs->next()){
			foreach ($rs->getRow() as $k=>$v) {
				$item[$rs->get('mod_id').'_'][$k]=$v;
			}
			if($rs->getInt('mod_parent_id')){
				$item[$rs->get('mod_parent_id').'_']['children'][$rs->getInt('mod_id').'_']=&$item[$rs->get('mod_id').'_'];
			}else{
				$menu[$rs->get('mod_id').'_']=&$item[$rs->get('mod_id').'_'];
			}
		}
		return $menu;
	}
	function getMenuLoc($loc='',$parent=0){
		global $ST;
		$menu=array();
		$query="SELECT mod_id,mod_parent_id,mod_alias,mod_title,mod_name ,mod_position 
			FROM sc_module 
			WHERE mod_state>0  
				AND mod_location like '%$loc%' 
				AND mod_parent_id=$parent  
			ORDER BY mod_position";
		$rs=$ST->select($query);
		while($rs->next()){
			$menu[$rs->get('mod_id')]=$rs->getRow();
		}
		return $menu;
	}
	function getFooterMenu(){
		global $ST;
		$menu=array();
		$query="SELECT mod_id,mod_parent_id,mod_alias,mod_title,mod_name ,mod_position 
			FROM sc_module 
			WHERE mod_state>0  
				AND mod_location like '%footer%' 
				AND mod_parent_id=0  
			ORDER BY mod_position";
		$rs=$ST->select($query);
		while($rs->next()){
			$menu[$rs->get('mod_id').'_']=$rs->getRow();
		}
		return $menu;
	}
	
		
//	function checkMail($mail,$exists=true){
//		global $ST;
//		if(!check_mail($mail)){
//			return 'Введите правильный e-mail';
//		}elseif($exists){
//			$rs=$ST->select("SELECT * FROM sc_users WHERE (mail='".SQL::slashes($mail)."' OR login='".SQL::slashes($mail)."') AND u_id!=".$this->getUserId()."  LIMIT 1");
//			if($rs->next()){
//				return 'e-mail уже зарегистрирован';
//			}
//		}
//		return '';
//	}
	
	

	/**
	 * Капча
	 *
	 * @param string $name
	 */
	function capture($name='capture',$inp=true){
		include("template/{$this->theme()}/pages/capture.tpl.php");
	}

	function checkCapture($val,$nme='capture'){
		if(defined('IMG_SECURITY') && (!Cookie::get(IMG_SECURITY.$nme) || (md5($val)!=Cookie::get(IMG_SECURITY.$nme)))){
			return false;
		}
		return true;
	}
	
	//Страниц
	function getPageSize(){
		if(!$default=intval($this->cfg('PAGE_SIZE'))){
			$default=20;
		}
		return isset($_COOKIE['pgs'])&& $_COOKIE['pgs']?intval($_COOKIE['pgs']):$default;
	}
	function actChangePageSize(){
		setcookie('pgs',intval($_GET['pages']),COOKIE_EXP,'/');
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	
	/**
	 * блок работы рекламы
	 * 
	 */
	function updateAdvertising(){
		global $ST;
		$q="SELECT ad.*,pl.width,pl.height,pl.description AS pl_desc FROM sc_advertising AS ad,sc_advertising_place AS pl WHERE ad.start_date<=DATE(NOW()) AND ad.stop_date>=DATE(NOW()) AND pl.id=ad.place ORDER BY RAND()";
		
		foreach ($this->cacheSelect($q,0) as $row) {
		 	
			$this->advertising[$row['place']]=$row;
			$this->advertising[$row['pl_desc']]=$row;
		}
	}
	
	function updateAdvView(){
		if($this->advertisingView){
			$this->getStatement()->update('sc_advertising',array('show_ad=show_ad+1'),"id IN ('".join("','",$this->advertisingView)."')");
		}
	}
	
	function adv($id){
		if(isset($this->advertising[$id])){
			$advertising=$this->advertising[$id];
			$id=$advertising['id'];
			
			$r="<!--adv#{$id} -->\n";
			
			$w_str='';
			$h_str='';
			if($advertising['width']){
				$w_str="width:{$advertising['width']}px;";
			}
			if($advertising['height']){
				$h_str="height:{$advertising['height']}px;";
			}
			$r.='<div title="'.$advertising['description'].'" id="adv'.$id.'" class="adv_place'.$advertising['place'].'" style="'.$w_str.$h_str.';overflow:hidden;position:relative">';
			
			if($this->isAdmin()){
				$r.='<a href="/admin/adv/place/?act=plEdit&id='.$id.'" class="coin-place-edit" title="Править место"></a>';
				$r.='<a href="/admin/adv/?act=edit&id='.$advertising['id'].'" class="coin-banner-edit" title="Править баннер"></a>';
			}
			
			if(trim($advertising['file'])){
				$r.='<div class="banner-img">';
				$rand=time();
				$href="";
				if($advertising['url']){
					$href='/redirect.php?url='.$advertising['url'].'&r='.$rand.'&p='.$advertising['id'].'&k='.md5(session_id().$rand.$advertising['url']);
				}
				
				if(preg_match('/\.(jpg|jpeg|png|gif)$/i',$advertising['file'])){
					if($href)$r.='<a href="'.$href.'" style="padding:0" title="'.$advertising['description'].'" >';
					$r.='<img src="'.$advertising['file'].'" alt="'.$advertising['url'].'" style="margin:0;border:0">';
					if($href)$r.='</a>';
				}elseif(preg_match('/\.(swf)$/i',$advertising['file'])){
						if($href)$r.='<a title="'.$advertising['description'].'"  href="'.$href.'" style="display:block;position:absolute;z-index:1;width:100%;height:100%;background:url(/img/pixel.gif)"></a>';
						$r.='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$advertising['width'].'" height="'.$advertising['height'].'">
						<param name="movie" value="'.$advertising['file'].'?link='.urlencode($href).'" />
						<param name="quality" value="high" />
						<param name="wmode" value="opaque" />
						<param name="allowScriptAccess" value="always" />
						<embed src="'.$advertising['file'].'?link='.urlencode($href).'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$advertising['width'].'" height="'.$advertising['height'].'" allowScriptAccess="always" wmode="opaque"></embed>
						</object>';
				}
				$r.='</div>';
			}
			
			if(trim($advertising['code'])){
				$r.='<div class="banner-code">';
				$r.=$advertising['code'];
				$r.='</div>';
			}
			
			
			$r.='</div>';
			$this->advertisingView[]=$advertising['id'];		
			return $r;
		}
		return '';
	}
	
	function getCeoText($place){
		global $ST;
		$text='';
		$output='';
		
		/*ceo*/
		$rs=$ST->select("SELECT text,url FROM sc_ceo_text
			 WHERE
			 	place='$place' 
			 	AND ((url='".SQL::slashes($_SERVER['REQUEST_URI'])."' AND rule='=') 
			 	OR ('".SQL::slashes($_SERVER['REQUEST_URI'])."' LIKE CONCAT(url,'%') AND rule!='=' ))
			 ORDER BY LENGTH(url) DESC LIMIT 1");
//		$rs=$ST->select("SELECT text,url FROM sc_ceo_text
//			 WHERE
//			 	place='$place' 
//			 	AND ((url='".SQL::slashes($_SERVER['REQUEST_URI'])."' AND rule='=') 
//			 	OR ('".SQL::slashes($_SERVER['REQUEST_URI'])."' LIKE url||'%' AND rule!='=' ))
//			 ORDER BY LENGTH(url) DESC LIMIT 1");
		/*ceo*/
		$url=$_SERVER['REQUEST_URI'];
		
		
		if($rs->next()){
			$text.= $rs->get('text');
			$url= $rs->get('url');
		}
		if($this->isAdmin()){
			$url='<a href="'.$url.'" rel="'.$url.'" class="coin-text-edit ceo-text" id="ceo-place'.$place.'" title="Редактировать"></a>';
			if(!$text){
				$text='{ANY TEXT HERE}';
			}
			$output.=$url;
		}
		$output.=$text;
		return $output;
	}
	
	function getText($name){
		return '{%'.$name.'%}';
	}
	
	function getTplContainer($tplContainer=null){
		if(!$tplContainer){
			$tplContainer=$this->tplContainer;
		}
		if($this->getURIBoolVal('print')){
			$tplContainer='core/tpl/admin/admin_print.php';
		}
		return preg_replace('|/www/|',"/{$this->theme()}/",$tplContainer);
	}
	
	function getTpl($tpl_name){
		$path="template/{$this->theme()}/pages/{$this->mod_module_name}/{$tpl_name}";
		$path2="modules/{$this->mod_module_name}/{$tpl_name}";
		if(file_exists($path)){
			return $path;
		}elseif(file_exists($path2)){
			return $path2;
		}
		return false;
	}
	
	function display($data=array(),$tpl=null,$tplContainer=null,$cache=null){
		global $ST;
		if(!$tpl){
			$tpl=$this->tplComponent;
		}
		
		if(!$tplContainer){
			$tplContainer=$this->getTplContainer();
		}
		$this->refreshContainer();

		/*ceo*/		
		$rs=$ST->select("SELECT * FROM sc_ceo_meta
			 WHERE 
			 	(url='".SQL::slashes($_SERVER['REQUEST_URI'])."' AND rule='=') 
			 	OR ('".SQL::slashes($_SERVER['REQUEST_URI'])."' LIKE CONCAT(url,'%') AND rule!='=' ) 
			 ORDER BY LENGTH(url) DESC LIMIT 1");
		/*ceo*/
		$this->setCeo(array('url'=>$_SERVER['REQUEST_URI']));
		if($rs->next()){
			$this->setCeo($rs->getRow());
			if($this->getCeo('title'))$this->setTitle($this->getCeo('title'));
			if($this->getCeo('header'))$this->setHeader($this->getCeo('header'));
			if($this->getCeo('description'))$this->setDescription($this->getCeo('description'));
			if($this->getCeo('keywords'))$this->setKeywords($this->getCeo('keywords'));
			
		}
		
		
		$rnd=$this->render($data,$tpl);
		$out=$this->render($data,$tplContainer,$rnd);
		$t=microtime(true);
		if(preg_match_all('/\{%(.+)%\}/U',$out,$res) && $res){
			$vals=array();
			foreach ($res[1] as $id=>$key){
				//07.09.2011 Возможность создавать содержимое по названию
				$tmp='';
				if($this->isAdmin()){
					$tmp= '<a href="/admin/content/?act=edit&name='.trim($key).'" class="coin-text-edit" title="Редактировать"></a>'.$res[0][$id];
				}
				
				$vals[trim($key)]=$tmp;
			}
			
			foreach ($this->cacheSelect("SELECT * FROM sc_content WHERE c_name IN('".implode("','",array_keys($vals))."')",0) as $row) {
			 	
				$res=$row['c_text'];
				if($this->isAdmin()){
					$res= '<a href="/admin/content/?act=edit&name='.$row['c_name'].'" class="coin-text-edit" title="Редактировать"></a>'.$res;
				}
				$vals[$row['c_name']]=$res;
			}
			$out=preg_replace('/\{%(.+)%\}/Ue','$vals[trim("\1")]',$out);//_replace($keys[$rs->get('c_name')],$res,$out);
		}
		
		$this->visitLog();
		//Вывод
		ob_start(); 
		ob_implicit_flush(0);
		
		echo $out;
		
		global $begin_time,$query_count,$query_time,$query_report,$cache_time;
		$t=microtime(true)-$begin_time;
		?><!-- <?=$t;?> --><!-- <?=$query_count?> <?=$query_time?> --><!-- <?=$t-$query_time?> <?=$cache_time?>--><?
		?><!-- <?=(isset($_GET['debug']) && $_GET['debug']=='true')?$query_report:'';?> --><?
		GzDocOut(3,(isset($_GET['debug'])&&$_GET['debug']=='true'));
				
		$this->updateAdvView();
	}
	//////////////////////////
	// site extention
	/////////////////////////
	function getCurrentCatalog(){
		$cat=$this->getURIIntVal('catalog');
		return $cat?$cat:0;
	}

	
	function getBannerPlace($name='top_banner'){
		global $ST;
		return $ST->select("SELECT DISTINCT p.id,p.description FROM sc_advertising_place p,sc_advertising a  WHERE a.place=p.id AND p.description LIKE '$name%' AND a.start_date<=DATE(NOW()) AND a.stop_date>=DATE(NOW()) ORDER BY p.description")->toArray();
	}
	
	function getNews($type='news',$limit=1){
		global $ST;
		$news=array();
		
		$cond="state='public' AND type='$type'";
		if($ids=$this->cfg('MAIN_'.strtoupper($type).'_IDS')){
			$cond.=" AND id IN($ids)";
		}
		
		$rs=$ST->select("SELECT title,id,description,img,date,type FROM sc_news WHERE $cond ORDER BY date DESC,id DESC LIMIT $limit");
		while ($rs->next()) {
			$news[]=$rs->getRow();
		}
		return $news;
	}
	
	function getAction(){
		global $ST;
		$news=array();
		$type='action';
		$cond="state='public' AND type='$type'";
		
		$cond.=" AND date<='".date('Y-m-d')."' AND date_to>'".date('Y-m-d')." 23:59:59'";
		$limit=10;
		$rs=$ST->select("SELECT title,id,description,img,date FROM sc_news WHERE $cond ORDER BY position DESC, date DESC,id DESC LIMIT $limit");
		while ($rs->next()) {
			$news[]=$rs->getRow();
		}
		return $news;
	}
	
		
	function getCityList(){
		global $GET_CITY_LIST;
		
		if(!empty($GET_CITY_LIST)){
			return $GET_CITY_LIST;
		}
		$res=array();
		$rs=DB::select("SELECT * FROM sc_enum WHERE field_name='city' AND position>0  ORDER BY position");
		while ($rs->next()) {
			$res[$rs->get('field_value')]=$rs->get('value_desc');
		}
		$rs=DB::select("SELECT DISTINCT city FROM sc_users WHERE type='vendor' AND city<>''");
		while ($rs->next()) {
			if(!in_array($rs->get('city'),$res)){
				$res[]=$rs->get('city');
			}
		}
		$cat=LibCatalog::getInstance();
		$rs=DB::select("SELECT DISTINCT delivery_city FROM sc_shop_proposal p,sc_shop_item i WHERE delivery_city<>'' {$cat->proposalNoCityCond()}");
		while ($rs->next()) {
			if(!in_array($rs->get('delivery_city'),$res)){
				$res[]=$rs->get('delivery_city');
			}
		}
		return $GET_CITY_LIST=$res;
	}	
	
	
	
	//////////////////////////
	// shop extention
	/////////////////////////

	function getMenuCatalog(){
		global $ST,$MENU_CATALOG;
		if(!empty($MENU_CATALOG)){
			return $MENU_CATALOG;
		}
		$q="SELECT * FROM sc_shop_catalog c
		
			WHERE sort>-1 ";//AND state=1 //AND ((sort>0 AND sort<20 AND parentid=0) OR parentid>0)
		$q.=" ORDER BY sort";
		$rs=$ST->select($q);//WHERE parentid={$menu_catalog[1]}
			$menu=array();
			$item=array();
			$items=array();

			while ($rs->next()) {
				$items[]=$rs->get('id');
				$item[$rs->get('id')]['name']=$rs->get('name');
				$item[$rs->get('id')]['id']=$rs->get('id');
				$item[$rs->get('id')]['img']=$rs->get('img');
				
				if($rs->get('parentid')==0){
					$menu[$rs->getInt('id')]=&$item[$rs->get('id')];
				}else{
					$item[$rs->get('parentid')]['children'][$rs->get('id')]=&$item[$rs->get('id')];
				}
			}
//		if($items){
//			$rs=$ST->select("SELECT COUNT(itemid) AS c,catid FROM sc_shop_item2cat WHERE catid IN(".implode(',',$items).") GROUP BY catid");
//			while ($rs->next()) {
//				$item[$rs->get('catid')]['cm']=$rs->get('c');
//			}
//		}
		return $MENU_CATALOG=$menu;	
	}
	
	
	function getManufacturer(){
		global $ST;
		$q="SELECT * FROM sc_manufacturer WHERE  sort>0 ORDER BY sort,name";
		$rs=$ST->select($q);//WHERE parentid={$menu_catalog[1]}
			$res=array();
			while ($rs->next()) {
				$res[$rs->get('id')]=$rs->get('name');
			}
			return $res;
	}
	
	
	function getCatalog(){
		return LibCatalog::getInstance()->getCatalogTree();
	}
	
	
	function getUnit($val=null){
		return $this->enum('sh_unit',$val);
	}
	
	function getFavoriteData(){
		return LibCatalog::getFav();
	}
	function inFav($id){
		return in_array($id,$this->getFavoriteData());
	}

	function getPrice($price){
		return LibCatalog::getInstance()->getPrice($price);
	}
	
	function getBasket(){
		return LibCatalog::getInstance()->getBasket();
	}
	
	function renderExplorer($delimitter=' - '){
//		global $ST;
//		$this->explorer[]=array('name'=>$this->mod_name,'url'=>$this->mod_alias);
//		$parent=$this->mod_parent_id;
//		while ($parent) {
//			$rs=$ST->select("SELECT * FROM sc_module WHERE mod_id=".$parent);
//			if($rs->next()){
//				$parent=$rs->getInt('mod_parent_id');
//				$this->explorer[]=array('name'=>$rs->get('mod_name'),'url'=>$rs->get('mod_alias'));
//			}else{
//				break;
//			}
//		}
//		if($this->mod_alias!='/'){
//			$this->explorer[]=array('name'=>'Главная','url'=>'/');
//		}
		return parent::renderExplorer($delimitter);
	}
	
	
	function getExplorer(){
		global $ST;
		$this->explorer[]=array('name'=>$this->mod_name,'url'=>$this->mod_alias);
		$parent=$this->mod_parent_id;
		while ($parent) {
			$rs=$ST->select("SELECT * FROM sc_module WHERE mod_id=".$parent);
			if($rs->next()){
				$parent=$rs->getInt('mod_parent_id');
				$this->explorer[]=array('name'=>$rs->get('mod_name'),'url'=>$rs->get('mod_alias'));
			}else{
				break;
			}
		}
		if($this->mod_alias!='/'){
			$this->explorer[]=array('name'=>'Главная','url'=>'/');
		}
		return $this->explorer;
	}
	
	function displayPageNav(Page $page){
		echo $this->render(array('page'=>$page),"template/{$this->theme()}/pages/pagenav.tpl.php");
	}
	
	
	function bar($name,$d=null){
		$module=$name;
		$class_name=ucfirst($name).'Bar';
		$module_path=MODULE_PATH.'/'.$module.'/'.$class_name.'.class.php';
		include_once($module_path);
		new $class_name($this,$d);
	}
	
	function getPartnersList($limit=7){
		global $ST;
		return $ST->select("SELECT * FROM sc_gallery WHERE type='clients' ORDER BY sort LIMIT $limit")->toArray();
	}
	function getGallery($type='gallery',$limit=1){
		global $ST;
		return $ST->select("SELECT * FROM sc_gallery WHERE type='$type' ORDER BY sort LIMIT $limit")->toArray();
	}
	function getFaq($type='faq',$limit=1){
		return DB::select("SELECT * FROM sc_faq WHERE type='$type' ORDER BY pos LIMIT $limit")->toArray();
	}
	
	
	function getCity(){
		$default='Екатеринбург';
		if($res=Cookie::get('city')){
			return $res;//Закоментить для отладки
		}
		include_once('core/lib/GeoIp.class.php');
		$res=GeoIp::getCity();
		if(!$res){
			$res=$default;
		}
		Cookie::set('city',$res);
		return $res;
	}
	
	


	function actChangeCity(){
		global $post;
		if($city=$post->get('city')){
			Cookie::set('city',$city);
		}
		echo printJSON(array('city'=>$city));exit;
		
	}	
	function sort($field_name,$label){
		$ord=$this->getURIVal('ord');
		$sort_class='';
		$ord_class='';
		if($ord==$field_name){
			$ord_class='act';
			$sort_class=$this->getURIVal('sort')!='asc'?'asc':'desc';
		}
		?><a href="<?=$this->getURI(array('ord'=>$field_name,'sort'=>$this->getURIVal('sort')!='asc'?'asc':'desc'),true)?>" class="<?=$ord_class?> <?=$sort_class?>"><span><?=$label?></span></a><?
	}
}
?>