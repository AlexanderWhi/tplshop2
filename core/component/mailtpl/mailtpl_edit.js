$(document).ready(function(){
	
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save',data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
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