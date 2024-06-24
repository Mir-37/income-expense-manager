<?php

namespace Hellm\ExpenseApp\src;

class IncomeExpenseTracker
{
    public float $income;
    public float $expense;
    public float $total;
    public string $category;

    public function __construct()
    {
        $this->income = 0;
        $this->expense = 0;
        $this->total = 0;
        $this->category = "";
    }

    public function getIncome(): int
    {
        return $this->income;
    }

    public function getExpense(): int
    {
        return $this->expense;
    }

    public function setIncome(int $money): void
    {
        $this->income = $money;
    }

    public function setExpense(int $money): void
    {
        $this->expense = $money;
    }

    public function viewIncome()
    {
    }

    public function viewExpense()
    {
    }

    public function viewSavings()
    {
    }

    public function viewAllCategories()
    {
    }

    public function viewIncomesCategoryWise(string $category)
    {
    }

    public function viewExpenseCategoryWise(string $category)
    {
    }
}
