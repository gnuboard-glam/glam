<?php
/**
 * @global $list array
 * @global $bo_table string
 * @global $page int
 * @global $token string
 * @global $sfl
 * @global $stx
 * @global $spt
 * @global $sca string
 * @global $sst
 * @global $sod
 * @global $wr_id int
 * @global $write_min int
 * @global $write_max int
 */

/**
 * @global $is_notice bool
 * @global $is_secret bool
 * @global $is_dhtml_editor
 */

use Dot\Dev;
use Dot\Html\Tag\Input;
use Dot\Html\Tag\Input\Checkbox;
use Dot\Html\Tag\Input\Hidden;
use Dot\Html\Tag\Input\Password;
use Dot\Html\Tag\Textarea;

require __DIR__ . '/../_global.php';

$glam->head->scripts->url(GLAM_JS . 'board/write.js');

$listHref = GNU_URL . "bbs/board.php?bo_table=${bo_table}";

$options = [];
$hiddenOptions = [];

if ($is_notice) {
    $options['공지사항'] = new Checkbox('notice', $notice_checked);
}

if ($is_html) {
    if ($is_dhtml_editor) {
        $hiddenOptions[] = new Hidden('html', 'html1');
    } else {
        $options['HTML 사용'] = new Checkbox('html', $html_checked, $html_value ? $html_value : 'html1');
    }
}

if ($is_secret) {
    if ($is_admin || $is_secret == 1) {
        $options['비밀글'] = new Checkbox('secret', $secret_checked, 'secret');
    } else {
        $hiddenOptions[] = new Hidden('secret', 'secret');
    }
}

if ($is_mail) {
    $options['답변 메일로도 받기'] = new Checkbox('mail', 'mail', $recv_email_checked);
}
$hiddenOptions = $hiddenOptions ? implode(PHP_EOL, $hiddenOptions) : '';

if ($is_member) {
    $glam->head->scripts->url(GNU_JS . 'autosave.js');
}

$name = (new Input('wr_name', $wr_name ?? ''))
    ->required();

$password = (new Password('wr_password'))
    ->required();

$subject = (new Input('wr_subject', $subject ?? ''))
    ->required()
    ->autofocus();

$content = (new Textarea('wr_content', $content ?? ''))
    ->required();

$is_limit = $write_min || $write_max;
$is_homepage = null;
$is_email = null;

// $attach = (new Input(''))

const formClose = '</form>';
?>
<div class="board-write">
    <form name="fwrite" id="fwrite" action="<?= $action_url ?>" method="post"
          enctype="multipart/form-data" autocomplete="off"
          data-write-min="<?= $write_min ?>"
          data-write-max="<?= $write_max ?>"
    >
        <input type="hidden" name="uid" value="<?= get_uniqid() ?>">
        <input type="hidden" name="w" value="<?= $w ?>">
        <input type="hidden" name="bo_table" value="<?= $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?= $wr_id ?>">
        <input type="hidden" name="sca" value="<?= $sca ?>">
        <input type="hidden" name="sfl" value="<?= $sfl ?>">
        <input type="hidden" name="stx" value="<?= $stx ?>">
        <input type="hidden" name="spt" value="<?= $spt ?>">
        <input type="hidden" name="sst" value="<?= $sst ?>">
        <input type="hidden" name="sod" value="<?= $sod ?>">
        <input type="hidden" name="page" value="<?= $page ?>">
