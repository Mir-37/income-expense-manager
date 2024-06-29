<?php

namespace Hellm\ExpenseApp\Commands;

use AuthManager;
use Hellm\ExpenseApp\IncomeExpenseTracker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Hellm\ExpenseApp\Traits\Helper;

class MainMenu extends Command
{
    use Helper;
    protected static $defaultDescription = 'Manage your income and expenses.';
    protected static $defaultName = 'manage-income-expense';

    protected function configure(): void
    {
        $this->setHelp('This command allows you to manage your income and expenses...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Welcome!</info>');

        $question = new ChoiceQuestion(
            'Please select an option (defaults to 1)',
            ['Login', 'Register'],
            0
        );
        $question->setErrorMessage('Option %s is invalid.');

        $option = $helper->ask($input, $output, $question);

        if (strtolower($option) === "login") {
            $this->handleLogin($input, $output, $helper);
        } else {
            $this->handleRegister($input, $output, $helper);
        }

        return Command::SUCCESS;
    }

    private function handleLogin(InputInterface $input, OutputInterface $output, $helper)
    {
        $output->write(sprintf("\033\143"));
        $output->writeln('<info>               Login           </info>');
        $output->writeln('=======================================');

        $nameOrEmailQuestion = new Question('Name or Email: ');
        $nameOrEmail = $helper->ask($input, $output, $nameOrEmailQuestion);

        $passwordQuestion = new Question('Password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $auth = new AuthManager();
        $login = $auth->authenticate($nameOrEmail, $password);

        if (!is_null($login) && count($login) >= 1) {
            $this->current_user_id = $login['id'];
            $output->writeln('<info>Welcome, ' . $nameOrEmail . '!</info>');
            $this->showMainMenu($input, $output, $helper);
        } else {
            $output->writeln('<error>Wrong Credentials, Try Again</error>');
        }
    }

    private function handleRegister(InputInterface $input, OutputInterface $output, $helper)
    {
        $output->write(sprintf("\033\143"));
        $output->writeln('<info>               Register           </info>');
        $output->writeln('=======================================');

        $nameQuestion = new Question('Name: ');
        $name = $helper->ask($input, $output, $nameQuestion);

        $emailQuestion = new Question('Email: ');
        $email = $helper->ask($input, $output, $emailQuestion);

        $passwordQuestion = new Question('Password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $auth = new AuthManager();
        $register = $auth->register($name, $email, $password);

        if ($register) {
            $output->writeln('<info>Registration successful. Please login.</info>');
        } else {
            $output->writeln('<error>Registration failed. Try again.</error>');
        }
    }

    private function showMainMenu(InputInterface $input, OutputInterface $output, $helper)
    {
        $mainMenuQuestion = new ChoiceQuestion(
            'Please select an option',
            ['1. View Savings', '2. Add Income', '3. Add Expense', '4. View Incomes', '5. View Expenses', '6. View Categories'],
            0
        );
        $mainMenuQuestion->setErrorMessage('Option %s is invalid.');

        $mainOption = $helper->ask($input, $output, $mainMenuQuestion);
        $output->writeln('You have just selected: ' . $mainOption);

        $tracker = new IncomeExpenseTracker();
        $tracker->setUser($this->current_user_id);

        switch ($mainOption) {
            case '1. View Savings':
                $output->writeln('Total Savings: ' . $tracker->viewSavings());
                break;
            case '2. Add Income':
                $this->handleAddIncome($input, $output, $helper, $tracker);
                break;
            case '3. Add Expense':
                $this->handleAddExpense($input, $output, $helper, $tracker);
                break;
            case '4. View Incomes':
                $tracker->viewIncome();
                break;
            case '5. View Expenses':
                $tracker->viewExpense();
                break;
            case '6. View Categories':
                $tracker->viewAllCategories();
                break;
            default:
                $output->writeln('<error>Invalid Option</error>');
        }
    }

    private function handleAddIncome(InputInterface $input, OutputInterface $output, $helper, IncomeExpenseTracker $tracker)
    {
        $amountQuestion = new Question('Enter the amount of income: ');
        $amount = $helper->ask($input, $output, $amountQuestion);

        $categoryQuestion = new Question('Enter the category of income: ');
        $category = $helper->ask($input, $output, $categoryQuestion);

        try {
            $tracker->addIncome((float)$amount);
            $tracker->setCategory($category);
            $output->writeln('<info>Income added successfully.</info>');
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

    private function handleAddExpense(InputInterface $input, OutputInterface $output, $helper, IncomeExpenseTracker $tracker)
    {
        $amountQuestion = new Question('Enter the amount of expense: ');
        $amount = $helper->ask($input, $output, $amountQuestion);

        $categoryQuestion = new Question('Enter the category of expense: ');
        $category = $helper->ask($input, $output, $categoryQuestion);

        try {
            $tracker->addExpense((float)$amount);
            $tracker->setCategory($category);
            $output->writeln('<info>Expense added successfully.</info>');
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}
