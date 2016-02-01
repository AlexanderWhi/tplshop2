$(function(){
	$('[name=apply]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=plApply&'+this.name,data,function(resp){
			$('#preloader').hide();
			msg(resp.msg)
		},'json');
		return false;
	});
});