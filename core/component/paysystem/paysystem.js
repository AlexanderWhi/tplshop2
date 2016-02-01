$(function(){
	$('.save').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize(),function(resp){
			$('#preloader').hide();
			util.msg(resp.msg);
		},'json');
		return false;
	});
})