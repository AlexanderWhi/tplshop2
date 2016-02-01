_cb=function(resp){
	$('#preloader').hide();
	$('[name=id]').val(resp.id);
	if(resp.img){
		$('#img-file').attr('src',resp.img);
	}
	util.msg(resp.msg)
}

$(function(){
	$('[name=date]').datepicker({ dateFormat: 'dd.mm.yy'});
	$('#article-form').submit(function(){
		$('#preloader').show();
	});	
	
});