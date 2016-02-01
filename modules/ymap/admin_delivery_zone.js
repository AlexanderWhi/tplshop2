$(function(){
	$('#zone-form').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			$('#preloader').hide();
			util.msg(res.msg);
		},'json');
		return false;
	});
	
	$('.add').click(function(){
		var data=$('tfoot tr').clone();
		data.find('[name]').each(function(){
			$(this).attr('name',$(this).attr('name').replace(new RegExp('_$'),'[]'));
		});
		$('#zone-form table tbody').append(data.show());
		return false;
	});
	
	$('#zone-form').on('click','.del',function(){
		var data=$(this).parents('tr').remove();
		return false;
	});
	$('#zone-form').on('click','.edit',function(){
		var data=$(this).parents('tr').find('[name^=id]').val();
		document.location.href='/admin/content/?act=edit&name=delivery_zone_'+data
		return false;
	});
	
	
});	