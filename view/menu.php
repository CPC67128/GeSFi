<?php
include '../component/component_autoload.php';
include '../component/component_security.php';

$translator = new Translator();
$accountsHandler = new AccountsHandler();
$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();

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
	else
	{
		?><u><?php
	}
	echo $text;
	if ($isLinkVisible)
	{
		?></a><?php
	}
	else
	{
		?></u><?php
	}

	if ($addMenuItemSeparator)
	{
		?> / <?php
	}
}

$menus = array(
		'investment_record_dashboard' => array(
				'image' => 'freeiconmaker_10.png',
				'image_original' => 'assetManagementMenuIcon.png',
				'text' => 'Situation'
		),
		'investment_record_dashboard_statistics' => array(
				'image' => 'freeiconmaker_15.png',
				'image_original' => 'statsMenuIcon.png',
				'text' => 'Graphiques'
		),
		'investment_record' => array(
				'image' => 'freeiconmaker_1.png',
				'image_original' => 'recordsMenuIcon.png',
				'text' => 'Lignes'
		),
		'investment_record_value' => array(
				'image' => 'freeiconmaker_5.png',
				'image_original' => 'valueMenuIcon.gif',
				'text' => 'Valorisation'
		),
		'investment_record_credit' => array(
				'image' => 'freeiconmaker_3.png',
				'image_original' => 'depositMenuIcon.gif',
				'text' => 'Dépôt'
		),
		'investment_record_withdrawal' => array(
				'image' => 'freeiconmaker_4.png',
				'image_original' => 'paymentMenuIcon.png',
				'text' => 'Retrait'
		),
		'investment_record_income' => array(
				'image' => 'freeiconmaker_2.png',
				'image_original' => 'paymentMenuIcon.png',
				'text' => 'Revenu'
		),
		'investment_record_remark' => array(
				'image' => 'freeiconmaker_9.png',
				'image_original' => 'remarkMenuIcon.png',
				'text' => 'Remarque'
		),
		'investment_record_statistics' => array(
				'image' => 'freeiconmaker_15.png',
				'image_original' => 'statsMenuIcon.png',
				'text' => 'Courbes'
		),

		'record' => array(
				'image' => 'freeiconmaker_1.png',
				'image_original' => 'recordsMenuIcon.png',
				'text' => 'Lignes'
		),
		'record_payment' => array(
				'image' => 'freeiconmaker_26.png',
				'image_original' => 'paymentMenuIcon.png',
				'text' => 'Dépense'
		),
		'record_income' => array(
				'image' => 'freeiconmaker_21.png',
				'image_original' => 'depositMenuIcon.gif',
				'text' => 'Revenu'
		),
		'record_transfer' => array(
				'image' => 'freeiconmaker_33.png',
				'image_original' => 'transferMenuIcon.png',
				'text' => 'Virement'
		),
		'record_remark' => array(
				'image' => 'freeiconmaker_9.png',
				'image_original' => 'remarkMenuIcon.png',
				'text' => 'Remarque'
		),
		'record_balance' => array(
				'image' => 'freeiconmaker_27.png',
				'image_original' => 'balanceMenuIcon.png',
				'text' => 'Balance'
		),
		'statistics' => array(
				'image' => 'freeiconmaker_10.png',
				'image_original' => 'statsMenuIcon.png',
				'text' => 'Statistiques'
		),

		'administration_connection' => array(
				'image' => 'freeiconmaker_55.png',
				'image_original' => 'connectionMenuIcon.jpg',
				'text' => 'Connections'
		),
		'administration_accounts' => array(
				'image' => 'freeiconmaker_36.png',
				'image_original' => 'accountsMenuIcon.png',
				'text' => 'Comptes'
		),
		'administration_category' => array(
				'image' => 'freeiconmaker_47.png',
				'image_original' => 'categoriesMenuIcon.jpg',
				'text' => 'Catégories'
		),
		'administration_designation' => array(
				'image' => 'freeiconmaker_6.png',
				'image_original' => 'designationMenuIcon.png',
				'text' => 'Désignation'
		),
		'administration_user' => array(
				'image' => 'freeiconmaker_52.png',
				'image_original' => 'userMenuIcon.png',
				'text' => 'Utilisateur'
		)
);

function AddMenuLeftItem($pageName)
{
	global $translator, $menus, $page;

	$imagePath = 'menuIcons/'.$menus[$pageName]['image'];
	$text = $menus[$pageName]['text'];

	if ($imagePath != '')
	{
		?><img id="<?= $pageName ?>" class="menuIcon" src="../media/<?= $imagePath ?>"/><br /><?php
	}
	?><?= $page == $pageName ? '<u>' : '' ?><?= $translator->getTranslation($text) ?><?= $page == $pageName ? '</u>' : '' ?><br /><br /><?php
}
