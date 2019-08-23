<?php
/**
 * @global $wr_id int
 */

require_once __DIR__ .'/text.php';

require __DIR__ . '/list/head.php';


?>

<?php if ($limit): ?>
    <table class="board-items">
        <thead>
        <tr>

            <th class="board-item-th board-item-no" scope="col">
                    <span>
                        <?php if ($is_checkbox): ?>
                            <label>
                                <input type="checkbox" id="chkall"
                                       onclick="if (this.checked) all_checked(true); else all_checked(false);">
                                <?= $totalText ?>
                            </label>
                        <?php else: ?>
                            <?= $numberText ?>
                        <?php endif ?>
                    </span>
            </th>
            <?php if ($is_category): ?>
                <th class="board-item-th board-item-category" scope="col"><span><?= $categoryTh ?></span></th>
            <?php endif ?>
            <th class="board-item-th board-item-subject" scope="col"><span><?= $subjectText ?></span></th>
            <th class="board-item-th board-item-name" scope="col"><span><?= $writeTh ?></span></th>
            <th class="board-item-th board-item-date" scope="col">
                <span><?= subject_sort_link('wr_datetime', $qstr2, 1) ?><?= $dateTh ?></a></span></th>
            <th class="board-item-th board-item-read" scope="col"><span><?= subject_sort_link('wr_hit', $qstr2, 1) ?>
                        <?= $hitTh ?></a></span></th>
            <?php if ($is_good) : ?>
                <th class="board-item-th board-item-good" scope="col">
                <span><?= subject_sort_link('wr_good', $qstr2, 1) ?><?= $goodTh ?></a></span></th><?php endif ?>
            <?php if ($is_nogood) : ?>
                <th class="board-item-th board-item-bad" scope="col">
                    <span><?= subject_sort_link('wr_nogood', $qstr2, 1) ?><?= $badTh ?></a></span></th>
            <?php endif ?>

        </tr>
        </thead>

        <tbody>
        <?php foreach ($list as $item) {
            $trClass = [];
            $no = $item['num'];

            if ($item['is_notice']) {
                $trClass[] = 'notice';
                $no = $noticeText;
            }

            if ($wr_id == $item['wr_id']) {
                $trClass[] = 'board-item_read';
            }

            if ($is_checkbox) {
                $no = '<label><input type="checkbox" name="chk_wr_id[]" value="' . $item['wr_id'] . '"> ' . $no . '</label>';
            }

            $icons = [];
            if (!!empty($item['icon_new'])) {
                $icons[] = '<span class="board-item-icon board-item-icon-new">새글</span>';
            }

            if (!!empty($item['icon_hot'])) {
                $icons[] = '<span class="board-item-icon board-item-icon-hot">인기</span>';
            }

            if (!!empty($item['icon_file'])) {
                $icons[] = '<span class="board-item-icon board-item-icon-file">파일이 첨부된</span>';
            }

            if (!!empty($item['icon_link'])) {
                $icons[] = '<span class="board-item-icon board-item-icon-link">링크가 첨부된</span>';
            }

            if (!!empty($item['icon_secret'])) {
                $icons[] = '<span class="board-item-icon board-item-icon-secret">비밀</span>';
            }

            $icons = empty($icons) ?
                '' :
                '<span class="board-item-icons"><i>이 글은 </i>' . implode('<i>, </i>', $icons) . '<i>글 입니다.</i></span>';

            $trClass = implode(' ', $trClass);
            ?>
            <tr class="board-item <?= $trClass ?>">
                <td class="board-item-td board-item-no">
                    <?= $no ?>
                </td>
                <?php if ($is_category): ?>
                    <td class="board-item-td board-item-category">
                        <a href="<?= $item['ca_name_href'] ?>"><?= $item['ca_name'] ?></a>
                    </td>
                <?php endif ?>
                <td class="board-item-td board-item-subject">
                    <?= $item['icon_reply']; ?>
                    <a href="<?= $item['href'] ?>">
                        <?= $item['subject'] ?>
                    </a>
                    <?= $icons ?>
                    <?php if ($item['comment_cnt']) { ?><span class="sound_only">댓글</span><?= $item['comment_cnt']; ?>
                        <span
                                class="sound_only">개</span><?php } ?>
                </td>
                <td class="board-item-td board-item-name">
                    <?= $item['name'] ?>
                </td>
                <td class="board-item-td board-item-date">
                    <?= $item['datetime2'] ?>
                </td>
                <td class="board-item-td board-item-read"><?= $item['wr_hit'] ?></td>
                <?php if ($is_good) : ?>
                    <td class="board-item-td board-item-good"><?= $item['wr_good'] ?></td>
                <?php endif ?>
                <?php if ($is_nogood) : ?>
                    <td class="board-item-td board-item-bad"><?= $item['wr_nogood'] ?></td>
                <?php endif ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php else: ?>
    <div class="board-item_blank"><?= $emptyText ?></div>
<?php endif ?>

<?php

require __DIR__ . '/list/foot.php';