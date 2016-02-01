


<div id="map" style="width: 100%; height: 400px;"></div>

<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
ymaps.ready(init);
var myMap;
function init(ymaps){ 
	var _tmp=function(){
				// �������� ���������� ���������� � ���� ������������� ��������� GeoObjectArray
				return function (res) {
					var point = res.geoObjects.get(0);
					myMap = new ymaps.Map("map", {
		                center: point.geometry.getCoordinates(),
		                zoom: 13
		            });
				}
	}
	ymaps.geocode('������������').then(_tmp());
}
    </script>
<form>

<input type="checkbox" name="editmode" id="editmode" checked><label for="editmode">����� ��������������</label>

<hr>
<select name="delivery_zone_data">
<option value="">--������� ������ �� �����</option>
<?foreach ($delivery_zone_data_list as $row) {?>
	<option value="<?=$row['time']?>"><?=$row['time']?></option>
<?}?>
</select>


<button name="remove" type="button">������� ������</button>
<hr>
<a href="/admin/ymap/DeliveryZone">���� ��������</a>:


    <select name="delivery_zone">
    <?foreach ($delivery_zone_list as $d) {?>
    	<option value="<?=$d['id']?>" style="background:<?=$d['color']?>" color="<?=$d['color']?>"><?=$d['name']?></option>
   	<?}?>
    </select>
    
    <button name="add" type="button">�������� �� �����</button>
<hr>
<button name="save" type="button">���������</button>
<hr>
<input name="address" value="������������, ��������� 32" style="width:400px;"><button name="search" type="button">�����</button>
</form>

<script type="text/javascript">
var COLOR_LIST=<?=printJSON($data['delivery_zone_color'])?>;
</script>
<script src="/js/jquery.json.js" type="text/javascript"></script>

<script type="text/javascript" src="/modules/ymap/admin_ymap.js"></script>