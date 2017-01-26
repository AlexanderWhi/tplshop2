<? if ($this->isAdmin()) { ?>
    <!--<script type="text/javascript" src="/ui-1.11.1/jquery-ui.min.js"></script>-->
<script defer type="text/javascript" src="/modules/ceo/ceo.js"></script>
    <div id="ceo-bar" style="display:none" title="Метатеги">
        <form>
            <div><label>URL</label><input name="url" value="<?= $this->getCeo('url') ?>"/></div>
            <input type="checkbox" name="rule" value="=" <?= $this->getCeo('rule') ? 'checked' : '' ?>/>Строгое равенство
            <div><label>HEADER</label><input name="header" value="<?= $this->getCeo('header') ?>"/></div>
            <div><label>TITLE</label><input name="title" value="<?= $this->getCeo('title') ?>"/></div>
            <div><label>KEYWORDS</label><input name="keywords" value="<?= $this->getCeo('keywords') ?>"/></div>
            <div><label>DESCRIPTION</label><input name="description" value="<?= $this->getCeo('description') ?>"/></div>
        </form>
    </div>

    <div id="ceo-text-bar" style="display:none" title="Текстовки">
        <form>
            <input type="hidden" name="place">
            <div><label>URL</label><input name="url"/></div>
            <input type="checkbox" name="rule" value="="/>Строгое равенство
            <div><textarea name="text"></textarea> </div>
        </form>
    </div>
<?
}?>