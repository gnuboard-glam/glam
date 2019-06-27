<?php
if(!isset($submitFor)) {
    $submitFor = $w == 'u' ? '수정' : '등록';
}
?>
<div class="form-buttons">
    <input type="submit" value="<?=$submitFor?>" id="btn_submit" accesskey="s" class="button button-submit">
    <a href="<?=$listHref?>" class="button button-cancel">취소</a>
</div>

<?='</form>'?>
<?='</div>'?>
<script type="text/javascript">
	function fwrite_submit_editor() {
        <?= $editor_js;?>
	}

	function fwrite_submit_captcha() {
        <?= $captcha_js;?>
	}
</script>