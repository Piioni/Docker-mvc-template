<?php
$title = $title ?? 'MiApp';
require VIEWS_PATH.'/components/head.php';
require VIEWS_PATH.'/components/nav.php';
?>

    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            <?= $content ?? '' ?>
        </div>
    </main>

<?php require __DIR__.'/../components/footer.php';

