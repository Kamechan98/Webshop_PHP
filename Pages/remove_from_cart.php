<?php

require_once("Models/Database.php");
require_once("Models/Cart.php");

$dbContext = new Database();

// Säkerställ att vi har ett giltigt productId
$productId = isset($_GET['productId']) ? trim($_GET['productId']) : '';
if (empty($productId)) {
    // Om inget produkt-ID, avbryt
    die("Missing productId");
}

// Default: ta bort 1
$removeCount = isset($_GET['removeCount']) ? intval($_GET['removeCount']) : 1;
$removeCount = max(1, $removeCount); // säkerställ minst 1

// Säkerställ korrekt redirect URL
$fromPage = $_GET['fromPage'] ?? '/';
$fromPage = filter_var($fromPage, FILTER_SANITIZE_URL);

$userId = null;
$session_id = session_id();

if ($dbContext->getUsersDatabase()->getAuth()->isLoggedIn()) {
    $userId = $dbContext->getUsersDatabase()->getAuth()->getUserId();
}

$cart = new Cart($dbContext, $session_id, $userId);

// Kör borttagning
$cart->removeItem($productId, $removeCount);

// Tillbaka till sidan
header("Location: $fromPage");
exit;
?>
