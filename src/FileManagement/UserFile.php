<?php

namespace Hellm\ExpenseApp\FileManagement;

use DateTime;
use Hellm\ExpenseApp\Constant;
use Stringable;

class UserFile extends FileManager
{

    private int $id;

    public function __construct()
    {
        parent::__construct("user", ["id", "name", "email", "password", "created_at"]);
    }

    protected function prepareUpdateData(array $row, array $data): array
    {
        return [
            $row[Constant::ID],
            $data['name'] ?? $row[Constant::NAME],
            $data['email'] ?? $row[Constant::EMAIL],
            $data['password'] ?? $row[Constant::PASSWORD],
            $data['created_at'] ?? $row[Constant::CREATED_AT]
        ];
    }

    protected function prepareInsertData(array $data): array
    {
        $date = new DateTime('now');
        $result = $date->format('Y-m-d');
        $this->id = parent::loadLastId();
        return [
            ++$this->id,
            $data['name'],
            $data['email'],
            $data['password'],
            $data['created_at'] ?? $result
        ];
    }
}
