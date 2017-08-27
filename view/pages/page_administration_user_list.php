<?php
$translator = new Translator();

$usersHandler = new UsersHandler();
$users = $usersHandler->GetAllUsers();
?>
<select name="usersList" size="<?= count($users) + 1 ?>" onChange="changeUser(this)">
<?php foreach ($users as $user) { ?>
<option value="<?= $user->get('userId') ?>"><?= $user->get('name') ?></option>
<?php } ?>
</select>