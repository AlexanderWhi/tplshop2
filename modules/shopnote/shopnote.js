var fillList=function(html){
	$('.list_result').html(html);
					$('.list_result .count').count();
					
					$('.list_result label[for]').custom();
					
					$('.list_result .remove').click(function(){
						$(this).parent().remove();
						return false;
					});
					$('.list_result [name=save_list]').click(function(){
						if($('[name=name]').val()==''){
							alert('Введите название списка');
							return false;
						}
						
						$.post('?act=saveList',$(this.form).serialize(),function(html){
							
							$('.list_content').html(html);
							alertBox('Изменения внесены')
						});
						return false;
					});
}


$('.list_content a').live('click',function(){
		$('[name=name]').val($(this).text());
		$.post('?act=getList',{name:$(this).text()},fillList);
		return false;
	});
$('#shopnote-form .remove_list').live('click',function(){
	if(confirm('Удалить выбранный список?')){
		$.post('?act=removeList',{name:$('[name=name]').val()},function(html){
			$('[name=name]').val('');
			$('.list_result').html('');
			$('.list_content').html(html);
			
		});
	}
	return false;
	
});
$(function(){
	
	$('[name=add]').click(function(){
		$('[name=name]').val($('[name=addname]').val());
		$('.list_result').html('');
		return false;
	});


	$('.item_head .expand').click(function(){
		var id=$(this).attr('href').replace(new RegExp('.*#'),'');
		if($('#head'+id).hasClass('act')){
			$('#head'+id).removeClass('act');
			$('#body'+id+' .item_content').hide();
		}else{
			$('#head'+id).addClass('act');
			$('#body'+id+' .item_content').show();
		}
		
		return false;
	});
	
	
	
	$('#shopnote-form [name=search]').autocomplete('/autocomplete/autocomplete_search.php',
		{
			width:300,
			selectFirst:false,
			max:50,
			minChars:3,
			delay:100,
			formatItem :function (row, i, num) {  
				var result = row[0] + "<strong> " + row[1] + "</strong> р.";  
				return result;  
			}
		}).keypress(function(e){
			if((e.keyCode == 0xA)||(e.keyCode == 0xD)){//нажатие ентер
				$.post('?act=search',$(this.form).serialize(),function(html){
					
					$('.search_result').html(html);
					$('.search_result .count').count();
					$('.search_result .remove').click(function(){
						$(this).parent().remove();
						return false;
					});
					$('.search_result [name=add_to_list]').click(function(){
						$.post('?act=addToList',$(this.form).serialize(),fillList);
						return false;
					});
					
				});
				return false;
			}
			
		});
	
	
});	

