$(function(){
	$('#catalog-form').submit(function(){
		
		$.post(this.action,$(this).serialize(),function(res){
			util.msg(res.msg);
			
		},'json');
		
		return false;
	});
	$('#catalog-form [name=remove]').click(function(){
		if(confirm('Удалить?')){
			$.post('?act=onRemove',$(this.form).serialize(),function(res){
				util.msg(res.msg);
				document.location.reload();
			},'json');
		}
			
		
		return false;
	});
	$('#catalog-form [name=UpdateCatalogCache]').click(function(){
		$.post('?act=UpdateCatalogCache',{},function(res){
			util.msg(res.msg);
		},'json');
		return false;
	});
	
	
	
	$('.add_to').click(function(){
		if($('#catalog-form [name^=item]:checked').length>0){
			if(confirm('Переместить выбранные элементы сюда?')){
				var to=$(this).attr('rel');
				$.postData('?act=MoveTo',$('#catalog-form').serialize()+'&to='+to,function(){
					document.location.reload();
				});
			}
			
			return false;
		}
	});
	
	$('#export_all').item('export');
	
});