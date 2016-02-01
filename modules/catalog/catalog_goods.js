var acPropName=function(){
		var grp=$('.pgrp',$(this).parent().parent());
//		alert(grp.val())
		$(this).autocomplete({
			source: function(request,response){
				
				request.grp=grp.val();
				$.getJSON("/autocomplete/autocomplete_sh_prop.php",request,response);				
			},
			minLength: 1
		});
	}

function addProp(){
	
	var el=$('#add_prop tfoot tr').clone();
	
	$('[name=new_prop_name_]',el).each(acPropName);
	
	$('[name]',el).each(function(){
		$(this).attr('name',$(this).attr('name').replace(new RegExp('_$'),'[]'));
		
	});
	$('.remove',el).click(function(){
		$(this).parent().parent().remove();
		return false;
	})
	el.appendTo($('#add_prop tbody'));
	
}


$(function(){
	$('[name=catalog]').change(function(){
		document.location='?category='+this.value		
		return false;
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
			$.post('?act=deleteGoods',$('#goods_item').serialize(),function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#goods_item'+res.ids[i]).remove();
					}
				}
				
			},'json');
		}
	});
	
	$('.remove').click(function(){
		if(confirm('Вы действительно хотите удалить запись?')){
			$.get(this.href,{},function(res){
				if(res.ids){
					for(var i=0;i<res.ids.length;i++){
						$('#goods_item'+res.ids[i]).remove();
					}
				}
			},'json');
		}
		return false;
	});
	
	$('input[name=price]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}
		$( "#dialog-price-expr").dialog('open');
	});
	
	$( "#dialog-price-expr").dialog({
		modal: false,
		autoOpen: false ,
		height:200,
		resizable:false,
		buttons: {  
			'Применить': function() {  
				var data=$('#goods_item').serialize()+'&price_expr='+$('[name=price_expr]').val().replace(/\+/g,'%2B');
			
				$.post('?act=setPrice',data,function(res){
					var count=0;
					for(var i in res){
						$('#goods_item'+i+' .price').text(res[i]);
						count++;
					}
					$( "#dialog-price-expr").dialog('close');
					util.msg('Обновлено '+count+' товаров');
				},'json'); 
			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});	
	
//	$('.price').parent().click(function(){
//		var ch=$(this).find('input.item'); 
//		ch.attr('checked',!ch.attr('checked'));
//	}).find('input.item').click(function(){
//		$(this).attr('checked',!$(this).attr('checked'));
//	});
	
	
	$( "#dialog-goods").dialog({
		modal: false,
		autoOpen: false ,
		width:800,
		height:600,
		resizable:true,
		buttons: {  
			'Соединить': function() {  
				var data=$('#goods_item').serialize();
				data+=$(this).find('iframe').contents().find('#goods_item_popup').serialize()+'&mode=join';
				data+='&relation='+$('[name=relation]',this).val()
				$.post('?act=saveRelation',data,function(res){
					var count=0;
					for(var i in res){
						count++;
					}
					$( "#dialog-goods").dialog('close');
					util.msg('Обновлено '+count+' товаров');
				},'json'); 
			},
			'Разорвать': function() {  
				var data=$('#goods_item').serialize();
				data+=$(this).find('iframe').contents().find('#goods_item_popup').serialize()+'&mode=split';
				data+='&relation='+$('[name=relation]',this).val()
				$.post('?act=saveRelation',data,function(res){
					var count=0;
					for(var i in res){
						count++;
					}
					$( "#dialog-goods").dialog('close');
					util.msg('Обновлено '+count+' товаров');
				},'json'); 
			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	
	$('#dialog-movegoods').dialog({
		modal: false,
		autoOpen: false ,
		width:600,
		height:200,
		resizable:true,
		buttons: { 
		'Переместить': function() {  
				var data=$('#goods_item').serialize();
				data+='&category='+$('#catalog-category').val();
				$.post('?act=removeToCategory',data,function(res){
				var count = 0;
				for (var i in res) { count++; } 	
				$("#dialog-movegoods").dialog('close');
//				window.location.href="/admin/catalog/goods/?category="+$('#catalog-category').val(); 

				util.msg(res.msg);
				setTimeout(function(){document.location.reload()},500);
				 
								
				},'json'); 
			},'Копировать': function() {  
				var data=$('#goods_item').serialize();
				data+='&category='+$('#catalog-category').val();
				$.post('?act=removeToCategory&mode=copy',data,function(res){
				
				$("#dialog-movegoods").dialog('close');
//				window.location.href="/admin/catalog/goods/?category="+$('#catalog-category').val(); 

				util.msg(res.msg);
				setTimeout(function(){document.location.reload()},500);
				 
								
				},'json'); 
			},'Извлечь': function() {  
				var data=$('#goods_item').serialize();
				data+='&category='+$('#catalog-category').val();
				$.post('?act=removeToCategory&mode=remove',data,function(res){
					
				$("#dialog-movegoods").dialog('close');

				util.msg(res.msg);
				setTimeout(function(){document.location.reload()},500);
				 
								
				},'json'); 
			},
		'Закрыть': function() {  
				$(this).dialog("close");  
			}
		}
	});
	
	$('#dialog-propgoods').dialog({
		modal: false,
		autoOpen: false ,
		width:600,
		height:600,
		resizable:true,
		buttons: { 
		'Назначить': function() {  
					var data=$('#goods_item').serialize();
					data+='&'+$('#dialog-propgoods form').serialize();
					$.post('?act=setGoodsProp',data,function(res){
						var count = 0;
						for (var i in res) { count++; } 	
						$("#dialog-propgoods").dialog('close');
						 
						util.msg(res.msg); 		
				},'json'); 
		},
		'Удалить': function() {  
					var data=$('#goods_item').serialize();
					data+='&mode=remove&'+$('#dialog-propgoods form').serialize();
					$.post('?act=setGoodsProp',data,function(res){
						var count = 0;
						for (var i in res) { count++; } 	
						$("#dialog-propgoods").dialog('close');
						 
						util.msg(res.msg); 		
				},'json'); 
		},
		'Закрыть': function() {  
				$(this).dialog("close");  
			}
		}
	});
	
	
	$('#dialog-mangoods').dialog({
		modal: false,
		autoOpen: false ,
		width:400,
		height:200,
		resizable:true,
		buttons: { 
		'Назначить': function() {  
					var data=$('#goods_item').serialize();
					data+='&'+$('#dialog-mangoods form').serialize();
					$.post('?act=setGoodsMan',data,function(res){
						var count = 0;
						for (var i in res) { count++; } 	
						$("#dialog-mangoods").dialog('close');
						 document.location.reload()
						util.msg(res.msg); 		
				},'json'); 
		},
		'Закрыть': function() {  
				$(this).dialog("close");  
			}
		}
	});
	
$('#dialog-actiongoods').dialog({
		modal: false,
		autoOpen: false ,
		width:400,
		height:200,
		resizable:true,
		buttons: { 
		'Назначить': function() {  
					var data=$('#goods_item').serialize();
					data+='&'+$('#dialog-actiongoods form').serialize();
					$.post('?act=setGoodsAction',data,function(res){
						var count = 0;
						for (var i in res) { count++; } 	
						$("#dialog-actiongoods").dialog('close');
						 document.location.reload()
						util.msg(res.msg); 		
				},'json'); 
		},
		'Закрыть': function() {  
				$(this).dialog("close");  
			}
		}
	});
	

	$('input[name=Related]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}
		
		if(!$("#frame-content iframe").length){
			$("#frame-content").html('<iframe src="/admin/catalog/goodsPopup/" style="width:100%;height:90%"></iframe>');
		}
		
		$("#dialog-goods").dialog('open');
	});
	
	$('input[name=removetocategory]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}		
		$("#dialog-movegoods").dialog('open');
	});
	
	$('input[name=propgoods]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}

		$.post('?act=GetPropList',$(this.form).serialize(),function(html){
			$('#dialog-propgoods .edit_prop').html(html);
			
			$('#dialog-propgoods [name^=pvalue]').click(function(){
				var id=$(this).attr('name').replace(new RegExp('\\D','g'),'');
				$('#dialog-propgoods [value='+id+']').prop('checked',true);
			});
		
			
			$("#dialog-propgoods").dialog('open');
		});
	
			
		
	});	
	$('input[name=mangoods]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}		
		$("#dialog-mangoods").dialog('open');
	});	
	$('input[name=actiongoods]').click(function(){
		if($('input.item:checked').length==0){
			alert('Не выбрано ни одного элемента');
			return;
		}		
		$("#dialog-actiongoods").dialog('open');
	});	
	
	
//	$('[name=search]').keyup(function(){
//		if($(this).val()){
//			val=new RegExp($(this).val().replace(/(\\|\/|\)|\(|\]|\[|\+|\?|\$|\^|\.|\*|\|)/g,'\\\$&'),'gi');
//			$('.grid tbody tr').each(function(){
//				$(this).toggle(val.test($(this).text()));
//			});
//		}else{
//			$('.grid tbody').find('tr:hidden').show();
//		}
//		$('input.item:checked').attr('checked',false)
//	});
	
	
	$('input[name=sort]').click(function(){
		$.post('?act=setSort',$('#goods_item').serialize(),function(res){
			util.msg('Успешно');
		},'json');
	});
	
	
	/*$( "#dialog-import").dialog({
		modal: false,
		autoOpen: false ,
		width:300,
		height:200,
		resizable:false,
		buttons: {  
			'Импорт': function() {  
				$('#import-form').submit();
			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	
	$('input[name=import]').click(function(){

		$( "#dialog-import").dialog('open');
	});*/
	$('#export_all').item('export');
});

function _import(res){
	$( "#dialog-import").dialog('close');
}