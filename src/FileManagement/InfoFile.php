<?php

namespace Hellm\ExpenseApp\FileManagement;

use DateTime;
use Hellm\ExpenseApp\Constant;
use Hellm\ExpenseApp\FileManagement\FileManager;
use Hellm\ExpenseApp\Traits\Helper;

class InfoFile extends FileManager
{

    private int $id;
    use Helper;

    public function __construct()
    {
        parent::__construct("info", ["id", "user_id", "category", "amount", "type", "date_added"]);
    }

    protected function prepareUpdateData(array $row, array $data): array
    {
        return [
            $row[Constant::ID],
            $data['user_id'] ?? $row[Constant::USER_ID],
            $data['category'] ?? $row[Constant::CATEGORY],
            $data['amount'] ?? $row[Constant::AMOUNT],
            $data['type'] ?? $row[Constant::TYPE],
            $data['date_added'] ?? $row[Constant::DATE_ADDED]
        ];
    }

    protected function prepareInsertData(array $data): array
    {
        $date = new DateTime('now');
        $result = $date->format('Y-m-d');
        $this->id = parent::loadLastId();
        return [
            ++$this->id,
            $data['user_id'],
            $data['category'] ?? "",
            $data['amount'] ?? "0.0",
            $data['type'] ?? "",
            $data['date_added'] ?? $result
        ];
    }
}
