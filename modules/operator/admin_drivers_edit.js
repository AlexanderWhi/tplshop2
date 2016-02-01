_cb=function(resp){
	$('#preloader').hide();
	if(resp.img){$('#img').attr('src',resp.img);$('[name=img]').val(resp.img);}
	if(resp.img_car){$('#img_car').attr('src',resp.img_car);$('[name=img_car]').val(resp.img_car);}
	util.msg(resp.msg);
}

$(function(){
	$('#driver-form').submit(function(){
		$('#preloader').show();
	});
	
	$('[name=clear]').click(function(){
		var par=$(this).parent();
		par.find('img').attr('src','/img/admin/no_image.png');
		par.find('input:text').val('clear');
	});
});