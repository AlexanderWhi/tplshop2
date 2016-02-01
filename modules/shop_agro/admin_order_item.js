_cb=function(res){
			$('#preloader').hide();			
			if(res.start_time){$('#start_time').text(res.start_time)	}
			if(res.stop_time){$('#stop_time').text(res.stop_time)	}
						
			util.msg(res.msg)
			
			
			if(res.newid){
				document.location.href='?act=orderItem&id='+res.newid;
			}
		}

$(function(){
	$('#order-form').submit(function(){
		$('#preloader').show();
		$.post(this.action,$(this).serialize(),_cb,'json');
		return false;
	});
	
	$('[name=set_discount]').click(function(){
		$('#preloader').show();
		$.post('?act=setDiscount',$(this.form).serialize(),function(res){
			$('#preloader').hide();
			util.msg('Принято');	
		},'json');
		
	});
	
	$('[name=save_with_notice]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize()+'&save_with_notice=true',_cb,'json');
		return false;
	});
	$('.send').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize()+'&method='+this.name,_cb,'json');
		return false;
	});
	$('[name=save_with_notice_vendor]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize()+'&save_with_notice_vendor=true',_cb,'json');
		return false;
	});

//	$('[name=close]').click(function(){
//		window.history.back();
//		return false;
//	});
	
	$('[name=print]').click(function(){
		var url='?act=print&item[]='+this.form.id.value;
		util.openWin(url,800,600);
	});
	
	$('.date').datepicker({ dateFormat: 'dd.mm.yy'});
	
	
	$('[name=recount]').click(function(){
		$('#preloader').show();
		$.post('?act=renderOrderContent',$(this.form).serialize(),function(res){
			$('#preloader').hide();
			$('#orderContent').html(res).find('.grid').grid();
		});
	});
	
	
	
	$( "#dialog-goods").dialog({
		modal: false,
		autoOpen: false ,
		width:800,
		height:600,
		resizable:true,
		buttons: {  
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	$( "#dialog-perf").dialog({
		modal: false,
		autoOpen: false ,
		width:1000,
		height:750,
		resizable:true,
		buttons: {  
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	$( "#dialog-nmn").dialog({
		modal: false,
		autoOpen: false ,
		width:550,
		height:200,
		resizable:true,
		buttons: {  
			'Закрыть': function() {  
				$(this).dialog("close");  
			},'Сохранить': function() { 
				
				nmn_cb($('textarea',this).val());
				 
				$(this).dialog("close");  
			} 
		}  
	});
	
	/*$('.replace').live('click',function(){
		goods_id=0;
		if(this.tagName=='A'){
			goods_id=$(this).parent().parent().parent().attr('id').replace('item','');
		}
		$( "#dialog-goods").html('<iframe src="/operator/goodsPopup/'+(goods_id?'?goods='+goods_id:'')+'" style="width:100%;height:100%"></iframe>').dialog('open');
		return false;
	});*/
	$('#orderContent').on('keyup','[name^=count]',function(){
		var val=$(this).val();
		val=val.replace(new RegExp('[^\\d\\.]','g'),'');
		if(!new RegExp('\\.$').test(val) && val!=''){
			val=parseFloat(val);
		}
		$(this).val(val);
		
		return false;
	});
});	

function _select(id){
//	alert(id)
	replace_cb(id);
}

function replace_cb(new_id){
	$( "#dialog-goods").dialog("close");
	$('#preloader').show();
	
	$.post('?act=ReplaceOrderContent',{'old_id':selected_goods,'new_id':new_id,'order_id':$('[name=id]').val()},function(){
		$.post('?act=renderOrderContent',$('#order-form').serialize()+'&old_id='+selected_goods+'&new_id='+new_id,function(res){
			$('#preloader').hide();
			$('#orderContent').html(res);//.find('.grid').grid();
		});
	},'json');
	
		
}
function remove(id){
	if(confirm('Удалить?')){
		selected_goods=id;
		replace_cb(0);
	}
	
}

function replace(id){
	selected_goods=id;
	$( "#dialog-goods").html('<iframe src="/admin/catalog/goodsPopup/mode/one/'+(id?'?goods='+id:'')+'" style="width:99%;height:99%"></iframe>').dialog('open');
}
var selected_goods=0;



function selectPerf(){
	$( "#dialog-perf").html('<iframe src="/admin/usr/popup/usertype/partner/" style="width:99%;height:99%"></iframe>').dialog('open');

}
function _selectUsr(id){
	$( "#dialog-perf").dialog('close');
	$('[name=perfid]').val(id);
}



function nmn(id){
	selected_goods=id;
	$( "#dialog-nmn").dialog('open');
}

function nmn_cb(txt){
	$.post('?act=ReplaceOrderContentNmn',{'old_id':selected_goods,'txt':txt},function(){
		$.post('?act=renderOrderContent',$('#order-form').serialize()+'&old_id='+selected_goods,function(res){
			$('#preloader').hide();
			$('#orderContent').html(res).find('.grid').grid();
		});
	},'json');
}




