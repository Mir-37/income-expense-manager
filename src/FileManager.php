<?php

namespace Hellm\ExpenseApp;

use DateTime;

class FileManager
{
    public array $data;
    private mixed $file;
    private string $file_name;
    public string $directory;
    public array $headers = ["id", "amount", "category", "type", "user_name"];
    private int $id = 0;

    public function __construct(string $file_name)
    {
        $this->directory = dirname(__DIR__) . "/files/";
        if (!is_dir($this->directory)) {
            mkdir($this->directory, "0777", true);
        }
        if (!file_exists($this->directory . $file_name . ".csv")) {
            $this->file = fopen($this->directory . $file_name . ".csv", "w");
            fputcsv($this->file, $this->headers);
            fclose($this->file);
        }

        $csvToArray = file($this->directory . $file_name . ".csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lastLine = end($csvToArray);
        $fields = str_getcsv($lastLine);
        $this->id = $fields[0];
    }

    public function getAllData(string $file_name): array
    {
        $filePath = $this->directory . $file_name . ".csv";

        $csvArray = array_map('str_getcsv', file($filePath));

        return $csvArray;
    }

    public function getId(): int
    {
        return $this->id++;
    }
    public function insert(array $data, string $file_name): void
    {
        $this->file = fopen($this->directory . $file_name . ".csv", "w");
        fputcsv($this->file, $data);
        fclose($this->file);
    }

    public function update(int $id, array $data, string $file_name): void
    {
        $filePath = $this->directory . $file_name . ".csv";

        $csvArray = array_map('str_getcsv', file($filePath));

        // var_dump(file($filePath));

        $file = fopen($filePath, "w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0) {
                fputcsv($file, $row);
                continue;
            }

            if ($row[0] == $id) {
                $row[1] = $data[0] ?? $row[1];
                $row[2] = $data[1] ?? $row[2];
                $row[3] = $data[2] ?? $row[3];
                $row[4] = $data[3] ?? $row[4];
            }

            fputcsv($file, $row);
        }

        fclose($file);
    }


    public function delete(int $id, string $file_name): void
    {
        $filePath = $this->directory . $file_name . ".csv";

        $csvArray = array_map('str_getcsv', file($filePath));

        $file = fopen($filePath, "w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0) {
                fputcsv($file, $row);
                continue;
            }

            if ($row[0] != $id) {
                fputcsv($file, $row);
            }
        }

        fclose($file);
    }
}
