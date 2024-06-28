<?php

use Hellm\ExpenseApp\FileManager;

class AuthManager
{
    private $user_name;
    private $user_password;
    private FileManager $file;
    public function __construct()
    {
        $this->file = new FileManager("auth");
    }

    // public function authenticate(): bool
    // {
    // }
}
