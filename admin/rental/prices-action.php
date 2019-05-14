<?php
/**
 * @global $glam \Glam\GlamRentalAdmin
 */
require '../_common.php';

$updates = $_POST['update'] ?? [];
$purchases = $_POST['purchase'];
$regists = $_POST['regist'];
$installs = $_POST['install'];
$checkCycles = $_POST['check-cycle'];
// many
$terms = $_POST['term'];
$selfs = $_POST['self'];
$year1s = $_POST['year1'];
$year2s = $_POST['year2'];
$year3s = $_POST['year3'];
$year4s = $_POST['year4'];
$year5s = $_POST['year5'];
$transes = $_POST['trans'];

foreach ($updates as $id) {

	$purchase = $purchases[$id];
	$regist = $regists[$id];
	$install = $installs[$id];
	$checkCycle = $checkCycles[$id];

	$term = $terms[$id];
	$self = $selfs[$id] ?? [];
	$year1 = $year1s[$id];
	$year2 = $year2s[$id];
	$year3 = $year3s[$id];
	$year4 = $year4s[$id];
	$year5 = $year5s[$id];
	$trans = $transes[$id];

	$options = [];

	foreach ($term as $termId => $_term) {
		if (!$_term) {
			continue;
		}
		$_self = isset($self[$termId]);
		$_year1 = $year1[$termId];
		$_year2 = $year2[$termId];
		$_year3 = $year3[$termId];
		$_year4 = $year4[$termId];
		$_year5 = $year5[$termId];
		$_trans = $trans[$termId];

		$is24 = $_term >= 24;
		$is36 = $_term >= 36;
		$is48 = $_term >= 48;
		$is60 = $_term >= 60;

		if ($is48) {
			if (!$_year1 && !$_year2 && !$year3 && $_year4) {
				$_year1 = $year4;
				$_year2 = $year4;
				$_year3 = $year4;
				if ($is60 && !$_year5) {
					$_year5 = $year4;
				}
			}
		}
		if ($is24) {
			if ($_year1 && !$_year2) {
				$_year2 = $_year1;
				if($is36 && !$_year3) {
					$_year3 = $_year1;
					if ($is48 && !$_year4) {
						$_year4 = $_year1;
					}
				}
			}
		}

		if($is36){
			if ($_year2 && !$_year3) {
				$_year3 = $_year2;
			}
		}

		// todo: 36 months as minimum
		for ($year = 1, $verify = floor($_term / 12) + 1; $year < $verify; $year++) {
			if (!${'_year' . $year}) {
				continue 2;
			}
		}

		$options[] = [
			'term'  => $_term,
			'self'  => $_self,
			'year1' => $_year1,
			'year2' => $_year2,
			'year3' => $_year3,
			'year4' => $_year4,
			'year5' => $_year5,
			'trans' => $_trans
		];
	}

	$glam->setShopProduct($id, [
		'rental_prices' => \serialize([
			'purchase'   => $purchase,
			'regist'     => $regist,
			'install'    => $install,
			'checkCycle' => $checkCycle,
			'options'    => $options
		])
	]);
}

Dot\Dot::redirect($_SERVER['HTTP_REFERER']);