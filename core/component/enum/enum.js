$(function(){
	$('#enum-form').submit(function(){
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
		data.find('[name=field_value_]').attr('name','field_value[]');
		data.find('[name=value_desc_]').attr('name','value_desc[]');
		data.find('[name=position_]').attr('name','position[]');
		$('#enum-form table tbody').append(data.show());
		return false;
	});
	
	$('#enum-form').on('click','.del',function(){
		var data=$(this).parents('tr').remove();
		return false;
	});
	$('.remove').click(function(){
		if(confirm('Удалить?')){
			var fnme=$(this).attr('href').replace(new RegExp('.*#'),'');
			var el=this;
			$('#preloader').show();
			$.post('?act=remove',{'field_name':fnme},function(res){
				$('#preloader').hide();
				util.msg(res.msg);
				$(el).parents('tr').remove();
			},'json');
		}

		return false;
	});
	
	
});	