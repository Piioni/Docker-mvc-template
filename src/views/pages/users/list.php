<?php

use Core\Session;

/** @var array $users */
/** @var string $searchTerm */

$title = 'Usuarios';
$success = Session::get_value('success') ?? null;

ob_start();
?>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold leading-6 text-gray-900">Usuarios</h1>
                <p class="mt-2 text-sm text-gray-700">Lista de todos los usuarios registrados en el sistema.</p>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="mt-4 rounded-md bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800"><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>

        <!-- Formulario de Búsqueda -->
        <div class="mt-6">
            <form method="GET" action="/users" class="flex gap-4 items-end">
                <div class="flex-1 max-w-md">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        Buscar por nombre
                    </label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                           placeholder="Introduce el nombre..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Buscar
                    </button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="/users"
                           class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if (!empty($searchTerm)): ?>
            <div class="mt-4 text-sm text-gray-600">
                Mostrando resultados para: <span class="font-semibold">"<?= htmlspecialchars($searchTerm) ?>"</span>
                (<?= count($users) ?> resultado<?= count($users) !== 1 ? 's' : '' ?>)
            </div>
        <?php endif; ?>

        <div class="mt-6 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nombre</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="3" class="py-4 pl-4 pr-3 text-sm text-center text-gray-500 sm:pl-6">
                                            No hay usuarios registrados.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                <?= htmlspecialchars($user->id ?? $user['id'] ?? '') ?>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <?= htmlspecialchars($user->name ?? $user['name'] ?? '') ?>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <?= htmlspecialchars($user->email ?? $user['email'] ?? '') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/base.php';

