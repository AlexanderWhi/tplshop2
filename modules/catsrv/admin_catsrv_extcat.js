$(function(){
	$('#catsrv_form').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),function(res){
			$('#preloader').hide();
			msg(res.msg)
		},'json')
				
		return false;
	});
	
	$('[name^=lnk]').change(function(){
		$('#preloader').show();
		var data={
			id:$(this).attr('name').replace(new RegExp('lnk\\[(.+)\\]'),'$1'),
			val:$(this).val(),
			offer:$('[name=offer]').val()
		}
		$.post(this.form.action,data,function(res){
			$('#preloader').hide();	
		},'json')
	});
	
	$("[name=import]").click(function(){
		
		$('#preloader').show();
		$.post('?act=importFile',$(this.form).serialize(),function(res){
			$('#preloader').hide();
			msg(res.msg)
		},'json')
				
		return false;
		
	});
	
	$('.move').click(function(){
		var ext_id=this.rel;
		var id=$('[name=lnk\['+ext_id+'\]]').val();
		
		var data={
			'ext_id':ext_id,
			'id':id
		};
		
		$.post('?act=MoveSubCat',data,function(res){
			
			
			
		},'json');
		
		
		return false;
	});
	
	$('.move_to').click(function(){
		var ext_id=this.rel;
		var id=$('[name=lnk\['+ext_id+'\]]').val();
		
		var data={
			'ext_id':ext_id,
			'id':id
		};
		
		$.post('?act=MoveToSubCat',data,function(res){
			for(var i in res){
				$('[name=lnk\['+i+'\]]').val(res[i]);
			}
			
			
		},'json');
		
		
		return false;
	});
	
});	