<?= $this->extend("layout") ?>

<?= $this->section("content") ?>

<div class="row">
    <div class="col-12 col-lg-8 mx-auto">

        <div class="d-flex flex-wrap align-items-center sort-toolbar mb-3">
            <label for="sort-field" class="mb-0 mr-2">Сортировка:</label>
            <select id="sort-field" class="form-control form-control-sm w-auto">
                <option value="id" <?= $sort === "id" ? "selected" : "" ?>>по ID</option>
                <option value="date" <?= $sort === "date" ? "selected" : "" ?>>по дате добавления</option>
            </select>
            <select id="sort-dir" class="form-control form-control-sm w-auto">
                <option value="desc" <?= $dir === "desc" ? "selected" : "" ?>>по убыванию</option>
                <option value="asc"  <?= $dir === "asc" ? "selected" : "" ?>>по возрастанию</option>
            </select>
        </div>

        <div id="comments-list">
            <?= view("comments/_list", [
                "comments" => $comments,
                "pager" => $pager,
                "sort" => $sort,
                "dir" => $dir,
            ]) ?>
        </div>

        <hr class="my-4">

        <section>
            <h2 class="h5 mb-3">Добавить комментарий</h2>
            <form id="comment-form" novalidate>
                <div class="form-group">
                    <label for="field-name">Email</label>
                    <input type="email" id="field-name" name="name" class="form-control" required>
                    <div class="field-error" data-error-for="name"></div>
                </div>
                <div class="form-group">
                    <label for="field-text">Комментарий</label>
                    <textarea id="field-text" name="text" class="form-control" rows="3" required></textarea>
                    <div class="field-error" data-error-for="text"></div>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
                <span id="form-status" class="ml-2"></span>
            </form>
        </section>
    </div>
</div>

<?= $this->endSection() ?>
