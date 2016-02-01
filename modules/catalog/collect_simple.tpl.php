<form id="collect" class="common">
<div class="menu">
<span>Для начинающих пользователей</span> <a href="/catalog/collect/">Для профессионалов</a>
</div>
<?=$this->getText($this->uri)?>

<input type="hidden" name="type" value="collect">
<h2>Компьютер</h2>
<div class="block">
<label class="i">Какие задачи будет выполнять
компьютер?:*</label><input class="field" name="tasks"><?=$this->fieldExample('tasks')?><br>
<div class="error" id="error-tasks"></div>
<label class="i">Желаемая стоимость:*</label><input class="field" name="price"><br>
<div class="error" id="error-price"></div>
<label class="i">Общие пожелания к компьютеру :</label><textarea class="field" name="common"></textarea><?=$this->fieldExample('common')?> <br>
<div class="error" id="error-common"></div>
</div>
<h2>Контактные данные</h2>

<div class="block">
<label class="i">Фио</label><input class="field" name="name"><br>
<div class="error" id="error-name"></div>
<label class="i">Email</label><input class="field" name="mail"><br>
<div class="error" id="error-mail"></div>
<label class="i">Телефон</label><input class="field" name="phone"><br>
<div class="error" id="error-phone"></div>
</div>

<button name="send" class="button long">Отправить</button> 
</form>
<div class="success">
<?=$this->getText('msg_collect')?>

</div>

<script type="text/javascript" src="/modules/<?=$this->mod_module_name?>/collect.js"></script>