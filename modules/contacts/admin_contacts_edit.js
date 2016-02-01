$(function(){
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post(this.form.action,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg);
		},'json');
		
		return false;
	});	
})