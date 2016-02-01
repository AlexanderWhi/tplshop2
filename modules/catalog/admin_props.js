var edit = function(id,name,type,grp){
	$( "#dialog-add [name=id]").val(id);
	$( "#dialog-add [name=name]").val(name);
	$( "#dialog-add [name=type]").val(type);
	$( "#dialog-add [name=grp]").val(grp);
	$( "#dialog-add").dialog('open');
}


$(function(){
	
	
	$('input[name=all]').click(function(){
		$('input.item').attr('checked',false);
		$('tr:visible input.item').attr('checked',$(this).attr('checked'));
	});
	
	$('input[name=delete]').click(function(){
		if($('input.item:checked').length==0){
			alert('�� ������� �� ������ ��������');
			return;
		}else if(confirm('������� '+$('input.item:checked').length+' ���������?')){
			$.post('?act=deleteProps',$('#props_item').serialize(),function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#prop_item'+res.ids[i]).remove();
					}
				}
				
			},'json');
		}
	});
	
	$('input[name=apply]').click(function(){
		$.post('?act=applyProps',$('#props_item').serialize(),function(res){
			util.msg(res.msg);
		},'json');
	});

	
	$('.remove').click(function(){
		
		if(confirm('�� ������������� ������ ������� ������?')){
			$.post('?act=deleteProps',{id:$(this).attr('rel')},function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#prop_item'+res.ids[i]).remove();
					}
				}
			},'json');
		}	
		return false;
	});
	
	$('.merge').click(function(){
		
		if(confirm('�� ������������� ������ ���������� ��������?')){
			
			var data=$('#props_item').serialize()+'&cur='+$(this).attr('rel');
			$.post('?act=mergeProps',data,function(res){
				$('#props_item :checked').attr('checked',false);
			
				util.msg(res.msg);
			},'json');
		}	
		return false;
	});
	
	$('.to_num').click(function(){
		if(confirm('�� ������������� ������ ������������� ��� �������� � �����?')){
			$.get($(this).attr('href'),function(res){
				util.msg(res.msg);
			},'json');
		}	
		return false;
	});

	
	
//	$('input[name=price]').click(function(){
//		if($('input.item:checked').length==0){
//			alert('�� ������� �� ������ ��������');
//			return;
//		}
//		$( "#dialog-price-expr").dialog('open');
//	});
	
	
	$('#props_item .add').click(function(){
		$( "#dialog-add").dialog('open');return false;
	});

	
	$( "#dialog-add").dialog({
		modal: false,
		autoOpen: false ,
		height:300,
		resizable:false,
		buttons: {  
			'���������': function() {  
				var data=$(this).serialize();
			
				$.post(this.action,data,function(res){
					$( "#dialog-add").dialog('close');
					util.msg('��������� �������');
					setTimeout(function(){document.location.reload()},1000)
				},'json'); 
			},
			'�������': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
});
