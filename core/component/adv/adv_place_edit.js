$(function(){
	$('[name=save]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=plSave&'+this.name,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			msg(resp.msg)
		},'json');
		return false;
	});
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
});