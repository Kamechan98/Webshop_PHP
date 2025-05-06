<?php
// Inkludera nödvändiga filer
require_once("Models/Product.php");
require_once("Models/Database.php");
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once("Models/Cart.php");

$dbContext = new Database();
$userId = null;
$session_id = null;

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}
//$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($dbContext, $session_id, $userId);

if (!isset($_GET['id'])) {
    echo "Ingen produkt angiven!";
    exit;
}

$productId = $_GET['id'];
$product = $dbContext->getProductById($productId);

if (!$product) {
    echo "Produkten kunde inte hittas!";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo $product->title; ?> - Produktdetaljer</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="/css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <?php echo Nav($dbContext, $cart) ?>

    <!-- Produktdetaljer-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $product->imgUrl; ?>" alt="Produktbild" class="img-fluid" />
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bolder"><?php echo $product->title; ?></h2>
                    <p class="h4">SEK: <?php echo $product->price; ?>.00</p>
                    <p><?php echo $product->productDescription; ?></p>
                    <div class="text-start"><a class="btn btn-outline-dark mt-auto"
                            href="/addToCart?productId=<?php echo $product->id ?>&fromPage=<?php echo urlencode((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>">Add
                            to cart</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer-->
    <?php Footer(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>