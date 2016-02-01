<?if($this->isAdmin()){
	echo '<a href="/admin/contacts/addr/" class="coin-text-edit" title="�������������"></a><br><br>';
}?>

<div id="myMap" style="width: 100%; height: 400px"></div>

<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?coordorder=longlat&load=package.full&wizard=constructor&lang=ru-RU&onload=fid_ymap"></script>
<script type="text/javascript">

var POINTS=<?=printJSON($addrList)?>;
var myMap=null;
var myPlacemarkArr=[];

function fid_ymap(ymaps){
	var myCollection = new ymaps.GeoObjectCollection ({},{});

	myMap = new ymaps.Map('myMap', {
		    center: [55.8, 37.6],
		    zoom: 8,
	        type: "yandex#map"
	});
	
	myMap.geoObjects.add(myCollection);
	myMap.controls.add("zoomControl").add("mapTools").add(new ymaps.control.TypeSelector(["yandex#map", "yandex#satellite", "yandex#hybrid", "yandex#publicMap"]));
	var n=0;
	for(var i in POINTS){
		var itm=POINTS[i];
		if($.trim(itm.point)){
			var myPlacemark = new ymaps.Placemark(itm.point.split(',').reverse(),{balloonContent: POINTS[i]});
			myPlacemarkArr[n++]=myPlacemark;
			myCollection.add(myPlacemark);
			myMap.setBounds(myCollection.getBounds());
			
			
		}else{
			
			var _tmp=function(i,itm){
				// �������� ���������� ���������� � ���� ������������� ��������� GeoObjectArray
					return function (res) {
						var point = res.geoObjects.get(0);
						
						var html='<strong>'+itm.name+'</strong>'+itm.description;
						var myPlacemark = new ymaps.Placemark(point.geometry.getCoordinates(),{balloonContent: html});
//						alert(n)
						myPlacemarkArr[i]=myPlacemark;
						
						myCollection.add(myPlacemark);
//						myCollection.add (point);

						myMap.setBounds(myCollection.getBounds());
					}
			}
			
			
			ymaps.geocode(itm.addr).then(_tmp(i,itm));
			    
		}
//		n++;
	}
	
	
	
// ������������� ����� ����� � ������� ���, ����� �������� ��������� �������.
//	myMap.setBounds(myCollection.getBounds());
	
	
};
</script>

<div class="map_list">
<table>
<tr>
<td>
<?foreach ($addrList as $k=>$itm) {
	if(!($k%2)){continue;}
	?>
	<div class="item">
	<a href="#" class="header"><?=$itm['name']?></a>
	<div class="hidden">
	<a class="m" link="<?=$k?>" href="?<?=htmlspecialchars($itm['addr'])?>" rel="<?=htmlspecialchars($itm['addr'])?>"></a>
	<?=$itm['description']?>
	<a class="n" link="<?=$k?>"   href="?<?=htmlspecialchars($itm['addr'])?>" rel="<?=htmlspecialchars($itm['addr'])?>">�������� �� �����</a>
	</div>
	</div>
<?}?>
</td>
<td>
<?foreach ($addrList as $k=>$itm) {
	if($k%2){continue;}
	?>
	<div class="item">
	<a href="#" class="header"><?=$itm['name']?></a>
	<div class="hidden">
	<a class="m" link="<?=$k?>"  href="?<?=htmlspecialchars($itm['addr'])?>" rel="<?=htmlspecialchars($itm['addr'])?>"></a>
	<?=$itm['description']?>
	<a class="n" link="<?=$k?>"  href="?<?=htmlspecialchars($itm['addr'])?>" rel="<?=htmlspecialchars($itm['addr'])?>">�������� �� �����</a>
	</div>
	</div>
	
<?}?>
</td>
</table>



</div>
<script type="text/javascript">

$(function(){
	
	
	$('a[link]').click(function(){
//		alert($(this).attr('link'));
		try{
//			myPlacemarkArr[$(this).attr('link')].options({state:'active'});
			myPlacemarkArr[$(this).attr('link')].balloon.open();
		}catch(e){
//			alert('����� �� �������������');
		}
		
		document.location.href='#';
		return false;
	});	


	$('.map_list a.header').click(function(){
		$(this).next().toggle();
		
		return false;
	});

	/*$('.map_list a.m').click(function(){
		var addr=$(this).attr('rel').replace(/.*\?/,'');
//		alert(addr)
		ymaps.geocode(addr).then(
				// �������� ���������� ���������� � ���� ������������� ��������� GeoObjectArray
					function (res) {
						var point = res.geoObjects.get(0);
						if(myMap){
							
							document.location.href='#';
							myMap.panTo(point.geometry.getCoordinates(), {
                                flying: true,
                                duration: 500
                            });
						}
					});
		return false;
	});*/
	
});

</script>