<?php

namespace App\Controllers;

use Core\Authenticator;
use Core\Session;
use Core\Validator;
use JetBrains\PhpStorm\NoReturn;
use App\Models\Service\UserService;

class UserController
{
    private UserService $userService;
    private Authenticator $auth;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->auth = new Authenticator();
    }

    public function listUsers(): void
    {
        $searchTerm = $_GET['search'] ?? '';

        if (!empty($searchTerm)) {
            $users = $this->userService->search($searchTerm);
        } else {
            $users = $this->userService->all();
        }

        render_view('users/list.php', [
            'users' => $users,
            'searchTerm' => $searchTerm
        ]);
    }

    public function showRegisterForm(): void
    {
        render_view('auth/register.php');
    }

    #[NoReturn]
    public function register(): void
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];

        // Validaciones
        if (!Validator::string($name, 2, 100)) {
            $errors[] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }

        if (!Validator::email($email)) {
            $errors[] = 'El email no es válido.';
        }

        if (!Validator::string($password, 6, 255)) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        // Verificar si el email ya existe
        if (empty($errors) && $this->userService->findBy('email', $email)) {
            $errors[] = 'El email ya está registrado.';
        }

        if (!empty($errors)) {
            Session::set_flash('errors', $errors);
            Session::set_flash('old', ['name' => $name, 'email' => $email]);
            redirect_to('/register');
        }

        // Crear usuario
        $userId = $this->userService->create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        if ($userId) {
            Session::set_flash('success', 'Registro exitoso. Por favor, inicia sesión.');
            redirect_to('/login');
        }

        Session::set_flash('errors', ['Error al crear el usuario. Inténtalo de nuevo.']);
        Session::set_flash('old', ['name' => $name, 'email' => $email]);
        redirect_to('/register');
    }

    public function showLoginForm(): void
    {
        render_view('auth/login.php');
    }

    #[NoReturn]
    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];

        // Validaciones
        if (!Validator::email($email)) {
            $errors[] = 'El email no es válido.';
        }

        if (!Validator::string($password, 1)) {
            $errors[] = 'La contraseña es requerida.';
        }

        if (!empty($errors)) {
            Session::set_flash('errors', $errors);
            Session::set_flash('old', ['email' => $email]);
            redirect_to('/login');
        }

        // Intentar autenticar
        if ($this->auth->attempt($email, $password)) {
            Session::set_flash('success', '¡Bienvenido!');
            redirect_to('/');
        }

        Session::set_flash('errors', ['Credenciales incorrectas.']);
        Session::set_flash('old', ['email' => $email]);
        redirect_to('/login');
    }

    #[NoReturn]
    public function logout(): void
    {
        $this->auth->logout();
        Session::set_flash('success', 'Sesión cerrada correctamente.');
        redirect_to('/login');
    }

    public function showDashboard(): void
    {
        $userId = Session::get_value('user')['id'] ?? null;

        if (!$userId) {
            redirect_to('/login');
        }

        $user = $this->userService->find($userId);

        if (!$user) {
            Session::set_flash('errors', ['Usuario no encontrado.']);
            redirect_to('/');
        }

        render_view('/dashboard.php', ['user' => $user]);
    }

    #[NoReturn]
    public function updateProfile(): void
    {
        $userId = Session::get_value('user')['id'] ?? null;

        if (!$userId) {
            redirect_to('/login');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirmation = $_POST['new_password_confirmation'] ?? '';

        $errors = [];

        // Validaciones básicas
        if (!Validator::string($name, 2, 100)) {
            $errors[] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }

        if (!Validator::email($email)) {
            $errors[] = 'El email no es válido.';
        }

        // Verificar si el email ya existe en otro usuario
        $existingUser = $this->userService->findBy('email', $email);
        if ($existingUser && $existingUser->id !== $userId) {
            $errors[] = 'El email ya está registrado por otro usuario.';
        }

        // Preparar datos para actualizar
        $updateData = [
            'name' => $name,
            'email' => $email
        ];

        // Si se proporcionó contraseña nueva
        if (!empty($newPassword)) {
            // Verificar contraseña actual
            $user = $this->userService->find($userId);
            if (!password_verify($currentPassword, $user->password)) {
                $errors[] = 'La contraseña actual es incorrecta.';
            }

            if (!Validator::string($newPassword, 6, 255)) {
                $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            }

            if ($newPassword !== $newPasswordConfirmation) {
                $errors[] = 'Las contraseñas nuevas no coinciden.';
            }

            if (empty($errors)) {
                $updateData['password'] = $newPassword;
            }
        }

        if (!empty($errors)) {
            Session::set_flash('errors', $errors);
            redirect_to('/dashboard');
        }

        // Actualizar usuario
        if ($this->userService->update($userId, $updateData)) {
            // Actualizar sesión con nuevos datos
            Session::set('user', [
                'id' => $userId,
                'name' => $name,
                'email' => $email
            ]);

            Session::set_flash('success', 'Perfil actualizado correctamente.');
            redirect_to('/dashboard');
        }

        Session::set_flash('errors', ['Error al actualizar el perfil. Inténtalo de nuevo.']);
        redirect_to('/dashboard');
    }

    #[NoReturn]
    public function deleteAccount(): void
    {
        $userId = Session::get_value('user')['id'] ?? null;

        if (!$userId) {
            redirect_to('/login');
        }

        $password = $_POST['password'] ?? '';

        $errors = [];

        // Verificar contraseña antes de eliminar
        $user = $this->userService->find($userId);
        if (!$user) {
            $errors[] = 'Usuario no encontrado.';
        } elseif (!password_verify($password, $user->password)) {
            $errors[] = 'La contraseña es incorrecta.';
        }

        if (!empty($errors)) {
            Session::set_flash('errors', $errors);
            redirect_to('/dashboard');
        }

        // Eliminar usuario
        if ($this->userService->delete($userId)) {
            $this->auth->logout();
            Session::set_flash('success', 'Tu cuenta ha sido eliminada correctamente.');
            redirect_to('/');
        }

        Session::set_flash('errors', ['Error al eliminar la cuenta. Inténtalo de nuevo.']);
        redirect_to('/dashboard');
    }
}