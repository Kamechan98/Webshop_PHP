<?php
require_once("Models/Product.php");
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once("components/ProductCard.php");
require_once("Models/Database.php");
require_once('Models/Cart.php');
require_once('Models/CartItem.php');

$dbContext = new Database();
$userId = null;
$session_id = null;

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}
//$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($dbContext, $session_id, $userId);


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
     <?php 
    
    $googleItems = [];
    foreach($cart->getItems() as $cartitem){
        array_push($googleItems, [
            
            "quantity" => $cartitem->quantity,
            "price" =>$cartitem->productPrice  ,
            "item_id"=>$cartitem->id,
            "item_name"=>$cartitem->productName,
        ]);
    }
    
    ?>

    <script>
        gtag("event", "purchase", {
        transaction_id:Math.floor(Math.random() * 99999999),
        currency: "SEK",
        value: <?php echo $cart->getTotalPrice(); ?>,
        items: [
        <?php echo json_encode($googleItems); ?>
        ]
        });
    </script>
    <!-- Navigation-->
    <?php echo Nav($dbContext, $cart) ?>
    <!-- Section-->
    <section class="py-5">
        <section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
    <h1>Tack</h1>
   <p>Tack för ditt köp!</p>
    <p>Du kommer att få en bekräftelse via e-post inom kort.</p>

</div>
</section>
    </section>
    <!-- Footer-->
    <?php Footer(); ?>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>