<?php
require __DIR__ . '/view/head.php';

$hasAttaches = count($view['file']);

$attachedFiles = [];
foreach($view['file'] as $file){
    if(!(isset($file['source']) && $file['source'])){
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
            <?php if ($update_href) { ?><a href="<?= $update_href ?>"
                                           class="board-mode board-mode-left board-mode-update">수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?= $delete_href ?>"
                                           class="board-mode board-mode-left board-mode-delete"
                                           onclick="del(this.href); return false;">삭제</a><?php } ?>
            <?php if ($reply_href) { ?><a href="<?= $reply_href ?>" class="board-mode board-mode-left board-mode-reply">
                    답변</a><?php } ?>
            <?php if ($search_href) { ?><a href="<?= $search_href ?>"
                                           class="board-mode board-mode-left board-mode-search">검색</a><?php } ?>
        </div>
        <div class="board-modes-right">
            <a href="<?= $list_href ?>" class="board-mode board-mode-right board-mode-list">목록</a>
        </div>
    </div>

</article>

<script>
    <?php if ($board['bo_download_point'] < 0) { ?>
	$(function () {
		$("a.view_file_download").click(function () {
			if (!g5_is_member) {
				alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
				return false;
			}

			var msg = "파일을 다운로드 하시면 포인트가 차감(<?=number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

			if (confirm(msg)) {
				var href = $(this).attr("href") + "&js=on";
				$(this).attr("href", href);

				return true;
			} else {
				return false;
			}
		});
	});
    <?php } ?>

	function board_move(href) {
		window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
	}

	$(function () {
		$("a.view_image").click(function () {
			window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
			return false;
		});

		// 추천, 비추천
		$("#good_button, #nogood_button").click(function () {
			var $tx;
			if (this.id == "good_button")
				$tx = $("#bo_v_act_good");
			else
				$tx = $("#bo_v_act_nogood");

			excute_good(this.href, $(this), $tx);
			return false;
		});

		// 이미지 리사이즈
		$("#bo_v_atc").viewimageresize();
	});

	function excute_good(href, $el, $tx) {
		$.post(
			href,
			{js: "on"},
			function (data) {
				if (data.error) {
					alert(data.error);
					return false;
				}

				if (data.count) {
					$el.find("strong").text(number_format(String(data.count)));
					if ($tx.attr("id").search("nogood") > -1) {
						$tx.text("이 글을 비추천하셨습니다.");
						$tx.fadeIn(200).delay(2500).fadeOut(200);
					} else {
						$tx.text("이 글을 추천하셨습니다.");
						$tx.fadeIn(200).delay(2500).fadeOut(200);
					}
				}
			}, "json"
		);
	}
</script>
