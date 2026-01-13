<?php

namespace Core;

use App\Models\Service\UserService;
use App\Models\Entity\User as UserEntity;

class Authenticator
{
    protected UserService $service;

    public function __construct(?UserService $service = null)
    {
        $this->service = $service ?? new UserService();
    }

    public function attempt($email, $password): bool
    {
        $user = $this->service->authenticate($email, $password);

        if ($user instanceof UserEntity) {
            $this->login($user);
            return true;
        }

        return false;
    }

    public function login($userData): void
    {
        if ($userData instanceof UserEntity) {
            $data = [
                'id' => $userData->id,
                'name' => $userData->name,
                'email' => $userData->email,
            ];
        } elseif (is_array($userData)) {
            $data = [
                'id' => $userData['id'] ?? null,
                'name' => $userData['name'] ?? null,
                'email' => $userData['email'] ?? null,
            ];
        } else {
            return;
        }

        Session::set('user', $data);

        $_SESSION['user'] = $data;

        session_regenerate_id(true);
    }

    public function logout(): void
    {
        Session::destroy_session();
    }
}