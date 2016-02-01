$(function(){
	
	$('#contacts-form-custom').submit(function(){
//		alert('')
		$.post(this.action,$(this).serialize(),function(res){
			if(res.err){
				var text='';
				for(var i in res.err){
					text+=res.err[i]+'\n';
				}
				if(text){
					alert(text);
				}else{
					$('#contacts-form-custom').hide();
					$('.success').show();							
				}
			}	
		},'json')
				
		return false;
	});	

	$('#contacts-form').submit(function(){
		var el=this;
		$.post(this.action,$(this).serialize(),function(res){
			if(res.err){
				var text='';
				$('input.error,textarea.error',el).removeClass('error');
				$('small.error',el).hide();
				
				for(var i in res.err){
					$('[name='+i+']',el).addClass('error');
					$('#error-'+i,el).show().text(res.err[i]);
					text+=res.err[i]+'\n';
				}
				if(text){
//					alert(text);
				}else{
					$(el).hide();
					$('.success').show();							
				}
			}	
		},'json')
				
		return false;
	});		
});	