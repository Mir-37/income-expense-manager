<?php

namespace Hellm\ExpenseApp;

use Hellm\ExpenseApp\FileManagement\InfoFile;
use Hellm\ExpenseApp\AuthManager;
use Exception;
use DateTime;
use Hellm\ExpenseApp\Traits\Helper;

class IncomeExpenseTracker
{
    use Helper;
    private float $income = 0.0;
    private float $expense = 0.0;
    private float $total = 0.0;
    private array $categories;
    private string $category;
    private AuthManager $auth_manager;
    private array $data;
    private InfoFile $infoFile;
    public array $income_array = [];
    public array $expense_array = [];

    public function __construct(AuthManager $auth_manager)
    {
        $this->auth_manager = $auth_manager;
        $this->infoFile = new InfoFile();
        $this->reloadData();
    }

    private function reloadData(): void
    {
        $this->data = $this->getInfoFromUser($this->auth_manager);
    }

    public function addEntry(string $type, float $amount, string $category = ""): void
    {
        if (!in_array(strtolower($type), ['income', 'expense', 'category'])) {
            throw new Exception("Invalid entry type. Must be 'income', 'expense', or 'category'.");
        }
        if ($amount < 0) {
            throw new Exception(ucfirst($type) . " amount can't be negative");
        }

        $data = [
            'user_id' => $this->auth_manager->getUserId(),
            'category' => $category,
            'amount' => $amount,
            'type' => $type,
            'date_added' => (new DateTime('now'))->format('Y-m-d')
        ];

        $this->infoFile->insert($data);
        // print_r($this->data);
        $this->reloadData();
    }

    public function getTotalIncome(): float
    {
        $this->reloadData();
        $this->income_array = array_filter($this->data, function ($rec) {
            return strtolower($rec[Constant::TYPE]) === 'income';
        });
        $this->income = array_sum(array_column($this->income_array, Constant::AMOUNT));
        return $this->income;
    }

    public function getTotalExpense(): float
    {
        $this->reloadData();
        $this->expense_array = array_filter($this->data, function ($rec) {
            return strtolower($rec[Constant::TYPE]) === 'expense';
        });

        $this->expense = array_sum(array_column($this->expense_array, Constant::AMOUNT));
        return $this->expense;
    }

    public function getCategories(): array
    {
        $this->reloadData();
        $category_array = array_filter($this->data, function ($rec) {
            return strtolower($rec[Constant::CATEGORY]) !== '';
        });

        $this->categories = array_unique(array_column($category_array, Constant::CATEGORY));
        return $this->categories;
    }

    public function setCategory(string $category): void
    {
        if (empty($category)) throw new Exception("Category can't be empty");
        $this->category = $category;
    }

    public function getAllData(): array
    {
        $this->reloadData();
        return $this->data;
    }

    public function viewEachIncome(): array
    {
        $this->reloadData();
        return array_filter($this->data, function ($rec) {
            return strtolower($rec[Constant::TYPE]) === 'income';
        });
    }

    public function viewExpense(): array
    {
        $this->reloadData();
        return array_filter($this->data, function ($rec) {
            return strtolower($rec[Constant::TYPE]) === 'expense';
        });
    }

    public function viewSavings(): float
    {
        $this->reloadData();
        if (count($this->data) >= 1) {
            $this->total = $this->getTotalIncome() - $this->getTotalExpense();
        } else {
            $this->total = 0.0;
        }
        // print_r($this->income_array);
        return $this->total;
    }

    public function viewAllCategories()
    {
        $this->reloadData();
        return $this->getCategories();
    }

    public function viewIncomeExpenseCategoryWise(string $category)
    {
        $this->reloadData();
        return array_filter($this->data, function ($rec) use ($category) {
            return strtolower($rec[Constant::CATEGORY]) === strtolower($category);
        });
    }
}
