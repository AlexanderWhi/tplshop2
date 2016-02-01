<!--<a href="?type=alone">�������� �� ����� �����</a>-->
<?if($this->isAdmin()){
	echo '<a href="/admin/contacts/addr/" class="coin-text-edit" title="�������������" style="margin-left:40px"></a>';
}?>

<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_ymap"></script>
<script type="text/javascript">

var POINTS=<?=printJSON($addrList)?>;

function fid_ymap(ymaps){
var myMap=[];
	for(var i in POINTS){
		myMap[i] = new ymaps.Map('myMap'+i, {
		    center: [55.8, 37.6],
		    zoom: 16,
	        type: "yandex#map"
		});
		myMap[i].controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
	
		var itm=POINTS[i];
		if($.trim(itm.point) && new RegExp('\d+,\d+/').test($.trim(itm.point))){
			var html='<strong>'+itm.city+', '+itm.name+'</strong>'+itm.description;
			var myPlacemark = new ymaps.Placemark(itm.point.split(','),{balloonContent: html});
			myMap[i].geoObjects.add(myPlacemark);
			myMap[i].setCenter(myPlacemark.geometry.getCoordinates());

		}else{
			
			var _tmp=function(i,itm){
				// �������� ���������� ���������� � ���� ������������� ��������� GeoObjectArray
				return function (res) {
					var point = res.geoObjects.get(0);
						
					var html='<strong>'+itm.city+', '+itm.name+'</strong>'+itm.description;
					var myPlacemark = new ymaps.Placemark(point.geometry.getCoordinates(),{balloonContent: html});
											
					myMap[i].setCenter(myPlacemark.geometry.getCoordinates());
					myMap[i].geoObjects.add(myPlacemark);;
				}
			}

			ymaps.geocode(itm.point).then(_tmp(i,itm));
			    
		}
	}
};
</script>


<?foreach ($addrList as $k=>$itm) {?>
<div itemscope itemtype="http://schema.org/Organization" style="display:none">
  <span itemprop="name"><?=$itm['name']?></span>
  ��������:
  <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
    �����:
    <span itemprop="streetAddress"><?=$itm['addr']?></span>
    <span itemprop="postalCode"> <?=$itm['code']?></span>
    <span itemprop="addressLocality"><?=$itm['city']?></span>,
  </div>
  �������:<span itemprop="telephone"><?=$itm['phone']?></span>,
  ����:<span itemprop="faxNumber"><?=$itm['fax']?></span>,
  ����������� �����: <span itemprop="email"><?=$itm['mail']?></span>
</div>
<?}?>

<div class="map_list">
<table>
<?foreach ($addrList as $k=>$itm) {?>
<tr class="item">
<td class="mapInf">
	<strong><?=$itm['name']?>:</strong><br>
	<?=$itm['description']?>
	<div class=" no-print">
	<a target="_blank" href="http://maps.yandex.ru/?text=<?=urlencode($itm['addr'])?>">�������� ����� �� ������.������</a><br><br>
	</div>
	<!--<div>
	<a target="_blank" href="http://maps.yandex.ru/print/?text=<?=urlencode($itm['addr'])?>">��������</a>
	</div>-->
</td>
<td>
<div id="myMap<?=$k?>"  class="myMap"></div>
</td>
</tr>
<?}?>
</table>

</div>