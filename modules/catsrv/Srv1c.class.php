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
			
			$this->catalog=$this->xml->{u('�������������')}->{u('������')};
		}
	}
	
	function walkCatalog($call_back='call_back',$params=array(),$cat=null){
		if(!$cat)$cat=$this->catalog;
		
		if(!empty($cat->{u('������')})){
			
		
		foreach ($cat->{u('������')} as $group) {
					$d=array(
					'id'=>u2w($group->{u('��')}),
					'name'=>u2w($group->{u('������������')}),
					'ch'=>$group->{u('������')},
					);
					
					$call_back($d,$params,$this);
//					
//					if(!empty($group->{u('������')})){
//						
//						$this->walkCatalog($call_back,$params,);
//						
////						$d['ch']=fill_ext_catalog($group->{u('������')});
//					}
//					$res[]=$d;
				}
		}
	}
	
	
	static function get1cExtCatalog($file){
		$tree=array();
		//�������
		if(file_exists($file)){
			
			$xml=simplexml_load_file($file);
			
			function fill_ext_catalog($groups){
				$res=array();
				foreach ($groups->{u('������')} as $group) {
					$d=array(
					'id'=>u2w($group->{u('��')}),
					'name'=>u2w($group->{u('������������')}),
					'ch'=>array(),
					);
					if(!empty($group->{u('������')})){
						$d['ch']=fill_ext_catalog($group->{u('������')});
					}
					$res[]=$d;
				}
				return $res;
			}
			
			$groups=$xml->{u('�������������')}->{u('������')};
			$tree=fill_ext_catalog($groups);
		}
		return $tree;
	}
	
}

?>