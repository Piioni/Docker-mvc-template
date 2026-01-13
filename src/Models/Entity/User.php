<?php

namespace App\Models\Entity;

class User
{
    public const FILLABLE = ['name', 'email', 'password'];

    public ?int $id;
    public ?string $name;
    public ?string $email;
    public ?string $password;

    public function __construct(array $attributes = [])
    {
        $this->id = isset($attributes['id']) ? (int)$attributes['id'] : null;
        $this->name = $attributes['name'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->password = $attributes['password'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

}

