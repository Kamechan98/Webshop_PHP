<?php
require_once('Models/Product.php');
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once('Models/Database.php');

$dbContext = new Database();
// Hämta den produkt med detta ID

$userId = null;
$session_id = null;

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}
//$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($dbContext, $session_id, $userId);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Här kommer vi när man har tryckt  på SUBMIT
    $title = $_POST['title'];
    $price = $_POST['price'];
    $stockLevel = $_POST['stockLevel'];
    $imgUrl = $_POST['imgUrl'];
    $categoryName = $_POST['categoryName'];
    $popularityFactor = $_POST['popularityFactor'];
    $productDescription = $_POST['productDescription'];
    $dbContext->insertProduct($title, $price, $imgUrl, $stockLevel, $categoryName, $popularityFactor, $productDescription);
    header("Location: /admin/products");
    exit;
} else {
    // Det är INTE ett formulär som har postats - utan man har klickat in på länk tex edit.php?id=12
}

//Kunna lagra i databas


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Homepage - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/styles.css" rel="stylesheet" />
</head>

<body>
    <?php echo Nav($dbContext, $cart) ?>
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">

            <form method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" value="">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" name="price" value="">
                </div>
                <div class="form-group">
                    <label for="imgUrl">ImgUrl</label>
                    <input type="text" class="form-control" name="imgUrl" value="">
                </div>
                <div class="form-group">
                    <label for="stockLevel">Stock</label>
                    <input type="text" class="form-control" name="stockLevel" value="">
                </div>
                <div class="form-group">
                    <label for="categoryName">Category name:</label>
                    <input type="text" class="form-control" name="categoryName" value="">
                </div>
                <div class="form-group">
                    <label for="popularityFactor">Popularity factor</label>
                    <input type="number" class="form-control" name="popularityFactor" value="">
                </div>
                <div class="form-group">
                    <label for="productDescription">Product description</label>
                    <input type="text" class="form-control" name="productDescription" value="">
                </div>
                <input type="submit" class="btn btn-primary" value="Skapa">
            </form>
        </div>
    </section>



    <?php Footer(); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>

</body>

</html>