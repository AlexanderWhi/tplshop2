$(function(){
	$('[name=save],[name=save_with_notice]').click(function(){
		var data=$(this.form).serialize();
		var type=$(this).attr('name');
		$('#preloader').show();
		$.post('?act=save&type='+type,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg);
		},'json');
		
		return false;
	});	
})