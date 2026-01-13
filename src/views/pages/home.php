<?php
$title = 'Inicio';
ob_start();
?>
<h1 class="text-2xl font-bold">Bienvenido</h1>
<p class="mt-4">Hola, <?= htmlspecialchars($_SESSION['user']['email'] ?? 'Invitado') ?></p>

<?php
$content = ob_get_clean();
require __DIR__.'/../layouts/base.php';

