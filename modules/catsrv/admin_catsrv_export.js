$(function(){
	$('[name=clear_history]').click(function(){
		if(confirm('�������� �������?')){
			$('#preloader').show();
			$.post('?act=clearHistory',{},function(res){
				$('#preloader').hide();
				msg(res.msg)
			},'json')
		}	
		return false;
	});
});	