$(function(){
		
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save&'+this.name,data,function(resp){
			$('#preloader').hide();
			$('[name=u_id]').val(resp.u_id);
			msg(resp.msg);
		},'json');
		
		return false;
	});
	
})