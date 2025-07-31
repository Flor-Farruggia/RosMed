<!DOCTYPE html>
<!--http://localhost/MVC-2024/public/home/inds-->
<html lang="es">
<head>
	<?=$head?>
	<title><?=$title?></title>
</head>
<body>

	<?=$nav?>

	<header class="pt-5 bg-light">
        <div class="container">
            <div class="row justify-content-between align-content-center h-header text-start">
                <div class="col-12 col-sm-8 col-md-7 col-lg-6">
                    <h1 class="text-404 bold text-danger mb-5">404!</h1>
                    <p class="text-xl x-medium text-danger mb-5">Oops! No pudimos encontrar la página!</p>
                </div>
                <div class="col-12 col-sm-8 col-md-7 col-lg-5">
                    <img src="public/img/404.png" class="img-fluid">
                </div>
            </div>
        </div>
    </header>

	<?=$footer?>
	<script src="../js/menu.js"></script>
</body>
</html>