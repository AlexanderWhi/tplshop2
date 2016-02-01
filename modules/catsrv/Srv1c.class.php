<?
if(!function_exists('u')){
	function u($str){
		return iconv('cp1251','utf-8',$str);
	}
}

class Srv1c{
	public $xml=null;
	public $catalog=array();
	
	function __construct($file){
		if(file_exists($file)){
			$this->xml=simplexml_load_file($file);
			
			$this->catalog=$this->xml->{u('Классификатор')}->{u('Группы')};
		}
	}
	
	function walkCatalog($call_back='call_back',$params=array(),$cat=null){
		if(!$cat)$cat=$this->catalog;
		
		if(!empty($cat->{u('Группа')})){
			
		
		foreach ($cat->{u('Группа')} as $group) {
					$d=array(
					'id'=>u2w($group->{u('Ид')}),
					'name'=>u2w($group->{u('Наименование')}),
					'ch'=>$group->{u('Группы')},
					);
					
					$call_back($d,$params,$this);
//					
//					if(!empty($group->{u('Группы')})){
//						
//						$this->walkCatalog($call_back,$params,);
//						
////						$d['ch']=fill_ext_catalog($group->{u('Группы')});
//					}
//					$res[]=$d;
				}
		}
	}
	
	
	static function get1cExtCatalog($file){
		$tree=array();
		//Каталог
		if(file_exists($file)){
			
			$xml=simplexml_load_file($file);
			
			function fill_ext_catalog($groups){
				$res=array();
				foreach ($groups->{u('Группа')} as $group) {
					$d=array(
					'id'=>u2w($group->{u('Ид')}),
					'name'=>u2w($group->{u('Наименование')}),
					'ch'=>array(),
					);
					if(!empty($group->{u('Группы')})){
						$d['ch']=fill_ext_catalog($group->{u('Группы')});
					}
					$res[]=$d;
				}
				return $res;
			}
			
			$groups=$xml->{u('Классификатор')}->{u('Группы')};
			$tree=fill_ext_catalog($groups);
		}
		return $tree;
	}
	
}

?>