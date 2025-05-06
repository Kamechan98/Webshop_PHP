<?php
// ONCE = en gång även om det blir cirkelreferenser
#include_once("Models/Products.php") - OK även om filen inte finns
require_once("Models/Product.php");
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once("components/NavHeader.php");
require_once("components/ProductCard.php");
require_once('Models/Cart.php');
require_once('Models/CartItem.php');
require_once("Models/Database.php");

$dbContext = new Database();

$userId = null;
$session_id = null;

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}
//$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($dbContext, $session_id, $userId);


$q = $_GET['q'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$sortOrder = $_GET['sortOrder'] ?? "";

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$pageSize = 8; // Antal produkter per sida
$offset = ($page - 1) * $pageSize;

$totalProducts = $dbContext->countSearchResults($q);
$totalPages = ceil($totalProducts / $pageSize);

$products = $dbContext->searchProducts($q, $sortCol, $sortOrder, $pageSize, $offset);

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
    <!-- Navigation-->
    <?php echo Nav($dbContext, $cart) ?>
    <!-- Header-->
    <?php echo NavHeader(); ?>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <a href="?sortCol=title&sortOrder=asc&q=<?php echo $q; ?>" class="btn btn-secondary">Title asc</a>
            <a href="?sortCol=title&sortOrder=desc&q=<?php echo $q; ?>" class="btn btn-secondary">Title desc</a>
            <a href="?sortCol=price&sortOrder=asc&q=<?php echo $q; ?>" class="btn btn-secondary">Price asc</a>
            <a href="?sortCol=price&sortOrder=desc&q=<?php echo $q; ?>" class="btn btn-secondary">Price desc</a>

            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

                <?php
                foreach ($dbContext->searchProducts($q, $sortCol, $sortOrder, $pageSize, $offset) as $prod) {
                    ProductCard($prod);
                    ?>
                <?php } ?>
            </div>
            <?php
            $totalProducts = $dbContext->countSearchResults($q);
            $totalPages = ceil($totalProducts / $pageSize);

            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a class='btn btn-secondary' href='?q=" . urlencode($q) . "&sortCol=$sortCol&sortOrder=$sortOrder&page=$i'>$i</a>";
            }
            ?>
    </section>
    <!-- Footer-->
    <?php Footer(); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>




</body>

</html>