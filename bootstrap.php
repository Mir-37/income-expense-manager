<?php

use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

class App extends Application
{
    public function __construct()
    {
        parent::__construct("Income Expense Tracker", "1.0.0");
    }
}
