<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
$current = $pager->getCurrentPageNumber();
$total = $pager->getPageCount();
?>

<?php if ($total > 1): ?>
<nav aria-label="Постраничная навигация">
    <ul class="pagination justify-content-center flex-wrap my-3">
        <?php if ($current > 1): ?>
            <li class="page-item">
                <a class="page-link js-page" href="<?= $pager->getFirst() ?>" aria-label="Первая">&laquo;</a>
            </li>
            <li class="page-item">
                <a class="page-link js-page" href="<?= $pager->getPreviousPage() ?>" aria-label="Предыдущая">&lsaquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item<?= $link["active"] ? " active" : "" ?>">
                <a class="page-link js-page" href="<?= $link["uri"] ?>"><?= $link["title"] ?></a>
            </li>
        <?php endforeach; ?>

        <?php if ($current < $total): ?>
            <li class="page-item">
                <a class="page-link js-page" href="<?= $pager->getNextPage() ?>" aria-label="Следующая">&rsaquo;</a>
            </li>
            <li class="page-item">
                <a class="page-link js-page" href="<?= $pager->getLast() ?>" aria-label="Последняя">&raquo;</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>
