<h1><?= $translator->getTranslation("Saisir la valorisation d'un placement") ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>

<td>
<?= $translator->getTranslation('Date') ?> <input title="aaaa-mm-jj hh:mm:ss" size="10" id="datePicker" name="date" value="<?php echo date("Y-m-d") ?>"><br/>
<?= $translator->getTranslation('Valorisation') ?> <input type="text" name="value" tabindex="-1" size="6">&nbsp;&euro;<br/>
<?= $translator->getTranslation('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= $translator->getTranslation('Valorisation') ?>">
</td>

</tr>
</table>
<br />
<input value="<?= $translator->getTranslation('Ajouter') ?>" id="submitForm" type="submit">
<input id="resetForm" name="reset" value="<?= $translator->getTranslation('Effacer') ?>" type="reset">
<input type='button' value='<?= $translator->getTranslation('Annuler') ?>' onclick='LoadRecords();' />
<div id="formResult"></div>
</form>