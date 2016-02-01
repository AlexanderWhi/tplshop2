<?
include_once('core/lib/Diagram.class.php');
include_once(dirname(__FILE__)."/../AdminComponent.class.php");
class SiteStatistic extends AdminComponent {	

	private $browsers=array(
			'MSIE 4',
			'MSIE 5','MSIE 6','MSIE 7','MSIE 8','MSIE 9',
			'Opera/7','Opera/8','Opera/9','Opera/10',
			'Firefox','AppleWebKit','Chrome','Safari','Apple',
		);
		
	private $bot=array(
			'facebook',
			'Google','Yahoo',
			'Mail.Ru','Rambler',
			'DotBot','aiHitBot',
			'msnbot','Mnogosearch',
			'ParchBot',
			'bot',
			'Yandex',
			'Twiceler',
			'80legs',
			'igdeSpyder',
			'FeedDemon'
		);
	
	function actDefault(){
		$data=array(
		'date_from'=>date('d.m.Y',time()-3600*24*31),
		'date_to'=>date('d.m.Y'),
		);
		$this->display($data,dirname(__FILE__).'/statistic.tpl.php');
	}
	
	function actVisitPerDay(){
		global $ST,$post;
		$cond=" DATE(LOG_TIME) BETWEEN '".dte($post->get('date_from'),'Y-m-d')."' AND '".dte($post->get('date_to'),'Y-m-d')."'";
		
		$noBotCond=" AND TRIM(USER_AGENT) <> ''";
		foreach($this->bot as $bot){
			$noBotCond.=" AND USER_AGENT NOT LIKE  '%{$bot}%' ";
		}
		$noBotCond.=" AND REQUEST_URI NOT LIKE '%admin%' ";
		$q="SELECT COUNT(DISTINCT REMOTE_ADDR) as c_d, DATE(LOG_TIME) AS d FROM sc_loger
				WHERE $cond  $noBotCond
				GROUP BY d";
		$rs=$ST->select($q);
		$result=array();
		while ($rs->next()) {
			$result[$rs->get('d')]=$rs->get('c_d');
		}
		
		$diagram=new Diagram();
		$diagram->m_width=3;
		$diagram->m_maxHeight=220;
		$i=0;
		foreach ($result as $k=>$v){
			$diagram->m_x[$i]=$k;
			$diagram->m_y[$i]=$v;
			$diagram->m_desc[$i]=preg_replace('|^\d+-\d+-|','',$k);
			$i++;
		}
		echo $diagram->render();exit;
	}

	function actVisit(){
		global $post;
		$data=array('rs'=>$this->getModulesData($post->get('date_from'),$post->get('date_to')));
		
		echo $this->render($data,dirname(__FILE__).'/visit.tpl.php');exit;
	}

	function actReferer(){
		global $post;
		$data=array('rs'=>$this->getRefererData($post->get('date_from'),$post->get('date_to')));
		echo $this->render($data,dirname(__FILE__).'/referer.tpl.php');exit;
	}
	
	function actRefererUrl(){
		global $post;
		$data=array('rs'=>$this->getRefererUrlData($post->get('date_from'),$post->get('date_to'),$post->get('url')));
		echo $this->render($data,dirname(__FILE__).'/referer_url.tpl.php');exit;
	}
	
	function actBrowsers(){
		global $post;
		$data=array('rs'=>$this->getBrowsersData($post->get('date_from'),$post->get('date_to')));
		echo $this->render($data,dirname(__FILE__).'/browsers.tpl.php');exit;
	}
	
	function getRefererData($begin=0,$end=0){
		global $ST;
		$result=array();
		$limitCond='';
		$limitCond.=" AND HTTP_REFERER not like '%".str_replace('www.','',$_SERVER['HTTP_HOST'])."%' and HTTP_REFERER <> ''";
		$limitCond.=" AND DATE(LOG_TIME) BETWEEN '".dte($begin,'Y-m-d')."' AND '".dte($end,'Y-m-d')."'";
		$queryString="SELECT REPLACE(substring( HTTP_REFERER , 8, LOCATE( '/', HTTP_REFERER, 8 ) -8 ),'www.','') AS hr , count( HTTP_REFERER ) AS c
			FROM sc_loger 
			WHERE 1=1
			$limitCond
			GROUP BY hr
			ORDER BY c DESC
			";
		$rs=$ST->select($queryString);
		while ($rs->next()) {
			$result[$rs->get('hr')]['all']=$rs->getInt('c');
		}
		return $result;
	}
	
