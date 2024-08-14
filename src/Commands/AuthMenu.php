<?php

namespace Hellm\ExpenseApp\Commands;

use Hellm\ExpenseApp\AuthManager;
use Hellm\ExpenseApp\Traits\Helper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuthMenu
{
    use Helper;
    private InputInterface $input;
    private OutputInterface $output;
    private HelperInterface $helper;
    protected AuthManager $auth_manager;
    private string $option;

    public function __construct(InputInterface $input, OutputInterface $output, HelperInterface $helper, string $option)
    {
        $this->input = $input;
        $this->output = $output;
        $this->option = $option;
        $this->helper = $helper;
        $this->auth_manager = new AuthManager();
    }

    public function handleAuthentication(): ?AuthManager
    {
        if (strtolower($this->option) === "login") {
            return $this->handleLogin($this->input, $this->output, $this->helper);
        } else {
            return $this->handleRegister($this->input, $this->output, $this->helper);
        }
    }

    private function handleLogin(InputInterface $input, OutputInterface $output, HelperInterface $helper): ?AuthManager
    {
        $counter = 0;
        while ($counter < 3) {
            $output->write(sprintf("\033\143"));
            $output->writeln('<info>               Login           </info>');
            $output->writeln('=======================================');

            $nameOrEmailQuestion = new Question('Name or Email: ');
            $nameOrEmail = $helper->ask($input, $output, $nameOrEmailQuestion);

            $passwordQuestion = new Question('Password: ');
            $passwordQuestion->setHidden(true);
            $passwordQuestion->setHiddenFallback(false);
            $password = $helper->ask($input, $output, $passwordQuestion);

            $login = $this->auth_manager->authenticate($nameOrEmail, $password);

            if (!is_null($login) && count($login) >= 1) {
                $output->writeln('<info>Welcome, ' . $login['name'] . '!</info>');
                sleep(2);
                return $this->auth_manager;
            }

            $output->writeln('<error>Wrong Credentials, You have got ' . (2 - $counter) . ' tries left' . '</error>');
            sleep(2);
            $counter++;
        }
        return null;
    }

    private function handleRegister(InputInterface $input, OutputInterface $output, HelperInterface $helper): bool|AuthManager
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

        $register = $this->auth_manager->register($name, $email, $password);

        if ($register) {
            $output->writeln('<info>Registration successful. Please login.</info>');
            sleep(2);
            return $this->handleLogin($input, $output, $helper);
        } else {
            $output->writeln('<error>Registration failed. Try again.</error>');
            return false;
        }
    }
}
