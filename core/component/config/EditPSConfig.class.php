<?php
include_once 'class/component/www/admin/AdminComponent.class.php';

class EditPSConfig extends AdminComponent {	
	
	protected $rs=array();
	
	function refresh(){
		$rs=$this->getStatement()->execute("SELECT * FROM sc_pay_system");
		while ($rs->next()){
			$this->rs[$rs->get('name')]=$rs->getRow();
		}
		
	}
	
	function onSubmit(ArgumentList $args,ArgumentList $post){
		if($post->exists('name') && $post->exists('save')){
			if($post->getArgument('fields')){
				$fields=explode(',',$post->getArgument('fields'));
				$config=array();
				foreach ($fields as $f){
					list($name,$desc)=explode(':',$f);
					if($post->exists($name)){
						$config[$name]=$post->getArgument($name);
					}				
				}
				$vals['config']=serialize($config);
			}
			
			
			$fields=explode(',',$post->getArgument('error_codes'));
			$config=array();
			foreach ($fields as $f){
				if($post->exists("e".$f)){
					$config[$f]=$post->getArgument("e".$f);
				}				
			}
			$vals['error_desc']=serialize($config);
			
			$vals['description']=$post->getArgument('description');
			$vals['text']=$post->getArgument('text');
			
			$this->getStatement()->update('sc_pay_system',$vals,"name='".$post->getArgument('name')."'");
		}
		
		$this->callSelfComponent();
	}
	function display(){
		?><h1><?=$this->getTitle()?></h1>
		
		<?foreach ($this->rs as $item){?>
		<form method="POST">
		<input type="hidden" name="name" value="<?=$item['name']?>"/>
		<input type="hidden" name="fields" value="<?=$item['fields']?>"/>
		<input type="hidden" name="error_codes" value="<?=$item['error_codes']?>"/>
		<table class="form">
		<tr>
		<th style="width:200px">Описание</th><td><input class="input-text" name="description" value="<?=htmlspecialchars($item['description'])?>"/></td>
		<? $fields=split(',',$item['fields']);
		$config=@unserialize($item['config']);
		if($item['fields'] && $fields){
			?><tr><th></th><th>Настройки</th></tr><?
		foreach ($fields as $f){
			list($name,$desc)=@explode(':',$f);
			?><tr><th><?=$desc?></th><td><input class="input-text" name="<?=$name?>" value="<?=isset($config[$name])?$config[$name]:''?>"/></td></tr><?
		}
		}
		?>
		<tr><th>Текст</th><td><textarea class="input-text" name="text"><?=htmlspecialchars($item['text'])?></textarea></td></tr>
		
		<? $fields=split(',',$item['error_codes']);
		$config=@unserialize($item['error_desc']);
		if($item['error_codes'] && $fields){
			?><tr><th></th><th>Ошибки</th></tr><?
		foreach ($fields as $f){
			$name="e".$f;
			$desc=$f;
			?><tr><th><?=$desc?></th><td><input class="input-text" name="<?=$name?>" value="<?=isset($config[$f])?$config[$f]:''?>"/></td></tr><?
		}
		}
		?>
		</tr>
		<tr>
		<th></th><td><input type="submit" name="save" value="Сохранить"/></td>
		</tr>
		</table>
		<hr/>		
		</form>
		<?}
	}
}
?>