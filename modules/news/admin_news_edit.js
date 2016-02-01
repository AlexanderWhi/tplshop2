_upload=function(resp){
	$('#preloader').hide();
	$('[name=img]').val(resp.path);
	$('#img-file').attr('src',resp.img);
}

$(function(){
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	$('[name=save]').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post('?act=save&'+this.name,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.nws_id);
			msg(resp.msg)
		},'json');
		return false;
	});
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});

	$('[name=clear]').click(function(){
		$('[name=img]').val('clear');
		$('#img-file').attr('src','/img/admin/no_image.png');
	});
	
	$('[name=upload]').upload({params:'size=w100h100'});
	
});