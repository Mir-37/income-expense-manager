<?php

namespace Hellm\ExpenseApp;

use Constant;

use Hellm\ExpenseApp\FileManagement\UserFile;
use Hellm\ExpenseApp\Traits\Helper;

class AuthManager
{
    use Helper;
    private UserFile $file;
    private array $user;

    public function __construct()
    {
        $this->file = new UserFile();
    }

    public function register(string $name, string $email, string $password): bool
    {
        $hashed_pass = password_hash(trim($password), PASSWORD_BCRYPT);

        $validation = $this->validate($name, $email, $password);

        if ($validation) {
            $this->file->insert([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_pass
            ]);
            return true;
        }

        return false;
    }

    public function validate(string $name, string $email, string $password): string|bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    public function authenticate(string $nameOrEmail, string $password): ?array
    {
        $user = $this->findUser($nameOrEmail);
        if (!$user) {
            return null;
        }
        if (password_verify($password, $user['password'])) {
            $this->user = $user;
            return $user;
        }
        return null;
    }

    public function getUser(): string
    {
        return $this->user['name'];
    }

    public function getUserId(): int
    {
        return (int)$this->user['id'];
    }
}
