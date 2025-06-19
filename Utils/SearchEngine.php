<?php


require_once("vendor/autoload.php"); // LADDA ALLA DEPENDENCIES FROM VENDOR
require_once("Models/Product.php"); // LADDA ALLA DEPENDENCIES FROM VENDOR
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

require_once("Models/Database.php"); // LADDA ALLA DEPENDENCIES FROM VENDOR
//  :: en STATIC funktion
$dotenv = Dotenv\Dotenv::createImmutable("."); // . is  current folder for the PAGE
$dotenv->load();




class SearchEngine{
    // Nr 12
    private $accessKey;
    private $secretKey;
    private $url;
    private $index_name;


    private  $client;

    function __construct(){
        $this->accessKey = $_ENV['ACCESS_KEY'];
        $this->secretKey = $_ENV['SECRET_KEY'];
        $this->url = $_ENV['SEARCH_ENGINE_URL'];
        $this->index_name = $_ENV['INDEX_KEY'];
        $this->client = new Client([
            'base_uri' => $this->url,
            'verify' => false,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->accessKey . ':' . $this->secretKey),
                'Content-Type' => 'application/json'
            ]
        ]);

    }

    function getDocumentIdOrUndefined(string $webId): ?string {
        $query = [
            'query' => [
                'term' => [
                    'webid' => $webId
                ]
            ]
        ];


        try {
            $response = $this->client->post("/api/index/v1/{$this->index_name}/_search", [
                'json' => $query
            ]);

            $data = json_decode($response->getBody(), true);

            if (empty($data['hits']['total']['value'])) {
                return null;
            }

            return $data['hits']['hits'][0]['_id'];
        } catch (RequestException $e) {
            // Hantera eventuella fel här
            echo $e->getMessage();
            return null;
        }
    }

    // Integration med tredjepartssystem: REST/JSON, Filer (XML mot Prisjakt) - språk/regelverk att förhålla sig till

    function search(string $query,string $sortCol, string $sortOrder, int $pageNo, int $pageSize){
        // "språk" mot sökmotorn
        // offset, limit, 
        // 50, 10
        // from  , size
        $q = $query . '*';
//        $aa = " and color:silver";
        $query = [
            'query' => [
                'query_string' => [
                    'query' => $q,
                ]
                ],
                'from' => ($pageNo - 1) * $pageSize,
                'size' => $pageSize,
                'sort' => [
                    $sortCol => [
                        'order' => $sortOrder
                    ]
                    ],
             'aggs' => [
                'facets'=> [
                    'nested' => [
                        'path' => 'string_facet',

                    ],
                    'aggs' => [
                        'names' => [
                            'terms' => [
                                'field' => 'string_facet.facet_name',
                                'size' => 10
                            ],
                            'aggs' => [
                                'values' => [
                                    'terms' => [
                                        'field' => 'string_facet.facet_value',
                                        'size' => 10
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];

        try {
            $response = $this->client->post("/api/index/v1/{$this->index_name}/_search", [
                'json' => $query
            ]);

            $data = json_decode($response->getBody(), true);

                             // data.hits.total.value
            if (empty($data['hits']['total']['value'])) {
                return null;
            }
            //print_r($data["aggregations"]["facets"]['names']['buckets'] );

            $data["hits"]["hits"] = $this->convertSearchEngineArrayToProduct($data["hits"]["hits"]);
            $pages = ceil($data["hits"]["total"]["value"] / $pageSize);

            return  ["data"=>$data["hits"]["hits"],
                     "num_pages"=>$pages,
                     "aggregations"=>$data["aggregations"]["facets"]['names']['buckets']
                    ];
        } catch (RequestException $e) {
            // Hantera eventuella fel här
            echo $e->getMessage();
            return null;
        }  
    }

    function getSimilarProducts(string $documentId) {
         $query = [
        "query" => [
            "more_like_this" => [
                "fields" => ["title", "description"],
                "like" => [
                    ["_id" => $documentId]
                ],
                "min_term_freq" => 1
            ]
        ],
        "size" => 3 // justera antalet liknande produkter
    ];
    try {
        $response = $this->client->post("/api/index/v1/{$this->index_name}/_search",[
            'json' => $query
        ]);
        $data = json_decode($response->getBody(), true);
        if (empty($data['hits']['total']['value'])) {
            return null;
        }
        $data["hits"]["hits"] = $this->convertSearchEngineArrayToProduct($data["hits"]["hits"]);
        return $data["hits"]["hits"];
        
    }catch(RequestException $e) {
            // Hantera eventuella fel här
            echo $e->getMessage();
            return null;
        }
    }



    function convertSearchEngineArrayToProduct($searchengineResults){
        $newarray = [];
        foreach($searchengineResults as $hit){
            // echo "MUUU";
            // var_dump($hit);
            $prod = new Product();
            $prod->id = $hit["_source"]["webid"];
            $prod->title = $hit["_source"]["title"];
            $prod->productDescription = $hit["_source"]["description"];
            $prod->imgUrl = $hit["_source"]["imgUrl"] ?? "https://via.placeholder.com/150"; // Om ingen bild finns, använd en placeholder
            $prod->price = $hit["_source"]["price"];
            $prod->categoryName = $hit["_source"]["categoryName"];
            // $prod->title . ' ' . $prod->imgUrl =$hit["_source"]["combinedsearchtext"] ?? '';

            array_push($newarray, $prod);
        }
        return $newarray;

    }



// $res = search("cov*",$accessKey,$secretKey,$url,$index_name);
// //var_dump(count($res["hits"]["hits"]));
// for($i =0 ; $i < count($res["hits"]["hits"]); $i++){
//     $hit = $res["hits"]["hits"][$i];
// //    var_dump($hit);
//     echo $hit["_id"] . ","; 
//     echo $hit["_source"]["webid"] . ","; 
//     echo $hit["_source"]["title"] . ","; 
//     echo $hit["_source"]["price"] . "</br>"; 
// }



}





// $res = getDocumentIdOrUndefined(1,$accessKey,$secretKey,$url,$index_name);
// if ($res == null){
//     die("INGET");
// }else{
// }