<?php
$company = &$default['de_admin_company_name'];
$post = &$default['de_admin_company_zip'];
$address = &$default['de_admin_company_addr'];
$owner = &$default['de_admin_company_owner'];
$phone = &$default['de_admin_company_tel'];
$fax = &$default['de_admin_company_fax'];
$crn = &$default['de_admin_company_saupja_no'];
$crnCheck = null;
$osdn = &$default['de_admin_tongsin_no'];
$subOsdn = &$default['de_admin_buga_no'];
$infoManager = &$default['de_admin_info_name'];
$infoManagerEmail = &$default['de_admin_info_email'];

if ($company == '회사명') {
	$company = $config['cf_title'];
}

if ($address == 'OO도 OO시 OO구 OO동 123-45') {
	$address = null;
}else if($post != '123-456'){
	$address = $post . ', ' . $address;
}

if ($owner == '대표자명') {
	$owner = null;
}
if ($phone == '02-123-4567') {
	$phone = null;
}
if ($fax == '02-123-4568') {
	$fax = null;
}

if ($crn == '123-45-67890') {
	$crn = null;
} else {
	$crnCheck = 'http://www.ftc.go.kr/info/bizinfo/communicationViewPopup.jsp?wrkr_no=' . str_replace('-', '', $crn);
}

if ($osdn == '제 OO구 - 123호') {
	$osdn = null;
}

if ($subOsdn == '12345호') {
	$subOsdn = null;
}

if ($infoManager == '정보책임자명') {
	$infoManager = null;
}
if ($infoManagerEmail == '정보책임자 E-mail') {
	$infoManagerEmail = null;
}