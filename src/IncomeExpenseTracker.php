<?php

namespace Hellm\ExpenseApp;

use Hellm\ExpenseApp\FileManager;


class IncomeExpenseTracker
{
    public float $income;
    public string $user_name;
    public float $expense;
    public float $total;
    public string $category;
    public FileManager $file;
    public array $data;

    public function __construct()
    {
        $this->income = 0.0;
        $this->expense = 0.0;
        $this->total = 0.0;
        $this->category = "";
        $this->user_name = "";
        $this->file = new FileManager("user");
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
        $this->category = $category;
    }

    public function setIncome(float $money): void
    {
        $this->income += $money;
    }

    public function setExpense(float $money): void
    {
        $this->expense -= $money;
    }

    public function setUser(string $user)
    {
        $this->user_name = $user;
    }

    public function getUser(): string
    {
        return $this->user_name;
    }

    public function viewIncome()
    {
        $datas = $this->file->getAllData("users");
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
        $datas = $this->file->getAllData("users");
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
        $datas = $this->file->getAllData("users");
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
        $datas = $this->file->getAllData("users");
        foreach ($datas as $key => $value) {
            if (strtolower($this->getUser()) == $value[4]) {
                echo $value[2];
            }
        }
    }

    public function viewIncomeExpenseCategoryWise(string $category)
    {
        $datas = $this->file->getAllData("users");
        foreach ($datas as $key => $value) {
            if (strtolower($value[1]) == strtolower($category) && strtolower($this->getUser()) == $value[4]) {
                foreach ($value as $k => $v) {
                    echo $v;
                }
            }
        }
    }
}
