$(function(){
	$('#import').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			$('#preloader').hide();
			msg(res.msg)
		},'json')
				
		return false;
	});
});	