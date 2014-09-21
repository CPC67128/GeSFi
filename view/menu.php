<?php
include '../component/component_autoload.php';
include '../component/component_security.php';

$translator = new Translator();
$accountsHandler = new AccountsHandler();

if (!isset($_POST))
	exit();

$page = isset($_POST['page']) ? $_POST['page'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$data = isset($_POST['data']) ? $_POST['data'] : '';

function AddMenuTopItem($isLinkVisible, $text, $page, $area, $id, $data, $addMenuItemSeparator)
{
	if ($isLinkVisible)
	{
		?><a href="#" onclick="javascript:ChangeContext('<?= $page ?>','<?= $area ?>','<?= $id ?>','<?= $data ?>'); return false;"><?php
	}
	echo $text;
	if ($isLinkVisible)
	{
		?></a><?php
	}

	if ($addMenuItemSeparator)
	{
		?> / <?php
	}
}

function AddMenuLeftItem($page, $imagePath, $text)
{
	if ($imagePath != '')
	{
		?><img id="<?= $page ?>" class="menuIcon" src="../media/<?= $imagePath ?>"/><br /><?php
	}
	?><?= $text ?><br /><br /><?php
}

