<?php

namespace Hellm\ExpenseApp\Commands;

use Exception;
use Hellm\ExpenseApp\Traits\Helper;
use Hellm\ExpenseApp\Commands\AuthMenu;
use Hellm\ExpenseApp\IncomeExpenseTracker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class MainCommandClass extends Command
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

        $auth_menu = new AuthMenu($input, $output, $helper, $option);
        $auth_manager = $auth_menu->handleAuthentication();
        if (!is_null($auth_manager)) {
            $main_menu = new MainMenu($input, $output, $helper, $auth_manager);
            $main_menu->showMainMenu();
        }

        return Command::FAILURE;
    }
}
