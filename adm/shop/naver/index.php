<?php
/**
 * @global $glam \Glam\GlamShopAdmin
 */
require '../../_common.php';

require '../../head.php';

$naver = $glam->getOption('naverShopping', [
	'rentalPrefix'     => '',
	'rental'           => '$name',
	'rentalCategories' => [],
	'purchasePrefix'   => '',
	'purchase'         => '$name'
]);

$rentalPrefix = &$naver['rentalPrefix'];
$rental = &$naver['rental'];
$rentalCategories = &$naver['rentalCategories'];
$purchasePrefix = &$naver['purchasePrefix'];
$purchase = &$naver['purchase'];

$categories = $glam->getShopCategories();
$byId = &$categories['byId'];

$fileUrl = \GLAM_URL . 'shop/ep_all.txt';

?>
<?= h2('상품 정보 연동 주소') ?>
    <input type="text" value="<?= $fileUrl ?>" readonly class="frm_input frm_input_full">
    <p>
        접속 주소에 따른 도메인 변경에 주의 하세요.
    </p>

<?= formOpen('./action.php') ?>
<?= formTable(
	'이름 형식',
	[
		['rentalPrefix', '렌탈 상품 말머리'],
		['rental', '렌탈 상품 이름', $rental, ['required' => true]],
		['purchasePrefix', '구매 상품 말머리'],
		['purchase', '구매 상품 이름', $purchase, ['required' => true]],
	]
) ?>

<?= h2('분류별 이름 형식') ?>
<?= tableOpen() ?>
<?php foreach ($categories['byId'] as $category) {
	$id = $category['ca_id'];
	?>
	<?= trInput('rentalCategories[' . $id . ']', $category['ca_name'], $rentalCategories[$id] ?? '', [
	        'placeholder' => $rental
    ]) ?>
<?php } //endforeach?>
<?= tableClose ?>

<?= buttonsOpen ?>
<?= buttonSubmit() ?>
<?= buttonsClose ?>
<?= formClose ?>

<?php

require '../../tail.php';