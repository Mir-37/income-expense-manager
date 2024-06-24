<?php
require __DIR__ . '/vendor/autoload.php';

use Hellm\ExpenseApp\FileManager;
use Hellm\ExpenseApp\IncomeExpenseTracker;

$tracker = new IncomeExpenseTracker();
$file = new FileManager();
$file->createFile("users", null);

// echo $tracker->getIncome();
