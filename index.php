<?php
// Denna fil kommer alltid att laddas in först
// vi ska mappa urler mot Pages
// om url = "/admin" så visa admin.php
// om url = "/edit" så visa edit.php
// om url = "/" så visa index.php
error_reporting(E_ALL & ~E_DEPRECATED); // Enable all error reporting & 
ob_start(); //Start output buffering
session_start(); //Start session
require_once('Utils/Router.php');
require_once('vendor/autoload.php');



$dotenv = Dotenv\Dotenv::createImmutable(".");
$dotenv->load();


$router = new Router();
$router->addRoute('/', function () {
    require __DIR__ . '/Pages/index.php';
});
$router->addRoute('/products', function () {
    require __DIR__ . '/Pages/product_details.php';
});
$router->addRoute('/category', function () {
    require __DIR__ . '/Pages/category.php';
});
$router->addRoute('/admin/products', function () {
    require __DIR__ . '/Pages/admin.php';
});
$router->addRoute('/admin/edit', function () {
    require __DIR__ . '/Pages/edit.php';
});
$router->addRoute('/admin/new', function () {
    require __DIR__ . '/Pages/new.php';
});
$router->addRoute('/admin/delete', function () {
    require __DIR__ . '/Pages/delete.php';
});
$router->addRoute('/user/login', function () {
    require_once(__DIR__ . '/Pages/users/login.php');
});
$router->addRoute('/user/logout', function () {
    require_once(__DIR__ . '/Pages/users/logout.php');
});
$router->addRoute('/user/register', function () {
    require_once(__DIR__ . '/Pages/users/register_user.php');
});
$router->addRoute('/user/registerThanks', function () {
    require_once(__DIR__ . '/Pages/users/register_thanks.php');
});
$router->addRoute('/addToCart', function () {
    require_once(__DIR__ . '/Pages/add_to_cart.php');
});
$router->addRoute('/removeFromCart', function () {
    require_once(__DIR__ . '/Pages/remove_from_cart.php');
});
$router->addRoute('/cart', function () {
    require_once(__DIR__ . '/Pages/show_cart.php');
});
$router->addRoute('/search', function () {
    require_once(__DIR__ . '/Pages/search.php');
});

$router->addRoute('/checkout', function () { // Betyder ta bort EN 
        require_once( __DIR__ .'/Pages/checkout.php');
});

$router->addRoute('/checkoutsuccess', function () { // Betyder ta bort EN 
        require_once( __DIR__ .'/Pages/checkoutsuccess.php');
});

$router->dispatch();
