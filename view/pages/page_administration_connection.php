<h1><?= $translator->getTranslation('Vos dernières connections') ?></h1>
<table class="summaryTable">
<thead>
<tr>
<th>Date et heure</th>
<th>IP</th>
<th>Navigateur</th>
</tr>
</thead>
<tbody>
<?php
$usersHandler = new UsersHandler();
$user = $usersHandler->GetCurrentUser();

$result = $user->GetLastConections();
while ($row = $result->fetch())
{
  ?>
  <tr class="tableRow<?php if ($i % 2 == 0) echo '0'; else echo '1'; ?>">
  <td>
  <?php echo $row["connection_date_time"]; ?>
  </td>
  <td>
  <?php echo $row["ip_address"]; ?>
  </td>
  <td>
  <?php echo $row["browser"]; ?>
  </td>
  </tr>
  <?php
}
?>
</tbody>
</table>