<form id="delivery-form" method="POST" action="?act=saveDelivery">
		
		
		<table class="grid" style="width:auto">
		<thead>
		<tr><th>Страна</th><th>Регион</th><th>Город</th><th>Условие</th><th>Стоимость</th><th>Наценка %</th></tr>
		</thead>
		<tbody>
		<?foreach($rs as $item){?>
		
		<tr id='item<?=$item['id']?>'>
		
		<td>
		<input id="country" class="country" name="country[<?=$item['id']?>]" value="<?=$item['country']!='other'?$item['country']:''?>" />
		</td>
		<td>
		<input id="region" class="region" name="region[<?=$item['id']?>]" value="<?=$item['region']!='other'?$item['region']:''?>" />
		</td>		
		<td>
		<input id="city" class="city" name="city[<?=$item['id']?>]" value="<?=$item['city']!='other'?$item['city']:''?>" />
		</td>
			<td>
		<input style="width:50px" name="price_condition[<?=$item['id']?>]" value="<?=$item['price_condition']?>"/>
		</td>
		<td>
		<input style="width:50px" name="price[<?=$item['id']?>]" value="<?=$item['price']?>"/>
		</td>
		<td>
		<input style="width:50px" name="margin[<?=$item['id']?>]" value="<?=$item['margin']?>"/>
		</td>
		<td>
			
			<a class="details" href="#" >Детализация доставки</a>
			
			<a class="remove"  href="#<?=$item['id']?>"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
			<div style="position:relative">
			<textarea style="width:300px;height:200px;display:none;position:absolute;top:100%;right:0;" name="details[<?=$item['id']?>]"><?=htmlspecialchars($item['details'])?></textarea>
			</div>
			</td>
		</tr>
		
		<?}?>
		</tbody>
		
		<tfoot style="display:none">
		
		<tr>
		<td>
		<input id="country" class="country" name="country_">
		</td>
		<td>
		<input id="region" class="region" name="region_">
		</td>
		<td>
		<input id="city" class="city" name="city_"></td>
		<td>
		<input style="width:50px" name="price_condition_">
		</td>
		<td>
		<input style="width:50px" name="price_">
		</td>
		<td>
		<input style="width:50px" name="margin_">
		</td>
		<td>
			
			<a class="details" href="#" >Детализация доставки</a>
			
			<a class="remove"  href="#"><img src="/img/pic/trash_16.gif" title="Удалить запись" border="0" alt=""/></a>
			<div style="position:relative">
			<textarea style="width:300px;height:200px;display:none;position:absolute;top:100%;right:0;" name="details_"></textarea>
			</div>
			</td>
		</tr>
		</tr>
		
		</tfoot>
		
		
	
		</table>
		<button type="button" name="add">Добавить</button><button type="submit" name="save">Применить</button>
		
		</form>
		
		
		<script type="text/javascript" src="/autocomplete/jquery.autocomplete.js"></script>		
		<script type="text/javascript">
		
		$('#delivery-form').submit(function(){
			$('#preloader').show();
			$.post(this.action,$(this).serialize(),function(res){
				$('#preloader').hide();
				util.msg(res.msg);
			},'json');
			return false;
		});
		
		function init(el){
//			$(".city",el).autocomplete("/autocomplete/autocomplete_city.php",{scroll:false,delay:400, max:10, minChars:2});
//		  	$(".country",el).autocomplete("/autocomplete/autocomplete_country.php",{scroll:false,delay:400, max:10, minChars:2});
//		  	$(".region",el).autocomplete("/autocomplete/autocomplete_region.php",{scroll:false,delay:400, max:10, minChars:2});
			
			
			$('.details',el).click(function(){
				var parent=$(this).parents('td').find('textarea').toggle();				
				return false;
			});
			
			$('.remove',el).click(function(){
				if(confirm('Вы действительно хотите удалить запись?')){
					$(this).parents('tr').remove();
				}
				return false;
			});
		}
		
		$(function(){
			init($('table tbody'));
			$('[name=add]').click(function(){
				var data=$('tfoot tr').clone();
				$('[name]',data).each(function(){
					this.name=this.name.replace(/_$/,'[]');
				});
				init(data);
				$('table tbody').append(data);
				return false;
			});
		});
		</script>