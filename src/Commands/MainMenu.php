<?php

namespace Hellm\ExpenseApp\Commands;

use Exception;
use Hellm\ExpenseApp\Constant;
use Hellm\ExpenseApp\AuthManager;
use Hellm\ExpenseApp\Traits\Helper;
use Hellm\ExpenseApp\IncomeExpenseTracker;
use Hellm\ExpenseApp\Traits\MainMenuHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class MainMenu
{
    use Helper, MainMenuHelper;

    private InputInterface $input;
    private OutputInterface $output;
    private HelperInterface $helper;
    private AuthManager $auth_manager;
    private IncomeExpenseTracker $tracker;

    public function __construct(InputInterface $input, OutputInterface $output, HelperInterface $helper, AuthManager $auth_manager)
    {
        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
        $this->auth_manager = $auth_manager;
        $this->tracker = new IncomeExpenseTracker($auth_manager);
    }

    private function handleMenuOption(string $option): void
    {
        try {
            switch ($option) {
                case 'View Savings':
                    $this->viewSavings();
                    break;
                case 'Add Income':
                    $this->addIncome();
                    break;
                case 'Add Expense':
                    $this->addExpense();
                    break;
                case 'View Incomes':
                    $this->viewIncomes();
                    break;
                case 'View Expenses':
                    $this->viewExpenses();
                    break;
                case 'View Categories':
                    $this->viewCategories();
                    break;
                case 'View History':
                    $this->viewHistory();
                    break;
                case 'Search by category':
                    $this->searchByCategory();
                    break;
            }
        } catch (Exception $e) {
            $this->output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        $this->promptToContinue();
    }

    private function addIncome(): void
    {
        $category = $this->getCategoryInput();
        $amount = $this->getAmountInput('income');

        $this->tracker->addEntry("income", $amount, $category);
        $this->output->writeln('<info>Income added successfully.</info>');
    }

    private function addExpense(): void
    {
        $category = $this->getCategoryInput();
        $amount = $this->getAmountInput('expense');

        $this->tracker->addEntry("expense", $amount, $category);
        $this->output->writeln('<info>Expense added successfully.</info>');
    }

    private function getCategoryInput(): string
    {
        $categories = $this->tracker->getCategories();
        $categories[] = 'New Category';

        $question = new ChoiceQuestion('Select from previous categories or enter a new one:', $categories);
        $category = $this->helper->ask($this->input, $this->output, $question);

        if ($category === 'New Category') {
            $newCategoryQuestion = new Question('Enter the new category: ');
            $category = $this->helper->ask($this->input, $this->output, $newCategoryQuestion);
        }

        return $category;
    }

    private function getAmountInput(string $type): float
    {
        $question = new Question("Enter the amount of $type: ");
        $amount = $this->helper->ask($this->input, $this->output, $question);

        return floatval($amount);
    }

    private function viewIncomes(): void
    {
        $incomes = $this->tracker->viewIncomes();
        $this->displayDataInTable($incomes, 'Incomes');
    }

    private function viewExpenses(): void
    {
        $expenses = $this->tracker->viewExpenses();
        $this->displayDataInTable($expenses, 'Expenses');
    }

    private function viewHistory(): void
    {
        $data = $this->tracker->getData();
        $this->displayHistoryInTable($data, 'History');
    }

    private function viewSavings(): void
    {
        $savings = $this->tracker->viewSavings();
        $this->output->writeln("Total Savings: $savings");
    }

    private function viewCategories(): void
    {
        $categories = $this->tracker->viewAllCategories();
        $this->output->writeln('<info>Available Categories:</info>');
        foreach ($categories as $category) {
            $this->output->writeln('- ' . $category);
        }
    }

    private function searchByCategory(): void
    {
        $categories = $this->tracker->getCategories();

        $question = new ChoiceQuestion('Select a category to search income expense category wise:', $categories);
        $category = $this->helper->ask($this->input, $this->output, $question);

        $data = $this->tracker->viewIncomeExpenseCategoryWise($category);

        $this->displayHistoryInTable($data, 'History');
    }
}
