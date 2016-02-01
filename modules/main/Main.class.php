<?
include_once 'core/component/Component.class.php';

class Main extends Component  {
	
	protected  $tplContainer="template/www/pages/main_container.tpl.php";
	
	function actDefault(){	
		global $ST;
		$data=array(
			'news'=>$this->getNews('news',3),
			'public'=>$this->getMainPublic(3),
			
			'fb'=>$ST->select("SELECT * FROM sc_contacts WHERE type='feedback' ORDER BY id DESC limit 3")->toArray(),
			'catalog'=>$this->getMainCat(),
			'staff'=>$this->getMainStaff(),
			'faq'=>$this->getFaq('faq',10),
			'manufacturer'=>LibCatalog::getInstance()->getManufacturer(),
			'goods'=>LibCatalog::getInstance()->getMainGoods(),
		);
		$this->display($data,$this->getTpl('main.tpl.php'));
	}


	function getMainCat(){
		return LibCatalog::getInstance()->getCatalogTree();
	}	
	function getMainPublic($limit=3){
		global $ST;
		
		$condition="state='public' AND type='public'";
		$order=" ORDER BY date DESC,position DESC";
		$queryStr="SELECT *,value_desc AS category_desc FROM sc_news n
		LEFT JOIN (SELECT field_value,value_desc FROM sc_enum WHERE field_name='public_category') AS c ON c.field_value=n.category
		WHERE $condition $order LIMIT $limit";
		return $ST->select($queryStr)->toArray();
		
	}
	function getMainStaff($limit=100){
		global $ST;
		$condition="g.type='staff' AND g.sort>-1 AND sort_main>0";
		$order="ORDER BY g.sort DESC, g.date DESC, g.id DESC";
		$order="ORDER BY RAND()";
		$queryStr="SELECT g.*,p.title AS p_title,p.id AS p_id FROM sc_gallery g
			LEFT JOIN (SELECT n.* FROM sc_news n,(SELECT MAX(id) as id ,gallery FROM sc_news GROUP BY gallery) AS mn WHERE type='public' AND mn.id=n.id) AS p ON p.gallery=g.id 
			WHERE $condition $order LIMIT $limit";
		
		return $ST->select($queryStr)->toArray();
	}
	
}
?>