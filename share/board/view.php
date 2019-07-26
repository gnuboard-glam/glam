<?php
require __DIR__ . '/view/head.php';

$hasAttaches = count($view['file']);

$attachedFiles = [];
foreach ($view['file'] as $file) {
    if (!(isset($file['source']) && $file['source'])) {
        continue;
    }
    $attachedFiles[] = $file;
}

?>


<article class="board-article<?= $withListClass ?>">
    <header>
        <h1 class="board-article-title"><?= $view['wr_subject'] ?></h1>
        <section class="board-article-info">
            <?php if ($category_name): ?>
                <dl>
                    <dt>분류<span class="glue">:</span></dt>
                    <dd><?= $view['ca_name'] ?></dd>
                </dl>
            <?php endif ?>

            <dl>
                <dt>작성자<span class="glue">:</span></dt>
                <dd><?= $view['name'] ?></dd>
            </dl>

            <dl>
                <dt>작성일<span class="glue">:</span></dt>
                <dd><?= date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></dd>
            </dl>

            <dl class="board-article-hit">
                <dt>조회수<span class="glue">:</span></dt>
                <dd><?= number_format($view['wr_hit']) ?></dd>
            </dl>
        </section>
    </header>

    <main>
        <div class="board-article-content">
            <?= $content ?>
        </div>
    </main>

    <?php if ($is_signature): ?>
        <section class="board-article-signature">
            <?= $signature ?>
        </section>
    <?php endif ?>

    <?php if ($attachedFiles): ?>
        <section class="board-attaches">
            <h3 class="board-attaches-title"><?= count($attachedFiles) ?>개 첨부 파일</h3>
            <ul class="dot-grid-gap10 dot-grid2 dot-grid3-76 dot-grid4-at-12">
                <?php foreach ($attachedFiles as $file) : ?>
                    <li class="dot-grid">
                        <div class="board-attach respond-height_16-9">
                            <a href="<?= $file['href'] ?>" title="<?= $file['source'] ?> 파일 내려 받기"
                               class="board-attach-link respond-height">
                                <dl class="board-attach-info">
                                    <dt class="board-attach-name"><?= $file['source'] ?></dt>
                                    <dd class="board-attach-size"><?= $file['size'] ?></dd>
                                    <dd class="board-attach-time"><?= $file['datetime'] ?></dd>
                                    <dd class="board-attach-download"><?= $file['download'] ?>회 내려 받음</dd>
                                    <?php if ($file['content']): ?>
                                        <dd class="board-attach-content"><?= $file['content'] ?></dd>
                                    <?php endif ?>
                                </dl>
                            </a>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </section>
    <?php endif ?>

    <?php if (!$useYoutube && $links): ?>
        <section class="board-links">
            <h3>링크<span class="glue">:</span></h3>
            <ul>
                <?php foreach ($links as $link): ?>
                    <li class="board-link">
                        <a href="<?= $link['href'] ?>">
                            <?= $link['text'] ?>
                        </a>
                        <?= $link['hit'] ?>회 연결
                    </li>
                <?php endforeach ?>
            </ul>
        </section>
    <?php endif ?>


    <?php
    // 코멘트 입출력
    if ($useComment) {
        include_once(G5_BBS_PATH . '/view_comment.php');
    }
    ?>

    <div class="board-modes">
        <div class="board-modes-left">
            <?php if ($update_href): ?>
            <a href="<?= $update_href ?>"
               class="board-mode board-mode-left board-mode-update">
                수정
            </a>
            <?php endif ?>
            <?php if ($delete_href) :?>
            <a href="<?= $delete_href ?>" class="board-mode board-mode-left board-mode-delete"
               onclick="del(this.href); return false;">삭제</a>
            <?php endif ?>
            <?php if ($reply_href) : ?>
            <a href="<?= $reply_href ?>" class="board-mode board-mode-left board-mode-reply">
                답변
            </a>
            <?php endif ?>
            <?php if ($search_href) : ?>
            <a href="<?= $search_href ?>" class="board-mode board-mode-left board-mode-search">
                검색
            </a>
            <?php endif ?>
        </div>
        <div class="board-modes-right">
            <a href="<?= $list_href ?>" class="board-mode board-mode-right board-mode-list">목록</a>
        </div>
    </div>

</article>
