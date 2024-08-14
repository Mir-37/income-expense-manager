<?php

namespace Hellm\ExpenseApp\Traits;

use Hellm\ExpenseApp\Constant;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

trait MainMenuHelper
{
    public function showMainMenu(): void
    {
        while (true) {
            $this->clearScreen();
            $this->displayMenu();

            $option = $this->getMenuOption();
            $this->handleMenuOption($option);

            if ($option === 'Exit') {
                $this->output->writeln('<info>Exiting the application. Goodbye!</info>');
                break;
            }
        }
    }

    private function clearScreen(): void
    {
        $this->output->write("\033\143");
    }

    private function displayMenu(): void
    {
        $this->output->writeln('<info>               Main Menu           </info>');
        $this->output->writeln('=======================================');
    }

    private function getMenuOption(): string
    {
        $options = ['View Savings', 'Add Income', 'Add Expense', 'View Incomes', 'View Expenses', 'View Categories', 'View History', 'Search by category', 'Exit'];
        $question = new ChoiceQuestion('Please select an option', $options, 0);
        $question->setErrorMessage('Option %s is invalid.');

        return $this->helper->ask($this->input, $this->output, $question);
    }

    private function promptToContinue(): void
    {
        $this->output->writeln('Press Enter to continue.');
        $this->helper->ask($this->input, $this->output, new Question(''));
    }

    private function displayDataInTable(array $data, string $title): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['Category', 'Amount', 'Date Added']);
        $table->setColumnMaxWidth(0, 50);
        $table->setColumnMaxWidth(1, 50);
        $table->setStyle('box-double');

        foreach ($data as $item) {
            $table->addRow([
                $item[Constant::CATEGORY],
                $item[Constant::AMOUNT],
                $item[Constant::DATE_ADDED],
            ]);
        }

        if (empty($data)) {
            $this->output->writeln("<info>No $title Found.</info>");
        } else {

            $table->render();
        }
    }

    private function displayHistoryInTable(array $data, string $title): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['Category', 'Amount', 'Type', 'Date Added']);
        $table->setColumnMaxWidth(0, 50);
        $table->setColumnMaxWidth(1, 50);
        $table->setStyle('box-double');

        foreach ($data as $item) {
            $table->addRow([
                $item[Constant::CATEGORY],
                $item[Constant::AMOUNT],
                $item[Constant::TYPE],
                $item[Constant::DATE_ADDED],
            ]);
        }

        if (empty($data)) {
            $this->output->writeln("<info>No $title Found.</info>");
        } else {
            $table->render();
        }
    }
}
