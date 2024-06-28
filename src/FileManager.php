<?php

namespace Hellm\ExpenseApp;

use DateTime;
use Exception;

class FileManager
{
    public array $data = [];
    private string $file_name;
    private string $file_path;
    public string $directory;
    public array $headers = ["id", "amount", "category", "type", "user_name", "date"];
    private int $id = 0;

    // columns in numbers from csv file
    const ID = 0;
    const AMOUNT = 1;
    const CATEGORY = 2;
    const TYPE = 3;
    const DATE = 4;

    public function __construct(string $file_name)
    {
        $this->directory = dirname(__DIR__) . "/files/";
        $this->file_name = $file_name . ".csv";
        $this->file_path = $this->directory . $this->file_name;
        $this->initFile();
        $this->loadLastId();
    }

    private function openFile(string $mode): mixed
    {
        $file = fopen($this->file_path, $mode);

        if ($file === false) {
            throw new Exception("Unable to openS file: " . $this->file_path);
        }

        return $file;
    }

    public function initFile(): void
    {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, "0777", true);
        }
        if (!file_exists($this->file_path)) {
            $file = $this->openFile("w");
            fputcsv($file, $this->headers);
            fclose($file);
        }
    }

    public function loadLastId(): void
    {
        $file = file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($file === false || count($file) <= 1) {
            $this->id = 0;
        } else {
            $lastLine = end($file);
            $fields = str_getcsv($lastLine);
            $this->id = (int)$fields[self::ID];
        }
    }

    public function getAllData(): array
    {
        $csvArray = array_map('str_getcsv', file($this->file_path));

        return $csvArray;
    }

    public function getNextId(): int
    {
        return ++$this->id;
    }

    public function insert(array $data): void
    {
        $file = $this->openFile("a");
        fputcsv($file, $data);
        fclose($file);
    }

    public function update(int $id, array $data): void
    {
        $csvArray = array_map('str_getcsv', file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $file = $this->openFile("w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0 || (int)$row[self::ID] != $id) {
                fputcsv($file, $row);
            } else {
                $new_data = [
                    $row[self::ID],
                    $data[0] ?? $row[self::AMOUNT],
                    $data[1] ?? $row[self::CATEGORY],
                    $data[2] ?? $row[self::TYPE],
                    $data[3] ?? $row[self::DATE],
                ];
                fputcsv($file, $new_data);
            }
        }
        fclose($file);
    }


    public function delete(int $id): void
    {

        $csvArray = array_map('str_getcsv', file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $file = $this->openFile("w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0 || (int)$row[self::ID] != $id) {
                fputcsv($file, $row);
            }
        }

        fclose($file);
    }
}
