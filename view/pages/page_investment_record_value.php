<h1><?= t("Saisir la valorisation d'un placement") ?></h1>

<form id="form" action="/">
<table class="actionsTable">
<tr>

<td>
<?= t('Date') ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
</td>

<td>
<?= t('Valorisation') ?> <input type="text" name="value" tabindex="-1" size="6">&nbsp;&euro;<br>
<?= t('Désignation') ?> <input type="text" name="designation" id="designation" size="30" value="<?= t('Valorisation') ?>">
<?php AddFormButton(); ?>
</td>

</tr>
</table>
</form>