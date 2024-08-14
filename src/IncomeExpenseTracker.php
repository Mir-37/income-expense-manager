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

    private AuthManager $auth_manager;
    private InfoFile $infoFile;
    private array $data;

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

    public function getData(): array
    {
        return $this->data;
    }

    public function addEntry(string $type, float $amount, string $category = ""): void
    {
        $this->validateEntry($type, $amount);

        $data = [
            'user_id' => $this->auth_manager->getUserId(),
            'category' => $category,
            'amount' => $amount,
            'type' => $type,
            'date_added' => (new DateTime())->format('Y-m-d'),
        ];

        $this->infoFile->insert($data);
        $this->reloadData();
    }

    private function validateEntry(string $type, float $amount): void
    {
        if (!in_array(strtolower($type), ['income', 'expense'])) {
            throw new Exception("Invalid entry type. Must be 'income' or 'expense'.");
        }

        if ($amount < 0) {
            throw new Exception(ucfirst($type) . " amount can't be negative.");
        }
    }

    public function getTotalIncome(): float
    {
        return $this->calculateTotal('income');
    }

    public function getTotalExpense(): float
    {
        return $this->calculateTotal('expense');
    }

    private function calculateTotal(string $type): float
    {
        $this->reloadData();
        $filteredData = array_filter($this->data, function ($rec) use ($type) {
            return strtolower($rec[Constant::TYPE]) === $type;
        });

        return array_sum(array_column($filteredData, Constant::AMOUNT));
    }

    public function getCategories(): array
    {
        $this->reloadData();
        $categories = array_unique(array_column($this->data, Constant::CATEGORY));
        return array_filter($categories);
    }

    public function viewIncomes(): array
    {
        return $this->filterByType('income');
    }

    public function viewExpenses(): array
    {
        return $this->filterByType('expense');
    }

    private function filterByType(string $type): array
    {
        $this->reloadData();
        return array_filter($this->data, function ($rec) use ($type) {
            return strtolower($rec[Constant::TYPE]) === $type;
        });
    }

    public function viewSavings(): float
    {
        return $this->getTotalIncome() - $this->getTotalExpense();
    }

    public function viewAllCategories(): array
    {
        return $this->getCategories();
    }

    public function viewIncomeExpenseCategoryWise(string $category): array
    {
        $this->reloadData();
        return array_filter($this->data, function ($rec) use ($category) {
            return strtolower($rec[Constant::CATEGORY]) === strtolower($category);
        });
    }
}
