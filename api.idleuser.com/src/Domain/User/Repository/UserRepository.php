<?php
declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Database;
use App\Domain\User\Data\User;
use App\Domain\User\Exception\UserLoginFailedException;
use App\Domain\User\Exception\UsernameAlreadyExistsException;
use App\Domain\User\Exception\UserNotFoundException;

use PDOException;

class UserRepository
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM user";
        $stmt = $this->db->query($sql);
        $ret = [];
        while ($row = $stmt->fetch()) {
            $ret[] = User::withRow($row);
        }
        return $ret;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM user WHERE id=?";
        $stmt = $this->db->query($sql, [$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new UserNotFoundException();
        }
        return User::withRow($row);
    }

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM user WHERE username=?";
        $stmt = $this->db->query($sql, [$username]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new UserNotFoundException();
        }
        return User::withRow($row);
    }

    public function searchByUsername($username)
    {
        $sql = "SELECT * FROM user WHERE username LIKE ?";
        $stmt = $this->db->query($sql, [$username]);
        $ret = [];
        while ($row = $stmt->fetch()) {
            $ret[] = User::withRow($row);
        }
        return $ret;
    }

    public function register(array $data)
    {
        $sql = "INSERT INTO user (username, secret, date_created, last_login) VALUES (?, ?, NOW(), NOW())";
        $args = [
            $data['username'],
            password_hash($data['secret'], PASSWORD_BCRYPT),
        ];

        try {
            $this->db->query($sql, $args);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new UsernameAlreadyExistsException();
            } else {
                throw $e;
            }
        }

        $userId = $this->db->lastInsertId();

        return $this->findById($userId);
    }

    public function login(array $data)
    {
        $args = [
            'username' => $data['username'],
            'secret' =>  $data['secret']
        ];

        try {
            if (password_verify($args['secret'], $this->findSecretByUsername($args['username']))) {
                $sql = "UPDATE user SET last_login=NOW() WHERE username=?";
                $this->db->query($sql, [$args['username']]);
                return $this->findByUsername($args['username']);
            } else {
                throw new UserLoginFailedException();
            }
        } catch (UserNotFoundException $e) {
            throw new UserLoginFailedException();
        }
    }


    private function findSecretByUsername($username)
    {
        $sql = "SELECT secret FROM user WHERE username=?";
        $stmt = $this->db->query($sql, [$username]);
        $result = $stmt->fetchColumn();
        if (!$result) {
            throw new UserNotFoundException();
        }
        return $result;
    }
}
