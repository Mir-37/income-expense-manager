<?php

namespace Hellm\ExpenseApp;

use DateTime;

class FileManager
{
    public array $data;
    private mixed $file;
    public string $directory;
    public array $headers = ["id", "amount", "category", "type"];
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
        $this->directory = dirname(__DIR__) . "/files/";
        if (!is_dir($this->directory)) {
            mkdir($this->directory, "0777", true);
        }
        if (!is_null($names)) {
            for ($i = 0; $i < count($names); $i++) {
                $this->file = fopen($this->directory . $names[$i] . '.csv', "w");
                fputcsv($this->file, $this->headers);
            }
        } else {
            $this->file = fopen($this->directory . $name . ".csv", "w");
            fputcsv($this->file, $this->headers);
        }

        fclose($this->file);
    }
}
