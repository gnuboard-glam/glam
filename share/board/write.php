<?php

use Dot\Html\Component\FormField;
use Dot\Html\Component\Grid;

require __DIR__ . '/write/head.php';
?>
    <fieldset class="board-form">
    <legend>글 작성</legend>


<?php if (!$member['mb_id']): ?>
    <?= Grid::open(2, 20) ?>
    <?= FormField::div($name->label($nameText)) ?>
    <?= FormField::div($password->label($passwordText)) ?>
    <?= Grid::close ?>
<?php endif ?>


    <div class="form-field">
            <span class="form-label">
                <?=$optionText?>
            </span>
        <span class="form-checkboxes">
            <?php foreach ($options as $label => $checkbox): ?>
                <label><?= $checkbox ?> <?= $label ?></label>
            <?php endforeach ?>
        </span>
    </div>

<?= FormField::div($subject->label($subjectText)) ?>
<?= FormField::div($content->label($contentText)) ?>

<?php if ($is_file): ?>
    <div class="form-field_files">
        <?php for ($i = 0; $i < $file_count; $i++): ?>
            <div class="form-field">
            <span class="form-field-label">
                <?=$attachText?> #<?= $i + 1 ?>
            </span>
                <?php if ($w == 'u' && $file[$i]['file']): ?>
                    <span class="file_del">
                        <label>
                            <input type="checkbox" name="bf_file_del[<?php echo $i; ?>]" value="1">
                            <em><?= $file[$i]['source'] ?></em>(<?= $file[$i]['size'] ?>) <?=$deleteText?>
                        </label>
                    </span>
                <?php endif ?>
                <span class="form-field-input">
                    <input type="file" name="bf_file[]" id="bf_file_<?= $i + 1 ?>">
                </span>
            </div>

        <?php endfor ?>
        <p class="form-field-description">
            <?php echo $upload_max_filesize ?> <?=$maxAttachSizeText?>
        </p>
    </div>
<?php endif ?>


<?php
require __DIR__ . '/write/foot.php';
