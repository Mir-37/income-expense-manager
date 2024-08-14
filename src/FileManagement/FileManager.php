<?php

namespace Hellm\ExpenseApp\FileManagement;

use DateTime;
use Exception;
use Hellm\ExpenseApp\Constant;

abstract class FileManager
{
    public array $data = [];
    private string $file_name;
    private string $file_path;
    public string $directory;
    protected array $headers;
    private int $id = 0;

    public function __construct(string $file_name, array $headers)
    {
        $this->directory = dirname(dirname(__DIR__)) . "/resources/";
        $this->file_name = $file_name . ".csv";
        $this->file_path = $this->directory . $this->file_name;
        $this->headers = $headers;
        $this->initFile();
        $this->loadLastId();
    }

    private function openFile(string $mode): mixed
    {
        $file = fopen($this->file_path, $mode);

        if ($file === false) {
            throw new Exception("Unable to open file: " . $this->file_path);
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

    public function loadLastId(): int
    {
        $file = file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($file === false || count($file) <= 1) {
            $this->id = 0;
        } else {
            $lastLine = end($file);
            $fields = str_getcsv($lastLine);
            $this->id = (int)$fields[Constant::ID];
        }
        return $this->id;
    }

    public function getAllDataFromFile(): array
    {
        $csvArray = array_map('str_getcsv', file($this->file_path));

        return $csvArray;
    }

    public function insert(array $data): void
    {
        $file = $this->openFile("a");
        $preparedData = $this->prepareInsertData($data);
        fputcsv($file, $preparedData);
        fflush($file);
        fclose($file);
    }

    public function update(int $id, array $data): void
    {
        $csvArray = array_map('str_getcsv', file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $file = $this->openFile("w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0 || (int)$row[Constant::ID] != $id) {
                fputcsv($file, $row);
            } else {
                $new_data = $this->prepareUpdateData($row, $data);
                fputcsv($file, $new_data);
            }
        }
        fclose($file);
    }

    abstract protected function prepareUpdateData(array $row, array $data): array;
    abstract protected function prepareInsertData(array $data): array;

    public function delete(int $id): void
    {

        $csvArray = array_map('str_getcsv', file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $file = $this->openFile("w");

        foreach ($csvArray as $rowIndex => $row) {
            if ($rowIndex == 0 || (int)$row[Constant::ID] != $id) {
                fputcsv($file, $row);
            }
        }

        fclose($file);
    }
}
