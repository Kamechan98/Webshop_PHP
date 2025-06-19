<?php
require_once('Models/Product.php');
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once('Models/Cart.php');
require_once('Models/Database.php');

$id = $_GET['id'];
$dbContext = new Database();
// Hämta den produkt med detta ID
$product = $dbContext->getProductById($id); // TODO felhantering om inget produkt
$userId = null;
$session_id = session_id();
$cart = new Cart($dbContext, $session_id, $userId);


// $product->title = "";
// $product->stockLevel = "";
// $product->price = "";
// $product->imgUrl = "";
// $product->categoryName = "";
// $product->popularityFactor = "";
// $product->productDescription = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Här kommer vi när man har tryckt  på SUBMIT
    $product->title = $_POST['title'];
    $product->stockLevel = $_POST['stockLevel'];
    $product->price = $_POST['price'];
    $product->imgUrl = $_POST['imgUrl'];
    $product->categoryName = $_POST['categoryName'];
    $product->popularityFactor = $_POST['popularityFactor'];
    $product->productDescription = $_POST['productDescription'];
    $dbContext->updateProduct($product);
    echo "<h1>Produkten har uppdaterats</h1>";
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-33MXX941B5"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-33MXX941B5', {
        debug_mode: true
    });
    </script>
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
                    <input type="text" class="form-control" name="title" value="<?php echo $product->title ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" name="price" value="<?php echo $product->price ?>">
                </div>
                <div class="form-group">
                    <label for="imgUrl">ImgUrl</label>
                    <input type="text" class="form-control" name="imgUrl" value=<?php echo $product->imgUrl ?>>
                </div>
                <div class="form-group">
                    <label for="stockLevel">Stock</label>
                    <input type="text" class="form-control" name="stockLevel"
                        value="<?php echo $product->stockLevel ?>">
                </div>
                <div class="form-group">
                    <label for="categoryName">Category name:</label>
                    <input type="text" class="form-control" name="categoryName"
                        value="<?php echo $product->categoryName ?>">
                </div>
                <div class="form-group">
                    <label for="popularityFactor">Popularity factor</label>
                    <input type="number" class="form-control" name="popularityFactor"
                        value="<?php echo $product->popularityFactor ?>">
                </div>
                <div class="form-group">
                    <label for="productDescription">Product description:</label>
                    <input type="text" class="form-control" name="productDescription"
                        value="<?php echo $product->productDescription ?>">
                </div>
                <input type="submit" class="btn btn-primary" value="Uppdatera">
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