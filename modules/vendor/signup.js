
$(function(){
	$('#sign').submit(function(){
			
		$('input.error').removeClass('error');
		$('.error').hide();
		
		$.post(this.action,$(this).serialize(),function(res){
			if(res.error){
				var c=0;
				for(var i in res.error){
					if(res.error[i]){
						if(c++==0) $('input[name='+i+']').focus();
						$('input[name='+i+']').addClass('error');
						$('#error-'+i).show().text(res.error[i]);
					}
				}
			}
			if(res.status && res.status=='ok'){
				$('#success').show();
				$('#sign').hide();
			}
		},'json')	
		return false;
	});
	
});	