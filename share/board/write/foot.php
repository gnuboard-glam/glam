<?php
if (!isset($submitFor)) {
    $submitFor = $w == 'u' ? $modifyText : $submitText;
}
?>


<?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
<?php } ?>

<div class="form-buttons">
    <div class="form-buttons-left">
        <input type="submit" value="<?= $submitFor ?>(Alt+S)" accesskey="s">
    </div>
    <div class="form-buttons-right">
        <a href="<?= $listHref ?>" data-confirm><?=$listText?></a>
    </div>
</div>

<?= '</form>' ?>
<?= '</div>' ?>
<?= '</fieldset>' ?>
<script type="text/javascript">
	function fwrite_submit_editor() {
        <?= $editor_js;?>
	}

	function fwrite_submit_captcha() {
        <?= $captcha_js;?>
	}
</script>
