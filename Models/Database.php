<?php

require_once('Models/UserDatabase.php');
require_once('Models/Cart.php');
require_once('Models/CartItem.php');


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

    function addProductIfNotExists($title, $price, $stockLevel, $imgUrl, $categoryName, $popularityFactor, $productDescription)
    {
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE title = :title");
        $query->execute(['title' => $title]);
        if ($query->rowCount() == 0) {
            $this->insertProduct($title, $price, $imgUrl, $stockLevel, $categoryName, $popularityFactor, $productDescription);
        }
    }


    function initData()
    {
        $this->addProductIfNotExists(title: "Lord of the Rings", price: 300, imgUrl: "https://th.bing.com/th/id/OIP.TpPI9Xo84FEIODUn2tjjdgHaLK?rs=1&pid=ImgDetMain", stockLevel: 100, categoryName: "Books", popularityFactor: 10, productDescription: "Följ Frodo och hans följeslagare på en episk resa genom Midgård för att förgöra den mörka härskarringens makt. Denna klassiska fantasytrilogi av J.R.R. Tolkien har trollbundit generationer med sin rika värld, djupa teman och oförglömliga karaktärer.");
        $this->addProductIfNotExists(title: "Song of Achilles", price: 170, imgUrl: "https://litbooks.com.my/wp-content/uploads/2021/07/919UVPXfX9L.jpg", stockLevel: 50, categoryName: "Books", popularityFactor: 8, productDescription: "En gripande och hjärtskärande berättelse som återberättar Iliaden ur Patroklos perspektiv, och utforskar den djupa kärleken mellan honom och den legendariske hjälten Akilles. Madeline Miller ger de grekiska myterna en mänsklig röst som går rakt in i hjärtat.");
        $this->addProductIfNotExists(title: "The Hobbit", price: 200, imgUrl: "https://th.bing.com/th/id/OIP.MkXExfs8B8Luo5Vapfko3AHaLX?rs=1&pid=ImgDetMain", stockLevel: 70, categoryName: "Books ", popularityFactor: 8, productDescription: "Innan Ringens Brödraskap började sin färd, fanns det Bilbo Baggins – en ovanlig hobbit på ett oväntat äventyr. Denna älskade berättelse blandar magi, drakar och mod på ett sätt som bara Tolkien kan.");
        $this->addProductIfNotExists(title: "The Iliad", price: 250, imgUrl: "https://th.bing.com/th/id/OIP.l1IiTu3LHwkFjhlLpM9a3gHaLQ?rs=1&pid=ImgDetMain", stockLevel: 30, categoryName: "Books", popularityFactor: 6, productDescription: "Ett av litteraturens mest kända epos, där Homeros skildrar det trojanska kriget med starka teman om ära, vrede och mänsklig tragedi. En kraftfull och poetisk resa in i hjältarnas och gudarna värld.");
        $this->addProductIfNotExists(title: "The Odyssey", price: 250, imgUrl: "https://m.media-amazon.com/images/I/71auePo1a8L.jpg", stockLevel: 40, categoryName: "Books", popularityFactor: 6, productDescription: "Odysseus färd hem efter det trojanska kriget blir en prövning som sträcker sig över årtionden. Monster, gudar och faror väntar – men också hopp, list och viljestyrka. Homeros odödliga verk är både äventyr och själsresa.");
        $this->addProductIfNotExists(title: "Percy Jackson", price: 500, imgUrl: "https://th.bing.com/th/id/OIP.hlKk62Oln29Kvt6dTmEHFAHaLO?rs=1&pid=ImgDetMain", stockLevel: 20, categoryName: "Books", popularityFactor: 5, productDescription: "Rick Riordans moderna tolkning av grekisk mytologi följer Percy, en tonåring som upptäcker att han är en halvgud. Full av action, humor och hjärta – perfekt för unga läsare och mytfantaster.");
        $this->addProductIfNotExists(title: "Mythos", price: 150, imgUrl: "https://th.bing.com/th/id/OIP.eQBsGwoYctz-gA6yvIVQaQAAAA?rs=1&pid=ImgDetMain", stockLevel: 180, categoryName: "Books", popularityFactor: 4, productDescription: "Stephen Fry återberättar de grekiska myterna med charm, humor och modern touch. En lättillgänglig och fängslande guide till gudar, hjältar och tragedier, perfekt för både nybörjare och veteraner.");
        $this->addProductIfNotExists(title: "Lord of the Rings", price: 300, imgUrl: "https://th.bing.com/th/id/OIP.VM3rE1rkPsfSnlaURqf5kwHaKg?rs=1&pid=ImgDetMain", stockLevel: 70, categoryName: "Movies", popularityFactor: 10, productDescription: "Peter Jacksons mästerliga filmatisering av Tolkiens verk, med storslagen cinematografi, ikoniska scener och oförglömliga prestationer. Ett måste för alla fantasyälskare.");
        $this->addProductIfNotExists(title: "Wicked", price: 150, imgUrl: "https://cdn.kinocheck.com/i/8mxyyhborj.jpg", stockLevel: 180, categoryName: "Movies", popularityFactor: 9, productDescription: "En musikalfilm som avslöjar den dolda sidan av Oz – sett genom den missförstådda häxan Elphabas ögon. En berättelse om vänskap, mod och vad det innebär att vara sann mot sig själv.");
        $this->addProductIfNotExists(title: "The Wizard of Oz", price: 130, imgUrl: "https://th.bing.com/th/id/OIP._Vw5JqbqkQrfYhcQPaNXAwHaK9?w=205&h=304&c=7&r=0&o=5&dpr=1.3&pid=1.7", stockLevel: 40, categoryName: "Movies", popularityFactor: 5, productDescription: "Följ Dorothy när hon färdas genom det magiska landet Oz, möter oförglömliga vänner och lär sig att det inte finns någon plats som hemma. En tidlös klassiker fylld med färg, sång och hjärta.");
        $this->addProductIfNotExists(title: "Into the Spiderverse", price: 120, imgUrl: "https://th.bing.com/th/id/OIP.u-03iGf1uJsiFRsKgd9_cgHaLH?rs=1&pid=ImgDetMain", stockLevel: 20, categoryName: "Movies", popularityFactor: 9, productDescription: "En visuell banbrytande film där flera versioner av Spider-Man möts. Med Miles Morales i centrum, blandas humor, action och stil i en hyllning till mångfald och hjältemod.");
        $this->addProductIfNotExists(title: "Iron Man", price: 110, imgUrl: "https://posterspy.com/wp-content/uploads/2021/03/Iron_Man-200th_Poster.jpg", stockLevel: 50, categoryName: "Movies", popularityFactor: 9, productDescription: "Tony Stark förändrar världen när han blir Iron Man. Denna film markerade starten på Marvels filmuniversum och levererar både karaktärsdjup och spektakulär action.");
        $this->addProductIfNotExists(title: "Hairspray", price: 100, imgUrl: "https://i0.wp.com/www.needcoffee.com/wp-content/uploads/2007/07/hairspray-movie-poster.jpg?w=550", stockLevel: 70, categoryName: "Movies", popularityFactor: 7, productDescription: "En musikalisk explosion av färg, dans och 60-talets sociala förändringar. Med charmiga karaktärer och smittande musik hyllar filmen kampen för inkludering och självacceptans.");
        $this->addProductIfNotExists(title: "Cabaret", price: 130, imgUrl: "https://th.bing.com/th/id/OIP.ekDrOETMHeCVPNRNEtPKTwHaKe?rs=1&pid=ImgDetMain", stockLevel: 30, categoryName: "Movies", popularityFactor: 4, productDescription: "Liza Minnelli briljerar i denna mörka och glamorösa musikal, som utspelar sig i 30-talets Berlin. En film som blandar politik, passion och performance på ett oförglömligt sätt.");
        $this->addProductIfNotExists(title: "Our Flag Means Death", price: 200, imgUrl: "https://th.bing.com/th/id/OIP.t3P5S8gVlfA3n7kgO0Tm9wHaK9?rs=1&pid=ImgDetMain", stockLevel: 20, categoryName: "TV Show", popularityFactor: 9, productDescription: "En udda men charmig piratkomedi som blandar historisk absurditet med oväntad hjärtevärme. Serien följer gentlemanspiraten Stede Bonnet och hans brokiga besättning i jakten på frihet och vänskap.");
        $this->addProductIfNotExists(title: "Good Omens", price: 300, imgUrl: "https://wallpapers.com/images/hd/good-omens-2019-tv-series-5tvntvsq4x5t6gwi.jpg", stockLevel: 30, categoryName: "TV Show", popularityFactor: 9, productDescription: "En ängel och en demon förenas för att stoppa apokalypsen. Med briljanta skådespelare och torr brittisk humor är Neil Gaimans och Terry Pratchetts skapelse en himmelsk underhållning.");
        $this->addProductIfNotExists(title: "Hazbin Hotel", price: 200, imgUrl: "https://th.bing.com/th/id/OIP.n_oyCAQ42EWZHKYFGZDnggHaLH?rs=1&pid=ImgDetMain", stockLevel: 30, categoryName: "TV Show", popularityFactor: 8, productDescription: "I ett färgstarkt helvete försöker Charlie, djävulens dotter, rehabilitera syndare genom ett hotell. En mörk komedi full av sång, satir och stil, med en lojal fanbas.");
        $this->addProductIfNotExists(title: "Kaos", price: 130, imgUrl: "https://th.bing.com/th/id/R.d256666167cbfc00b5859b59a09e0f1a?rik=djVLw5XWmYKu0w&riu=http%3a%2f%2fwww.impawards.com%2fintl%2fuk%2ftv%2fposters%2fkaos_ver2_xlg.jpg&ehk=UPLZXVJ0OkQMSjwmLDxTzNtv2AeLt%2fsTcSJAC8vH8EM%3d&risl=&pid=ImgRaw&r=0", stockLevel: 10, categoryName: "TV Show", popularityFactor: 5, productDescription: "En mörk och nytolkad version av den grekiska mytologin. Gudarna är trasiga, människorna lider, och framtiden är oviss. En djärv och stilistisk serie för mytälskare med smak för drama.");
        $this->addProductIfNotExists(title: "Doctor Who", price: 180, imgUrl: "https://th.bing.com/th/id/OIP.odt1KN3OC8afezRc_pbcFAHaKX?rs=1&pid=ImgDetMain", stockLevel: 40, categoryName: "TV Show", popularityFactor: 9, productDescription: "Res genom tid och rum med Doktorn – en mystisk främling i en blå låda. Denna brittiska kultklassiker blandar sci-fi, filosofi och mänskliga frågor i ett ständigt föränderligt äventyr.");
        $this->addProductIfNotExists(title: "The World According to Jeff Goldblum", price: 200, imgUrl: "https://th.bing.com/th/id/R.b6280612cb729b279675cb455830437e?rik=78q2iQUf3is9Vw&riu=http%3a%2f%2fwww.impawards.com%2ftv%2fposters%2fworld_according_to_jeff_goldblum_ver2_xlg.jpg&ehk=clOlvSHNoxVqWZqNnwvKN8h7ApTJZSk1qa1KfPN095Q%3d&risl=&pid=ImgRaw&r=0", stockLevel: 30, categoryName: "TV Show", popularityFactor: 3, productDescription: "Upptäck världen genom Jeff Goldblums nyfikna ögon. Serien tar oväntade ämnen och gör dem fascinerande – från sneakers till glass. En udda, varm och informativ resa.");
        $this->addProductIfNotExists(title: "What We Do In The Shadows", price: 300, imgUrl: "https://th.bing.com/th/id/R.632cb4f9cc771993b4cf9c77bdef6e4a?rik=G71Axe4D8aehQg&riu=http%3a%2f%2fwww.impawards.com%2ftv%2fposters%2fwhat_we_do_in_the_shadows_ver11.jpg&ehk=ARu6AFBHDFd%2ftqFv3Tf9siS1gAFkR1%2b3D12fFT3Yl2c%3d&risl=&pid=ImgRaw&r=0", stockLevel: 30, categoryName: "TV Show", popularityFactor: 5, productDescription: "Följ en grupp vampyrer som försöker leva ett normalt liv i modern tid – med katastrofala (och hysteriska) resultat. En mockumentär fylld med absurd humor och charmig vampyrkaos.");
    }

    function initDatabase()
    {
        $this->pdo->query('CREATE TABLE IF NOT EXISTS Products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50),
                price INT,
                imgUrl VARCHAR(1000),
                stockLevel INT,
                categoryName VARCHAR(50),
                popularityFactor INT,
                productDescription VARCHAR(1000)
            )');
        $this->pdo->query('CREATE TABLE IF NOT EXISTS CartItem (
                id INT AUTO_INCREMENT PRIMARY KEY,
                productId INT,
                quantity INT,
                addedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                sessionId VARCHAR(50), # b77e0a1d7b4f9286f4ddcb8c61b80403
                userId INT NULL,
                FOREIGN KEY (productId) REFERENCES Products(id) ON DELETE CASCADE
            )');
    }

    function getProductById($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM Products WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, 'Product');
        return $query->fetch();
    }

    function insertProduct($title, $price, $imgUrl, $stockLevel, $categoryName, $popularityFactor, $productDescription)
    {
        $sql = "INSERT INTO Products (title, price, stockLevel, imgUrl, categoryName, popularityFactor, productDescription) VALUES (:title, :price, :stockLevel, :imgUrl, :categoryName, :popularityFactor, :productDescription)";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'title' => $title,
            'price' => $price,
            'imgUrl' => $imgUrl,
            'stockLevel' => $stockLevel,
            'categoryName' => $categoryName,
            'popularityFactor' => $popularityFactor,
            'productDescription' => $productDescription
        ]);
    }

    function updateProduct($product)
    {
        $s = "UPDATE Products SET title = :title," .
            " price = :price, imgUrl = :imgUrl, stockLevel = :stockLevel, categoryName = :categoryName, popularityFactor=:popularityFactor, productDescription=:productDescription WHERE id = :id";
        $query = $this->pdo->prepare($s);
        $query->execute([
            'title' => $product->title,
            'price' => $product->price,
            'imgUrl' => $product->imgUrl,
            'stockLevel' => $product->stockLevel,
            'categoryName' => $product->categoryName,
            'popularityFactor' => $product->popularityFactor,
            'productDescription' => $product->productDescription,
            'id' => $product->id
        ]);
    }

    function deleteProduct($id)
    {
        $query = $this->pdo->prepare("DELETE FROM Products WHERE id = :id");
        $query->execute(['id' => $id]);
    }



    public function searchProducts($query, $sortCol = "id", $sortOrder = "asc", $limit = 8, $offset = 0)
    {
        $sortCol = in_array($sortCol, ['title', 'price', 'id']) ? $sortCol : 'id';
        $sortOrder = ($sortOrder === 'desc') ? 'desc' : 'asc';

        $sql = "SELECT * FROM Products WHERE title LIKE :query ORDER BY $sortCol $sortOrder LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }


    //function getAllProducts($sortCol, $sortOrder){
    function getAllProducts($sortCol = "id", $sortOrder = "asc")
    {
        if (!in_array($sortCol, ["id", "categoryName", "title", "price", "imgUrl", "stockLevel", "popularityFactor", "productDescription"])) {
            $sortCol = "id";
        }
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "asc";
        }

        // SELECT * FROM Products ORDER BY  id asc
        $query = $this->pdo->query("SELECT * FROM Products ORDER BY $sortCol $sortOrder"); // Products är TABELL 
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product'); // Product är PHP Klass
    }

    function getCategoryProducts($catName = "", $limit = 8, $offset = 0)
    {
        if ($catName == "") {
            $sql = "SELECT * FROM Products LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
        } else {
            $sql = "SELECT * FROM Products WHERE categoryName = :category LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue('category', $catName, PDO::PARAM_STR);
        }
        $stmt->bindValue('limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    function getAllCategories()
    {
        // SELECT DISTINCT categoryName FROM Products
        $data = $this->pdo->query('SELECT DISTINCT categoryName FROM Products')->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }

    function countCategoryProducts($catName = "")
    {
        if ($catName === "") {
            $sql = "SELECT COUNT(*) FROM Products";
            $stmt = $this->pdo->query($sql);
        } else {
            $sql = "SELECT COUNT(*) FROM Products WHERE categoryName = :category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':category' => $catName]);
        }
        return $stmt->fetchColumn();
    }

    function countSearchProducts($catName = "")
    {
        if ($catName === "") {
            $sql = "SELECT COUNT(*) FROM Products";
            $stmt = $this->pdo->query($sql);
        } else {
            $sql = "SELECT COUNT(*) FROM Products WHERE category = :category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':category' => $catName]);
        }
        return $stmt->fetchColumn();
    }

    public function countSearchResults($query)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Products WHERE title LIKE :query");
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchColumn();
    }
    function getPopularProducts()
    {
        $query = $this->pdo->query("SELECT * FROM Products ORDER BY popularityFactor DESC LIMIT 10"); // Products är TABELL 
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product'); // Product är PHP Klass
    }

    //CART FUNKTIONS
    function getCartItems($userId, $sessionId)
    {

        $query = $this->pdo->prepare("SELECT CartItem.Id as id, CartItem.productId, CartItem.quantity, 
        Products.title as productName, Products.price as productPrice, Products.price * CartItem.quantity as rowPrice     
        FROM CartItem JOIN Products ON Products.id=CartItem.productId  WHERE userId=:userId or sessionId = :sessionId");
        $query->execute(['sessionId' => $sessionId, 'userId' => $userId]);


        return $query->fetchAll(PDO::FETCH_CLASS, 'CartItem');
    }

    function convertSessionToUser($session_id, $userId, $newSessionId)
    {
        $query = $this->pdo->prepare("UPDATE CartItem SET userId=:userId, sessionId=:newSessionId WHERE sessionId = :sessionId");
        $query->execute(['sessionId' => $session_id, 'userId' => $userId, 'newSessionId' => $newSessionId]);
    }

    function updateCartItem($userId, $sessionId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            $query = $this->pdo->prepare("DELETE FROM CartItem WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
            $query->execute(['userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId]);
            return;
        }
        $query = $this->pdo->prepare("SELECT * FROM CartItem  WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
        $query->execute(['userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId]);
        if ($query->rowCount() == 0) {
            $query = $this->pdo->prepare("INSERT INTO CartItem (productId, quantity, sessionId, userId) VALUES (:productId, :quantity, :sessionId, :userId)");
            $query->execute(['userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId, 'quantity' => $quantity]);
        } else {
            $query = $this->pdo->prepare("UPDATE CartItem SET quantity = :quantity WHERE (userId=:userId or sessionId=:sessionId) AND productId = :productId");
            $query->execute(['userId' => $userId, 'sessionId' => $sessionId, 'productId' => $productId, 'quantity' => $quantity]);
        }
    }

    function getRecommendedProducts ($productId, $limit = 3)
    {
        $query = $this->pdo->prepare("Select * from products where id <> :productId order by rand() limit 3;");
        $query->bindValue(':productId', $productId, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'Product');
    }

}
?>