	function getRefererUrlData($begin=0,$end=0,$url=''){
		global $ST;
		
		$url=str_replace('http://','',$url);
		
		$result=array();
		$limitCond='';
		$limitCond.=" AND HTTP_REFERER not like '%".str_replace('www.','',$_SERVER['HTTP_HOST'])."%' and HTTP_REFERER <> ''";
		$limitCond.=" AND DATE(LOG_TIME) BETWEEN '".dte($begin,'Y-m-d')."' AND '".dte($end,'Y-m-d')."'";
		$limitCond.=" AND HTTP_REFERER LIKE '%$url%'";
		$queryString="SELECT HTTP_REFERER AS hr , count( HTTP_REFERER ) AS c
			FROM sc_loger 
			WHERE 1=1
			$limitCond
			GROUP BY hr
			ORDER BY c DESC
			";
		$rs=$ST->select($queryString);
		while ($rs->next()) {
			$result[$rs->get('hr')]['all']=$rs->getInt('c');
		}
		return $result;
	}
		
	function getBrowsersData($begin=0,$end=0){
		global $ST;
		
		$limitCond=" AND DATE(LOG_TIME) BETWEEN '".dte($begin,'Y-m-d')."' AND '".dte($end,'Y-m-d')."'";
		$limitCond.=" AND REQUEST_COMPONENT NOT LIKE '/admin%'";
//		$queryString="SELECT USER_AGENT ,count(USER_AGENT) as c FROM sc_loger where USER_AGENT is not null group by USER_AGENT order by c desc";
		
		$browsers=$this->browsers;
		
		$bot=$this->bot;
		
		$queryString=" SELECT * ";
		$queryString.=" FROM (";
		foreach ($browsers as $br){
			$queryString.=" SELECT '{$br}' AS br, count( * ) AS c";
			$queryString.=" FROM sc_loger";
			$queryString.=" WHERE USER_AGENT LIKE '%{$br}%' {$limitCond}";
			$queryString.=" UNION ";
		}
		$queryString.=" SELECT 'bot' AS br, count( * ) AS c
		FROM sc_loger
		WHERE (";
		
		foreach ($bot as $b){
			$queryString.=" USER_AGENT LIKE '%{$b}%' OR";
		}
		
		$queryString=preg_replace('| or$|i',')',$queryString);
		
		$queryString.=$limitCond;
		
		$queryString.=" UNION 
		SELECT 'Other' AS br, count( * ) AS c
		FROM sc_loger
		WHERE NOT(";
		
		foreach ($bot as $b){
			$queryString.=" USER_AGENT LIKE '%{$b}%' OR";
		}
		
		foreach ($browsers as $b){
			$queryString.=" USER_AGENT LIKE '%{$b}%' OR";
		}
		
		$queryString=preg_replace('| or$|i',')',$queryString);
		
		$queryString.=$limitCond;
		
		$queryString.=") AS t ORDER BY 2 DESC";
		
		
		$rs=$ST->execute($queryString);
		$result=array();
		while ($rs->next()) {
			$result[$rs->get('br')]=$rs->get('c');
		}
		return $result;
	}
	
	function getModulesData($begin=0,$end=0){
		global $ST;
		$result=array();
		$limitCond=" AND DATE(LOG_TIME) BETWEEN '".dte($begin,'Y-m-d')."' AND '".dte($end,'Y-m-d')."'";
		$limitCond.=" AND REQUEST_COMPONENT NOT LIKE '/admin%'";
		
		$notBotCond='';
		foreach ($this->bot as $b){
			$notBotCond.=" AND USER_AGENT NOT LIKE '%{$b}%'";
		}
	
		$queryString="SELECT REQUEST_COMPONENT AS hr , count( REQUEST_COMPONENT ) AS c FROM sc_loger where 1=1
			$limitCond
			$notBotCond
			GROUP BY hr
			ORDER BY c DESC
			";
		$rs=$ST->select($queryString);
		while ($rs->next()) {
			$result[$rs->get('hr')]['all']=$rs->getInt('c');
		}
		
		$queryString="SELECT REQUEST_COMPONENT AS hr , count( DISTINCT REMOTE_ADDR ) AS c FROM sc_loger where 1=1
			$limitCond
			$notBotCond
			GROUP BY hr
			ORDER BY c DESC
			";
		$rs=$ST->select($queryString);
		while ($rs->next()) {
			$result[$rs->get('hr')]['all_v']=$rs->getInt('c');
		}
		return $result;
	}	
}
?>