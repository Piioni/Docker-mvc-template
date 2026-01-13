<?php

namespace App\Models\Repository;

use Core\Database;
use App\Models\Entity\User as UserEntity;
use InvalidArgumentException;
use PDO;

class UserRepository
{
    private const string TABLE = 'users';

    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getInstance()->getConnection();
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE);
        $stmt->execute();

        return array_map(
            fn(array $row) => UserEntity::fromArray($row),
            $stmt->fetchAll()
        );
    }

    public function find(int $id): ?UserEntity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ? UserEntity::fromArray($row) : null;
    }

    public function findBy(string $column, mixed $value): ?UserEntity
    {
        if (!property_exists(UserEntity::class, $column)) {
            throw new InvalidArgumentException("Columna no válida: $column");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE $column = :value LIMIT 1");
        $stmt->execute(['value' => $value]);

        $row = $stmt->fetch();
        return $row ? UserEntity::fromArray($row) : null;
    }

    public function create(array $data): ?int
    {
        $data = array_intersect_key($data, array_flip(UserEntity::FILLABLE));

        if (empty($data)) {
            throw new InvalidArgumentException('No hay datos válidos para insertar.');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . self::TABLE . " ($columns) VALUES ($placeholders)";

        $this->pdo->prepare($sql)->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = array_intersect_key($data, array_flip(UserEntity::FILLABLE));

        if (empty($data)) {
            return false;
        }

        $set = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));

        $sql = "UPDATE " . self::TABLE . " SET $set WHERE id = :id";

        $data['id'] = $id;

        return $this->pdo->prepare($sql)->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " LIMIT :limit OFFSET :offset");
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $countStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM " . self::TABLE);
        $countStmt->execute();
        $total = (int) ($countStmt->fetch()['total'] ?? 0);

        return [
            'data' => array_map(fn($r) => UserEntity::fromArray($r), $rows),
            'meta' => [
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
                'last_page'    => (int) ceil($total / $perPage),
            ]
        ];
    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM " . self::TABLE);
        $stmt->execute();

        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function search(string $name): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE name LIKE :name");
        $stmt->execute(['name' => '%' . $name . '%']);

        return array_map(
            fn(array $row) => UserEntity::fromArray($row),
            $stmt->fetchAll()
        );
    }
}

