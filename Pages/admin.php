<?php
require_once("Models/Product.php");
require_once("components/Footer.php");
require_once("components/Nav.php");
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


// admin.php?sortCol=price&sortOrder=asc
// admin.php?sortCol=price&sortOrder=desc
// admin.php?sortCol=stockLevel&sortOrder=asc

$sortCol = $_GET['sortCol'] ?? "";

// $sortCol = $_GET['sortCol'];
// if($sortCol == null){
//     $sortCol = "";
// }
$sortOrder = $_GET['sortOrder'] ?? "";

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
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <a href="/admin/new" class="btn btn-primary">Create new</a>
            <table class="table">
                <thead>
                    <th>Name
                        <a href="/admin/products?sortCol=title&sortOrder=asc"><i
                                class="bi bi-arrow-down-circle-fill"></i></a>
                        <a href="/admin/products?sortCol=title&sortOrder=desc"><i
                                class="bi bi-arrow-up-circle-fill"></i></a>
                    </th>
                    <th>Category
                        <a href="/admin/products?sortCol=categoryName&sortOrder=asc"><i
                                class="bi bi-arrow-down-circle-fill"></i></a>
                        <a href="/admin/products?sortCol=categoryName&sortOrder=desc"><i
                                class="bi bi-arrow-up-circle-fill"></i></a>
                    </th>
                    <th>Price
                        <a href="/admin/products?sortCol=price&sortOrder=asc"><i
                                class="bi bi-arrow-down-circle-fill"></i></a>
                        <a href="/admin/products?sortCol=price&sortOrder=desc">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </a>
                    </th>
                    <th>Stock level
                        <a href="/admin/products?sortCol=stockLevel&sortOrder=asc"><i
                                class="bi bi-arrow-down-circle-fill"></i></a>
                        <a href="/admin/products?sortCol=stockLevel&sortOrder=desc">
                            <i class="bi bi-arrow-up-circle-fill"></i>
                        </a>
                    </th>
                    <th>action</th>
                </thead>

                <tbody>
                    <?php foreach ($dbContext->getAllProducts($sortCol, $sortOrder) as $prod) { ?>
                        <tr>
                            <td><?php echo $prod->title; ?></td>
                            <td><?php echo $prod->categoryName; ?></td>
                            <td><?php echo $prod->price; ?></td>
                            <td><?php echo $prod->stockLevel; ?></td>
                            <td>
                                <a href="edit?id=<?php echo $prod->id; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete?id=<?php echo $prod->id; ?>" class="btn btn-danger">Delete</a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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