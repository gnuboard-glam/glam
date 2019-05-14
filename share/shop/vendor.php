<?php
require __DIR__ . '/vendorValues.php';
?>
<section>
	<div class="dls_inline">
		<?php if ($address): ?>
			<dl>
				<dt>주소</dt>
				<dd><?= $address ?></dd>
			</dl>
		<?php endif ?>
		<?php if ($crn): ?>
			<a href="<?= $crnCheck ?>" target="_blank">
				<dl>
					<dt>사업자 등록번호</dt>
					<dd><span class="non-kr"><?= $crn ?></span> <i>확인</i></dd>
				</dl>
			</a>
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
	</div>

	<div class="site-license">
		Copyright(c) <b> <?= date('Y') ?> <?= $company ?></b> all
		rights reserved.
	</div>
</section>