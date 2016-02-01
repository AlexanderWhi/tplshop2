var edit = function(id,name,type,grp){
	$( "#dialog-add [name=id]").val(id);
	$( "#dialog-add [name=name]").val(name);
	$( "#dialog-add [name=type]").val(type);
	$( "#dialog-add [name=grp]").val(grp);
	$( "#dialog-add").dialog('open');
}

var _cb=function(res){
	
	for(var i in res.img){
//		alert(util.scaleImg(res.img[i],'w100'))
		$("#image"+i).attr('src',util.scaleImg(res.img[i],'w100')).show()
	}
}
$(function(){
	
	
	$(".upload").change(function(){
		$(this.form).submit()
	});
	
//	$('input[name=all]').click(function(){
//		$('input.item').attr('checked',false);
//		$('tr:visible input.item').attr('checked',$(this).attr('checked'));
//	});
	
	$('input[name=delete]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}else if(confirm('Удалить '+$('input.item:checked').length+' Элементов?')){
			$.post('?act=deleteMans',$('#props_item').serialize(),function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#prop_item'+res.ids[i]).remove();
					}
				}
				
			},'json');
		}
	});
	$('input[name=delete_all]').click(function(){
		if(confirm('Удалить все записи?')){
			$.post('?act=truncMans',{},function(res){
				document.location.reload();
				
			},'json');
		}
		return false;
	});
	
	$('input[name=apply]').click(function(){
		$.post('?act=applyMans',$('#props_item').serialize(),function(res){
			util.msg(res.msg);
		},'json');
	});

	
	$('.remove').click(function(){
		
		if(confirm('Вы действительно хотите удалить запись?')){
			$.post('?act=deleteMans',{id:$(this).attr('rel')},function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#prop_item'+res.ids[i]).remove();
					}
				}
			},'json');
		}	
		return false;
	});
	
	$('.edit').click(function(){
		
		$.get($(this).attr('href'),{},function(res){
			$( "#dialog-add").dialog('open');
			for(var i in res){
				$( "#dialog-add [name="+i+"]").val(res[i]);
			}
			
		},'json');
		
		return false;
	});


	
	
	$('#props_item .add').click(function(){
		$( "#dialog-add input").val('');
		$( "#dialog-add textarea").val('');
		$( "#dialog-add").dialog('open');return false;
	});

	
	$( "#dialog-add").dialog({
		modal: false,
		autoOpen: false ,
		width:700,
		height:500,
		resizable:false,
		buttons: {  
			'Применить': function() {
				$( "#dialog-add").submit();
//				var data=$(this).serialize();
//			
//				$.post(this.action,data,function(res){
//					$( "#dialog-add").dialog('close');
//					util.msg('Добавлено успешно');
//					document.location.reload();
//				},'json'); 
			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
});
