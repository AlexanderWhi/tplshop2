//_upload=function(resp){
//	$('#preloader').hide();
//	$('[name=img]').val(resp.path);
//	$('#img-file').attr('src',resp.path);
//}

_cb=function(res){
	$('#preloader').hide();			
	if(res.id){
		$('[name=id]').val(res.id)
	}
	$('[name=img]').val(res.img)
	if(res.img){
		$('#img-file').attr('src',res.img);
	}
	msg(res.msg)
}

$(function(){
	$('#catalog').submit(function(){
		$('#preloader').show();
	});
	
});	