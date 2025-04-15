<?php
// Denna fil kommer alltid att laddas in först
// vi ska mappa urler mot Pages
// om url = "/admin" så visa admin.php
// om url = "/edit" så visa edit.php
// om url = "/" så visa index.php
require_once('Utils/Router.php');
require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(".");
$dotenv->load();


$router = new Router();
$router->addRoute('/', function () {
    require __DIR__ . '/Pages/index.php';
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
$router->addRoute('/search', function () {
    require_once(__DIR__ . '/Pages/search.php');
});

$router->dispatch();
?>