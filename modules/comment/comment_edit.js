$(function(){
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	$('#comment-form').submit(function(){
		var data=$(this).serialize();
		$('#preloader').show();
		$.post(this.action,data,function(res){
			$('#preloader').hide();
			$('[name=id]').val(res.id);
			util.msg(res.msg)
		},'json');
		return false;
	});	
});