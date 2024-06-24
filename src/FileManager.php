<?php

namespace Hellm\ExpenseApp;

use DateTime;

class FileManager
{
    public array $data;
    public mixed $file;

    // function getAllIncomeData(): array
    // {
    //     return [];
    // }

    // function getAllExpenseData(): array
    // {
    //     return [];
    // }

    // function getAllIncomeDataCategoryWise(string $category): array
    // {
    //     return [];
    // }

    // function getAllExpenseDataCategoryWise(string $category): array
    // {
    //     return [];
    // }

    // function getIncomeOnDate(DateTime $date): array
    // {
    //     return [];
    // }

    // function getExpenseOnDate(DateTime $date): array
    // {
    //     return [];
    // }

    public function getAllData(): void
    {
    }

    public function insert(string $data): void
    {
    }

    public function insertMultiple(array $data): void
    {
    }

    public function update(int $id, string $data): void
    {
    }

    public function delete(int $id, string $data): void
    {
    }

    public function deleteMultiple(array $id, string $data): void
    {
    }

    public function createFile(string $name, ?array $names)
    {
        if (!is_null($names)) {
            for ($i = 0; $i < count($names); $i++) {
                $this->file = fopen(__DIR__ . "/files/" . $names[$i] . '.csv', "w");
            }
        } else {
            $this->file = fopen(__DIR__ . "/files/" . $name . ".csv", "w");
        }
    }
}
