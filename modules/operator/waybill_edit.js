var wb={
	_cb : function(res){
		$('#preloader').hide();			
		if(res.id){$('[name=id]').val(res.id)	}
		if(res.order_status){$('.order_status').text(res.order_status)	}
		util.msg(res.msg)
	},
	_refreshOrder : function(res){
		$('#orders').html(res);
		$("#dialog-orders").dialog("close");
		$('[name=all]').click(function(){
			$('input.item').attr('checked',$(this).attr('checked'));
		});
	}
}


$(function(){
	$('[name=all]').click(function(){
		$('input.item').attr('checked',$(this).attr('checked'));
	});
	
	
	$('[name=save]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize(),wb._cb,'json');
		return false;
	});


	
	$('[name=print]').click(function(){
		var url='?act=printWaybill&item[]='+this.form.id.value;
		util.openWin(url,800,600);
	});
	
	$('[name=print_orders]').click(function(){
		if($('.item:checked').length==0){
			alert('Необходимо выбрать хотя бы 1 заказ для печати!');
			return false;
		}
		var url='?act=print';
		$('.item:checked').each(function(){
			url+='&item[]='+this.value;
		});
		util.openWin(url,800,600);
		return false;
	});

	$('[name=delete]').click(function(){
		if(confirm('Удалить')){
			$('.item:checked').parent().parent().remove();
			$.post('?act=refreshOrders',$('#waybill-form').serialize(),wb._refreshOrder);
		}
		return false;
	});
	
	$('.sort').live('change',function(){
		$.post('?act=refreshOrders',$('#waybill-form').serialize(),wb._refreshOrder)
		return false;
	});

	
	$('button[name=add]').click(function(){
		$("#dialog-orders").html('<iframe src="/operator/ordersPopup/" style="width:100%;height:100%"></iframe>').dialog('open');
		return false;
	});	
	
	
	$( "#dialog-orders").dialog({
		modal: false,
		autoOpen: false ,
		width:800,
		height:600,
		resizable:true,
		buttons: {  
			'Ок': function() {
				var data=$(this).find('iframe').contents().find('#orders_popup_form').serialize();
				$.post('?act=refreshOrders',data+'&'+$('#waybill-form').serialize(),wb._refreshOrder); 
			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	

		
	$(".grid tbody tr").live('mouseover',function(){
		$(this).addClass("tr_hover")
	}).live('mouseout',function(){
		$(this).removeClass("tr_hover")
	});
});	