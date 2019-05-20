<?php
require __DIR__ . '/vendorValues.php';
?>
<section class="dls_inline">
	<?php if ($company): ?>
        <dl>
            <dt>상호명</dt>
            <dd><?= $company ?></dd>
        </dl>
	<?php endif ?>

	<?php if ($crn): ?>
        <dl>
            <dt>사업자 등록번호</dt>
            <dd><a href="<?= $crnCheck ?>" target="_blank" title="사업자 등록번호 확인"><span
                            class="non-kr"><?= $crn ?></span></a></dd>
        </dl>
	<?php endif ?>

	<?php if ($address): ?>
        <dl>
            <dt>주소</dt>
            <dd><?= $address ?></dd>
        </dl>
	<?php endif ?>

	<?php if ($owner): ?>
        <dl>
            <dt>대표</dt>
            <dd><?= $owner ?></dd>
        </dl>
	<?php endif ?>
	<?php if ($phone): ?>
        <dl>
            <dt>전화</dt>
            <dd><span class="non-kr"><?= $phone ?></span></dd>
        </dl>
	<?php endif ?>
	<?php if ($fax): ?>
        <dl>
            <dt>팩스</dt>
            <dd><span class="non-kr"><?= $fax ?></span></dd>
        </dl>
	<?php endif ?>
	<?php if ($osdn): ?>
        <dl>
            <dt>통신판매업신고번호</dt>
            <dd><?= $osdn ?></dd>
        </dl>
	<?php endif ?>
	<?php if ($subOsdn): ?>
        <dl>
            <dt>부가통신 사업자번호</dt>
            <dd><?= $subOsdn ?></dd>
        </dl>
	<?php endif ?>
	<?php if ($infoManager): ?>
		<?php if ($infoManagerEmail): ?><a href="mailto:<?= $infoManagerEmail ?>"><?php endif ?>
        <dl>
            <dt>정보관리책임자</dt>
            <dd><?= $infoManager ?></dd>
        </dl>
		<?php if ($infoManagerEmail): ?></a><?php endif ?>
	<?php endif ?>
</section>

<section class="site-license">
    Copyright(c) <b> <?= date('Y') ?> <?= $config['cf_title'] ?></b> all
    rights reserved.
</section>
