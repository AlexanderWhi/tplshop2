$(function(){	
	$('.remove').click(function(){
		if(confirm('Удалить запись?')){
			$('#preloader').show();
			$.post('?act=remove',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().remove();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
	
	$('[name=save]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize(),function(res){
			$('#preloader').hide();
			util.msg(res.msg);
			
			
		},'json');
		
		return false;
	});
})