<?php


/**
 * @global $glam \Glam\GlamShopAdmin
 */

use Glam\NaverShopProducts;

require '../common.php';

$file = './ep_all.txt';

if(file_exists($file)){
    $fileUpdated = filemtime($file);

    $dbUpdated = $glam->db->select($glam->_tableShopProducts , 'it_update_time')
        ->order('it_update_time', 'desc')
        ->limit(1)
        ->result()
        ->fetchAssoc();

    $dbUpdated = mktime($dbUpdated['it_update_time']);

    if($dbUpdated > $fileUpdated){
        $update = true;
    }
}else{
    $update = true;
}

if($update || true) {

    $ns = new NaverShopProducts;

    $products = $glam->getShopProducts();

    $scheme = ($_SERVER['REQUEST_SCHEME'] ?? 'http'). '://';
    $server = $scheme . $_SERVER['SERVER_NAME'] . \rtrim(\GNU_URL, '/');

    $naverShopping = $glam->getOption('naverShopping');
    $rentalPrefix = &$naverShopping['rentalPrefix'];
    $rental = &$naverShopping['rental'];
    $rentalCategories = &$naverShopping['rentalCategories'];
    $purchasePrefix = &$naverShopping['purchasePrefix'];
    $purchase = &$naverShopping['purchase'];

    foreach ($products as $product) {
        $options = $product['prices']['options'];

        $price = $product['it_price'];
        $cate1 = $product['category1']['ca_name'] ?? '';
        $caid1 = $cate1 ? $product['category1']['ca_id'] : '';

        if ($price) {
            $name = $purchase;
            if ($purchasePrefix) {
                $name = $purchasePrefix . ' ' . $name;
            }
        } else {
            $name = isset($rentalCategories[$caid1]) && $rentalCategories[$caid1] ?
                $rentalCategories[$caid1] :
                $rental;

            if ($rentalPrefix) {
                $name = $rentalPrefix . ' ' . $name;
            }
        }

        $name = str_replace(
            [
                '$name',
                '$model',
                '$category',
            ],
            [
                $product['it_name'],
                $product['it_model'],
                $cate1
            ],
            $name
        );

        if ($price) {

        } elseif ($options) {
            $name = trim($name);

            foreach ($options as $key => $option) {
                $optionId = $key + 1;
                $optionName = str_replace('$term', $option['term'], $name);
                $ns->add([
                    'product_flag' => '렌탈',
                    'id' => $product['it_id'] . 'X' . $optionId,
                    'title' => $optionName,
                    'price_pc' => $option['year1'],
                    'link' => $server . $product['detailUrl'] . '#' . $optionId,
                    'image_link' => $product['cdn_images']['jpg'][0],
                    'category_name1' => $cate1,
                    'category_name2' => $product['category2']['ca_name'] ?? '',
                    'category_name3' => $product['category3']['ca_name'] ?? '',
//					'caid1' => $caid1,
//					'caid2' => $product['category2']['ca_id'] ?? '',
//					'caid3' => $product['category3']['ca_id'] ?? '',
                    'model_number' => $product['it_model'],

                    // todo: change it with admin
                    'brand' => '쿠쿠',

                    'shipping' => 0
                ]);
            }
        }
    }

    $ns->createFile($file);

}

header('Content-Type: text/plain; charset=utf-8');
require $file;