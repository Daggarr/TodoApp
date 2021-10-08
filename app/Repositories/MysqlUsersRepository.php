<?php
namespace App\Repositories;

use PDO;
use PDOException;
use App\Models\User;

class MysqlUsersRepository
{
    private PDO $conn;

    public function __construct()
    {
        $config = parse_ini_file('config.ini');

        try {
            $this->conn = new PDO("mysql:host={$config['serverName']};dbname={$config['dbName']}", $config['dbUser'], $config['dbPassword']);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function save(User $user): void
    {
        $sql = "INSERT INTO Users (username, password) VALUES (?, ?)";
        $hashPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $statement = $this->conn->prepare($sql);
        $statement->execute([
            $user->getUsername(),
            $hashPassword,
        ]);
    }

    public function verifyPassword(User $user): ?bool
    {
        $sql = "SELECT * FROM Users WHERE username = ?";
        $statement = $this->conn->prepare($sql);
        $statement->execute([$user->getUsername()]);
        $fetchedUser = $statement->fetch();

        if (empty($fetchedUser)) return null;

        return password_verify($user->getPassword(),$fetchedUser['password']);
    }
}