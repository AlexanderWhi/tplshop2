function rename(){
	var val=$(this).val();
	var old=$(this).data('old');
	var path=$('[name=path]').val();
	var pathTo='';
	if(path){
		pathTo=path+'/';
	}
	$(this).parent().html('<a href="?path='+pathTo+val+'">'+val+'</a>').attr('title',val);
	$.post('?act=rename',{oldname:old,newname:val,'path':path});
}



$(function(){
	
	//Session ID для Flash-загрузчика
	var SID=$(this).data('SID');


	$('.file-name').dblclick(function(){
		var text=$.trim($(this).text());
		$(this).empty().append('<input/>').find('input').val(text).data({old:text}).focus().css('width','99%').blur(rename);
	});
	
	$('.remove').click(function(){
		if(confirm('Удалить?')){
			var path=$('[name=path]').val();
			$.post('?act=remove',{name:$(this).parent().parent().attr('title'),'path':path});
			$(this).parent().parent().hide();
		}
		
		return false;
	});
	$('.removeAll').click(function(){
		if(confirm('Удалить?')){
			var path=$('[name=path]').val();
			$.post('?act=removeAll',{name:$(this).parent().parent().attr('title'),'path':path});
			alert('ok');
		}
		
		return false;
	});
	
	$('.archive').click(function(){
		$.get(this.href,function(res){
			alert(res);
		});
//		$(this).parent().parent().hide();
		return false;
	});
	
	$('#mkdir').click(function(){
		var path=$('[name=path]').val();
		$.post('?act=newfolder',{name:$('[name=dirname]').val(),'path':path},function(){
			window.location.reload(); 
		});
		return false;
	});
	
	$('#upload_file').submit(function(){
		$('#preloader').show();
	});
	
	$('[name=remove]').click(function(){
		if(confirm('Удалить?')){
			var path=$('[name=path]').val();
			$.post('?act=remove',$(this.form).serialize(),function(res){
				window.location.reload();
			},'json');
			
		}
		
		
	});
	
})
