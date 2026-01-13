<?php

namespace App\Models\Service;

use App\Models\Entity\User as UserEntity;
use App\Models\Repository\UserRepository;

class UserService
{
    protected UserRepository $repo;
    protected array $fillable = ['name', 'email', 'password'];

    public function __construct(?UserRepository $repo = null)
    {
        $this->repo = $repo ?? new UserRepository();
    }

    public function all(): array
    {
        return $this->repo->all();
    }

    public function find(int $id): ?UserEntity
    {
        return $this->repo->find($id);
    }

    public function findBy(string $column, $value): ?UserEntity
    {
        return $this->repo->findBy($column, $value);
    }

    public function create(array $data): ?int
    {
        $data = $this->onlyFillable($data);

        if (empty($data)) {
            throw new \InvalidArgumentException('No valid data provided to create user.');
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repo->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->onlyFillable($data);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->repo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

    public function paginate(int $perPage = 15, int $page = 1): array
    {
        return $this->repo->paginate($perPage, $page);
    }

    public function count(): int
    {
        return $this->repo->count();
    }

    public function search(string $name): array
    {
        return $this->repo->search($name);
    }

    /** Authenticate by email and password; return UserEntity|false */
    public function authenticate(string $email, string $password)
    {
        $user = $this->repo->findBy('email', $email);

        if (! $user) return false;

        if (isset($user->password) && password_verify($password, $user->password)) {
            return $user;
        }

        // fallback – compare plain text (legacy)
        if (isset($user->password) && $user->password === $password) {
            return $user;
        }

        return false;
    }

    protected function onlyFillable(array $data): array
    {
        return array_filter(
            $data,
            fn($k) => in_array($k, $this->fillable, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}

