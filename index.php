<?php
require __DIR__ . '/vendor/autoload.php';

use Hellm\ExpenseApp\FileManager;
use Hellm\ExpenseApp\IncomeExpenseTracker;

$tracker = new IncomeExpenseTracker();
$file = new FileManager();
// print_r($file->createFile("users", null));
echo $file->createFile("user", null);

// echo $tracker->getIncome();
