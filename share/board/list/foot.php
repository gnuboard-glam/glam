<?php
if (!isset($writeText)) {
    $writeText = '작성';
}
?>

    <div class="board-list-foot">
        <div class="board-list-foot-wrap">
            <?php if ($list_href || $write_href): ?>
                <div class="board-modes">
                    <?php if ($list_href) : ?>
                        <span class="board-modes-left">
                        <a href="<?= $list_href ?>" class="board-mode board-mode-left board-mode-list">목록</a>
                        </span>
                    <?php endif ?>

                    <?php if ($write_href) : ?>
                        <span class="board-modes-right">
                            <a href="<?= $write_href ?>" class="board-mode board-mode-right board-mode-write"><?=$writeText?></a>
                        </span>
                    <?php endif ?>

                </div>
            <?php endif ?>
        </div>

        <?= $write_pages; ?>

        <?php if($useSearch):?>
            <fieldset class="board-search">
                <legend>게시물 검색</legend>
                <form name="fsearch" method="get">
                    <input type="hidden" name="bo_table" value="<?= $bo_table ?>">
                    <input type="hidden" name="sca" value="<?= $sca ?>">
                    <input type="hidden" name="sop" value="and">
                    <label>
                        <i>검색대상: </i>
                        <?php
                        $options = [
                            'wr_subject' => '제목',
                            'wr_content' => '내용',
                            'wr_subject||wr_content' => '제목+내용',
                            'mb_id,1' => '회원',
                            'mb_id,0' => '회원(의견)',
                            'mb_name,1' => '글쓴이',
                            'mb_name,0' => '글쓴이(의견)'
                        ];
                        ?>
                        <?= '' //tagSelect('sfl', $options, $sfl) ?>
                    </label>
                    <label>
                        <i>검색 대상:</i>
                        <input type="text" name="stx" value="<?= stripslashes($stx) ?>" required id="stx" size="15" maxlength="20" class="board-search-keyword">
                    </label>
                    <input type="submit" value="검색" class="board-search-submit">
                </form>
            </fieldset>
        <?php endif?>
    </div>
    </form>