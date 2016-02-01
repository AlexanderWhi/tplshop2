var tmr=null;
var search='';
function findItems(){	
		if(search){
			val=new RegExp(search.replace(/(\\|\/|\)|\(|\]|\[|\+|\?|\$|\^|\.|\*|\|)/g,'\\\$&'),'i');
			$('.grid tbody tr').each(function(){
				$(this).toggle(val.test($(this).text()));
			});
		}else{
			$('.grid tbody').find('tr:hidden').show();
		}
	}

$(function(){
	$('[name=catalog],[name=page_size]').change(function(){
		this.form.submit()
	});
	
//	$('input[name=all]').click(function(){
//		$('input.sel_item').attr('checked',false);
//		$('tr:visible input.sel_item').attr('checked',$(this).attr('checked'));
//	});
	
//	$('.select').click(function(){
//		
//		return false;
//	});
	

	$('.count',window.parent.window.document).each(function(){
		
	
		var id=this.name.replace(/\D+/g,'');
//		alert(id)
		var count=this.value;
		$('[name=sel_item\['+id+'\]]').val(count);
		$('#goods_item'+id+' td:eq(1)').css({'font-weight':'bold','text-decoration':'underline'});
	});


	$('.count').count();
	
	
	$('.price').parent().mouseover(function(){
		$(this).find('input').select(); 
	}).click(function(){
//		alert('')
	
		var el=$(this).find('input');
		var par=el[0].name+'='+el[0].value;
//		alert(par);
		window.parent.window.refreshBasket(par);
		
//		$('[name='+el[0].name+']').val(count);
		$('td:eq(1)',this).css({'font-weight':'bold','text-decoration':'underline'});
		
	});

		
	$('[name=search]').keyup(function(){
		search=$(this).val();
		if(tmr){
			clearTimeout(tmr);
		}
		tmr=setTimeout("findItems()",200);
	})
	.autocomplete('/autocomplete/autocomplete_search.php?m=all',
		{
			width:300,
			selectFirst:false,
			max:50,
			minChars:1,
			delay:10,
			formatItem :function (row, i, num) {  
				var result = row[0] + "<strong> " + row[1] + "</strong> р.";  
				return result;  
			},
			onItemSelect:function(li) {  
				//if( li == null ) var sValue = "Ничего не выбрано!";  
				if( !!li.extra ) var sValue = li.extra[2];  
				else var sValue = li.selectValue;  
				//alert("Выбрана запись с ID: " + sValue); 
				return sValue;
			}
		})
	.each(function(){
		var el=this;
		$(this.form).submit(function(){
			if(!/[А-Яа-я]/.test(el.value))	el.value=str_replace(el.value );//Если есть русский символ, то транслит не нужен
		});
	}).select().mouseover(function(){
		$(this).select(); 
	});

});	