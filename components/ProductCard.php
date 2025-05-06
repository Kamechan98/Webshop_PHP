<?php

function ProductCard($prod)
{
    ?>
    <div class="col mb-5">
        <div class="card h-100">
            <?php if ($prod->price < 10) { ?>
                <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
            <?php } ?>
            <!-- Product image-->
            <img class="card-img-top" src="<?php echo $prod->imgUrl; ?>" alt="..." />
            <!-- Product details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Product name-->
                    <h5 class="fw-bolder"><?php echo $prod->title; ?></h5>
                    <!-- Product price-->
                    SEK: <?php echo $prod->price; ?>.00
                </div>
            </div>
            <!-- Product actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center"><a class="btn btn-outline-dark mt-auto"
                        href="/addToCart?productId=<?php echo $prod->id ?>&fromPage=<?php echo urlencode((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>">Add
                        to cart</a></div>
                <div class="text-center"><a class="btn btn-outline-dark mt-auto"
                        href="/products?id=<?php echo $prod->id; ?>">View Details</a></div>
            </div>
        </div>
    </div>

    <?php
}
