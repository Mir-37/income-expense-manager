<?php
require __DIR__ . '/vendor/autoload.php';

use Hellm\ExpenseApp\IncomeExpenseTracker;

$tracker = new IncomeExpenseTracker();

echo $tracker->getIncome();
