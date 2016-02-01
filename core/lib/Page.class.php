<?php
class Page{
	const PAGE_PARAM_NAME="page";
	public $all=0;
	public $current=1;
	public $per=10;
	public $add="";
	public $url="";
	private $delta=3;
	public $pages=array('30','60','100','500');
	function Page($per=30,$pages=null){
		$this->per=$per;
		preg_match('|/page/([0-9]+)|',$_SERVER['REQUEST_URI'],$res);
		if(isset($res[1])){
			$this->current=intval($res[1]);
		}
		$this->url=$_SERVER['REQUEST_URI'];
		if($pages){
			$this->pages=$pages;
			if(is_string($this->pages)){
				$this->pages=explode(',',$this->pages);
			}
		}

	}
	function countPages(){
		return ceil($this->all/$this->per);
	}
		
	function getBegin(){
		
		$page=$this->current;
		$per=$this->per;	
		$go=($page-1)*$per;
		if($go>$this->all)$go=1;
		return $go;
	}
	
	function displayPageSize($pages=null){
		if($pages){
			$this->pages=$pages;
			if(is_string($this->pages)){
				$this->pages=explode(',',$this->pages);
			}
		}
		$c=0;
		foreach ($this->pages as $v){
			if($c++){?> <?}
			if($v!=$this->per){
				?><a href="javascript:document.location='?act=ChangePageSize&pages=<?=intval($v)?>'"><?=intval($v)?></a><?
			}else{
				?><span><?=intval($v)?></span><?
			}
			
		}
	}
	function getUrl($url='',$page=1){
		$urlArr=explode('?',$url);
		$url1=preg_replace('|page/[0-9]+|','',$urlArr[0]);
		return preg_replace('|/+|','/',$url1.($page>1?'/page/'.$page:'').'/').(isset($urlArr[1])?'?'.$urlArr[1]:'');
	}

	function displayPageNav(){
?><table class="page_nav">
<td><div class="page"><?=$this->render1(array('<<'=>'','<'=>'Предыдущая','>'=>'Следующая','>>'=>'',))?></div></td>
<td class="right">
Показано c <?=$this->getBegin()+1?> по <?=min($this->per+$this->getBegin(),$this->all)?> 
(всего <?=$this->countPages()?> <?=morph($this->countPages(),'страница','страницы','страниц')?>)</td>
</table><?
	}
	function display($tpl=0){
		if($tpl==1){
			if($this->all/$this->per>0){
				$rnd=$this->render1(array('<<'=>'Первая страница','<'=>'','>'=>'','>>'=>'Последняя страница',));
				echo $rnd;
			}
			
		}else{
			$rnd=$this->render();
			$out='';
			if($rnd){
				$out.='Всего :'.$this->all;
				$out='Страница: '.$rnd.' '.$out;
			}
			echo $out;
		}
		
	}
	function render($opt=array()){
		if(empty($this->per))return;
		$fin=ceil($this->all/$this->per);
		if($fin<2)return;
		if (empty($this->current) || $this->current<0) {$this->current=1;}
		if ($this->current>$fin) {$this->current=$fin;}
		
		$out='';
		
		$pg=$this->current;
		$pervpage='';
		if ($pg != 1) {
			if(isset($opt['<<'])){
				$pervpage .=' <a class="begin" href="'.$this->getUrl($_SERVER['REQUEST_URI'],1).'">'.$opt['<<'].'</a>';
			}else{
				$pervpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],1).'">&lt;&lt;</a>';
			}
			
