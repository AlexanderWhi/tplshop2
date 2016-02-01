$(function(){
	$('#deliv-form').submit(function(){
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
//		data.find('[name=distance_]').attr('name','distance[]');
		data.find('[name=price_]').attr('name','price[]');
		data.find('[name=summ_]').attr('name','summ[]');
		$('#deliv-form table tbody').append(data.show());
		return false;
	});
	
	$('.del').live('click',function(){
		var data=$(this).parent().parent().remove();
		return false;
	});
});	