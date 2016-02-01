var _cb=function(res){
			$('#preloader').hide();			
			if(res.start_time){$('#start_time').text(res.start_time)	}
			if(res.stop_time){$('#stop_time').text(res.stop_time)	}
			if(res.id){$('[name=id]').val(res.id)	}
			
			util.msg(res.msg)
		}

var goods_id=0;
var refreshBasket=function(par) { 
//	alert(par) 
//				var data=$('#goods_item').serialize();
//				data+=$(this).find('iframe').contents().find('#goods_popup_form').serialize();
				$.post('?act=refreshBasket',par+'&'+$('#order-form').serialize()+(goods_id?'&replace='+goods_id:''),function(res){
					$('#basket').html(res);
//					$("#dialog-goods").dialog("close"); 
					goods_id=0;
					
				}); 
			}
		
$(function(){

	$('[name=date]').datepicker({ dateFormat: 'dd.mm.yy'});
	
	$('[name=save_with_notice]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize()+'&save_with_notice=true',_cb,'json');
		return false;
	});
	
	$('[name=save]').click(function(){
		$('#preloader').show();
		$.post(this.form.action,$(this.form).serialize(),_cb,'json');
		return false;
	});

	
	$('[name=print]').click(function(){
		var url='?act=print&item[]='+this.form.id.value;
		util.openWin(url,800,600);
	});
	$('[name=print1]').click(function(){
		var url='?act=print&type=collect&item[]='+this.form.id.value;
		util.openWin(url,800,600);
	});	
	
	
	///////////////////////////////
	$('.delete').live('click',function(){
		if(confirm('Удалить')){
			$(this).parent().parent().parent().remove();
			$.post('?act=refreshBasket',$('#order-form').serialize(),function(res){
				$('#basket').html(res);
			})
		}
		return false;
	});
	
	$('button[name=add],.replace').live('click',function(){
		goods_id=0;
		if(this.tagName=='A'){
			goods_id=$(this).parent().parent().parent().attr('id').replace('item','');
		}
		$( "#dialog-goods").html('<iframe src="/operator/goodsPopup/'+(goods_id?'?goods='+goods_id:'')+'" style="width:100%;height:100%"></iframe>').dialog('open');
		return false;
	});	
	
	
	$( "#dialog-goods").dialog({
		modal: false,
		autoOpen: false ,
		width:800,
		height:600,
		resizable:true,
		buttons: {  
//			'Ок': function() {  
//				var data=$('#goods_item').serialize();
//				data+=$(this).find('iframe').contents().find('#goods_popup_form').serialize();
//				$.post('?act=refreshBasket',data+'&'+$('#order-form').serialize()+(goods_id?'&replace='+goods_id:''),function(res){
//					$('#basket').html(res);
//					$("#dialog-goods").dialog("close"); 
//					goods_id=0;
//					
//				}); 
//			},
			'Закрыть': function() {  
				$(this).dialog("close");  
			} 
		}  
	});
	
//	$('#basket input').live('change',function(){
//		$.post('?act=refreshBasket',$(this.form).serialize(),function(res){
//			$('#basket').html(res);
//		});
//	});
		
	$('[name=refresh]').click(function(){
		$.post('?act=refreshBasket',$(this.form).serialize(),function(res){
			$('#basket').html(res);
		});
		return false;
	});

	
	$(".grid tbody tr").live('mouseover',function(){
		$(this).addClass("tr_hover")
	}).live('mouseout',function(){
		$(this).removeClass("tr_hover")
	});
	
	$('.count,.iprice').live('keyup',function(){
		this.value=this.value.replace(/[^\d,\.]/g,'').replace(/,/g,'.');
		var par=$(this).parent().parent();
		var count=parseFloat(par.find('.count').val());
		var iprice=parseFloat(par.find('.iprice').val());
		par.find('.price').val(parseFloat(count*iprice).toFixed(2));
		
	});
	
	
	var geocoder;	
	if('undefined'!=(typeof google)){
		geocoder = new google.maps.Geocoder();
	}
	
	
	var start_point=new google.maps.LatLng(56.8786246, 60.5250386);	
	
	$('#update-distance').click(function(){
		var address=$.trim($('[name=address]').val().replace(/\(.*\)/,''));
		if(address){
			if(geocoder){
				var request={'address':'Екатеринбург, '+address};
				geocoder.geocode(request,
					function(results,status){
						if(status == google.maps.GeocoderStatus.OK){
							var distance=google.maps.geometry.spherical.computeDistanceBetween (start_point,results[0].geometry.location)
							distance=(distance/1000).toFixed(1);
							var delivery_price=0;
							for(var i in delivery_list){
								if(parseFloat(delivery_list[i][0])<distance){
									delivery_price=delivery_list[i][1];
								}
							}	
							if(delivery_price){
								$('#update-distance-msg').html('<br>Стоимость доставки '+delivery_price+' руб.');
							}
						
//							var sublocality='';
//							for(var i in results[0].address_components){
//								var ac=results[0].address_components[i];
//								if('sublocality'==ac.types[0]){
//									sublocality=ac.long_name;
//								}
//							}
//							if($.trim(sublocality)){
								address=$.trim(address.replace(/\(.*\)/,''));
								address+=' ('+distance+' км.)';
								$('[name=address]').val(address);
//							}
						}
					}
				);
			}
		}
		return false;
	});

});	