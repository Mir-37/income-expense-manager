<?php

use Constant;

use Hellm\ExpenseApp\FileManagement\UserFile;
use Hellm\ExpenseApp\Traits\Helper;

class AuthManager
{
    use Helper;
    private UserFile $file;

    public function __construct()
    {
        $this->file = new UserFile();
    }

    public function register(string $name, string $email, string $password): bool
    {
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);

        $validation = $this->validate($name, $email, $password);

        if ($validation) {
            $this->file->insert([$name, $email, $hashed_pass]);
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
            return false;
        }
        if (password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
