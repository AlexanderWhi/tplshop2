<h2>ќпрос мес€ца</h2>
<form class="side-blk" id="vote" action="/vote/?act=send" onsubmit="$.post(this.action,$(this).serialize(),function(res){$('#vote').html(res)}) ;return false">


<?if($voted){?>
<?include('vote_result.tpl.php')?>
<?}else{?>
<?=$name?><br /><br />
<?foreach ($items as $item) {?>
<div class="item">
	<input type="radio" name="vote" value="<?=$item['id']?>" id="vote<?=$item['id']?>"/><label for="vote<?=$item['id']?>"><?=$item['name']?></label>
	
<strong style="border-left:solid <?=(100*$item['result']/$all)?>px #95c12b"><?=round($item['result']/$all*100)?>%</strong>	
</div>
<?}?>
<input class="button" type="submit" name="vote" value="ќтправить"/>
<?}?>

</form>
