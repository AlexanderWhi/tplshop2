var remove=function(id){
	$('#'+id).remove();
}

$(function(){
	
	$('#cabinet-edit').submit(function(){

		$.post(this.action,$(this).serialize(),function(res){
			$('input.error').removeClass('error');
			$('span.error,div.error').hide();
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
				$('#cabinet-edit').hide();
			}
		},'json')	
		return false;
	});
	
	$('#cabinet-edit [name=add_address]').click(function(){
//		alert('');
		
		$.post('?act=AddAddr',$(this.form).serialize(),function(html){
			$('#addr_list').html(html).find('label[for]').custom();
		});
//		alert('1');
		return false;
	});
	
});	