<?php

require_once('Models/UserDatabase.php');

// Hur kan man strukturera klasser
// Hir kan man struktirera filer? Folders + subfolders
// NAMESPACES       

// LÄS IN ALLA  .env VARIABLER till $_ENV i PHP



class Database
{
    public $pdo; // PDO är PHP Data Object - en klass som finns i PHP för att kommunicera med databaser
    // I $pdo finns nu funktioner (dvs metoder!) som kan användas för att kommunicera med databasen

    private $usersDatabase;
    function getUsersDatabase()
    {
        return $this->usersDatabase;
    }


    // Note to Stefan STATIC så inte initieras varje gång

    // SKILJ PÅ CONFIGURATION OCH KOD

    function __construct()
    {
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASSWORD'];
        $port = $_ENV['PORT'];

        $dsn = "mysql:host=$host:$port;dbname=$db"; // connection string
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->initDatabase();
        $this->initData();
        $this->usersDatabase = new UserDatabase($this->pdo);
        $this->usersDatabase->setupUsers();
        $this->usersDatabase->seedUsers();
    }

    function addProductIfNotExists($title, $price, $stockLevel, $imgUrl, $categoryName)
    {
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE title = :title");
        $query->execute(['title' => $title]);
        if ($query->rowCount() == 0) {
            $this->insertProduct(title: $title, stockLevel: $stockLevel, imgUrl: $imgUrl, price: $price, categoryName: $categoryName);
        }
    }


    function initData()
    {
        $this->addProductIfNotExists(title: "Lord of the Rings", price: 300, imgUrl: "https://th.bing.com/th/id/OIP.TpPI9Xo84FEIODUn2tjjdgHaLK?rs=1&pid=ImgDetMain", stockLevel: 100, categoryName: "Books");
        $this->addProductIfNotExists(title: "Song of Achilles", price: 170, imgUrl: "https://litbooks.com.my/wp-content/uploads/2021/07/919UVPXfX9L.jpg", stockLevel: 50, categoryName: "Books");
        $this->addProductIfNotExists(title: "The Hobbit", price: 200, imgUrl: "https://th.bing.com/th/id/OIP.MkXExfs8B8Luo5Vapfko3AHaLX?rs=1&pid=ImgDetMain", stockLevel: 70, categoryName: "Books");
        $this->addProductIfNotExists(title: "The Iliad", price: 250, imgUrl: "https://th.bing.com/th/id/OIP.l1IiTu3LHwkFjhlLpM9a3gHaLQ?rs=1&pid=ImgDetMain", stockLevel: 30, categoryName: "Books");
        $this->addProductIfNotExists(title: "The Odyssey", price: 250, imgUrl: "https://m.media-amazon.com/images/I/71auePo1a8L.jpg", stockLevel: 40, categoryName: "Books");
        $this->addProductIfNotExists(title: "Percy Jackson", price: 500, imgUrl: "https://orig00.deviantart.net/decc/f/2009/154/1/0/percy_jackson_series_by_wolfdragongod.jpg", stockLevel: 20, categoryName: "Books");
        $this->addProductIfNotExists(title: "Mythos", price: 5, imgUrl: "https://th.bing.com/th/id/OIP.eQBsGwoYctz-gA6yvIVQaQAAAA?rs=1&pid=ImgDetMain", stockLevel: 180, categoryName: "Books");
        $this->addProductIfNotExists(title: "Lord of the Rings", price: 300, imgUrl: "https://th.bing.com/th/id/OIP.VM3rE1rkPsfSnlaURqf5kwHaKg?rs=1&pid=ImgDetMain", stockLevel: 70, categoryName: "Movies");
        $this->addProductIfNotExists(title: "Wicked", price: 15, imgUrl: "https://cdn.kinocheck.com/i/8mxyyhborj.jpg", stockLevel: 180, categoryName: "Movies");
        $this->addProductIfNotExists(title: "The Wizard of Oz", price: 130, imgUrl: "https://th.bing.com/th/id/OIP._Vw5JqbqkQrfYhcQPaNXAwHaK9?w=205&h=304&c=7&r=0&o=5&dpr=1.3&pid=1.7", stockLevel: 40, categoryName: "Movies");
        $this->addProductIfNotExists(title: "Into the Spiderverse", price: 120, imgUrl: "https://th.bing.com/th/id/OIP.u-03iGf1uJsiFRsKgd9_cgHaLH?rs=1&pid=ImgDetMain", stockLevel: 20, categoryName: "Movies");
        $this->addProductIfNotExists(title: "Iron Man", price: 110, imgUrl: "https://posterspy.com/wp-content/uploads/2021/03/Iron_Man-200th_Poster.jpg", stockLevel: 50, categoryName: "Movies");
        $this->addProductIfNotExists(title: "Hairspray", price: 100, imgUrl: "https://i0.wp.com/www.needcoffee.com/wp-content/uploads/2007/07/hairspray-movie-poster.jpg?w=550", stockLevel: 70, categoryName: "Movies");
        $this->addProductIfNotExists(title: "Cabaret", price: 130, imgUrl: "https://th.bing.com/th/id/OIP.ekDrOETMHeCVPNRNEtPKTwHaKe?rs=1&pid=ImgDetMain", stockLevel: 30, categoryName: "Movies");
    }

