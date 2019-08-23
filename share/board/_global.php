<?php


if ($glam->is54) {
    $listHref = GNU_URL . "{$bo_table}";
    $writeHref = $listHref . '/write';
    // todo check
    $updateHref = $writeHref . "?w=u";
} else {
    $categoryHref = GNU_URL . 'board.php?bo_table=' . $bo_table . '&sca=';
    $listHref = GNU_URL . 'bbs/board.php?bo_table=' . $bo_table;
    $writeHref = GNU_URL . 'bbs/write.php?bo_table=' . $bo_table;
    $updateHref = GNU_URL . 'bbs/write.php?bo_table=' . $bo_table . '&w=u&wr_id=';
    $deleteHref = GNU_URL . 'delete.php?bo_table=' . $bo_table . '&page=' . $page . '&token=' . $token . '&wr_id=';
}

$totalText = $totalText ?? '전체';
$numberText = $numberText ?? '번호';
$writeTh = $writeTh ?? '이름';
$writeText = $writeText ?? '쓰기';
$categoryTh = $categoryTh ?? '분류';
$subjectText = $subjectText ?? '제목';
$contentText = $contentText ?? '본문';
$dateTh = $dateTh ?? '날짜';
$hitTh = $hitTh ?? '조회';
$goodTh = $goodTh ?? '추천';
$badTh = $badTh ?? '비추천';
$noticeText = $noticeText ?? '공지';
$emptyText = $emptyText ?? '게시물이 없습니다.';

$attachText = $attachText ?? '첨부';
$attachedText = $attachedText ?? '개 첨부 파일';
$downloadText = $downloadText ?? ' 파일 내려 받기';
$downloadedText = $downloadedText ?? '회 내려 받음';

$linkText = $linkText ?? '링크';
$linkedText = $linkedText ?? '회 연결';

$modifyText = $modifyText ?? '수정';
$deleteText = $deleteText ?? '삭제';
$replyText = $replyText ?? '답변';
$searchText = $searchText ?? '검색';
$listText = $listText ?? '목록';

$nameText = $nameText ?? '이름';
$passwordText = $passwordText ?? '비밀번호';
$optionText = $optionText ?? '기능';

$maxAttachSizeText = $maxAttachSizeText ?? ' 이하 용량만 첨부 가능';

$submitText = $submitText ?? '등록';
