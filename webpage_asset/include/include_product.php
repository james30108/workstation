<?php 

$product_id     = $data['product_id'];
$product_name   = $data['product_name'];
$product_price  = number_format($data['product_price']) . $l_bath;
$product_detail = $data['product_detail'];

$product_image_cover = ($data['product_image_cover'] != '') ? $data['product_image_cover'] : "dashboard/assets/images/etc/example.png";

if (isset($data_check_login) && $system_style == 1) {
    
    $cart_url = "dashboard/process/setting_visitor_buy.php?action=insert_cart&buyer_id=$buyer_id&product_id=$product_id&product_quantity=1";

} 
else {

    $cart_url = "visitor_login.php";

}


?>
<div class="col-6 col-sm-3">
    <div class="product-item <?php echo $style_card ?>">
        <div class="product-thumb">
            <span class="bage"><?php echo $product_title ?></span>
            <img src="<?php echo $product_image_cover ?>" alt="Cover Product" class="img-responsive">
            <div class="preview-meta">
                <ul>
                    <li>
                        <span  data-bs-toggle="modal" data-bs-target="#product-modal_<?php echo $product_id ?>">
                            <i class="tf-ion-ios-search-strong"></i>
                        </span>
                    </li>
                    <li>
                        <a href="<?php echo $cart_url ?>"><i class="tf-ion-android-cart"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="product-content">
            <h4 class="text-truncate px-3"><a href="?page=product_single&product_id=<?php echo $product_id ?>"><?php echo $product_name ?></a></h4>
            <p class="price"><?php echo $product_price ?></p>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal product-modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="product-modal_<?php echo $product_id ?>">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <div class="modal-image">
                            <img src="<?php echo $product_image_cover ?>" alt="Cover Product" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="product-short-details">
                            <h2 class="product-title"><?php echo $product_name ?></h2>
                            <p class="product-price"><?php echo $product_price ?></p>
                            <p class="product-short-description"><?php echo $product_detail ?></p>
                            <a href="<?php echo $cart_url ?>" class="btn btn-main"><?php echo $l_cart_insert ?></a><br>
                            <a href="?page=product_single&product_id=<?php echo $product_id ?>" class="btn btn-transparent"><?php echo $l_detail ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>