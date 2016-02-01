$(function(){
	$('input[name=all]').click(function(){
		$('input.item').attr('checked',false);
		$('tr:visible input.item').attr('checked',$(this).attr('checked'));
	});
	
	$('input.item').parent().parent().click(function(){
		var ch=$(this).find('input'); 
		ch.attr('checked',!ch.attr('checked'));
	}).find('input').click(function(){
		$(this).attr('checked',!$(this).attr('checked'));
	});
	
	$('input[name=print]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}
		var url='?act=printWaybill&'+$(this.form).serialize();
		util.openWin(url,800,600);
	});
});	