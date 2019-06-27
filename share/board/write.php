<?php

use Dot\Html\Component\FormField;
use Dot\Html\Component\Grid;

require __DIR__ . '/write/head.php';
?>
<fieldset>
    <legend>글 작성</legend>

    <?php if (!$member['mb_id']): ?>
        <?= Grid::open(2, 20) ?>
        <?= FormField::div($name->label('이름')) ?>
        <?= FormField::div($password->label('비밀번호')) ?>
        <?= Grid::close ?>
    <?php endif ?>

    <?= FormField::div($subject->label('제목')) ?>
    <?= FormField::div($content->label('내용')) ?>

    <div class="form-field">
            <span class="form-label">
                기능
            </span>
        <span class="form-checkboxes">
                <?php foreach ($options as $label => $checkbox): ?>
                    <label><?= $checkbox ?> <?= $label ?></label>
                <?php endforeach ?>
            </span>
    </div>

    <div class="form-buttons">
        <input type="submit" value="확인">
    </div>
</fieldset>

<?=formClose?>