$(function(){
	$('[name=clear_history]').click(function(){
		if(confirm('Очистить историю?')){
			$('#preloader').show();
			$.post('?act=clearHistory',{},function(res){
				$('#preloader').hide();
				msg(res.msg)
			},'json')
		}	
		return false;
	});
});	