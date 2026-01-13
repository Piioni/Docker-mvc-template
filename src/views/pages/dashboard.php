<?php

use Core\Session;


$title = 'Mi Perfil';
ob_start();
?>

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gray-800 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Mi Perfil</h1>
                </div>

                <!-- Flash Messages -->
                <div class="px-6 py-4">
                    <?php if ($success = Session::get_value('success')): ?>
                        <div
                            class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($errors = Session::get_value('errors')): ?>
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul class="list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Información del Usuario -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Información de la Cuenta</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">ID de Usuario</p>
                            <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($user->id) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nombre</p>
                            <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($user->name) ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($user->email) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Edición -->
                <div class="px-6 py-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Editar Perfil</h2>
                    <form method="POST" action="/dashboard/update" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="<?= htmlspecialchars($user->name) ?>"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="<?= htmlspecialchars($user->email) ?>"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Cambiar Contraseña (Opcional)</h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                        Contraseña Actual
                                    </label>
                                    <input type="password"
                                           id="current_password"
                                           name="current_password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <p class="mt-1 text-xs text-gray-500">Requerido solo si deseas cambiar tu
                                        contraseña</p>
                                </div>

                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nueva Contraseña
                                    </label>
                                    <input type="password"
                                           id="new_password"
                                           name="new_password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div>
                                    <label for="new_password_confirmation"
                                           class="block text-sm font-medium text-gray-700 mb-1">
                                        Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password"
                                           id="new_password_confirmation"
                                           name="new_password_confirmation"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 font-medium">
                                Actualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>

                <div class="px-6 py-6 bg-red-50">
                    <h2 class="text-xl font-semibold text-red-800 mb-4">Zona Peligrosa</h2>
                    <p class="text-sm text-gray-700 mb-4">
                        Una vez que elimines tu cuenta, no hay vuelta atrás. Por favor, asegúrate.
                    </p>

                    <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 font-medium">
                        Eliminar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Eliminar Cuenta</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center mb-4">
                        Esta acción no se puede deshacer. ¿Estás seguro de que deseas eliminar tu cuenta
                        permanentemente?
                    </p>

                    <form method="POST" action="/dashboard/delete" class="space-y-4">
                        <div>
                            <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirma tu contraseña
                            </label>
                            <input type="password"
                                   id="delete_password"
                                   name="password"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                                    class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require VIEWS_PATH.'layouts/base.php';

