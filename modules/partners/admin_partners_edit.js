_upload=function(resp){
	$('#preloader').hide();
	$('[name=img'+resp.num+']').val(resp.path);
	$('#img'+resp.num+'-file').attr('src',resp.path);
}

$(function(){
	$('[name=save]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post(this.form.action,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg)
		},'json');
		return false;
	});
	
	$('[name=clear]').click(function(){
		$('[name=img]').val('clear');
		$('#img-file').attr('src','/img/admin/no_image.png');
	});
	$('[name=clear1]').click(function(){
		$('[name=img1]').val('clear');
		$('#img1-file').attr('src','/img/admin/no_image.png');
	});
	
	$('[type=file]').upload({params:''});
		
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
});