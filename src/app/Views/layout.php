<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? "Комментарии") ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url("css/comments.css") ?>">
</head>
<body>
    <div class="container py-4">
        <header class="mb-4">
            <h1 class="h3"><?= esc($title ?? "Комментарии") ?></h1>
        </header>

        <main>
            <?= $this->renderSection("content") ?>
        </main>
    </div>

    <div id="loading-overlay" role="status" aria-live="polite" aria-hidden="true">
        <div class="loading-box">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Загрузка…</span>
            </div>
            <p class="loading-message">Подождите…</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
    <script src="<?= base_url("js/comments.js") ?>"></script>
</body>
</html>
