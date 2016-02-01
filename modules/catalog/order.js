var changeDeliveryType=function(type){
	$('.block_delivery_type').hide();
	$('#block_delivery_type_'+type).show();
	$('[name=delivery_type]').val(type);
	
	$('#no_pickup').toggle(type!=3);
	$('#pay_system_1_blk').toggle(type==3);//09.06.2012 Наличный расчет – оставить только при самовывозе.
	if(type==3){
		$('[name=pay_system][value=1]').attr('checked',true)
	}else{
		$('[name=pay_system][value=2]').attr('checked',true)
	}
	
	$.post('?act=getRenderBasket',{'delivery':type,'is_order':true},function(res){
		$('#order-basket .basket_content').html(res);
			
		$('#order-report-static .price').text($('#basket_total_summ').text());
			
	});
	
}

var fillJurList=function(j_list){
	if(j_list.length){
		$('#blk-account-jur-form-list').show();
		var sel='';
		for(var i in j_list){
			sel+='<option value="'+i+'">'+j_list[i].name+'</option>'
		}
		$('#blk-account-jur-form-select').html(sel);
		
		for(var i in j_list[0]){
			$('#blk-account-jur-form [name=jur\['+i+'\]]').val(j_list[0][i]);
		}
		$('#blk-account-jur-name').text(j_list[0].name);
	}
}
//ADDRESS_LIST
var fillAddrList=function(addr_list){
	var html='';
	for(var i in addr_list){
		var addr=addr_list[i];
		html+='<a href="#'+i+'">'+addr.fullname+' '+addr.street+' '+addr.house+'-'+addr.flat+'</a>'
		
	}
	$('#address-list').html(html).find('a').click(function(){
		var id=this.href.replace(/.*#/,'');
		
		for(var i in addr_list[id]){
			$('[name='+i+']').val(addr_list[id][i]);
		}
		$('#address-list').hide();
		return false;
	});
}



//Динамическое время
var timeElement;
function checkTime(now,changed){
	var d=new Date(now*1000);
	
}

function getChangedTime(){
	var tmp=$('[name=date]').val().split('.');
	var tmp1=$('[name=time]').val();
	var d=new Date(tmp[2],tmp[1],tmp[0],tmp1);
}

function showAccessed(){
	var tmp=$('[name=date]').val().split('.');
	var nowTime=new Date(NOW*1000);
	$('[name=time]').html(timeElement.html());
	
	$('[name=time] option').each(function(){
		var tmp1=$(this).attr('value');
		var d=new Date(tmp[2],tmp[1]-1,tmp[0],tmp1,0,0);
		
//		alert(d.getTime()+' '+d.getDate()+' '+nowTime.getTime()+' '+tmp1)
		if(d.getTime()<nowTime.getTime()){
			$(this).remove();
		}
	});
}
////////////////////////

$(function(){
	
	timeElement=$('[name=time]').clone();
	showAccessed();
	$('input.date').attachDatepicker().change(function(){
		showAccessed();
	});
	$('#order-form [name=logon]').click(function(){
		var data={
			login:$('#order-form [name=login]').val(),
			password:$('#order-form [name=password]').val()
		}
		$('#error-logon').hide()
		$.post('?act=logon',data,function(res){
			
			if(res.msg){
				$('#error-logon').text(res.msg).show();
			}else{
				$('[name=mail]').val(res.mail);
				$('[name=name]').val(res.name);
				$('[name=address]').val(res.address);
				$('[name=street]').val(res.street);
				$('[name=house]').val(res.house);
				$('[name=flat]').val(res.flat);
				$('[name=porch]').val(res.porch);
				$('[name=floor]').val(res.floor);
				$('[name=phone]').val(res.phone);
				$('#block-logon-change').remove();				
				$('#addrbk').html(res.addrbk);				
			}		
					
		},'json');
		return false;
	});
	
	$('[name=order]').click(function(){

		var data=$(this.form).serialize();
		$('input.error,select.error').removeClass('error');
		$('.error').hide();
		
		
		var order_but=this;
		
		$(order_but).data('label',order_but.value).val($(order_but).attr('alt'))//.attr('disabled',true);
		
		$.post(this.form.action,data,function(res){
			if(res.error){
				var focus=true;
				for(var i in res.error){
					if(focus){focus=false;$('[name='+i+']').focus()}
//					alert(res.error[i]);
					$('#error-'+i).html(res.error[i]).show();
					$('[name='+i+']').addClass('error');
				}
				$(order_but).val($(order_but).data('label')).attr('disabled',false);
				
			}else if(res.id){
				$('#order-form').hide();
				$('#order-content-bar').hide();
				var or=$('#order-report').show()
				
				var html=or.html();
				
				html=html.replace('{order_num}',res.id)
					.replace('{order_num1}',res.id)
					.replace('{order_count}',res.count)
					.replace('{ps_href}',res.ps_href)
					.replace('{order_delivery}',res.delivery)
					.replace('{order_total_price}',res.total_price);
				or.html(html);

				document.location.href='#';
			}			
		},'json');

		return false;
	});
		
	$('[name=want_reg]').click(function(){
		$('#want_reg_tab').toggle(this.checked)
	});
	$('[name=auto_pass]').click(function(){
		$('#auto_pass_tab').toggle(!this.checked)
	});
	
	
//	$('#block_delivery_type_3').hide();
//	$('#delivery_type_1,#delivery_type_2').click(function(){
//		$('.block_delivery_type').hide();
//		$('#block_delivery_type_1_2').show();
//		
//		
//	});
//	$('#delivery_type_3').click(function(){
//		$('.block_delivery_type').hide();
//		$('#block_delivery_type_3').show();
//	});
	
//	$('[name=delivery_type]').change(function(){
//		$.post('?act=getRenderBasket',{'delivery':this.value,'is_order':true},function(res){
//			$('#order-basket').html(res);
//			
//			$('#order-report-static .price').text($('#basket_total_summ').text());
//			
//		});
//				
//	});
	
		
	
	$('input[title]').title();
	
	
	$('#chb_logon').click(function(){
		$('#block-logon').toggle(this.checked);
		$('#want_reg_tab').toggle(!this.checked);
		$('[name=login]').focus()
	});
	$('#reg_1').click(function(){
		$('#block-logon').toggle(!this.checked);
		$('#want_reg_tab').toggle(this.checked);
		$('[name=reg_login]').focus()
	});
	$('#reg_0').click(function(){
		$('#block-logon').toggle(!this.checked);
		$('#want_reg_tab').toggle(!this.checked);
		$('[name=name]').focus()
	});
	
	
	$('#delivery_type a').click(function(){
		$('.block_delivery_type').hide();
		$('#delivery_type a').removeClass('act');
		$(this).addClass('act');
		var id=this.href.replace(/.*#/,'');
		
		changeDeliveryType(id)		
		return false;
	});
	
		
	$('#delivery_type a.act').each(function(){
		var id=this.href.replace(/.*#/,'');
		changeDeliveryType(id)
		
	});

	//Список адресов
	fillAddrList(ADDRESS_LIST);

	$('#open-address-list').click(function(){
		$('#address-list').toggle(300);
		return false;
	});

	
	//Безнал
	$('#blk-account-jur-name').click(function(){
		$('#blk-account-jur-form').toggle(300);
		return false;
	});
	$('#blk-account-jur-form button').click(function(){
		$('#blk-account-jur-form').toggle(300);
		
		$('#blk-account-jur-name').text($('[name=jur\[name\]]').val());
		
		return false;
	});
	
	//список компаний
	fillJurList(JUR_LIST);
	$('#blk-account-jur-form-select').change(function(){
//		alert(this.value)
		var j_list=JUR_LIST[this.value];
		for(var i in j_list){
			$('#blk-account-jur-form [name=jur\['+i+'\]]').val(j_list[i]);
		}
	});

	
	//Дополнительные сервисы
	$('#additional-service .service').click(function(){
		var id=$(this).attr('href').replace(/.*#/,'');
	
		var cb=$('[name=service\['+id+'\]]');
		if(cb[0].checked){
			cb[0].checked=false;
		}else{
			cb[0].checked=true;
		}
		return false;
	});

	
});	

$('#addrbk a').live('click',function(){
var addr=$(this).attr('alt').split('|');

$('[name=street]').val(addr[0]);
$('[name=house]').val(addr[1]);
$('[name=flat]').val(addr[2]);
$('[name=porch]').val(addr[3]);
$('[name=floor]').val(addr[4]);
return false;
});