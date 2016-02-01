$(function(){
	$('#cabinet-edit').submit(function(){
		$.post(this.action,$(this).serialize(),function(res){
			$('input.error').removeClass('error');
			$('span.error').hide();
			if(res.error){
				var c=0;
				for(var i in res.error){
					if(res.error[i]){
						if(c++==0) $('input[name='+i+']').focus();
						$('input[name='+i+']').addClass('error');
						$('span#error-'+i).show().text(res.error[i]);
					}
				}
			}
			if(res.status && res.status=='ok'){
				$('#success').show();
				$('#cabinet-edit').hide();
			}
		},'json')
				
		return false;
	});
});	