<?php

namespace Hellm\ExpenseApp;

use Exception;
use Hellm\ExpenseApp\FileManagement\UserFile;
use Hellm\ExpenseApp\FileManager;


class IncomeExpenseTracker
{
    private float $income;
    private string $user_name;
    private float $expense;
    private float $total;
    private string $category;
    private UserFile $file;
    private array $data;

    // columns in numbers from csv file
    const ID = 0;
    const AMOUNT = 1;
    const CATEGORY = 2;
    const TYPE = 3;
    const DATE = 4;
    const USER = 5;

    public function __construct()
    {
        $this->income = 0.0;
        $this->expense = 0.0;
        $this->total = 0.0;
        $this->category = "";
        $this->user_name = "";
        $this->file = new UserFile();
    }

    // public function makeData(): void
    // {
    //     $this->data = [$this->file->getId(), $this->get]
    // }

    public function getIncome(): float
    {
        return $this->income;
    }

    public function getExpense(): float
    {
        return $this->expense;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category)
    {
        if (empty($category)) throw new Exception("Category can't be empty");
        $this->category = $category;
    }

    public function addIncome(float $money): void
    {
        if ($money < 0) throw new Exception("Income can't be negative");
        $this->income += $money;
    }

    public function addExpense(float $money): void
    {
        if ($money < 0) throw new Exception("Expense can't be negative");
        $this->expense += $money;
    }

    public function setUser(string $user)
    {
        if (empty($user)) throw new Exception("User can't be empty");
        $this->user_name = $user;
    }

    public function getUser(): string
    {
        return $this->user_name;
    }

    public function filterDataByUser(): array
    {
        $users_data = $this->file->getAllDataFromFile();

        return array_filter($users_data, function ($record): bool {
            if (strtolower($record[self::USER]) === strtolower($this->user_name)) {
                return true;
            }
            return false;
        });
    }

    public function viewIncome()
    {
        $datas = $this->file->getAllDataFromFile("users");
        foreach ($datas as $key => $value) {
            if (strtolower($value[2]) == "income" && strtolower($this->getUser()) == $value[4]) {
                foreach ($value as $k => $v) {
                    echo $v;
                }
            }
        }
    }

    public function viewExpense()
    {
        $datas = $this->file->getAllDataFromFile("users");
        foreach ($datas as $key => $value) {
            if (strtolower($value[2]) == "expense" && strtolower($this->getUser()) == $value[4]) {
                foreach ($value as $k => $v) {
                    echo $v;
                }
            }
        }
    }

    public function viewSavings(): float
    {
        $total = 0.0;
        $datas = $this->file->getAllDataFromFile("users");
        foreach ($datas as $key => $value) {
            if (strtolower($value[2]) == "expense" && strtolower($this->getUser()) == $value[4]) {
                $total -= $value[2];
            } else if (strtolower($value[2]) == "income" && strtolower($this->getUser()) == $value[4]) {
                $total += $value[2];
            }
        }
        return $total;
    }

    public function viewAllCategories()
    {
        $datas = $this->file->getAllDataFromFile("users");
        foreach ($datas as $key => $value) {
            if (strtolower($this->getUser()) == $value[4]) {
                echo $value[2];
            }
        }
    }

    public function viewIncomeExpenseCategoryWise(string $category)
    {
        $datas = $this->file->getAllDataFromFile("users");
        foreach ($datas as $key => $value) {
            if (strtolower($value[1]) == strtolower($category) && strtolower($this->getUser()) == $value[4]) {
                foreach ($value as $k => $v) {
                    echo $v;
                }
            }
        }
    }
}
