var recount=function(){
	var total=0;
	$('#collect select').each(function(){
		if(this.value){
			var tr=$(this).parents('tr');
			var price=$(':selected',this).attr('price');
			var count=$('.count',tr).val();
			total+=price*count;
			
		}
		
		
	});
	$('#collect .total').text(total+' руб.');
	
}

$(function(){
	$('#collect select').change(function(){
//		alert(this.value);
		var tr=$(this).parents('tr');
		if(this.value){
			
			$('.count',tr).show().val(1);
			$('.price',tr).text($(':selected',this).attr('price')+' руб.');
			
		}else{
			$('.count',tr).hide().val(1);
			$('.price',tr).text('');
		}
		recount();
		
	});
	$('#collect .count').keyup(function(){
		recount();
	});
		
	
	$('#collect [name=send]').click(function(){
		var el=this.form;
		$('.error').hide();
		$.post('?act=SendCollect',$(el).serialize(),function(res){
			if(res.err){
				for(var i in res.err){
					$('#error-'+i).text(res.err[i]).show();
				}
			}else{
				$(el).hide();
				$('.success').show();							
			}	
		},'json')
				
		return false;
	});
});