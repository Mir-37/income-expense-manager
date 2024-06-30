<?php

namespace Hellm\ExpenseApp\Commands;

use Hellm\ExpenseApp\Constant;
use Hellm\ExpenseApp\AuthManager;
use Hellm\ExpenseApp\Traits\Helper;
use Hellm\ExpenseApp\IncomeExpenseTracker;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Exception;

class MainMenu
{
    use Helper;
    private InputInterface $input;
    private OutputInterface $output;
    private HelperInterface $helper;
    private AuthManager $auth_manager;
    private string $option;
    private IncomeExpenseTracker $tracker;

    public function __construct(InputInterface $input, OutputInterface $output, HelperInterface $helper, AuthManager $auth_manager)
    {
        $this->input = $input;
        $this->output = $output;
        $this->helper = $helper;
        $this->auth_manager = $auth_manager;
        $this->tracker = new IncomeExpenseTracker($auth_manager);
    }

    public function showMainMenu()
    {
        do {
            $this->output->write(sprintf("\033\143"));
            $this->output->writeln('<info>               Main Menu           </info>');
            $this->output->writeln('=======================================');

            $mainMenuQuestion = new ChoiceQuestion(
                'Please select an option',
                ['View Savings', 'Add Income', 'Add Expense', 'View Incomes', 'View Expenses', 'View Categories', 'Exit'],
                0
            );
            $mainMenuQuestion->setErrorMessage('Option %s is invalid.');

            $mainOption = $this->helper->ask($this->input, $this->output, $mainMenuQuestion);

            switch ($mainOption) {
                case 'View Savings':
                    $this->viewSavings($this->tracker);
                    break;
                case 'Add Income':
                    $this->addIncome($this->tracker);
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
                case 'Exit':
                    $this->output->writeln('<info>Exiting the application. Goodbye!</info>');
                    return;
                default:
                    $this->output->writeln('<error>Invalid Option</error>');
            }

            $this->output->writeln('Press Enter to return to the main menu.');
            $this->helper->ask($this->input, $this->output, new Question(''));
        } while (true);
    }

    private function addIncome(IncomeExpenseTracker $tracker)
    {
        $categories = $this->tracker->getCategories();
        $categories[] = 'New Category';

        $categoryQuestion = new ChoiceQuestion(
            'Select from previous categories or enter a new one: ',
            $categories
        );

        $category = $this->helper->ask($this->input, $this->output, $categoryQuestion);

        if ($category === 'New Category') {
            $newCategoryQuestion = new Question('Enter the new category of income: ');
            $category = $this->helper->ask($this->input, $this->output, $newCategoryQuestion);
        }

        $amountQuestion = new Question('Enter the amount of income: ');
        $amount = $this->helper->ask($this->input, $this->output, $amountQuestion);

        try {
            $this->tracker->addEntry("income", floatval($amount), $category);
            $this->output->writeln('<info>Income added successfully.</info>');
        } catch (Exception $e) {
            $this->output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    private function addExpense()
    {
        $categories = $this->tracker->getCategories();
        $categories[] = 'New Category';

        $categoryQuestion = new ChoiceQuestion(
            'Select from previous categories or enter a new one: ',
            $categories
        );

        $category = $this->helper->ask($this->input, $this->output, $categoryQuestion);

        if ($category === 'New Category') {
            $newCategoryQuestion = new Question('Enter the new category of expense: ');
            $category = $this->helper->ask($this->input, $this->output, $newCategoryQuestion);
        }

        $amountQuestion = new Question('Enter the amount of expense: ');
        $amount = $this->helper->ask($this->input, $this->output, $amountQuestion);

        try {
            $this->tracker->addEntry("expense", floatval($amount), $category);
            $this->output->writeln('<info>Expense added successfully.</info>');
        } catch (Exception $e) {
            $this->output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    private function viewIncomes()
    {
        $incomes = $this->tracker->viewEachIncome();
        $this->output->writeln('Incomes:');

        if (count($incomes) < 1) {
            $this->output->writeln('No Incomes Found..');
        } else {
            foreach ($incomes as $income) {
                $this->output->writeln('Category: ' . $income[Constant::CATEGORY] . ', Amount: ' . $income[Constant::AMOUNT]);
            }
        }
    }

    private function viewExpenses()
    {
        $expenses = $this->tracker->viewExpense();
        $this->output->writeln('Expenses:');
        if (count($expenses) < 1) {
            $this->output->writeln('No Expenses Found..');
        } else {
            foreach ($expenses as $expense) {
                $this->output->writeln('Category: ' . $expense[Constant::CATEGORY] . ', Amount: ' . $expense[Constant::AMOUNT]);
            }
        }
    }

    private function viewCategories()
    {
        $categories = $this->tracker->viewAllCategories();
        $this->output->writeln('Categories:');
        if (count($categories) < 1) {
            $this->output->writeln('No categories found..');
        } else {
            foreach ($categories as $category) {
                $this->output->writeln($category);
            }
        }
    }

    public function viewSavings(IncomeExpenseTracker $tracker): void
    {
        $expenses = $this->tracker->viewExpense();
        $incomes = $this->tracker->viewEachIncome();

        if (count($expenses) < 1) {
            $this->output->writeln('You have no expenses..');
        }

        if (count($incomes) < 1) {
            $this->output->writeln('You have no incomes..');
        }

        $this->output->writeln('Total Savings: ' . $tracker->viewSavings());
    }
}
