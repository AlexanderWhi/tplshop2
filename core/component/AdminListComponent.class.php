<?php
include_once 'AdminComponent.class.php';
class AdminListComponent extends AdminComponent{
	/**
	 * @var Page
	 */
	protected $page=null;
	public $pageSize=30;
	/**
	 * @var SQLResult
	 */
	protected $rs=null;
	
//	function onSubmit(ArgumentList $args,ArgumentList $post){
//		if($args->exists('pagesize')){
//			$this->pageSize=$args->getInt('pagesize');
//		}
//		$this->callSelfComponent($this->args);
//	}
	function refresh(){
		$this->page=new Page($this->getPages());
//		$this->page->setArgumentList(new ArgumentList($_GET));
	}
	function displayPageControl($add='edit'){?>
		<table style="width:100%">
		<tr>
		<?if($add){?>
		<td><a href="?act=<?=$add?>"><img src="/img/pic/add_16.gif" title="Добавить" alt="Добавить"/>Добавить</a></td>
		<?}?>
		<td><?$this->page->display();?></td>
		<td style="text-align:right">Показать:<?$this->page->displayPageSize($this->cfg('PAGE_SIZE_SELECT'),$this->pageSize);?></td>
		</tr>
		</table>
		<?
	}
	function sort($field_name,$label){
		?><a href="<?=$this->getURI(array('sort'=>$field_name,'ord'=>$this->getURIVal('ord')!='asc'?'asc':'desc'),true)?>"><?=$label?></a><?
		if($this->getURIVal('sort')==$field_name){
		?><img src="/img/sort_<?=$this->getURIVal('ord')!='asc'?'asc':'desc'?>.gif" /><?
		}
	}
	
	function actChangePageSize(){
		setcookie('pages',intval($_GET['pages']),COOKIE_EXP,'/');
		$this->pageSize=intval($_GET['pages']);
		header("Location: ".$_SERVER['HTTP_REFERER']);exit;
	}
	protected function getPages(){
		return isset($_COOKIE['pages'])&& $_COOKIE['pages']?intval($_COOKIE['pages']):30;
	}
}
?>