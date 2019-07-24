<?php

use Dot\Html\Component\FormField;
use Dot\Html\Component\Grid;

require __DIR__ . '/write/head.php';
?>
    <fieldset class="board-form">
    <legend>글 작성</legend>


<?php if (!$member['mb_id']): ?>
    <?= Grid::open(2, 20) ?>
    <?= FormField::div($name->label('이름')) ?>
    <?= FormField::div($password->label('비밀번호')) ?>
    <?= Grid::close ?>
<?php endif ?>


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

<?= FormField::div($subject->label('제목')) ?>
<?= FormField::div($content->label('내용')) ?>

<?php if ($is_file): ?>
    <div class="form-field_files">
        <?php for ($i = 0; $i < $file_count; $i++): ?>
            <div class="form-field">
            <span class="form-field-label">
                첨부 #<?= $i + 1 ?>
            </span>
                <?php if ($w == 'u' && $file[$i]['file']): ?>
                    <span class="file_del">
                        <label>
                            <input type="checkbox" name="bf_file_del[<?php echo $i; ?>]" value="1">
                            <em><?= $file[$i]['source'] ?></em>(<?= $file[$i]['size'] ?>) 제거
                        </label>
                    </span>
                <?php endif ?>
                <span class="form-field-input">
                    <input type="file" name="bf_file[]" id="bf_file_<?= $i + 1 ?>">
                </span>
            </div>

        <?php endfor ?>
        <p class="form-field-description">
            <?php echo $upload_max_filesize ?> 이하 용량만 첨부 가능
        </p>
    </div>
<?php endif ?>


<?php
require __DIR__ . '/write/foot.php';
