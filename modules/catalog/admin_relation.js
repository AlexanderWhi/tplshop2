$(function(){
		
	$('input[name=delete]').click(function(){
		if($('input.item:checked').length==0){
			alert('�� ������� �� ������ ��������');
			return;
		}
	});
	
	$('[name=all]').item();
});
