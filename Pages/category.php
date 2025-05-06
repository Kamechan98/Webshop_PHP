<?php
require_once("Models/Product.php");
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once("components/NavHeader.php");
require_once("components/ProductCard.php");
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

$catName = $_GET['catname'] ?? "";

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$pageSize = 8;
$offset = ($page - 1) * $pageSize;

$header = $catName;
if ($catName == "") {
    $header = "All Products";
}
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
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                foreach ($dbContext->getCategoryProducts($catName, $pageSize, $offset) as $prod) {
                    ProductCard($prod);
                    ?>
                <?php } ?>

            </div>
            <?php
            $totalProducts = $dbContext->countCategoryProducts($catName);
            $totalPages = ceil($totalProducts / $pageSize);
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<a class='btn btn-secondary' href='?catname=$catName&page=$i'>$i</a>";
            }
            ?>
        </div>

    </section>
    <!-- Footer-->
    <?php Footer(); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>