    function initDatabase()
    {
        $this->pdo->query('CREATE TABLE IF NOT EXISTS Products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50),
                price INT,
                imgUrl VARCHAR(100),
                stockLevel INT,
                categoryName VARCHAR(50)
            )');
    }

    function getProduct($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, 'Product');
        return $query->fetch();
    }

    function updateProduct($product)
    {
        $s = "UPDATE Products SET title = :title," .
            " price = :price, imgUrl = :imgUrl, stockLevel = :stockLevel, categoryName = :categoryName WHERE id = :id";
        $query = $this->pdo->prepare($s);
        $query->execute([
            'title' => $product->title,
            'price' => $product->price,
            'imgUrl' => $product->imgUrl,
            'stockLevel' => $product->stockLevel,
            'categoryName' => $product->categoryName,
            'id' => $product->id
        ]);
    }

    function deleteProduct($id)
    {
        $query = $this->pdo->prepare("DELETE FROM Products WHERE id = :id");
        $query->execute(['id' => $id]);
    }

    function insertProduct($title, $stockLevel, $imgUrl, $price, $categoryName)
    {
        $sql = "INSERT INTO Products (title, price, stockLevel, imgUrl, categoryName) VALUES (:title, :price, :stockLevel, :imgUrl, :categoryName)";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'title' => $title,
            'price' => $price,
            'stockLevel' => $stockLevel,
            'imgUrl' => $imgUrl,
            'categoryName' => $categoryName
        ]);
    }


    function searchProducts($q, $sortCol, $sortOrder)
    {
        if (!in_array($sortCol, ["title", "price"])) {
            $sortCol = "title";
        }
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "asc";
        }

        $query = $this->pdo->prepare("SELECT * FROM Products WHERE title LIKE :q or categoryName like :q ORDER BY $sortCol $sortOrder"); // Products är TABELL
        $query->execute(['q' => "%$q%"]);
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product');
    }


    //function getAllProducts($sortCol, $sortOrder){
    function getAllProducts($sortCol = "id", $sortOrder = "asc")
    {
        if (!in_array($sortCol, ["id", "categoryName", "title", "price", "imgUrl", "stockLevel"])) {
            $sortCol = "id";
        }
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "asc";
        }

        // SELECT * FROM Products ORDER BY  id asc
        $query = $this->pdo->query("SELECT * FROM Products ORDER BY $sortCol $sortOrder"); // Products är TABELL 
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product'); // Product är PHP Klass
    }

    function getCategoryProducts($catName)
    {
        if ($catName == "") {
            $query = $this->pdo->query("SELECT * FROM Products"); // Products är TABELL 
            return $query->fetchAll(PDO::FETCH_CLASS, 'Product'); // Product är PHP Klass
        }
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE categoryName = :categoryName");
        $query->execute(['categoryName' => $catName]);
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    function getAllCategories()
    {
        // SELECT DISTINCT categoryName FROM Products
        $data = $this->pdo->query('SELECT DISTINCT categoryName FROM Products')->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }

}
?>