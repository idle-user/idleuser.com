<?php
declare(strict_types=1);

namespace App\Domain\Auth\Data;

use JsonSerializable;

class Auth implements JsonSerializable
{
    private $user_id;
    private $auth_token;
    private $auth_token_exp;
    private $access_level;

    public function __construct()
    {
    }

    public static function withRow(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getAuthToken(): string
    {
        return $this->auth_token;
    }
    
    public function getAuthTokenExp(): string
    {
        return $this->auth_token_exp;
    }

    public function getAccessLevel(): int
    {
        return $this->access_level;
    }

    public function isAuthExpired(): bool
    {
        $authExpTimestamp = strtotime($this->auth_token_exp);
        return time() > $authExpTimestamp;
    }

    public function jsonSerialize()
    {
        return [
            'user_id' => $this->user_id,
            'auth_token' => $this->auth_token,
            'auth_token_exp' => $this->auth_token_exp,
        ];
    }

    protected function fill(array $row)
    {
        $this->user_id = $row['user_id'];
        $this->auth_token = $row['auth_token'];
        $this->auth_token_exp = $row['auth_token_exp'];
    }
}
