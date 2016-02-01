$(function(){
	$('#guestbook-form').submit(function(){
		$.post('?act=send',$(this).serialize(),function(res){
			if(res.err){
				var text='';
				for(var i in res.err){
					text+=res.err[i]+'\n';
				}
				if(text){
					alert(text);
				}else{
					$('#guestbook-form').hide();
					$('#success').show();
					document.location.href='#';						
				}
			}	
		},'json')
				
		return false;
	});			
});	