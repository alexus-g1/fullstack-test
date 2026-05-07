<?php if (empty($comments)): ?>
    <p class="text-muted">Пока нет ни одного комментария.</p>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div class="card comment-card" data-comment-id="<?= (int) $comment["id"] ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong><?= esc($comment["name"]) ?></strong>
                        <div class="comment-meta">
                            #<?= (int) $comment["id"] ?>
                            &middot; добавлен: <?= esc(date("d.m.Y H:i", strtotime($comment["date"]))) ?>
                        </div>
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger js-delete"
                            data-id="<?= (int) $comment["id"] ?>">
                        Удалить
                    </button>
                </div>
                <p class="mt-2 mb-0" style="white-space: pre-wrap;"><?= esc($comment["text"]) ?></p>
            </div>
        </div>
    <?php endforeach; ?>

    <?= $pager !== null ? $pager->links("default", "comments_bootstrap") : "" ?>
<?php endif; ?>
