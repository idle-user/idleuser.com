<?php

declare(strict_types=1);

namespace App\Domain\Auth\Repository;

use App\Domain\Database;
use App\Domain\Auth\Data\Auth;
use App\Domain\Auth\Exception\AuthTokenInvalidException;

class AuthRepository
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByAuthToken($authToken)
    {
        $sql = "SELECT * FROM auth WHERE auth_token=?";
        $stmt = $this->db->query($sql, [$authToken]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new AuthTokenInvalidException();
        }
        return Auth::withRow($row);
    }

    public function findByUserId($userId)
    {
        $sql = "SELECT * FROM auth WHERE userId=?";
        $stmt = $this->db->query($sql, [$userId]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new AuthTokenInvalidException();
        }
        return Auth::withRow($row);
    }

}
