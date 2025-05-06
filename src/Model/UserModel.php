<?php 

namespace stockalignment\Model;

use PDO;

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM StockAlignUsers WHERE emailadd = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() !== false;
    }

    public function createUser(string $email, string $passwordHash): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO StockAlignUsers ( id, username, bcrypt_pass) VALUES (UUID(), :email, :password)");
        $stmt->execute(['email' => $email, 'password' => $passwordHash]);
    }
}