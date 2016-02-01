$(function(){
	$( "#ceo-bar" ).dialog({
		modal: false,
		autoOpen: false ,
		width:550,
		height:450,
		resizable:false,
		buttons: {  
			'Принять': function() {  
				var f=$(this).find('form'); 
				$.post('/ceo/?act=save',f.serialize(),function(res){
					$("#ceo-bar").dialog("close");
				}); 
			} ,	
			'Удалить правило': function() {  
				var f=$(this).find('form'); 
				$.post('/ceo/?act=del',f.serialize(),function(res){
					$("#ceo-bar").dialog("close");
				}); 
			} ,
			'Закрыть': function() {  
				$(this).dialog("close");  
			}  
		}  
	});
	$('#ceo-open').click(function(){
		$('#ceo-bar').dialog("open");
		return false;
	});
	
	$( "#ceo-text-bar" ).dialog({
		modal: false,
		autoOpen: false ,
		width:450,
		height:300,
		resizable:false,
		buttons: {  
			'Принять': function() {  
				var f=$(this).find('form'); 
				$.post('/ceo/?act=saveText',f.serialize(),function(res){
					$("#ceo-text-bar").dialog("close");
				}); 
			} ,
			'Удалить правило': function() {  
				var f=$(this).find('form'); 
				$.post('/ceo/?act=delText',f.serialize(),function(res){
					$("#ceo-text-bar").dialog("close");
				}); 
			} ,
			'Закрыть': function() {  
				$(this).dialog("close");  
			}  
		}  
	});
	$('.ceo-text').click(function(){
		var place=this.id.replace('ceo-place','');
		var url=this.rel;
		$.post('/ceo/?act=getText',{'place':place,'url':url},function(res){
			$('#ceo-text-bar').find('[name=place]').val(res.place);
			$('#ceo-text-bar').find('[name=url]').val(res.url);
			$('#ceo-text-bar').find('[name=text]').val(res.text);
			$('#ceo-text-bar').find('[name=rule]').attr('checked',res.rule=='=');
			
			if(res.exists==true){
				$('#ceo-text-bar').dialog("option",'title','Редактировать правило');
			}else{
				$('#ceo-text-bar').dialog("option",'title','Добавить правило');
			}
			$('#ceo-text-bar').dialog("open");
		},'json');
		
		return false;
	});
	
});