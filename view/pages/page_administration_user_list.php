<?php
$translator = new Translator();

$usersHandler = new UsersHandler();
$users = $usersHandler->GetAllUsers();
?>
<select name="usersList" size="<?= count($users) + 1 ?>" onChange="changeUser(this)">
<?php
if ($activeUser->get('role') == 2)
{
?>
<option value="AddUser">Ajouter un nouvel utilisateur...</option>
<?php
}

foreach ($users as $user)
{
?>
<option value="<?= $user->get('userId') ?>"><?= $user->get('userName') ?> (<?= $user->get('name') ?>)</option>
<?php
}
?>
</select> 