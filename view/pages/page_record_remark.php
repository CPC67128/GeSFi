<h1><?= t("Ecrire une remarque") ?></h1>

<form action="/" id="form">
<table class="actionsTable">
<tr>

<td>
<input type="hidden" name="actor" value="1" />
<?= t("Date") ?> <input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br><br>
<?= t("Remarque") ?> <input type="text" name="designation" size="30">
<?php AddFormButton(); ?>
</td>

</tr>
</table>
</form>