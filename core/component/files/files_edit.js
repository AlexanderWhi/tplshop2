$(document).ready(function(){
	
	$('form').submit(function(){
		var data=$(this).serialize();
		$('#preloader').show();
		$.post('?act=save',data,function(resp){
			$('#preloader').hide();
			msg(resp.msg);
//			alert(resp.msg);

		},'json');
		
		return false;
	});
	
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});	
})
