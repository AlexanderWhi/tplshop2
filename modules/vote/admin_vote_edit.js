var del=function(){
	$(this).parent().parent().remove();
	return false;
}

$(function(){
	
	$('.date').datepicker({ dateFormat: 'dd.mm.yy',maxDate :null});
	$('#vote-form').submit(function(){
		var data=$(this).serialize();
		$('#preloader').show();
		$.post(this.action,data,function(resp){
			$('#preloader').hide();
			$('[name=id]').val(resp.id);
			util.msg(resp.msg)
		},'json');
		return false;
	});
//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
	
	$('#item-add').click(function(){
		$(this).parent().find('tbody').append('<tr>'+
			'<td><input name="item[]" style="width:200px"/></td>'+
			'<td><input name="item_sort[]" value="0" style="width:20px"/></td>'+
			'<td><input name="item_res[]" value="0" style="width:20px"/></td>'+
			'<td><a class="remove" href="#">Удалить</a></td>'+
			'</tr>').find('a.remove').click(del);
		return false;
	});
	$('#vote-item a.remove').click(del);
});