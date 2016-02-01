$(function(){
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	$('[name=save]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save&'+this.name,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			msg(resp.msg)
		},'json');
		return false;
	});	
});