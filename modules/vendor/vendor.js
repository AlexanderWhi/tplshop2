$(function(){
	$('[name=send]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=send',data,function(resp){
			$('#preloader').hide();
			msg(resp.msg);
		},'json');
		
		return false;
	});
	
})