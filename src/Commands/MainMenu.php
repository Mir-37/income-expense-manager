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
    }

    public function showMainMenu()
    {
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

        $tracker = new IncomeExpenseTracker($this->auth_manager);

        switch ($mainOption) {
            case 'View Savings':
                $this->output->writeln('Total Savings: ' . $tracker->viewSavings());
                break;
            case 'Add Income':
                $categoryQuestion = new Question('Enter the category of income: ');
                $category = $this->helper->ask($this->input, $this->output, $categoryQuestion);

                $amountQuestion = new Question('Enter the amount of income: ');
                $amount = $this->helper->ask($this->input, $this->output, $amountQuestion);

                try {
                    $tracker->addEntry("income", floatval($amount), $category);
                    $this->output->writeln('<info>Income added successfully.</info>');
                } catch (Exception $e) {
                    $this->output->writeln('<error>' . $e->getMessage() . '</error>');
                }
                break;
            case 'Add Expense':
                $categoryQuestion = new Question('Enter the category of expense: ');
                $category = $this->helper->ask($this->input, $this->output, $categoryQuestion);

                $amountQuestion = new Question('Enter the amount of expense: ');
                $amount = $this->helper->ask($this->input, $this->output, $amountQuestion);

                $tracker->addEntry("income", $category, $amount);
                try {
                    $tracker->addEntry("income", $category, $amount);
                    $this->output->writeln('<info>Income added successfully.</info>');
                } catch (Exception $e) {
                    $this->output->writeln('<error>' . $e->getMessage() . '</error>');
                }
                break;
            case 'View Incomes':
                $arr = $tracker->income_array;
                $this->output->writeln(
                    "Incomes: "
                );
                foreach ($arr as $key => $value) {
                    $this->output->writeln(
                        "Category: " . $value[Constant::CATEGORY] .
                            "Amount: ",
                        $value[Constant::AMOUNT]
                    );
                }
                break;
            case 'View Expenses':
                $arr = $tracker->expense_array;
                $this->output->writeln(
                    "Expenses: "
                );
                foreach ($arr as $key => $value) {
                    $this->output->writeln(
                        "Category: " . $value[Constant::CATEGORY] .
                            "Amount: ",
                        $value[Constant::AMOUNT]
                    );
                }
                break;
            case 'View Categories':
                $this->output->writeln(
                    "All categories: "
                );
                $tracker->viewAllCategories();
                break;
            default:
                $this->output->writeln('<error>Invalid Option</error>');
        }
    }
}
