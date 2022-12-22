<?php
include '../component/component_autoload.php';

$usersHandler = new UsersHandler();
$translator = new Translator();
$t = $translator;

$categoriesHandler = new CategoriesHandler();
$usersHandler = new UsersHandler();
$activeUser = $usersHandler->GetCurrentUser();
$partnerUser = $usersHandler->GetUser($activeUser->GetPartnerId());

function t($text)
{
	global $t;
	return $t->t($text);
}



// Login is given as GET parameter / In this mode, the user id is given as a GET parameter
	$user = $usersHandler->GetUser("4768b151-bd52-11e2-8d63-5c260a87ddbb");

	if (!$user->IsNull())
	{
		if (!empty($_GET['autologinpwd']))
			$pwd = $_GET['autologinpwd'];
		else
			$pwd = 'd41d8cd98f00b204e9800998ecf8427e';

		if ($user->IsPasswordCorrect($pwd))
		{
			$usersHandler->SetSessionUser($user->get('userId'));
			$usersHandler->RecordUserConnection();

    }
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Top navbar example · Bootstrap v5.2</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/navbar-static/">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="main.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );

  $( "#datePickerInline" ).datepicker({
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	altField : '#datePickerHidden'
});

  </script>

  </head>
  <body>
    
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">GeSFi</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

  <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Confirmation</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Dépense</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Revenu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Virement</a>
        </li>
        <li class="nav-item hidden-xs">
          <a class="nav-link" href="#">Lignes</a>
        </li>
        <li class="nav-item dropdown d-none d-lg-block">
          <a class="nav-link dropdown-toggle hidden-sm hidden-md hidden-lg hidden-xl" href="#" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>
          <ul class="dropdown-menu hidden-sm hidden-md hidden-lg hidden-xl">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>

      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<!--
<div class="row p-1">
<form>
<label for="startDate">Start</label>
<input id="startDate" class="form-control" type="date" required placeholder="dd-mm-yyyy" />

<div class="row">
<input type="hidden" id="datePickerHidden" name="date" value="<?php echo date("Y-m-d") ?>"><div id="datePickerInline"></div><br>
</div>
-->
<div class="container">
  
<?php
$i = 1;
$categories = $categoriesHandler->GetOutcomeCategoriesForDuo($activeUser->get("userId"));
foreach ($categories as $category) {
	DisplayCategorieLine($category);
}

$categories = $categoriesHandler->GetOutcomeCategoriesForUser($activeUser->get("userId"));

foreach ($categories as $category) {
	if ($page == "'record_payment") 
		DisplayCategorieLine($category, "100");
	else
		DisplayCategorieLine($category, "100", true);
}

$category = new Category();
$category->set("categoryId", 'USER/'.$activeUser->get("userId"));
$category->set("category", t("(Inconnue)"));
DisplayCategorieLine($category, "100");

function DisplayCategorieLine($category, $chargeLevel="50", $isChargeLevelFieldHidden=false)
{
	global $i;

	$categoryId = $category->get('categoryId');
	$category = $category->get('category');

	?>
  <div class="row">

      <div class="col-12 col-lg-5 mt-2">
      <label for="exampleFormControlInput1" class="form-label"><?= $category ?></label>
</div>
<div class="col-9 col-lg-5">
        <input type="text" class="form-control" id="exampleFormControlInput1"  aria-label="First name">
        </div>
<div class="col-3 col-lg-2">
        <input type="text" class="form-control" aria-label="First name" value="50">
      </div>


</div>
  
	<?php
	$i++;
}
?>


</div>
 

<script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );

  $( "#datePickerInline" ).datepicker({
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	dayNamesShort: [ "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam" ],
	dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	altField : '#datePickerHidden'
});

  </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
