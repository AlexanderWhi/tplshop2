$(function(){
	$('#raiting-ref-form').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			$('#preloader').hide();
			util.msg(res.msg);
		},'json');
		return false;
	});
	
	$('.add').click(function(){
		var data=$('tfoot tr').clone();
//		alert(data.html())
		
		data.find('[name]').each(function(){
			
			var n=$(this).attr('name').replace('|','[]');
			$(this).attr('name',n);
		});
		$('#raiting-ref-form table tbody').append(data.show());
		return false;
	});
	
	$('#raiting-ref-form').on('click','.del',function(){
		var data=$(this).parent().parent().remove();
		return false;
	});
});	