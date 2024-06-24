<?php

namespace Hellm\ExpenseApp;


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

    public function getIncome(): float
    {
        return $this->income;
    }

    public function getExpense(): float
    {
        return $this->expense;
    }

    public function setIncome(float $money): void
    {
        $this->income = $money;
    }

    public function setExpense(float $money): void
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
