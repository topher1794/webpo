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

    
    public function usernameExists(string $username): bool
    {

        $stmt = $this->pdo->prepare("SELECT id FROM StockAlignUsers WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch() !== false;
    }

    public function createUser(string $username, string $firstname, string $lastname, string $email, string $passwordHash): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO StockAlignUsers ( id, username, firstname, lastname, emailadd, bcrypt_pass) VALUES (UUID(), :username, :firstname,  :lastname,   :email, :password)");
        $stmt->execute(['username' => $username, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'password' => $passwordHash]);
    }
}