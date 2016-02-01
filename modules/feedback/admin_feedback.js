$(function(){
	$('.remove').click(function(){
		if(confirm('Удалить запись?')){
			$('#preloader').show();
			$.post('?act=remove',{id:this.id.replace('remove','')},function(resp){
				$('#remove'+resp.id).parent().parent().hide();
				$('#preloader').hide();
			},'json');
		}
		return false;
	});
	
	
	$('.save').click(function(){
		var data=$(this.form).serialize();
		$('#preloader').show();
		$.post(this.form.action,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg);
		},'json');
		
		return false;
	});	
})