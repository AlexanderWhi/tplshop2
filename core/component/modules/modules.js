$(function(){
		
	
	var moduleId=0;
	var mouseIsDown=false;
//	var canMove=false;
	$('.move').mousedown(function(){
		mouseIsDown=true
		$(this).css('cursor','move');
		$(this).parent().parent().parent().addClass('tr_active');
	}).mouseup(function(){
		$(this).css('cursor','pointer')
		
	});
	$('.modules-item').mousedown(function(){
		moduleId=this.id.replace('module','');
		
		
	}).mouseup(function(){
		if(!mouseIsDown){
			return false;
		}
		moduleId=0;
		mouseIsDown=false;
//		canMove=false;
		$(this).css('cursor','default');

		var idsStr='';
		$('.modules-item[parent='+$(this).attr('parent')+']').each(function(){
			
			var id=this.id.replace('module','');
			idsStr+=','+id;
		});
//		alert(idsStr);
		var data={
			parent:$(this).attr('parent'),
			ids:idsStr
		};
		var tr=$(this);
		$('#preloader').show();
		$.post('?act=moveall',data,function(resp){
//			alert(resp.msg)
			tr.removeClass('tr_active');
			$('#preloader').hide();
			
		},'json');
		
		
	}).mousemove(function(){return false}).mouseover(function(){
		if( 
			moduleId && 
			mouseIsDown && 
			moduleId!=this.id.replace('module','') && 
			$(this).attr('parent')==$('#module'+moduleId).attr('parent')
		){
			if($(this).position().top<$('#module'+moduleId).position().top){
				$('#module'+moduleId).insertBefore( $(this) );
			}else{
				$('#module'+moduleId).insertAfter( $(this) );
			}
				
		}
	});
	
	
	$('.add_to').click(function(){
		if($('#modules [name^=item]:checked').length>0){
			if(confirm('Переместить выбранные элементы сюда?')){
				var to=$(this).attr('rel');
				$.postData('?act=MoveTo',$('#modules').serialize()+'&to='+to,function(){
					document.location.reload();
				});
			}
			
			return false;
		}
	});
	
})