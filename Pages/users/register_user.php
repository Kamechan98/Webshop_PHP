<?php
require_once('Models/Product.php');
require_once("components/Footer.php");
require_once("components/Nav.php");
require_once('Models/Database.php');
require_once('Models/Cart.php');

$dbContext = new Database();

$userId = null;
$session_id = null;

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}
//$cart = $dbContext->getCartByUser($userId);
$session_id = session_id();

$cart = new Cart($dbContext, $session_id, $userId);




$errorMessage = "";
$username = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username);
        header('Location: /user/registerThanks');
        exit;
    } catch (\Delight\Auth\InvalidEmailException $e) {
        $errorMessage = "Ej korrekt email";
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        $errorMessage = "Invalid password";
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
        $errorMessage = "Finns redan";
    } catch (\Exception $e) {
        $errorMessage = "Ngt gick fel";
    }
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
            <h1>Register</h1>
            <?php
            if ($errorMessage != "") {
                echo "<div class='alert alert-danger' role='alert'>" . $errorMessage . "</div>";
            }
            ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Email</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $username ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" value="">
                </div>
                <div class="form-group">
                    <label for="password">Password igen</label>
                    <input type="password" class="form-control" name="password2" value="">
                </div>
                <input type="submit" class="btn btn-primary" value="Register">
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