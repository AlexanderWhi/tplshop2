<?include('function.tpl.php')?>
<?if($rs){?>
	<div class="noprint" style="display:none">
		<a href="#" class="expand-all"><img src="/img/pic/add_16.gif" title="�������� ���������� ���"/></a>
	</div>
	<?$i=0;foreach ($rs as $item){?>
		<div class="<?=++$i!=count($rs)?'pagebreak':''?>" style="width:600px">
		
		<div class="noprint"  style="display:none">
		<a href="#" class="expand"><img src="/img/pic/add_16.gif" title="�������� ����������"/></a>
		<a href="#" class="up"><img src="/img/pic/up_16.gif" title="�� ���� ������� ����"/></a>
		<a href="#" class="down"><img src="/img/pic/down_16.gif" title="�� ���� ������� ����"/></a>
		<a href="#" class="remove"><img src="/img/pic/trash_16.gif" title="�������" ></a>		
		</div>
		
		<h1>����� �<?=$item['id']?></h1>
		<div class="order">
		<?=$item['orderContent']?>
		
		<h2>��������</h2>
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">���</th><td><?=$item['fullname']?> <?=$item['name']?></td></tr>
		<tr><th>�����</th><td><?=parsAddr($item['address']);?></td></tr>
		<tr><th>�������</th><td><?=$item['phone']?></td></tr>
		<tr><th>E-Mail</th><td><?=$item['mail']?></td></tr>
		<tr><th>�������������</th><td><?=$item['additionally']?></td></tr>
		</table>
		
		<?/*h2>����������</h2>
		<table style="width:100%" class="grid">
		<tr><th style="width:200px">���</th><td><?=$item['fullname']?></td></tr>
		<tr><th>�����</th><td><?=$item['country']?>, <?=$item['region']?>, <?=$item['city']?>, <?=$item['address']?> </td></tr>
		<tr><th>�������</th><td><?=$item['phone']?></td></tr>
		<tr><th>�������������</th><td><?=$item['additionally']?></td></tr>
		</table*/?>
		
		<h2>�������� ������</h2>
		<table style="width:100%" class="grid">			
		<tr><th style="width:200px">���� ��������</th><td><?=dte($item['date'])?></td></tr>
		<tr><th>����� ��������</th><td><?=$item['time']?></td></tr>
		<tr><th>������ <?=$item['pay_system']?></th><td><?=$item['pay_status']?$item['pay_status']:'�� ��������'?></td></tr>

		</table>
		<br /><br /><br /><br />
		<table >
		<tr>
		<td>
		��������________________________
		</td>
		<td style="padding-left:50px">
		�����������________________________
		</td>
		</tr>
		</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
</div>
<hr />

		</div>
		<?
	}
}else{?>
������ �� �������
<?}?>
<script type="text/javascript" src="/modules/shop/admin_order_print.js"></script>