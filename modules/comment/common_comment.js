$(function(){
	$('#goods-fb-form').submit(function(){
		$.post(this.action,$(this).serialize(),function(res){
			if(res.err){
				$.alert(res.err);
			}else{
				$('#goods-fb-form').hide();
			}	
		},'json');
		
		return false;
	});
	
	$('.show_goods_comment').click(function(){
		
		var id=$(this).attr('rel');
		$.post('/comment/?act=showComment',{'id':id},function(res){
			alert('Показано');
		},'json');
		return false;
	});
	$('.remove_goods_comment').click(function(){
		
		var id=$(this).attr('rel');
		$.post('/comment/?act=RemoveComment',{'id':id},function(res){
			alert('Скрыто');
		},'json');
		return false;
	});
	
	$('.answer_comment').click(function(){
		$('.answer_form').show()
		
		$('button.answer').click(function(){
			alert($(this.form).serialize())
			return false;
		});
	
		return false;
	});
});	