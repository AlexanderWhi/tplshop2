$(function(){
		
	$('input[name=delete]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}
	});
	
	$('[name=all]').item();
});
