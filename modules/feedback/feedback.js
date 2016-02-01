_cb=function(res){
	
	var form=$('#feedback-form');
	if(res.err){
		for(var i in res.err){
			$('#error-'+i).html(res.err[i]).show();
			$('[name='+i+']',form).addClass('error');
		}
				
	}else{
		form.hide();
		$('.success').show();
	}
}

$(function(){
	$('#feedback-form').submit(function(){
		$('input.error,select.error,textarea.error').removeClass('error');
		$('.error').hide();
	});		
});