			if(isset($opt['<'])){
				$pervpage .=' <a class="prev" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg-1).'">'.$opt['<'].'</a>';
			}else{
				$pervpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg-1).'">&lt;</a>';
			}
		}
		$nextpage='';
		if ($pg != $fin) {
			
			if(isset($opt['>'])){
				$nextpage .=' <a class="next" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg+1).'">'.$opt['>'].'</a>';
			}else{
				$nextpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg+1).'">&gt;</a>';
			}
			if(isset($opt['>>'])){
				$nextpage .=' <a class="end" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$fin).'">'.$opt['>>'].'</a>';
			}else{
				$nextpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$fin).'">&gt;&gt;</a>';
			}
			
		
            
		}
		$delta=$this->delta;
		$margin=0;
		$all=$delta*2+1;
		if($all>=$fin){
			$all=$fin;
			$margin=$pg-1;
		}else{
			$margin=$delta;
			if($pg-$delta<2){
				$margin=$pg-1;
			}else{
				$pervpage.=' ..';
			}
			if($pg+$delta>=$fin){
				$margin=$all-($fin-$pg+1);
			}else{
				$nextpage=' ..'.$nextpage;
			}
		}
		$page_out ='';
		for ($i=0;$i<$all;$i++){
			$curpage=$i+$pg-$margin;
			if($curpage==$pg){
				$page_out .=' <strong>'.$curpage.'</strong>';
			}else{
				$page_out .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$curpage).'">'.$curpage.'</a>';
			}
		}
		$out.=$pervpage;
		$out.=$page_out;
		$out.=$nextpage;
		return $out;
	}
	
	function render1($opt=array()){
		if(empty($this->per))return;
		$fin=ceil($this->all/$this->per);
		if($fin<2)return;
		if (empty($this->current) || $this->current<0) {$this->current=1;}
		if ($this->current>$fin) {$this->current=$fin;}
		
		$out='';
		
		$pg=$this->current;
		$pervpage='';
		if ($pg != 1  ) {
			if(isset($opt['<<'])){
				$pervpage .=' <a class="begin" href="'.$this->getUrl($_SERVER['REQUEST_URI'],1).'">'.$opt['<<'].'</a>';
			}else{
				$pervpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],1).'">&lt;&lt;</a>';
			}
			if(isset($opt['<'])){
				$pervpage .=' <a class="prev" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg-1).'">'.$opt['<'].'</a>';
			}else{
				$pervpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg-1).'">&lt;</a>';
			}

		}
		$nextpage='';
		if ($pg != $fin ) {
			
			if(isset($opt['>'])){
				$nextpage .=' <a class="next" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg+1).'">'.$opt['>'].'</a>';
			}else{
				$nextpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$pg+1).'">&gt;</a>';
			}
			if(isset($opt['>>'])){
				$nextpage .=' <a class="end" href="'.$this->getUrl($_SERVER['REQUEST_URI'],$fin).'">'.$opt['>>'].'</a>';
			}else{
				$nextpage .=' <a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$fin).'">&gt;&gt;</a>';
			} 
		}
		$delta=$this->delta;
		$margin=0;
		$all=$delta*2+1;
		if($all>=$fin){
			$all=$fin;
			$margin=$pg-1;
		}else{
			$margin=$delta;
			if($pg-$delta<2){
				$margin=$pg-1;
			}else{
				$pervpage.=' ..';
			}
			if($pg+$delta>=$fin){
				$margin=$all-($fin-$pg+1);
			}else{
				$nextpage=' ..'.$nextpage;
			}
		}
		$page_out ='';
		for ($i=0;$i<$all;$i++){
			$curpage=$i+$pg-$margin;
			if($curpage==$pg){
				$page_out .='<strong>'.$curpage.'</strong>';
			}else{
				$page_out .='<a href="'.$this->getUrl($_SERVER['REQUEST_URI'],$curpage).'">'.$curpage.'</a>';
			}
		}
		$out='<table>';
		if($pervpage){
			$out.='<td class="pervpage">'.$pervpage.'</td>';
		}
		
		$out.='<td class="page_out">'.$page_out.'</td>';
		if($nextpage){
		$out.='<td class="nextpage">'.$nextpage.'</td>';
		}
		$out.='</table>';
		
//		$out.=$pervpage;
//		$out.=$page_out;
//		$out.=$nextpage;
		return $out;
	}	
}
?>