$(function(){
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save',data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg);
		},'json');
		
		return false;
	});	
});