_upload=function(resp){
	$('#preloader').hide();
	$('[name=file]').val(resp.path);
	$('#html').html(resp.html);
}

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
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
	
	
	$('[name=clear]').click(function(){
		$('[name=file]').val('clear');
		$('#img-file').attr('src','/img/admin/no_image.png');
		$('#html').html('');
	});
	
	$('[name=upload]').change(function(){
		$('#preloader').show();
		$(this.form).attr({action:'?act=upload',target:'upload'}).submit();
	}).each(function(){
		$('<iframe id="upload" name="upload" style="display:none"></iframe>').insertAfter($(this.form));
	})
});