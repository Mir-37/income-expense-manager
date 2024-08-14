#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Hellm\ExpenseApp\Commands\MainCommandClass;

$app = new Application("Income Expense Tracker", "1.0.0");
$app->add(new MainCommandClass());
$app->run();
